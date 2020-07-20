<header class="header bg-light dker bg-gradient">
	  <p>TAMBAH PENGGUNA</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
	<form class="form-signin" method="post" action="<?php echo base_url().'index.php/welcome/add_pengguna' ?>">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label>Name</label>
				<input name="name" id="name" class="form-control border-input" type="text" placeholder="" value="<?php echo set_value('name'); ?>"> <?php echo form_error('name'); ?> 
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label>Username </label>
				<input name="username" id="username" class="form-control border-input" type="text" placeholder="" value="<?php echo set_value('username'); ?>"> <?php echo form_error('username'); ?> 
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label>Password </label>
				<input name="password" id="password" class="form-control border-input" type="password" placeholder="" value="<?php echo set_value('password'); ?>"> <?php echo form_error('password'); ?> 
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label>Re-enter password </label>
				<input name="repassword" id="repassword" class="form-control border-input" type="password" placeholder="" value="<?php echo set_value('repassword'); ?>"> <?php echo form_error('repassword'); ?> 
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">
				<div class="form-group">
					<label>Cabang </label> 	
						<select name="cabang">
							<option></option>
							<?php	
							$this->db->where('hakakses !=', 0);
							$this->db->where('hakakses !=', 2);
							$this->db->where('hakakses !=', 3);
							$user = $this->db->get('user')->result_array();
							foreach($user as $c){ 
							?>
						<option value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
						<?php } ?>
				</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Hak akses </label> 	
						<select name="hakakses" value="">
							<?php
							$hakakses = array('Admin','Cabang','Karyawan');
							$count_hakakses = count($hakakses);
							for($e=0; $e<$count_hakakses; $e+=1){ ?>
								<option value=<?php echo $e; ?> > <?php echo $hakakses[$e] ?></option>";
							<?php
								 }	
							?>
					</select>
				</div>
			</div>
		</div>
		<br/>
		<input class="button-submit" type="submit" value="Register">
	</form>
</div>	
</section>	