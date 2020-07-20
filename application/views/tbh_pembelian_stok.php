<!--- NOMINAL -->
<div class="row">
  <div id="modal-tbhitemmasuk" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/tbhpembelianitemmasuk/'; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Tambah Kulak dan Non Kulak</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Tanggal</label><br>
						<input type="date" name="waktu" value="<?php echo date('Y-m-d'); ?>">
						<input type="hidden" name="tipe" value="1">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Supplier</label><br>
						<select name="supplier">
						<option value='0'>Non Kulak</option>
						<?php 
						$kategori = $this->mymodel->getItem('supplier');
						foreach($kategori as $k){ ?>
						<option value='<?php echo $k['id']; ?>'><?php echo $k['supplier']; ?></option>
						<?php } ?>
						</select>	
					</div>
				</div>	
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Deskripsi</label>
						<input type="text" name="deskripsi" class="form-control border-input" value="">

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
							<option <?php if( $id_cabang === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
							<?php } ?>
						</select>
						<?php } else {
							echo '<input type="hidden" name="cabang" class="form-control border-input" value="'.$sub_cabang[0]['id'].'">';
						}
						?>
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