<div class="row">
  <div id="modal-tbhorderbukutamu<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/add_new_order/'.$d['id']; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Tambah PreOrder</h4>
		</div>
		<div class="modal-body">
<?php
$this->db->where('username',$username);	
$user = $this->db->get('user')->result_array();
?>
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label>Nama</label>
						<input type="text"  class="form-control border-input" value="<?php echo $d['nama']; ?>" disabled>
						<input type="hidden" name="nama" value="<?php echo $d['nama']; ?>" >
						<input type="hidden" name="id_bukutamu" value="<?php echo $d['id']; ?>">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Tanggal Masuk</label>
						<input type="date" name="tgl_masuk" value="<?php echo date("Y-m-d"); ?>" style="width:200px">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Alamat</label>
						<input type="text" class="form-control border-input" value="<?php echo $d['alamat']; ?>" disabled>
						<input type="hidden" name="alamat" value="<?php echo $d['alamat']; ?>">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Nomer Hp</label>
						<input type="text" class="form-control border-input" placeholder="0853xxxxxxx" value="<?php echo $d['telepon']; ?>" disabled>
						<input type="hidden" name="telepon" placeholder="0853xxxxxxx" value="<?php echo $d['telepon']; ?>" >
					</div>
				</div>
				<?php if($username == 'ADMIN'){?>
				<div class="col-md-3">
					<div class="form-group">
						<label>Cabang</label><br>
						<select name="cabang" value="">
							<?php	
							$this->db->where('hakakses !=', 0);
							$this->db->where('hakakses !=', 2);
							$this->db->where('hakakses !=', 3);
							$user = $this->db->get('user')->result_array();
							foreach($user as $c){ 
							?>
							<option <?php if( $c['username'] === $c['username']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
							<?php } ?>
						</select>
						</div>
				</div>	
				<?php } else { ?>
							<input type="hidden" name="cabang" class="form-control border-input" value="<?php echo $sub_cabang[0]['cabang']; ?>">
						<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Nama Pengimput</label>
							<br>	
							<?php if($check_admin) { ?>	
							<select name="pengimput">
										<option value="0"></option>
										<?php	
										$this->db->where('hakakses !=', 0);
										$this->db->where('hakakses !=', 2);
										$this->db->where('hakakses !=', 3);
										$user = $this->db->get('user')->result_array();
										foreach($user as $c){ 
										?>
										<option <?php if( $cabang == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
										<?php } ?>
							</select>
							<?php 
								} else {
									echo '<input type="text" value="'.$username.'" disabled/>';
									echo '<input type="hidden" name="pengimput" value="'.$id_username.'" />';
								}	
							?>	
					</div>
				</div>
				<div class="col-md-3">
					<label>Sumber data</label>
					<select name="nama_transaksi">
						<option value="0">Datang Langsung</option>
						<option value="1">Online</option>
					</select>
				</div>	
			</div>
			<div class="row">
				<div class="col-md-7">
					<div class="form-group">
						<label>Rencana Order</label>
						<textarea type="text" name="keterangan" class="form-control border-input"></textarea>
					</div>
				</div>
			</div>
	</div>
		  
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-warning"><i class="icon-pencil5"></i> Add</button>
		  </div>
		</div> 
		</form>
	</div>	
  </div>
</div>