<div class="row">
  <div id="modal-status<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/tambah_proses_order/'.$d['id']; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Edit Status</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
				<label>Status</label>
				<select name="status" class="form-control border-input">
					<option value="0">-Select-</option>
					<option value="2">Negoisasi</option>
					<option value="3">Cancel</option>
					<option value="4">Deal</option>
				</select>
				<label>Keterangan</label>
				<textarea class="form-control border-input" name="keterangan"></textarea>
				<input type="hidden" name="id_pesan" value="<?php echo $d['id']; ?>"/>
			</div>
						<?php
							$x = 1;
							$this->db->where('id_pesan',$d['id']);
							$proses_pesan = $this->db->get('proses_pesan')->result_array();
							foreach($proses_pesan as $p){ 
							$tgl = $p['tanggal']; $tgl = new DateTime($tgl); $tgl = $tgl->getTimeStamp();		
							if($p['status'] == 1 ){
								$status = '<span class="fa fa-refresh" style="color:brown"> PROSES</span>';
							} elseif($p['status'] == 2) {
								$status = '<span class="fa fa-check-square" style="color:blue"> NEGOISASI</span>';
							} elseif($p['status'] == 3) {
								$status = '<span class="fa fa-times" style="color:red"> CANCLE</span>';
							} elseif($p['status'] == 4) {
								$status = '<span class="fa  fa-chevron-down" style="color:green"> DEAL</span>';
							}
						?>
							<?php echo $x++; ?> |
							<?php echo date("d/m/Y h:i" , $tgl); ?> |
							<?php echo $status; ?> |
							<?php echo $p['keterangan']; ?> |	
							<a href="<?php echo base_url().'index.php/welcome/deleteProsesitem/proses_pesan/'.$p['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
							<br>			
						<?php } ?>
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