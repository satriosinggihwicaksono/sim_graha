<div class="row">
  <div id="modal-order<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/rubah_order/'; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Ubah Order</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label>Nama</label>
							<input type="text" name="nama" class="form-control border-input" value="<?php if($d['nama'])  echo $d['nama']; ?>">
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label>Follow Up</label>
							<br>
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
								<option <?php if( $d['followup'] == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
								<?php } ?>
							<?php 
								} else {
								$this->db->where('cabang', $id_cabang);
								$user = $this->db->get('user')->result_array();
								foreach($user as $c){ 
								?>
								<option <?php if( $d['followup'] === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
								<?php 
									}
								 }
								?>
							</select>	
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Alamat</label>
							<input type="text" name="alamat" class="form-control border-input" value="<?php if($d['alamat']) echo $d['alamat']; ?>">
							<input type="hidden" name="id_bukutamu" class="form-control border-input" value="<?php if($d['id_bukutamu']) echo $d['id_bukutamu']; ?>">
							<input type="hidden" name="id_pesan" class="form-control border-input" value="<?php echo $d['id']; ?>">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Nomer HP</label>
							<input type="text" name="telepon" class="form-control border-input" placeholder="0853xxxxxxx" value="<?php if(!$check_admin && !$check_marketing) echo $this->mymodel->encrypt($d['telepon']);
						if(($check_admin || $check_marketing)) echo $d['telepon']; ?>">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Tipe</label>
						<?php 
							$array = array('Undetified','Perorangan','Badan usaha','Pemerintah');
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
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Tanggal Masuk</label>
							<input type="date" name="tgl_masuk" value="<?php echo date('Y-m-d', strtotime($d['tgl_masuk'])); ?>" >
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Nota</label>
							<input type="text" name="nota" class="form-control border-input" value="<?php echo $d['nota']; ?>" >
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Cabang</label>
							<br>
							<select name="id_cabang" value="">
								<?php	
								$this->db->where('hakakses !=', 0); 
								$this->db->where('hakakses !=', 2);
								$this->db->where('hakakses !=', 3);
								$user = $this->db->get('user')->result_array();
								foreach($user as $c){ 
								?>
								<option <?php if( $d['id_cabang'] === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Total</label>
							<input type="text" name="total" class="form-control border-input" value="<?php if($d['bayar']) echo $total_nominal; ?>" disabled />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Bayar</label>
							<input type="text" name="bayar" class="form-control border-input" value="<?php if($d['bayar']) echo $bayar_nominal; ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Seles</label>
							<input type="text" name="seles" class="form-control border-input" value="<?php echo $d['seles']; ?>">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Teknisi</label>
							<input type="text" name="teknisi" class="form-control border-input" value="<?php echo $d['teknisi']; ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
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
										$this->db->where('id!=',$id_username);
										$this->db->where('cabang',$id_username);
									}
									$user = $this->db->get('user')->result_array();
									foreach($user as $c){ 
									?>
									<option <?php if( $d['input'] == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
									<?php } ?>
								</select>
						</div>
					</div>
					<div class="col-md-4">
						<label>Sumber data</label>
						<br>
						<select name="nama_transaksi">
						<?php 
							$month_array = array("Datang Langsung","Online");
							$count_month=count($month_array);
							for($c=0; $c<$count_month; $c+=1){ ?>
								<option <?php if($c == $d['sum_trans']) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $month_array[$c] ?></option>";
						<?php
							}
						?>
						</select>
					</div>	
					<div class="col-md-3">
						<label>Kredit / Cash</label>
						<br>
						<select name="kondisi">
						<?php 
							$month_array = array("","Kredit","Cash");
							$count_month=count($month_array);
							for($c=1; $c<$count_month; $c+=1){ ?>
								<option <?php if($c == $d['kondisi']) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $month_array[$c] ?></option>";
						<?php
							}
						?>
						</select>
					</div>	
				</div>		
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label>Keterangan Order</label>
							<textarea type="text" name="keterangan" class="form-control border-input"><?php echo $d['keterangan']; ?></textarea>
						</div>
					</div>
				</div>
			</div>
		  <br>
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