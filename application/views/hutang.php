<!--- NOMINAL -->
<div class="row">
  <div id="modal-hutang<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/hutang/'.$d['id']; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Membayar Utang Piutang</h4>
		</div>
		<div class="modal-body">
		  <div class="row">
			<label class='col-md-2'>Nominal</label>
			  <div class='col-md-5'>
					<input style="text-align:right" type="text" value="<?php if($nominal) echo $this->mymodel->format($total_nominal); ?>" required placeholder="Masukkan Nominal" class="form-control" disabled>
			  </div>
		  </div>
			<?php 
				$total_bayar = array();
			  	if($d['id_pesan'] == 0){
			  		$id_pesan = $d['id'];
					$this->db->where('id', $id_pesan);
				} else {
					$id_pesan = $d['id_pesan'];
					$this->db->where('id_pesan', $id_pesan); 
				}
				$pesan = $this->db->get('pesan')->result_array();
			  	$tanggal_bayar = strtotime($pesan[0]['tgl_masuk']);
				$data_bayar = $pesan[0]['bayar'];
				$data_bayar = explode(',',$data_bayar);
				$dp = $data_bayar[1];
				$total_bayar[] = $dp;
			 ?>
		  <div class="row">
			<label class='col-md-2'><?php echo date('Y-m-d', $tanggal_bayar); ?></label>
			  <div class='col-md-5'>
					<input style="text-align:right" type="text" value="<?php if(!empty($data_bayar)) echo $this->mymodel->format($data_bayar[1]); ?>" required placeholder="Masukkan Nominal" class="form-control" disabled>	  
			  </div>  
			  <div class='col-md-5'>
				  <label>DP</label>
			  </div>  
		  </div>
		<?php 
			$this->db->where('id_pesan', $id_pesan); 
			$pesan = $this->db->get('pesan')->result_array();
			if(!empty($pesan)){
			foreach($pesan as $p){
				$tanggal_bayar = strtotime($p['tgl_masuk']);
				$data_bayar = $p['bayar'];
				$data_bayar = explode(',',$data_bayar);
				$dp = $data_bayar[1];
				$total_bayar[] = $dp;
					
		 ?>	
		<div class="row">
			<label class='col-md-2'><?php echo date('Y-m-d', $tanggal_bayar); ?></label>
			  <div class='col-md-5'>
					<input style="text-align:right" type="text" value="<?php if(!empty($data_bayar)) echo $this->mymodel->format($data_bayar[1]); ?>" required placeholder="Masukkan Nominal" class="form-control" disabled>	  
			  </div>  
			  <div class='col-md-5'>
					<a href="<?php echo base_url().'index.php/welcome/deletePesan/pesan/'.$p['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
			  </div>  
		</div>
		<?php 
				}
			}	 
		$total_bayar = array_sum($total_bayar);
		$kekurangan = $total_nominal - $total_bayar;	
		$kekurangan_array[] = $kekurangan;
		?>	
		  <div class="row">
			<label class='col-md-2'>Kekurangan</label>
			  <div class='col-md-5'>
					<input style="text-align:right" type="text" value="<?php if($nominal) echo $this->mymodel->format($kekurangan); ?>" required placeholder="Masukkan Nominal" class="form-control" disabled>
				  	<input type="hidden" name="id_pesan" value="<?php echo $id_pesan; ?>">
				  	<input type="hidden" name="nominal" value="<?php echo $total_nominal; ?>">
			  </div>
		  </div>		
		  <div class="row">
			<label class='col-md-2'>Bayar</label>
				<div class='col-md-5'>
					<input type="text" name="bayar" value="" required placeholder="Masukkan Nominal" class="form-control" >	
				</div>
			  	<div class='col-md-3'>
					<input type="date" name="tgl_masuk" value="<?php echo date('Y-m-d'); ?>" >	
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