<?php 
	$id_transaksi = $d['id'];
?>
<!--- NOTA -->
<div class="row">
	<div id="modal-edit<?=$d['id'];?>" class="modal fade">
		<div class="modal-dialog">
			<form action="<?php echo base_url().'index.php/welcome/update_addnota/'.$d['id']; ?>" method="post">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Tambah Nota</h4>
				</div>
				<div class="modal-body">
				<div class="form-group">
					<label class='col-md-3'>NOTA</label>
					<div class='col-md-9'><input type="text" name="nota" autocomplete="off" value="<?=$d['nota'];?>" required placeholder="Masukkan Modal" class="form-control" ></div>
				</div>
				<div class="form-group">
					<a href='<?php echo base_url().'index.php/welcome/penjualan/'.$id_transaksi; ?>'><button type="button" class="btn btn-danger">Tambah Item</button></a>
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
<!--- !NOTA -->