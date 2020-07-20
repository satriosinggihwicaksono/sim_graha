<div class="row">
  <div id="modal-tbhorder" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/add_new_order/'; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Tambah Order</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label>Nama</label><br>
							<input type="text" name="nama" class="form-control border-input" value="">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Tanggal</label><br>
							<input type="date" class="form-control border-input" name="tgl_masuk" value="<?php echo date("Y-m-d"); ?>" style="width:200px" >
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
							<label>Nomer Hp</label>
							<input type="text" name="telepon" class="form-control border-input" placeholder="0853xxxxxxx" value="">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Followup</label><br>
							<select name="followup" value="">
							<option></option>
							<?php	
							 if($check_admin) { ?>	
								<?php	
								$this->db->where('hakakses !=', 0);
								$this->db->where('hakakses !=', 1);
								$this->db->where('hakakses !=', 3);
								$user = $this->db->get('user')->result_array();
								foreach($user as $c){ 
								?>
								<option value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
								<?php } ?>
							<?php 
								} else {
								$this->db->where('cabang', $id_cabang);
								$user = $this->db->get('user')->result_array();
								foreach($user as $c){ 
								?>
								<option  value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
								<?php 
									}
								 }
								?>
							</select>	
						</div>
					</div>	
					<?php if($username == 'ADMIN'){?>
					<div class="col-md-4">
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
										<option <?php if( $cabang == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
										<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<label>Sumber data</label>
						<select name="nama_transaksi">
							<option value="0">Datang Langsung</option>
							<option value="1">Online</option>
						</select>
					</div>
					<div class="col-md-3">
						<label>Kredit / Cash </label>
						<select name="kondisi">
							<option value="1">Kredit</option>
							<option value="2">Cash</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label>Seles</label>
							<input type="text" name="seles" class="form-control border-input" value="">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Teknisi</label>
							<input type="text" name="teknisi" class="form-control border-input" value="" style="width:200px" >
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Nota</label>
							<input type="text" name="nota" class="form-control border-input" value="">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Tipe</label>
							<select name="tipe">
								<option value="1">Perorangan</option>
								<option value="2">Unit Usaha</option>
								<option value="3">Pemerintah</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label>Rencana Order</label>
							<textarea type="text" name="keterangan" class="form-control border-input"></textarea>
						</div>
					</div>
				</div>
			</div>
		  <br>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-warning"><i class="icon-pencil5"></i> Add</button>
		  </div>
		</form>
	</div>	
  </div>
</div>