<!DOCTYPE html>
<html>
	<head>
	<style type="text/css">
		body {
			font-family: "Lucida Sans Unicode", "Lucida Grande", "Segoe Ui";
		}
		
		/* Table */
		.demo-table {
			border-collapse: collapse;
			font-size: 13px;
		}
		.demo-table th, 
		.demo-table td {
			border: 1px solid #e1edff;
			padding: 7px 17px;
		}
		.demo-table .title {
			caption-side: bottom;
			margin-top: 12px;
		}
		
		/* Table Header */
		.demo-table thead th {
			background-color: #508abb;
			color: #FFFFFF;
			border-color: #6ea1cc !important;
			text-transform: uppercase;
		}
		
		/* Table Body */
		.demo-table tbody td {
			color: #353535;
		}
		.demo-table tbody td:first-child,
		.demo-table tbody td:last-child,
		.demo-table tbody td:nth-child(4) {
			text-align: left;
		}
		.demo-table tbody tr:nth-child(odd) td {
			background-color: #f4fbff;
		}
		.demo-table tbody tr:hover td {
			background-color: #ffffa2;
			border-color: #ffff0f;
			transition: all .2s;
		}
		
		/* Table Footer */
		.demo-table tfoot th {
			background-color: #e5f5ff;
		}
		.demo-table tfoot th:first-child {
			text-align: left;
		}
		.demo-table tbody td:empty
		{
			background-color: #ffcccc;
		}
		</style>
	</head>
	<body>
<?php 
	$tanggal = $date;
?>
	<form method="POST" action="">
		<div class="form-group">
			<label>Pencarian</label>
			<input type="date" name="tanggal" class="" value="<?=date('Y-m-d',$tanggal)?>" /> 
			<button type="submit" value="Cari">Search</button>
		</div>
	</form>	
	<table class="demo-table">
		<thead>
			<tr>	
				<th>Unit Usaha</th>
				<th>Omset</th>
				<th>Kas</th>
				<th>Presentase Pekerjaan</th>
				<th>Potensi Order</th>
			<tr>
		</thead>
		<tbody>
			<?php
				$total_omset_t = array();
				$total_kas_t = array();
				$this->db->select('user.id,user.username');
				$this->db->where('hakakses !=', 2);
				$this->db->where('hakakses !=', 3);
				$cabang = $this->db->get('user')->result_array();
				foreach($cabang as $cb){
			?>	
			<tr>
				<td><?=$cb['username'];?></td>
				<td>
				<?php
					$total_omset = $this->mymodel->total_omest($cb['id'],$tanggal);	
					$total = array();	
					foreach($total_omset as $c){
						if(gettype($c) == 'array'){
							$c = array_sum($c);
							} else {
								$c =0;
							}
						$total[] = $c;
					}	
					$total = array_sum($total);
					$grand_total[] = $total;
					$total_omset_t[] = $total;
					echo $this->mymodel->format($total);
				?>
				</td>
				<?php 
					$total_kas = $this->mymodel->total_kas($cb['id'],$tanggal);
					$total_kas_t[] = $total_kas;
					if($total_kas >= 250000){
						$warna = '#F5DEB3';
					} else {
						$warna = '';
					}
				?>
				<td style="text-align: right; background-color:<?php echo $warna; ?>;">
					<?php 
						echo $this->mymodel->format($total_kas);
						$total_kas_array[] = $total_kas;
					?>
				</td>
				<?php
					$this->db->where('YEAR(pesan.tgl_masuk)',date('Y'));
					$this->db->where('MONTH(pesan.tgl_masuk)',date('m'));
					$this->db->where('pesan.status',4);
					$this->db->where('pesan.id_cabang',$cb['id']);
					$this->db->where('pesan.presentase <',100);
					$this->db->join('bukutamu','pesan.id_bukutamu = bukutamu.id');
					$presentase = $this->db->get('pesan')->result_array();

				?>
				<td>
					<?php
					echo "<div style='width:100%; height: 50px; margin: 0; padding: 0; overflow-y: scroll;'>"; 
					foreach($presentase as $p){
						echo "".$p['nama']." / ".$p['alamat']." <b>(".$p['presentase']."%)</b><br>";
					} 
					echo "</div>";
					?>
				</td>

				<td>
				<?php
					$this->db->where('YEAR(pesan.tgl_masuk)',date('Y'));
					$this->db->where('MONTH(pesan.tgl_masuk)',date('m'));
					$this->db->where('pesan.status <',4);
					$this->db->where('pesan.id_cabang',$cb['id']);
					$this->db->join('bukutamu','pesan.id_bukutamu = bukutamu.id');
					$presentase = $this->db->get('pesan')->result_array();
					echo "<div style='width:100%; height: 50px; margin: 0; padding: 0; overflow-y: scroll;'>";
					$y = 1;
					foreach($presentase as $p){
						$status2 = $p['status']; 
						if($status2 == 0){
							$status = "belum di proses";
						} elseif($status2 == 2){
							$status = "negosiasi";
						} elseif($status2 == 3){
							$status = "cancel";
						} else {
							$status = "belum di proses";
						}
						
						echo $y++.".".$p['nama']."/".$p['alamat']."<b>(".$status.")</b><br>";
					
					} 
					echo "</div>";
				?>
				</td>
			</tr>	
				<?php } ?>
			<tr>
					<td>Total</td>
					<td><?=$this->mymodel->format(array_sum($total_omset_t))?></td>
					<td style="text-align:right;"><?=$this->mymodel->format(array_sum($total_kas_t))?></td>
					<td colspan="2"></td>
			</tr>	
		</tbody>	
	</table>
	</body>
</html>