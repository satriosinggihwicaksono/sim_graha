<!--- NOMINAL -->
<?php 
$this->db->where('id_user', $d['id']);
$gaji = $this->db->get('gaji')->result_array();
if($gaji){
	$id_user = $gaji[0]['id_user'];
}	
?>
<div class="row">
  <div id="modal-gaji<?=$d['id'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/add_slip_gaji/'; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Slip Gaji</h4>
		</div>
		<div class="modal-body">
			<h3><?php echo 'Nama : '.$d['name'].' Cabang : '.$this->mymodel->namaCabang($d['cabang']); ?></h3>
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label>Gaji Pokok</label><br>
						<input type="text" class="form-control border-input" name="gaji_pokok" value="<?php if($gaji) echo $gaji[0]['gaji_pokok']; ?>"/>	
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label>Uang Makan</label><br>
						<input type="text" class="form-control border-input" name="uang_makan" value="<?php if($gaji) echo $gaji[0]['uang_makan']; ?>"/>
						<input type="hidden" name="id_user" value="<?php echo $d['id']; ?>"/>
						<input type="hidden" name="cek_id_user" value="<?php if(!empty($gaji)){ echo $id_user; }?>"/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label>Tunjangan Transport</label><br>
						<input type="text" class="form-control border-input" name="transport" value="<?php if($gaji) echo $gaji[0]['transport']; ?>"/>	
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label>Tunjangan Lain-Lain</label><br>
						<input type="text" class="form-control border-input" name="lain_lain" value="<?php if($gaji) echo $gaji[0]['lain_lain']; ?>"/>	
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label>Tunjangan Komunikasi</label><br>
						<input type="text" class="form-control border-input" name="komunikasi" value="<?php if($gaji) echo $gaji[0]['komunikasi']; ?>"/>	
					</div>
				</div>
			</div>
		  <br>
		</div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-warning"><i class="icon-pencil5"></i> Submit</button>
		  </div>
		</div> 
		</form>
	</div>	
  </div>
</div>
<!--- !Nominal -->	