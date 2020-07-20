<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
	$cabang = (int)$t[0];
	$tanggal = (int)$t[1];
	$nama = $t[2];
	$nama = str_replace("%20"," ",$nama);
	$kategori = $t[3];
} else {
	$cabang = '';
	$tanggal = strtotime('Y-m-d');
	$nama = '';
	$kategori = '';
}

?>
<header class="header bg-light dker bg-gradient">
  <p>STOK OPNAME HARIAN</p>
</header>
<section class="scrollable wrapper">
	
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_stok_opname_harian' ?>">
			<th>Cabang : 
			<?php if($check_admin) { ?>	
			<select name="cabang">
						<option value="0"></option>
						<?php	
						$this->db->where('hakakses !=', 2);
						$this->db->where('hakakses !=', 3);
						$user = $this->db->get('user')->result_array();
						foreach($user as $c){ 
						?>
						<option <?php if( $cabang == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
						<?php } ?>
			</select>
			<?php 
				} else {
					echo '<input type="text" value="'.$username.'" disabled/>';
					echo '<input type="hidden" name="cabang" value="'.$id_username.'" />';
				}	
			?>	
				
			</th>	
			<th>Tanggal : 
				<input type="date" name="tanggal" value="<?php if(!empty($tanggal)) echo date('Y-m-d', $tanggal)?>"/>
			</th>
			<th>Nama : 
				<input type="text" name="nama" value="<?php echo $nama; ?>"/>
			</th>	
			<th>Kategori : 
				<select name='kategori'>
					<option value=''>SEMUA</option>
					<?php
					$this->db->where('tipe',0);
					$categories = $this->db->get('kategori_item')->result_array();
					foreach($categories as $c){ ?>
					<option <?php if($kategori == $c['kategori']) { ?> selected="selected" <?php } ?> value='<?php echo $c['kategori']; ?>'><?php echo $c['kategori']; ?></option>
					<?php } ?>
				</select>
			</th>		
			<th><button type="submit" class="btn btn-primary btn-fill btn-wd"><i class="fa fa-search"></i> Cari</button></th>
			</form>	
		</thead>
	</table>
</div>

<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">	
			<thead>
			<th>No</th>	
			<th>Nama Barang</th>
			<th>Stok Awal</th>	
			<th>Stok Keluar</th>	
			<th>Stok Masuk</th>
			<th>Penjualan</th>
			<th>Stok Mutasi Keluar</th>
			<th>Stok Mutasi Masuk</th>	
			<th>Fisik</th>	
		</thead>
		<tbody>
				<?php 
				$x = $this->uri->segment('4') + 1;
					foreach($data as $d){
				?>
				<tr>
					<td><?php echo $x++; ?></td>
					<td><?php echo $d['nama']; ?></td>
					<td>
					<?php 
						$id_item = $d['id'];
						$this->db->where('id_item', $id_item);
						$this->db->where('id_cabang', $cabang);	
						$stok_awal = $this->db->get('stok')->result_array();
						if($stok_awal){
						$stok_awal_masuk = $stok_awal[0]['stok'];
						} else {
						$stok_awal_masuk = 0;	
						}
						echo $stok_awal_masuk;
					?>
					</td>
					<td>
					<?php
				
					if(!empty($tanggal)){
						$this->db->where('YEAR(waktu)',date('Y', $tanggal));
						$this->db->where('MONTH(waktu)',date('m', $tanggal));
						$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
					}
					$this->db->where('id_cabang', $cabang);	
					$stok_masuk = $this->db->get('stok_masuk')->result_array();
					if($stok_masuk){
						$stok_item = array();
						foreach ($stok_masuk as $s){
							$id_stok_masuk = $s['id'];
							$this->db->where('id_stok_masuk', $id_stok_masuk);
							$this->db->where('id_cabang', $cabang);
							$this->db->where('id_item', $d['id']);	
							$sub_stok_masuk = $this->db->get('sub_stok_masuk')->result_array();
							if(!empty($sub_stok_masuk)){
								$stok_item[] = (int)$sub_stok_masuk[0]['unit'];
							}	
						}	
							
							$stok_item = array_sum($stok_item);
							echo $stok_item;
						
					}	
					?>
					</td>
					<td>
					<?php
					
					if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(waktu)',$year);
					if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(waktu)',$month);
		
					if(!empty($tanggal)){
						$this->db->where('YEAR(waktu)',date('Y', $tanggal));
						$this->db->where('MONTH(waktu)',date('m', $tanggal));
						$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
					}
					$this->db->where('id_cabang', $cabang);	
					$stok_masuk = $this->db->get('stok_keluar')->result_array();
					if($stok_masuk){
						$stok_item_keluar = array();
						foreach ($stok_masuk as $s){
							$id_stok_masuk = $s['id'];
							$this->db->where('id_stok_keluar', $id_stok_masuk);
							$this->db->where('id_cabang', $cabang);
							$this->db->where('id_item', $d['id']);
							$sub_stok_masuk = $this->db->get('sub_stok_keluar')->result_array();
							if(!empty($sub_stok_masuk)){
								$stok_item_keluar[] = (int)$sub_stok_masuk[0]['unit'];
							}	
						}	
							$stok_item_keluar = array_sum($stok_item_keluar);
							echo $stok_item_keluar;
					}	
					?>
					</td>
					<td>
					<?php
					$this->db->where('YEAR(tgl_masuk)',date('Y', $tanggal));
					$this->db->where('MONTH(tgl_masuk)',date('m', $tanggal));
					$this->db->where('DAYOFMONTH(tgl_masuk)',date('d', $tanggal));
					$this->db->where('id_pesan',0);
					$this->db->where('id_cabang', $cabang);	
					$penjualan = $this->db->get('pesan')->result_array();
					if($penjualan){
						$stok_penjualan = array();
						foreach ($penjualan as $p){
							$id_total_transaksi = $p['id'];
							$this->db->where('id_total_transaksi', $id_total_transaksi);
							$this->db->where('id_item', $d['id']);
							$sub_transaksi = $this->db->get('sub_transaksi')->result_array();
						
							if(!empty($sub_transaksi)){
								$stok_penjualan[] = (int)$sub_transaksi[0]['unit'];
							}	
						}	
							$stok_total_penjualan = array_sum($stok_penjualan);
							echo $stok_total_penjualan;
					}	
					?>
					</td>
				<form>
					<td>
					<?php 
						if(!empty($tanggal)){
							$this->db->where('YEAR(waktu)',date('Y', $tanggal));
							$this->db->where('MONTH(waktu)',date('m', $tanggal));
							$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
						}
							$mutasi_keluar = array();
							$this->db->where('id_from', $cabang);
							$stok_mutasi = $this->db->get('mutasi')->result_array();
							foreach($stok_mutasi as $m){
								$id_mutasi = $m['id'];
								$this->db->where('id_item', $d['id']);
								$this->db->where('id_mutasi', $id_mutasi);
								$sub_stok_mutasi = $this->db->get('sub_mutasi')->result_array();
								foreach($sub_stok_mutasi as $s_m){
									$s_m_unit = $s_m['unit'];
									$mutasi_keluar[] = $s_m_unit; 
								}
							}
							$mutasi_keluar = array_sum($mutasi_keluar);
							echo $mutasi_keluar;
					?>
					</td>
					<td>
						<?php 
						if(!empty($tanggal)){
							$this->db->where('YEAR(waktu)',date('Y', $tanggal));
							$this->db->where('MONTH(waktu)',date('m', $tanggal));
							$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
						}
							$mutasi_masuk = array();
							$this->db->where('id_to', $cabang);
							$stok_mutasi = $this->db->get('mutasi')->result_array();
							foreach($stok_mutasi as $m){
								$id_mutasi = $m['id'];
								$this->db->where('id_item', $d['id']);
								$this->db->where('id_mutasi', $id_mutasi);
								$sub_stok_mutasi = $this->db->get('sub_mutasi')->result_array();
								foreach($sub_stok_mutasi as $s_m){
									$s_m_unit = $s_m['unit'];
									$mutasi_masuk[] = $s_m_unit; 
								}
							}
							$mutasi_masuk = array_sum($mutasi_masuk);
							echo $mutasi_masuk;
					?>
					</td>
					<td>
					<?php 
						if(!empty($tanggal)){
							$this->db->where('YEAR(waktu)',date('Y', $tanggal));
							$this->db->where('MONTH(waktu)',date('m', $tanggal));
							$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
						}
							$this->db->where('id_item', $d['id']);
							$this->db->where('id_cabang', $cabang);
							$stok_masuk = $this->db->get('stok_laporan')->result_array();
							if($stok_masuk){
								$stok_laporan =  $stok_masuk[0]['stok'];
							} else {
								$stok_laporan =  0;
							}	
							echo $stok_laporan;
					?>
					</td>
				</form>	
				</tr>	
				<?php 
					}
				?>
		</tbody>
	</table>
</div>

<div class="text-center">
			<?php
				if($posisi > 0){
					$page = $this->uri->segment(4) - 10;
					$href = base_url().'index.php/welcome/'.$this->uri->segment(2).'/'.$link.'/'.$page;
					if(!empty($data) && $this->uri->segment(4)){
						echo '<a href ="'.$href.'"><button class="btn btn-info btn-fill btn-wd">previous</button></a>';
					}	
				} else {
					$page = $this->uri->segment(3) - 10;
					$href = base_url().'index.php/welcome/'.$this->uri->segment(2).'/'.$page;
					if(!empty($data) && $this->uri->segment(3)){
						echo '<a href ="'.$href.'"><button class="btn btn-info btn-fill btn-wd">previous</button></a>';
					}	
				}
				echo $this->pagination->create_links();
			?>
</div>
</section>	