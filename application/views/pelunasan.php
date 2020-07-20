<!--- NOMINAL -->
<div class="row">
  <div id="pelunasan<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/pelunasan/'.$d['id']; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Pelunasan</h4>
		</div>
		<div class="modal-body">
		<div class="row">
			<label class='col-md-2'>Nominal</label>
			<div class='col-md-5'>
				<input style="text-align:right" type="text" value="<?php if($total) echo $this->mymodel->format($total); ?>" required placeholder="Masukkan Nominal" class="form-control" disabled>
			</div>
		</div>
		<div class="row">
			<label class='col-md-2'>Awal Bayar</label>
			<div class='col-md-5'>
				<input style="text-align:right" type="text" value="<?php if($bayar) echo $this->mymodel->format($bayar); ?>"  class="form-control" disabled>
			</div>
		</div>	
		<?php 
			if(!empty($pelunasan)){
			$total_pelunasan = array();	
			foreach($pelunasan as $p){
			$total_pelunasan[] = $p['bayar'];
		?>	
		<div class="row">
			<label class='col-md-2'><?php echo date('d/M/Y',$p['waktu']); ?></label>
			<div class='col-md-5'>
				<input style="text-align:right" type="text" value="<?php if($p['bayar']) echo $this->mymodel->format($p['bayar']); ?>"  class="form-control" disabled>
			</div>
			<label class='col-md-2'>
			<a href="<?php echo base_url().'index.php/welcome/deleteItem/pelunasan/'.$p['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
			</label>
		</div>	
		<?php
				}
			 $total_pelunasan =  array_sum($total_pelunasan);	
			} else {
				$total_pelunasan = 0;
			} 
			$hutang = ((int)$bayar + (int)$total_pelunasan) - (int)$total;
		?>	
		<div class="row">
			<label class='col-md-2'>Kekurangan</label>
			<div class='col-md-5'>
				<input type="hidden" name="kekurangan" value="<?php echo (int)$total - (int)$bayar; ?>"/>
				<input style="text-align:right" type="text" value="<?php if($hutang) echo $this->mymodel->format($hutang); ?>" required placeholder="Masukkan Nominal" class="form-control" disabled>
			</div>
		</div>
		<div class="row">
			<label class='col-md-2'>Pelunasan</label>
			<div class='col-md-5'>
				<input style="text-align:right" name="pelunasan" type="text" value="" required placeholder="Nominal Pelunasan" class="form-control">
			</div>
			<div class='col-md-3'>
		  		<input type="date" name="waktu" value="<?php echo date('Y-m-d',strtotime($d['waktu'])); ?>">
		  	</div>
		</div>	
		<br>
		</div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-warning"><i class="icon-pencil5"></i> Bayar</button>
		  </div>
		</div> 
		</form>
	</div>	
  </div>
</div>
<!--- !Nominal -->	