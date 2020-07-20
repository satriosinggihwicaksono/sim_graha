<!--- NOMINAL -->
<div class="row">
  <div id="modal-ubahbukutamu<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/rubah_bukutamu/'.$d['id']; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Edit Bukutamu</h4>
		</div>
		<div class="modal-body">
			
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label>Nama</label>	
						<input type="text" class="form-control border-input" name="nama" value="<?php echo $d['nama']; ?>" />
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
							<option <?php if( $d['pengimput'] == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-7">
					<div class="form-group">
						<label>Alamat</label>
						<input type="text" class="form-control border-input" name="alamat" value="<?php echo $d['alamat']; ?>" />
					</div>
				</div>
			</div>			
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Telepon</label>
						<input type="text" class="form-control border-input" name="telepon" value="<?php echo $d['telepon']; ?>" style="width:110px"/>
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						<label>Tanggal</label><br>
						<input type="date" name="waktu" value="<?php echo date('Y-m-d', strtotime($d['waktu'])); ?>">
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label>Cabang</label>
						<?php if($check_admin || !$check_admin){ ?>
							<select name="cabang" value="">
								<?php	
								$this->db->where('hakakses !=', 0); 
								$this->db->where('hakakses !=', 3);
								$this->db->where('hakakses !=', 2);
								$user = $this->db->get('user')->result_array();
								foreach($user as $c){ 
								?>
								<option <?php if( $cabang === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
								<?php } ?>
							</select>
						<?php } else {
								echo '<input type="text" name="cabang" class="form-control border-input" value="'.$username.'" disabled>';
							}
						?>
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						<label>Tipe</label>
						<?php 
							$count = count($array);
						?>
						<select name="tipe">
							<?php for($a=0;$a<$count;$a++){?>
							<option <?php if($a == $d['tipe']){echo "selected='selected'";} ?> value="<?=$a?>"><?=$array[$a]?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>	
		  </div>
	
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-warning"><i class="icon-pencil5"></i> Edit</button>
		  </div>
		  	</div> 
		</form>
	</div>	
  </div>
</div>
<!--- !Nominal -->	