<!--- NOMINAL -->
<div class="row">
  <div id="modal-nominal<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/update_addnominal/'.$d['id']; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Edit Data Nominal</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
			<label class='col-md-3'>Nominal</label>
			<div class='col-md-9'><input type="text" value="<?php if($nominal) echo $total_nominal; ?>" required placeholder="Masukkan Nominal" class="form-control" disabled></div>
			<input type="hidden" name="nominal" value="<?php if($nominal) echo $total_nominal; ?>">	
		  </div>
		<br>
		<br>	
		  <div class="form-group">
			<label class='col-md-3'>Bayar</label>
			<div class='col-md-9'><input type="text" name="bayar" value="<?php if($nominal) echo $bayar_nominal; ?>" required placeholder="Masukkan Bayar" class="form-control" ></div>
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
<!--- !Nominal -->	