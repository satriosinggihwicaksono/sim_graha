<!--- NOTA -->
<div class="row">
  <div id="modal-editkas<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/ubah_kas/'.$d['id']; ?>" method="post">
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
						<input type="date" name="waktu" value="<?php echo date('Y-m-d', strtotime($d['waktu'])); ?>" />
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Status</label><br>
						<select name="status">
							<?php
								$status_array = array("","Kredit","Debit");
								$count_status=count($status_array);
								for($c=1; $c<$count_status; $c+=1){ ?>
									<option <?php if($d['status'] == $c) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $status_array[$c] ?></option>";
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
						<label>Nominal</label><br>
						<input type="text" name="saldo" value="<?php echo $d['saldo']; ?>"/>
						<input type="hidden" name="id_cabang" value="<?php echo $d['id_cabang']; ?>"/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-7">
					<div class="form-group">
						<label>Keterangan</label><br>
						<textarea name="deskripsi" cols="35%"><?php echo $d['deskripsi']; ?></textarea>
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
<!--- !NOTA -->