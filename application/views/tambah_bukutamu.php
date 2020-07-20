<!--- NOMINAL -->
<div class="row">
  <div id="modal-tbhbukutamu" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/add_bukutamu/'; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Tambah Costumer</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Nama</label>
						<input type="text" name="nama" class="form-control border-input" value="">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Tanggal</label><br>
						<input type="date" name="waktu" value="<?php date('d-m-Y'); ?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Pengimput</label>
							<select name="pengimput">
								<option value="0"></option>
								<?php 
								if($check_admin) { 	
									$this->db->where('hakakses !=', 0);
									$this->db->where('hakakses !=', 1);
									$this->db->where('hakakses !=', 3);
								} else {
									$this->db->where('cabang',$id_cabang);
								}
								$user = $this->db->get('user')->result_array();
								foreach($user as $c){ 
								?>
								<option value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
								<?php } ?>
							</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Alamat</label>
						<input type="text" name="alamat" class="form-control border-input" value="">

					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Nomer HP</label>
						<input type="text" name="telepon" class="form-control border-input" placeholder="0853xxxxxxx" value="" onkeypress="return isNumberKey(event)">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?php if($check_admin){ ?>
						<label>Cabang</label>
						<br>
						<select name="cabang" value="">
							<?php	
							$this->db->where('hakakses !=', 2);
							$this->db->where('hakakses !=', 3);
							$this->db->where('hakakses !=', 0); 
							$user = $this->db->get('user')->result_array();
							foreach($user as $c){ 
							?>
							<option <?php if( $c['username'] === $c['username']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
							<?php } ?>
						</select>
						<?php } else {
							echo '<input type="hidden" name="cabang" class="form-control border-input" value="'.$sub_cabang[0]['id'].'">';
						}
						?>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Tipe Costumer</label>
						<select name="tipe">
							<option value="1">Perorangan</option>
							<option value="2">Unit Usaha</option>
							<option value="3">Pemerintah</option>
						</select>
					</div>
				</div>
			</div>

				</div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-warning"><i class="icon-pencil5"></i> Tambah</button>
		  </div>
		</div> 
		</form>
	</div>	
  </div>
</div>
<!--- !Nominal -->	