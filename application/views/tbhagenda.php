<!--- NOMINAL -->
<div class="row">
  <div id="modal-tbhagenda" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/tambah_agenda/'; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Tambah agenda</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Tanggal</label><br>
						<input type="date" class="form-control border-input" name="tanggal" value="<?php echo date('Y-m-d'); ?>"/>	
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Waktu</label><br>
						<input type="time" name="waktu" value=""/>	
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Cabang</label><br>
						<select name="id_cabang">
						<?php
						$this->db->where('hakakses',1);
						$users = $this->db->get('user')->result_array();
						foreach($users as $u){ ?>
						<option value='<?php echo $u['id']; ?>'><?php echo $u['username']; ?></option>
						<?php } ?>
						</select>	
					</div>
				</div>	
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Deskripsi</label><br>
						<textarea class="form-control border-input" name="deskripsi"></textarea>	
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