<!--- NOMINAL -->
<div class="row">
  <div id="modal-tbhmutasi" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/tambah_mutasi/'; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Tambah Barang</h4>
		</div>
		<div class="modal-body">
			<div calss="row">
				<input type="date" name="waktu" value="<?php echo date('Y-m-d'); ?>"/>
			</div>	
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label>From</label><br>
						<?php if($check_admin) { ?>	
							<select name="from">
										<option value="0"></option>
										<?php	
										$this->db->where('hakakses !=', 2);
										$this->db->where('hakakses !=', 3);
										$user = $this->db->get('user')->result_array();
										foreach($user as $c){ 
										?>
										<option <?php if( $id_username == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
										<?php } ?>
							</select>
							<?php 
								} else {
									echo '<input type="text" name="cabang" value="'.$id_username.'" disabled/>';
								}	
							?>	
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label>To</label><br>
						<?php if($check_admin) { ?>	
							<select name="to">
										<option value="0"></option>
										<?php	
										$this->db->where('id !=', $id_username);
										$this->db->where('hakakses !=', 2);
										$user = $this->db->get('user')->result_array();
										foreach($user as $c){ 
										?>
										<option <?php if( $id_username == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
										<?php } ?>
							</select>
							<?php 
								} else {
									echo '<input type="text" name="cabang" value="'.$id_username.'" disabled/>';
								}	
							?>	
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label>Deskripsi</label>
						<textarea name="deskripsi"></textarea>
					</div>
				</div>
			</div>	
		  <br>
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