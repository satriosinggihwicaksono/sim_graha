<header class="header bg-light dker bg-gradient">
  <p>Laporan Omset dan Kas</p>
</header>
<section class="scrollable wrapper">
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:15%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_laporan_kas' ?>">
			<th>Tanggal : 
				<input type="date" name="tanggal" value="<?php if(!empty($tanggal)) echo date('Y-m-d', $tanggal);?>"/>
			</th>	
				
			<th><button type="submit" class="btn btn-primary btn-fill btn-wd"><i class="fa fa-search"></i> Cari</button></th>
			</form>	
		</thead>
	</table>
</div>

<div class="content table-responsive table-full-width">
	<table style="font-family: sans-serif; color:#232323; width:100%;" border="1">	
			<thead style="background: #35A9DB;">
			<tr style="color: #fff;">	
				<th rowspan ='3' style="text-align:center;"><?php echo date('d M Y',$tanggal);?></th>	
				<th rowspan ='3' style="text-align:center;" >
					GRAND TOTAL <br>
					<?php 
						$bulan_kemarin = mktime(0, 0, 0, date("m", $tanggal)-1, 1, date("Y", $tanggal));
						$hari_sesudah = mktime(0, 0, 0, date("m", $tanggal), date("d", $tanggal)+1, date("Y", $tanggal));
						$hari_kemarin = mktime(0, 0, 0, date("m", $tanggal), date("d", $tanggal)-1, date("Y", $tanggal));
						echo date('M Y', $bulan_kemarin);
					?>
				</th>
				<th colspan='2' style="text-align:center;">Sebelum</th>
				<th colspan='2' style="text-align:center;">Transaksi</th>	
				<th colspan='2' style="text-align:center;">Sesudah</th>
				<th colspan='2' style="text-align:center;">Target</th>
				<th rowspan ='3' style="text-align:center;">Kas</th>
			</tr>
			<tr style="color: #fff;">
				<td style="text-align:center;">IN</td>
				<td style="text-align:center;">OUT</td>
				<td style="text-align:center;">IN</td>
				<td style="text-align:center;">OUT</td>
				<td style="text-align:center;">IN</td>
				<td style="text-align:center;">OUT</td>
				<td style="text-align:center;">Target</td>
				<td style="text-align:center;">Pencapaian</td>
			</tr>
			</thead>
			<tbody>
				<?php 
					$kulak_bln_kemarin = $this->mymodel->total_kulak($bulan_kemarin); 
					$kulak_sebelum = $this->mymodel->total_kulak_terkini($hari_kemarin);
					$kulak_terkini = $this->mymodel->total_kulak_hariini($tanggal);
					$nonkulak_bln_kamarin = $this->mymodel->total_nonkulak($bulan_kemarin);
					$nonkulak_sebelum = $this->mymodel->total_nonkulak_terkini($hari_kemarin);
					$nonkulak_terkini = $this->mymodel->total_nonkulak_hariini($tanggal);
					$total_kulak_kemarin = $kulak_sebelum + $nonkulak_sebelum;
					$total_kulan_terkini = $kulak_terkini + $nonkulak_terkini;
					$total_kulak = $kulak_sebelum + $kulak_terkini;
					$total_nonkulak = $nonkulak_sebelum + $nonkulak_terkini;
					$grand_total_kulak = $total_kulak + $total_nonkulak;
				?>
			<tr>
				<td>Kulak</td>
				<td style="text-align: right;"><?php echo $this->mymodel->format($kulak_bln_kemarin); ?></td>
				<td></td>
				<td style="text-align: right;"><?php echo $this->mymodel->format($kulak_sebelum); ?></td>
				<td></td>
				<td style="text-align: right;"><?php echo $this->mymodel->format($kulak_terkini); ?></td>
				<td></td>
				<td style="text-align: right;"><?php echo $this->mymodel->format($total_kulak); ?></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Non Kulak</td>
				<td style="text-align: right;"><?php echo $this->mymodel->format($nonkulak_bln_kamarin); ?></td>
				<td></td>
				<td style="text-align: right;"><?php echo $this->mymodel->format($nonkulak_sebelum); ?></td>
				<td></td>
				<td style="text-align: right;"><?php echo $this->mymodel->format($nonkulak_terkini); ?></td>
				<td></td>
				<td style="text-align: right;"><?php echo $this->mymodel->format($total_nonkulak); ?></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>	
			<?php
				$total_target = array();
				$this->db->where('hakakses !=', 2);
				$this->db->where('hakakses !=', 0);
				$this->db->where('hakakses !=', 3);
				$this->db->order_by('urutan', 'asc');
				$data = $this->db->get('user')->result_array();
				$grand_total = array();
				$sebelum_total = array();
				$total_saat_ini = array();
				$total_kas_array = array();
				$total_sesudah_array = array();
				$total_kas = array();
				foreach($data as $d){
			?>	
			<tr>
				
				<td bgcolor='yellow'><?php echo $d['name']; ?></td>
				<td style="text-align: right;">
				<?php
					$total_omset = $this->mymodel->total_omest($d['id'],$bulan_kemarin);	
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
				<td style="text-align: right;">
				<?php
					$total_hari_kemarin = $this->mymodel->total_omest_hari($d['id'],$hari_kemarin);
					$total_kemarin = array();	
					foreach($total_hari_kemarin as $e){
						if(gettype($e) == 'array'){
							$e = array_sum($e);
							} else {
								$e =0;
							}
						$total_kemarin[] = $e;
					}	
					$total_kemarin = array_sum($total_kemarin);
					if(date('d',$tanggal) == 1){
						$total_kemarin = 0;
					}
					$sebelum_total[] = $total_kemarin;
					
					echo $this->mymodel->format($total_kemarin);
				?>
				</td>
				<td style="text-align: right;">0</td>
				<td style="text-align: right;">
				<?php
					$total_hari_ini = $this->mymodel->omset_hari_ini($d['id'],$tanggal);
					$total_ini = array();	
					foreach($total_hari_ini as $e){
						if(gettype($e) == 'array'){
							$e = array_sum($e);
							} else {
								$e =0;
							}
						$total_ini[] = $e;
					}	
					$total_ini = array_sum($total_ini);
					$total_saat_ini[] = $total_ini;
					echo $this->mymodel->format($total_ini);
					$total_sesudah = $total_kemarin + $total_ini;
					$total_sesudah_array[] = $total_sesudah;
				?>
				</td>
				<td style="text-align: right;">0</td>
				<td style="text-align: right;"><?php echo $this->mymodel->format($total_sesudah); ?></td>
				<td style="text-align: right;">0</td>			
				<?php	
				$this->db->where('id_cabang',$d['id']);
				$omzet = $this->db->get('omset')->result_array();
				foreach($omzet as $o){
					$id = $o['id'];
					$id_cabang = $o['id_cabang'];
					$target = $o['target'];
					$waktu = $o['waktu'];
				}	
				if(!empty($target)){
					$presentasi_omset = ($total_sesudah / $target) * 100;
				}	else {
					$presentasi_omset = 0;
				}
				$total_target[] = $target;
				?>
				<td style="text-align: right;"><?php echo $this->mymodel->format($target); ?></td>
				<td style="text-align: right;"><?php echo number_format($presentasi_omset,2).'%'; ?></td>
				<?php 
					$total_kas = $this->mymodel->total_kas($d['id'],$tanggal);
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
			<tr>	
			<?php 
				} 
			?>	
			<tr>
				<th>Total</th>	
				<th style="text-align:right;" >
					<?php echo $this->mymodel->format(array_sum($grand_total)); ?>
				</th>
				<th colspan='1' style="text-align:right;">
					<?php echo $this->mymodel->format(array_sum($sebelum_total)); ?>
				</th>
				<th colspan='1' style="text-align:right;">
					<?php echo $this->mymodel->format($total_kulak_kemarin); ?>
				</th>
				<th colspan='1' style="text-align:right;">
					<?php echo $this->mymodel->format(array_sum($total_saat_ini)); ?>				
				</th>
				<th colspan='1' style="text-align:right;">
					<?php echo $this->mymodel->format($total_kulan_terkini); ?>				
				</th>
				<th colspan='1' style="text-align:right;">
					<?php echo $this->mymodel->format(array_sum($total_sesudah_array)); ?>
				</th>
				<th colspan='1' style="text-align:right;">
					<?php echo $this->mymodel->format($grand_total_kulak); ?>
				</th>
				<th colspan='1' style="text-align:right;">
					<?php echo $this->mymodel->format(array_sum($total_target)); ?>
				</th>
				<?php
					$presentasi_omset_total = (array_sum($total_sesudah_array) / array_sum($total_target)) * 100;
				 ?>
				<th colspan='1' style="text-align:right;">
					<?php echo number_format($presentasi_omset_total,2).'%'; ?>
				</th>
				<th style="text-align:right;">
					<?php echo $this->mymodel->format(array_sum($total_kas_array)); ?>
				</th>
			<tr>
		</tbody>
	</table>
</div>
<br>

</section>	