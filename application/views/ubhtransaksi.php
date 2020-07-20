<?php 
$deskripsi = $d['deskripsi'];
if(!empty($deskripsi)){
	$deskripsi = explode(',',$deskripsi);
	$pembeli = $deskripsi[0];
	$alamat = $deskripsi[1];
	$keterangan = $deskripsi[2];
}
?>
<div class="modal fade" id="ubhtransaksi<?php echo $d['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'index.php/transaksi/ubah_transaksi/'.$d['id'] ?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="waktu" placeholder="" value="<?php if(!empty($d['waktu'])) echo date('Y-m-d',strtotime($d['waktu'])); ?>">
				</div>
				<div class="row">
					<label>Nota :</label>
					<input type="text" class="form-control border-input" name="nota" placeholder="" value="<?php if(!empty($d['nota'])) echo $d['nota']; ?>" style="width:200px;">
				</div>
				<div class="row">
					<label>Pembeli :</label>
					<input type="text" class="form-control border-input" name="pembeli" placeholder="" value="<?php if(!empty($deskripsi)) echo $pembeli; ?>" style="width:200px;">
				</div>
				<div class="row">
					<label>Alamat :</label>
					<input type="text" class="form-control border-input" name="alamat" placeholder="" value="<?php if(!empty($deskripsi)) echo $alamat; ?>" style="width:300px;">
				</div>
				<div class="row">
					<label>Telepone :</label>
					<input name="keterangan" class="form-control border-input" value="<?php if(!empty($deskripsi)) echo $keterangan; ?>" style="width:300px;"/>
				</div>
				<div class="row">
					<label>Cabang :</label>
					<?php if($check_admin){ ?>
					<select name="cabang" >
						<option></option>
						<?php
						$cabang = $this->db->get('cabang')->result_array();
						foreach($cabang as $c){ ?>
							<option <?php if($d['cabang'] == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
						<?php } ?>
					</select>
					<?php } else {
						echo '<input class="form-control border-input" value="'.$this->komputer->namaCabang($d['cabang']).'"  style="width:200px;" disabled/>';
						echo '<input type="hidden" name="cabang" value="'.$d['cabang'].'" />';
					}
					?>
				</div>
				<?php if($check_admin){ ?>
				<div class="row">
					<label>Seles :</label>
					<select name="seles" >
						<?php
						$this->db->where('hakakses',2);
						$user = $this->db->get('user')->result_array();
						foreach($user as $u){ ?>
							<option <?php if($d['id_user'] == $u['id']){ echo 'selected="selected"'; } ?> value='<?php echo $u['id']; ?>'><?php echo $u['name']; ?></option>
						<?php } ?>
					</select>
				</div>
				<?php } ?>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Ubah Transaksi</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>