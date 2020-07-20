<!--- NOMINAL -->
<div class="row">
  <div id="modal-ubah_pembelian_stok<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/ubahitemmasuk/'.$d['id']; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Tambah Daftar Item Keluar</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Tanggal</label><br>
						<input type="date" name="waktu" value="<?php echo date('Y-m-d',strtotime($d['waktu'])); ?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Supplier</label>
						<select name="supplier">
							<option  <?php if( $d['supplier'] == 0) { ?> selected="selected" <?php } ?> value='0'>Non Kulak</option>
							<?php 
								$supplier = $this->db->get('supplier')->result_array(); 
								foreach($supplier as $s){
							?>
							<option <?php if( $d['supplier'] === $s['id']) { ?> selected="selected" <?php } ?> value="<?php echo $s['id'] ?>"><?php echo $s['supplier'] ?></option>
							<?php 
								}
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Deskripsi</label>
						<input type="text" name="deskripsi" class="form-control border-input" value="<?php echo $d['deskripsi']; ?>">
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
							$user = $this->db->get('user')->result_array();
							foreach($user as $c){ 
							?>
							<option <?php if( $d['id_cabang'] === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
							<?php } ?>
						</select>
						<?php } else {
							echo '<input type="hidden" name="cabang" class="form-control border-input" value="'.$d['id_cabang'].'">';
						}
						?>
					</div>
				</div>
			</div>
				</div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-warning"><i class="icon-pencil5"></i> Ubah</button>
		  </div>
		</div> 
		</form>
	</div>	
  </div>
</div>
<!--- !Nominal -->	