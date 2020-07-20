<?php
$page = $this->uri->segment(2);
switch($page){
	case 'daftar_barang_inventaris';
		$tipe = 2;
		break;
	case 'daftar_barang_perlengkapan';
		$tipe = 1;
		break;	

}
?>
<!--- NOMINAL -->
<div class="row">
  <div id="modal-tbhbarang_pelengkapan" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/tambah_barang_perlengkapan/'; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Tambah Barang</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Nama</label><br>
						<input type="text" class="form-control border-input" name="nama" value="" style="width:200px"/>	
						<input type="hidden" name="tipe" value="<?php echo $tipe; ?>"/>	
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Kategori</label><br>
						<select name="kategori">
						<?php
						$this->db->where('tipe',$tipe);
						$kategori = $this->db->get('kategori_item')->result_array();
						foreach($kategori as $k){ ?>
						<option value='<?php echo $k['id']; ?>'><?php echo $k['kategori']; ?></option>
						<?php } ?>
						</select>	
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