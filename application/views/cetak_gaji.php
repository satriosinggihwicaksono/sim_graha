<!DOCTYPE html>
<html>
	<head>
		<title>Cetak Gaji</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css" type="text/css" />
	
	</head>
	<body>
		<div style="padding:20px 20px 20px 20px">
		<h3><?php echo 'SLIP GAJI CV.MGU '.date('Y'); ?></h3>
		<h4><?php echo 'Nama : '.$this->mymodel->namaCabang($username); ?></h4>
		<h4><?php echo date('d-m-Y', $awal).' s/d '.date('d-m-Y', $akhir); ?></h4>
		<table class="table table-striped m-b-none" style="width:50%">
				<thead style="font-size=20px;">
					<th><h5><b>RINCIAN</b></h5></th>
					<th style="text-align: center;"><h5><b>NOMINAL</b></h5></th>
					<th style="text-align: right;"><h5><b>TOTAL</b></h5></th>
				</thead>
				<tbody>
					<tr>
						<th><h5>Jumlah kehadiran</h5></th>
						<th style="text-align: center;"><h5><?php echo (int)$kehadiran; ?></h5></th>
						<th></th>
					</tr>	
					<tr>
						<th><h5>Lembur</h5></th>
						<th style="text-align: center;"><h5><?php echo (int)$lembur_absent; ?></h5></th>
						<th style="text-align: right;"><h5><?php echo (int)$total_lembur; ?></h5></th>
					</tr>
					<tr>
						<th><h5>Gaji Pokok</h5></th>
						<th></th>
						<th style="text-align: right;"><h5><?php echo number_format((int)$gaji_pokok); ?></h5></th>
					</tr>
					<tr>
						<th><h5>Uang Makan</h5></th>
						<th></th>
						<th style="text-align: right;"><h5><?php echo number_format((int)$uang_makan); ?></h5></th>
					</tr>
					<tr>
						<th><h5>Tunjangan Komunikasi</h5></th>
						<th></th>
						<th style="text-align: right;"><h5><?php echo number_format((int)$komunikasi); ?></h5></th>
					</tr>
					<tr>
						<th><h5>Tunjangan Lain-lain</h5></th>
						<th></th>
						<th style="text-align: right;"><h5><?php echo number_format((int)$lain_lain); ?></h5></th>
					</tr>
					<tr>
						<th><h5>Komisi Penjualan</h5></th>
						<th></th>
						<th style="text-align: right;"><h5><?php echo number_format((int)$penjualan); ?></h5></th>
					</tr>
					<tr>
						<th><h5>Komisi service/Psg</h5></th>
						<th></th>
						<th style="text-align: right;"><h5><?php echo number_format((int)$service); ?></h5></th>
					</tr>
					<tr>
						<th><h5>Bonus Target Omset</h5></th>
						<th></th>
						<th style="text-align: right;"><h5><?php echo number_format((int)$target); ?></h5></th>
					</tr>
					<tr>
						<th><h5>Potongan</h5></th>
						<th></th>
						<th style="text-align: right;"><h5><?php echo number_format((int)$potongan); ?></h5></th>
					</tr>
					<?php 
						$total = (int)$total_lembur + (int)$gaji_pokok + (int)$uang_makan + (int)$komunikasi + (int)$lain_lain + (int)$lain_lain +(int)$penjualan + (int)$service + (int)$target - (int)$potongan;
					?>
					<tr>
						<th><h5>Total</h5></th>
						<th></th>
						<th style="text-align: right;"><h5><b><?php echo number_format($total); ?></b></h5></th>
					</tr>
				</tbody>
			</table>
			<table class="table table-striped m-b-none" style="width:50%">
				<thead>
					<th style="text-align:center;"><h5><b>Bag. Admin</b></h5></th>
					<th style="text-align:center;"><h5><b>Penerima</b></h5></th>
				</thead>
				<body>
					<tr>
						<th>
							<br>
							<br>
							<br>
							<br>
							<br>
						</th>
						<th>
							<br>
							<br>
							<br>
							<br>
							<br>
						</th>
					</tr>
					<tr>
						<th style="text-align:center;">ttd</th>
						<th style="text-align:center;">ttd</th>
					</tr>
					<tr>
						<th></th>
						<th style="text-align:center;"><h5><?php echo $this->mymodel->namaCabang($username); ?></h5></th>
					</tr>
				</body>
			</table>
		</div>	
		<script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
	</body>	
</html>