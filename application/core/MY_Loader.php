<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
	/**
	 * Loader
	 *
	 * This function is used to load views and files.
	 * Variables are prefixed with _ci_ to avoid symbol collision with
	 * variables made available to view files
	 *
	 * @param	array
	 * @return	void
	 */
	protected function _ci_load($_ci_data)
	{
		// Set the default data variables
		foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val)
		{
			$$_ci_val = ( ! isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
		}

		$file_exists = FALSE;

		// Set the path to the requested file
		if ($_ci_path != '')
		{
			$_ci_x = explode('/', $_ci_path);
			$_ci_file = end($_ci_x);
		}
		else
		{
			$_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
			$_ci_file = ($_ci_ext == '') ? $_ci_view.'.php' : $_ci_view;

			foreach ($this->_ci_view_paths as $view_file => $cascade)
			{
				if (file_exists($view_file.$_ci_file))
				{
					$_ci_path = $view_file.$_ci_file;
					$file_exists = TRUE;
					break;
				}

				if ( ! $cascade)
				{
					break;
				}
			}
		}

		if ( ! $file_exists && ! file_exists($_ci_path))
		{
			show_error('Unable to load the requested file: '.$_ci_file);
		}

		// This allows anything loaded using $this->load (views, files, etc.)
		// to become accessible from within the Controller and Model functions.

		$_ci_CI =& get_instance();
		foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
		{
			if ( ! isset($this->$_ci_key))
			{
				$this->$_ci_key =& $_ci_CI->$_ci_key;
			}
		}

		/*
		 * Extract and cache variables
		 *
		 * You can either set variables using the dedicated $this->load_vars()
		 * function or via the second parameter of this function. We'll merge
		 * the two types and cache them so that views that are embedded within
		 * other views can have access to these variables.
		 */
		if (is_array($_ci_vars))
		{
			$this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
		}
		extract($this->_ci_cached_vars);

		/*
		 * Buffer the output
		 *
		 * We buffer the output for two reasons:
		 * 1. Speed. You get a significant speed boost.
		 * 2. So that the final rendered template can be
		 * post-processed by the output class.  Why do we
		 * need post processing?  For one thing, in order to
		 * show the elapsed page load time.  Unless we
		 * can intercept the content right before it's sent to
		 * the browser and then stop the timer it won't be accurate.
		 */
		ob_start();


		// begin addition for MY_Loader
		$script =  file_get_contents($_ci_path);

		// If the PHP installation does not support short tags we'll
		// do a little string replacement, changing the short tags
		// to standard PHP echo statements.

		if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE)
		{
			$script = str_replace('<?=', '<?php echo ', $script);
			$script = preg_replace("/;*\s*\?>/", "; ?>", $script);
		}


		$script = $this->_parse_template_tags($script);
		echo eval('?>'.$script);
		// end modification for MY_Loader

		log_message('debug', 'File loaded: '.$_ci_path);

		// Return the file data if requested
		if ($_ci_return === TRUE)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		/*
		 * Flush the buffer... or buff the flusher?
		 *
		 * In order to permit views to be nested within
		 * other views, we need to flush the content back out whenever
		 * we are beyond the first level of output buffering so that
		 * it can be seen and included properly by the first included
		 * template and any subsequent ones. Oy!
		 *
		 */
		if (ob_get_level() > $this->_ci_ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
			$_ci_CI->output->append_output(ob_get_contents());
			@ob_end_clean();
		}
	}


	//----------------------------------------------------------------------------------

	/**
	 * Parse the template item in the input script
	 * Reproduce the twig template tags
	 * 	
	 * @param  string $script The script content to be parsed
	 * @return string         The script whose content has been parsed
	 */
	function _parse_template_tags($script) {
		$ws = '(?:\s*)'; // optionnal ws
		$ows = '(?:\s+)'; // obligatory ws

		$name = '[a-zA-Z_]{1}[a-zA-Z0-9_]*';
		$const_name = '[A-Z_]{1}[A-Z0-9_]*';
		$var_array = "$name($ws\.$ws\\$?$name)+";

		// '[a-zA-Z_]{1}[a-zA-Z0-9_\.\$]*';
		$var_object =  "$name($ws\>$ws\\$?$name)+";
		// '[a-zA-Z_]{1}[a-zA-Z0-9_\>\$]*';
		$variable_name = '[a-zA-Z_]{1}[a-zA-Z0-9_\[\]\.$\'"\(\)\>-]*';

		$default = array(
			'delimiter' 	=> '/',
			'options' 		=> 'iU',
		);

		$tags = array(
			'php_tags' 	=> array(
				'pattern' 		=> '\{\{\{(.*)\}\}\}',
				'replacement' 	=> '<?php $1 ?>'
			),

			'comments' 	=> array(
				'pattern' 		=> '\{#.*#\}',
				'replacement' 	=> ''
			),


			// FUCNTIONS
			'functions'	=> array(
				'pattern'		=> "\{\{$ws($name$ws\(.*\))$ws\}\}",
				'replacement'	=> "<?php echo $1; ?>"
			),


			// VARIABLES
			'variables_object'	=> array(
				'pattern'		=> "\{\{$ws($var_object)$ws\}\}",

			),

			'variables_array'	=> array(
				'pattern'		=> "\{\{$ws($var_array)$ws\}\}",
			),

			'const'	=> array(
				'pattern'		=> "\{\{$ws($const_name)$ws\}\}",
				'replacement'	=> "<?php echo $1; ?>",
				'options'		=> '' // make case-sensitive
			),

			'variables'	=> array(
				'pattern'		=> "\{\{$ws($name)$ws\}\}",
				'replacement'	=> "<?php echo \$$1; ?>",
			),

			'setvariable' => array(
				'pattern'		=> "\{%(?:$ws)set$ws($name)$ws=$ws(.+)%\}",
				'replacement'	=> '<?php \$$1 = $2; ?>'
			),


			// FOREACH
			'foreach' => array(
				'pattern'		=> "\{%(?:$ws)for$ows($name)(?:$ows)in$ows($name)$ws%\}",
				'replacement'	=> '<?php foreach (\$$2 as \$$1): ?>'
			),

			'foreach_kv' => array(
				'pattern'		=> "\{%(?:$ws)for$ows($name)$ws,$ws($name)(?:$ows)in$ows($name)$ws%\}",
				'replacement'	=> '<?php foreach (\$$3 as \$$1 => \$$2): ?>'
			),

			'endfor' => array(
				'pattern'		=> "\{%(?:$ws)endfor$ws%\}",
				'replacement'	=> '<?php endforeach; ?>'
			),


			// IF ELSE

			'if_const' => array(
				'pattern'		=> "\{%(?:$ws)(if|elseif)$ws($const_name)$ws%\}",
				'replacement'	=> '<?php $1 ($2): ?>'
			),

			'if_name' => array(
				'pattern'		=> "\{%(?:$ws)(if|elseif)$ws($name)$ws%\}",
				'replacement'	=> '<?php $1 ($2): ?>'
			),

			'if' => array(
				'pattern'		=> "\{%(?:$ws)(if|elseif)(.+)%\}",
			),

			'if_isset' => array(
				'pattern'		=> "\{%(?:$ws)(if|elseif)$ws($name)$ows(is)$ows(defined)$ws%\}",
				'replacement'	=> '<?php $1 (isset($2)): ?>'
			),


			'else' => array(
				'pattern'		=> "\{%(?:$ws)else$ws%\}",
				'replacement'	=> '<?php else: ?>'
			),

			'endif' => array(
				'pattern'		=> "\{%(?:$ws)endif$ws%\}",
				'replacement'	=> '<?php endif; ?>'
			),
		);


		foreach ($tags as $tag_name => $tag) {
			if ( ! isset($tag['options']))
				$tag['options'] = $default['options'];

			if ( ! isset($tag['delimiter']))
				$tag['delimiter'] = $default['delimiter'];


			$pattern = $tag['delimiter'] . $tag['pattern'] . $tag['delimiter'] . $tag['options'];
			$replacement = '';
			if (isset($tag['replacement']))
				$replacement = $tag['replacement'];

			$matches = '';

			if ($tag_name == "variables_object")
			{
				preg_match($pattern, $script, $matches);

				if (isset($matches[1])) 
				{
					$exp = $matches[1];
					$exp = str_replace('>', '->', $exp);
					$replacement = "<?php echo \\$$exp; ?>";
				}
			}

			elseif ($tag_name == "variables_array")
			{
				preg_match($pattern, $script, $matches);
				
				if (isset($matches[1])) 
				{
					$exp = $matches[1];
					$exp = preg_replace("#\.($name)#", "['$1']", $exp);
					$replacement = "<?php echo \\$$exp; ?>";
				}
			}

			elseif ($tag_name == "if")
			{
				preg_match($pattern, $script, $matches);
				
				if (isset($matches[2])) 
				{
					$exp = $matches[2];
					$exp = str_replace(' is ', ' == ', $exp);
					$exp = str_replace(' is not', ' != ', $exp);
					$exp = str_replace(' or ', ' || ', $exp);
					$exp = str_replace(' and ', ' || ', $exp);

					$replacement = "<?php $1 ($exp): ?>";
				}
			}
			

			$script = preg_replace($pattern, $replacement, $script);
		}
		
		
		// $file = fopen('test_template.php', 'a+');
		// fwrite( $file, $script);
		// fclose($file);

		return $script;
	}

	function _escape_regex_chars($input) {

	}
}

/* End of file MY_Loader.php */
/* Location: ./application/core/MY_Loader.php */