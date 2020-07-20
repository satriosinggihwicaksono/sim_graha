<?php 
	$tanggal = strtotime(date('Y-m-d'));
	$bulan_kemarin = mktime(0, 0, 0, date("m", $tanggal)-1, 1, date("Y", $tanggal));
?>
<header class="header bg-light dker bg-gradient">
  <p>Laporan Perlengkapan</p>
</header>
<section class="scrollable wrapper">
<div class="content table-responsive table-full-width">
	<table border="1" style="width:100%">	
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
				$this->db->select('user.id,user.username');
				$this->db->where('hakakses !=', 0);
				$this->db->where('hakakses !=', 2);
				$this->db->where('hakakses !=', 3);
				$cabang = $this->db->get('user')->result_array();
				foreach($cabang as $cb){
			?>	
			<tr>
				<td><?=$cb['username'];?></td>
				<td>
				<?php
					$total_omset = $this->mymodel->total_omest($cb['id'],$bulan_kemarin);	
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
					echo $this->mymodel->format($total);
				?>
				</td>
				<?php 
					$total_kas = $this->mymodel->total_kas($cb['id'],$tanggal);
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
					$this->db->where('pesan.presentase <=',100);
					$this->db->join('bukutamu','pesan.id_bukutamu = bukutamu.id');
					$presentase = $this->db->get('pesan')->result_array();

				?>
				<td>
					<?php 
					foreach($presentase as $p){
						echo "".$p['nama']." / ".$p['alamat']." <b>(".$p['presentase']."%)</b><br>";
					} ?>
				</td>

				<td>
				<?php
					$this->db->where('YEAR(pesan.tgl_masuk)',date('Y'));
					$this->db->where('MONTH(pesan.tgl_masuk)',date('m'));
					$this->db->where('pesan.status <=',4);
					$this->db->where('pesan.id_cabang',$cb['id']);
					$this->db->join('bukutamu','pesan.id_bukutamu = bukutamu.id');
					$presentase = $this->db->get('pesan')->result_array();
				
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
						echo "".$p['nama']." / ".$p['alamat']."<b> (".$status.")</b><br>";
					} 
				?>
				</td>
			<tr>	
				<?php } ?>
		</tbody>	
	</table>
</div>
<br>

</section>	