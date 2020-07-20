<section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.html"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Workset</li>
              </ul>
              <div class="m-b-md">
				 <h3 class="m-b-none">PENGATURAN PENGGUNA</h3>
              </div>
<?php
	foreach($data as $d)	
?>
<div class="row">
	<div class="col-lg-10 col-md-7">
		<div class="content">
			<form method="POST" action="<?php echo base_url().'index.php/welcome/update_password_pengguna/'.$d['id']; ?>">
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label>Username</label>
							<input type="text" name="username" readonly class="form-control border-input" placeholder="Company" value="<?php echo $d['username'];?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Password lama</label>
							<input type="password" name="old_password" class="form-control border-input" placeholder="Password lama" value="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Password baru</label>
							<input type="password" name="new_password" class="form-control border-input" placeholder="Password baru" value="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Re-password</label>
							<input type="password" name="repassword" class="form-control border-input" placeholder="Repassword" value="">
						</div>
					</div>
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-info btn-fill btn-wd">Ubah Password</button>
				</div>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>