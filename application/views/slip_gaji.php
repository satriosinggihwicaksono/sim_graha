<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
	$cabang = $t[0];
	$tgl_awal = $t[1];
	$tgl_akhir = $t[2];
	
} else {
	$cabang = '';
	$tgl_awal = '';
	$tgl_akhir = strtotime(date('Y-m-d'));
}

$this->db->where('id_user',$cabang);
$gaji = $this->db->get('gaji')->result_array();

?>
<header class="header bg-light dker bg-gradient">
  <p>Slip Gaji</p>
</header>
<section class="scrollable wrapper">
<form method="POST" action="<?php echo base_url().'index.php/welcome/search_slip_gaji/'; ?>">
			<td>
				<labe>Awal Periode</labe>
				<input type='date' name='tgl_awal' value="<?php if(!empty($tgl_awal)) echo date('Y-m-d',$tgl_awal); ?>"/>
				<labe>Akhir Periode</labe>
				<input type='date' name='tgl_akhir' value="<?php if(!empty($tgl_akhir)) echo date('Y-m-d',$tgl_akhir); ?>"/>
			</td>
			<?php if($check_admin) { ?>
			<td>
				<select name="cabang" value="">
					<?php	
					$this->db->where('hakakses !=',1);
					$this->db->where('hakakses !=', 0);
					$this->db->where('hakakses !=', 3);
					$user = $this->db->get('user')->result_array();
					foreach($user as $c){ 
					?>
					<option <?php if( $cabang === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
					<?php } ?>
				</select>
			</td>
			<?php } else { ?>
			<td>
				<select name="cabang" value="">
					<?php	
						$this->db->where('cabang', $id_cabang);
						$this->db->where('id !=', $id_username);
						$this->db->where('hakakses !=', 0);
						$this->db->where('hakakses !=', 3);
					$user = $this->db->get('user')->result_array();
					foreach($user as $c){ 
					?>
					<option <?php if( $cabang === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
					<?php } ?>
				</select>
			</td>
			<?php } ?>
			<td><button type="submit" class="btn btn-info btn-fill btn-wd "><li class ="fa fa-search"></li> Cari</button></td>
		</form>	
	<hr>
	<?php 
	if(!empty($cabang) && !empty($tgl_awal)){
    $this->db->where('id_user',$cabang);
	$this->db->where('date >=', date('Y-m-d', $tgl_awal));
	$this->db->where('date <=', date('Y-m-d',$tgl_akhir));
	$slip_absen = $this->db->get('absent')->num_rows();
	if(!empty($slip_absen)){	
		$akhir_hari_tgl_awal = date('t',$tgl_awal) - 4;
		$gaji_pokok = ($slip_absen / $akhir_hari_tgl_awal) * $gaji[0]['gaji_pokok'];
	}	
		
	$this->db->where('id_user',$cabang);
	$this->db->where('lembur',1);
	$this->db->where('date >=', date('Y-m-d', $tgl_awal));
	$this->db->where('date <=', date('Y-m-d',$tgl_akhir));
	$lembur_absent = $this->db->get('absent')->num_rows();
	?>
	<div class="card card-plain">
			<form method="POST" action="<?php echo base_url().'index.php/welcome/cetak_gaji'; ?>" target="_blank">
			<table class="table table-striped m-b-none" style="width:30%">
				<thead>
					<th>RINCIAN</th>
					<th>NOMINAL</th>
					<th style="text-align: right;">TOTAL</th>
				</thead>
				<tbody>
					<tr>
						<th>Jumlah kehadiran</th>
						<th><?php echo $slip_absen; ?></th>
						<input name="kehadiran" type="hidden" value="<?php echo $slip_absen; ?>"/>
						<input name="username" type="hidden" value="<?php echo $cabang; ?>"/>
					</tr>	
					<tr>
						<th>Lembur</th>
						<th><?php if(!empty($lembur_absent)) { echo $lembur_absent; } ?></th>
						<th style="text-align: right;"><?php if(!empty($lembur_absent)) { echo number_format($lembur_absent * 16000); } ?></th>
						<input name="total_lembur" type="hidden" value="<?php echo $lembur_absent * 16000; ?>"/>
						<input name="lembur_absent" type="hidden" value="<?php echo $lembur_absent; ?>"/>
					</tr>
					<tr>
						<th>Gaji Pokok</th>
						<th><?php if(!empty($gaji)) echo number_format($gaji[0]['gaji_pokok']); ?></th>
						<th style="text-align: right;"><?php if(!empty($gaji)) echo number_format($gaji_pokok); ?></th>
						<input name="gaji_pokok" type="hidden" value="<?php if(!empty($slip_absen)) echo $gaji_pokok; ?>"/>
					</tr>
					<tr>
						<th>Uang Makan</th>
						<th><?php if(!empty($gaji)) echo number_format($gaji[0]['uang_makan']); ?></th>
						<th style="text-align: right;"><?php if(!empty($gaji)) echo number_format($gaji[0]['uang_makan'] * $slip_absen); ?></th>
						<input name="uang_makan" type="hidden" value="<?php if(!empty($gaji)) echo $gaji[0]['uang_makan'] * $slip_absen; ?>"/>
					</tr>
					<tr>
						<th>Tunjangan Komunikasi</th>
						<th></th>
						<th style="text-align: right;"><?php if(!empty($gaji)) echo number_format($gaji[0]['komunikasi']); ?></th>
						<input name="komunikasi" type="hidden" value="<?php if(!empty($gaji)) echo $gaji[0]['komunikasi']; ?>"/>
					</tr>
					<tr>
						<th>Tunjangan Lain-lain</th>
						<th></th>
						<th style="text-align: right;"><?php if(!empty($gaji)) echo number_format($gaji[0]['lain_lain']); ?></th>
						<input name="lain_lain" type="hidden" value="<?php if(!empty($gaji)) echo $gaji[0]['lain_lain']; ?>"/>
					</tr>
					<tr>
						<th>Komisi Penjualan</th>
						<th></th>
						<th><input style="direction: rtl; border: 0; font-size: 20px; font-weight: bold; text-align: right; background: transparent; color: #000000;" type="text" name="penjualan" value=""/></th>
						<input name="awal" type="hidden" value="<?php echo $tgl_awal; ?>"/>
						<input name="akhir" type="hidden" value="<?php echo $tgl_akhir; ?>"/>
						<input name="cabang" type="hidden" value="<?php echo $cabang; ?>"/>
					</tr>
					<tr>
						<th>Komisi service/Psg</th>
						<th></th>
						<th><input style="direction: rtl; border: 0; font-size: 20px; font-weight: bold; text-align: right; background: transparent; color: #000000;" type="text" name="service" value=""/></th>
					</tr>
					<tr>
						<th>Bonus Target Omset</th>
						<th></th>
						<th><input style="direction: rtl; border: 0; font-size: 20px; font-weight: bold; text-align: right; background: transparent; color: #000000;" type="text" name="target" value=""/></th>
					</tr>
					<tr>
						<th>Potongan</th>
						<th></th>
						<th><input style="direction: rtl; border: 0; font-size: 20px; font-weight: bold; text-align: right; background: transparent; color: #000000;" type="text" name="potongan" value=""/></th>
					</tr>
					<tr>
						<th><button type="submit" class="btn btn-info btn-fill btn-wd">Bayar</button></th>
						<th></th>
						<th></th>
					</tr>
					
				</tbody>
			</table>
			</form>	
		</div>
	<?php } ?>
</section>		