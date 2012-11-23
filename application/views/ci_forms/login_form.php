
			<div id="admin_login">
				{{ form_open("admin/login", array("class"=>"form-horizontal")) }} 
					<legend>{{ lang("menu_login") }}</legend>

					{{ get_form_errors() }} 

					<div class="control-group">
						<label class="control-label" for="name">User name, email, or id</label>
						<input type="text" class="controls" id="name" name="name" placeholder="Your user name, email, or id" value="<?php echo $name; ?>">
					</div>
					
					<div class="control-group">
						<label class="control-label" for="password">Password</label>
						<input type="password" class="controls" id="password" name="password" placeholder="Your password" >
					</div>
					
					<input type="submit" name="admin_login_form_submitted" value="Log in" class="btn btn-primary">
					<input type="submit" name="admin_login_form_lostpassword" value="I lost my password" >
				</form>
			</div> 
			<!-- /#admin_login -->
