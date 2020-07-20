<!--- NOTA -->
<div class="row">
  <div id="modal-omset" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/update_omset/'; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Target Omset</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
			<label class='col-md-3'>Target Omset</label>
			<div class='col-md-9'>
				<input type="text" name="target" autocomplete="off" value="<?php if(!empty($target)) echo $target; ?>" required placeholder="Target Omset" class="form-control" >
				<input type="hidden" name="id" value="<?php if(!empty($id)) echo $id; ?>" >
				<input type="hidden" name="id_cabang" value="<?php if(!empty($cabang)) echo $cabang; ?>" >
				<input type="hidden" name="waktu" value="<?php if(!empty($date)) echo date('Y-m-d',$date); ?>" >
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