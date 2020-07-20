<!--- NOMINAL -->
<div class="row">
  <div id="modal-presentase<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/update_presentase/'.$d['id']; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Edit Persentase</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
		  	<h5>Presentase</h5>
			
			  <select name="presentase">
				 <?php 
				 	for($t=1;$t<=10;$t++){
						 $m = $t*10; 
						 if($m == $d['presentase']){
							 $select = "selected='selected'";
						 } else {
							 $select = '';
						 }
						 echo "<option ".$select." value='".$m."'>".$m."</option>";	
					 }			 
				?> 	
			  </select>
		  </div>
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