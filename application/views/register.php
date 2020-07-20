<section class="panel panel-default bg-white m-t-lg">
	<header class="panel-heading text-center">
	  <strong>Sign in</strong>
	</header>
		<form class="panel-body wrapper-lg" method="post" action="<?php echo base_url().'index.php/auth/registration' ?>">
			<center><img src="<?php echo base_url();?>assets/images/logo.png" alt="..." class="section-heading" style="width:100px"/></center>
			<div class="row">
				<div class="form-group">
					<label class="control-label">Name </label>
					<input name="name" id="name" class="form-control input-lg" type="text" placeholder="" value="<?php echo set_value('name'); ?>"> <h4 style="color:red;"> <?php echo form_error('name'); ?> </h4>
					<input name="hakakses" type="hidden" placeholder="" value="2">
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<label class="control-label">Username </label>
					<input name="username" id="username" class="form-control input-lg" type="text" placeholder="" value="<?php echo set_value('username'); ?>"> <h4 style="color:red;"> <?php echo form_error('username'); ?> </h4>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<label class="control-label">Password </label>
					<input name="password" id="password" class="form-control input-lg" type="password" placeholder="" value="<?php echo set_value('password'); ?>"> <h4 style="color:red;"> <?php echo form_error('password'); ?> </h4>
				</div>
			</div>	
			<div class="row">
				<div class="form-group">
					<label class="control-label">Re Password </label>
					<input name="repassword" id="repassword" class="form-control input-lg" type="password" placeholder="" value="<?php echo set_value('repassword'); ?>"> <h4 style="color:red;"> <?php echo form_error('repassword'); ?> </h4> 
				</div>
			</div>	
			 <button type="submit" class="btn btn-primary">Register</button>
		</form>
</section>