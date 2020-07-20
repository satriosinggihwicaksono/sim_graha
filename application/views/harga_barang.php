<!--- NOMINAL -->
<div class="row">
  <div id="modal-hargaitem<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/harga_pokok_baru/'.$d['id']; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Harga Barang</h4>
		</div>
		<div class="modal-body">
			<?php 
				$total_harga = array();
				$this->db->select('*,sub_stok_masuk.id as id_stok_masuk');
			  	$this->db->where('id_item', $d['id']); 
				$this->db->join('stok_masuk', 'id_stok_masuk = stok_masuk.id', 'left');
				$harga_item = $this->db->get('sub_stok_masuk')->result_array();
			  	foreach($harga_item as $h){
				$harga = $h['harga'];
				$total_harga[] = (int)$harga;	
				if(!empty($harga_item)){
			 ?>
				<div class="row">
					<label class='col-md-4'><?php echo date('d-m-Y', strtotime($h['waktu'])); ?></label>
					  <div class='col-md-5'>
							<input style="text-align:right" type="text" value="<?php echo $this->mymodel->format($harga); ?>" required placeholder="Masukkan Nominal" class="form-control" disabled>
					  </div>
					  <div class = col-md-3>
					  		<a href="<?php echo base_url().'index.php/welcome/delete/sub_stok_masuk/'.$h['id_stok_masuk'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
					  </div>	  
				</div>	
		<?php
			}
				}
		$count = count($total_harga);
		if(!empty($total_harga)){
			$t_harga = array_sum($total_harga) / $count;		
		} else {
			$t_harga = 0;
		}	
		?>	
		  <div class="row">
			<label class='col-md-4'>Rata-rata Harga Pokok</label>
			  <div class='col-md-5'>
					<input style="text-align:right" type="text" value="<?php echo $this->mymodel->format($t_harga); ?>" class="form-control" disabled>
			  </div>
		  </div>		
		  <div class="row">
			<label class='col-md-4'>Masukan Harga Baru</label>
				<div class='col-md-5'>
					<input type="text" name="harga_pokok" value="" required placeholder="Masukkan Nominal" class="form-control" >	
				</div>
					<input type="hidden" name="waktu" value="<?php echo date('Y-m-d'); ?>" >
			  		<input type="hidden" name="id_item" value="<?php echo $d['id']; ?>" >
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
<!--- !Nominal -->	