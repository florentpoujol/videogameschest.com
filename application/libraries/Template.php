<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {

	private $_common_name = "[a-zA-Z_]{1}[a-zA-Z_0-9]+";
	//$_common_name = "alpha{1}[alphanum_]+";

	private $_schema_start = "\{\{\s*";
	private $_schema_end = "\s*\}\}";



	function parse( $input ) {
		$output = $input;

		$schemas = array(
			array(
				"pattern" => "(".$this->_common_name."\s*\(.*\))",
				"replacement" => '$1'
			),

			array(
				"pattern" => "(".$this->_common_name.")",
				"replacement" => '\$$1'
			),

			
		);

		foreach ($schemas as $schema) {
			$output = preg_replace(
				"#".$this->_schema_start.$schema["pattern"].$this->_schema_end."#",
				"<?php ".$schema["replacement"]."; ?>",
				$output
			);
		}

		return $output;
	}
}