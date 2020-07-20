<section class="panel panel-default bg-white m-t-lg">
	<header class="panel-heading text-center">
	  <strong>Sign in</strong>
	</header>
		<form class="panel-body wrapper-lg" method="post" action="<?php echo base_url().'index.php/auth/sigup' ?>">
			<center><img src="<?php echo base_url();?>assets/images/logo.png" alt="..." class="section-heading" style="width:100px"/></center>
			<p style="color:red;"><?php echo $this->session->flashdata('message'); ?></p>
			<div class="row">
				<div class="form-group">
					<label class="control-label">USERNAME </label>
					<input name="username"class="form-control input-lg" type="text" placeholder="" value="<?php echo set_value('username'); ?>">
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<label class="control-label">PASSWORD </label>
					<input name="password" class="form-control input-lg" type="password" placeholder="">
				</div>
			</div>
			<br/>
			 <button type="submit" class="btn btn-primary">Sign in</button>
			<p class="text-muted text-center"><small>Do not have an account?</small></p>
			<a href="<?php echo base_url();?>index.php/auth/register" class="btn btn-default btn-block">Create an account</a>
		</form>
</section>