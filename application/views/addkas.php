<!--- NOTA -->
<div class="row">
  <div id="modal-tbhkas" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/tambah_kas/'; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Tambah Daftar Kas</h4>
		</div>
		<div class="modal-body">
			  <div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Tanggal Kas</label><br>
						<input type="date" name="waktu" value="<?php echo date('Y-m-d'); ?>" />
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Status</label><br>
						<select name="status">
							<option value="1">Kredit</option>
							<option value="2">Debit</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Nominal</label><br>
						<input type="text" name="saldo" value=""/>
						<input type="hidden" name="id_cabang" value="<?php echo $id_cabang; ?>"/>
					</div>
				</div>
				<?php if($check_admin){?>
				<div class="col-md-4">
					<div class="form-group">
						<label>Cabang</label><br>
							<select name="id_cabang" value="">
								<?php	
								$this->db->where('hakakses !=', 2);
								$this->db->where('hakakses !=', 3);
								$user = $this->db->get('user')->result_array();
								foreach($user as $c){
								?>
								<option <?php if( $id_username === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
								<?php } ?>
							</select>
							</div>
					</div>
							<?php } else { ?>
								<input type="hidden" name="id_cabang" class="form-control border-input" value="<?php echo $id_cabang; ?>">
							<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-7">
					<div class="form-group">
						<label>Keterangan</label><br>
						<textarea name="deskripsi" cols="35%"></textarea>
					</div>
				</div>
			</div>	
		  <br>
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
<!--- !NOTA -->