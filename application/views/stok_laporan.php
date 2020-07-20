<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
	$cabang = (int)$t[0];
	$tanggal = (int)$t[1];
	$total = (int)$t[2];
	$nama = $t[3];
	$nama = str_replace("%20"," ",$nama);
	$kategori = $t[4];
	$merek = $t[5];
	$merek = str_replace("%20"," ",$merek);
	$tipe = $t[6];
	$tipe = str_replace("%20"," ",$tipe);
} else {
	$cabang = '';
	$tanggal = strtotime(date('Y-m-d'));
	$total = '';
	$nama = '';
	$merek = '';
	$tipe = '';
	$kategori = '';
}

?>
<header class="header bg-light dker bg-gradient">
  <p>STOK LAPORAN</p>
</header>
<section class="scrollable wrapper">
	
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_stok_laporan' ?>">
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
					echo '<input type="hidden" name="cabang" value="'.$id_username.'"/>';
				}	
			?>	
			</th>	
			<th>Tanggal : 
				<input type="date" name="tanggal" value="<?php if(!empty($tanggal)) echo date('Y-m-d', $tanggal)?>"/>
			</th>
			<th>Jumlah data : 
				<select name="jumlah">
					<?php 
					$jumlah = array('',10,20,30,40,50);
					foreach($jumlah as $j){ ?>
					<option <?php if($total == $j){ echo 'selected="selected"'; } ?>value="<?php echo $j;?>"><?php echo $j;?></option>
					<?php
						}
					?>
				</select>
			</th>
			<th>Nama : 
				<input type="text" name="nama" value="<?php if(!empty($nama)) echo $nama;?>"/>
			</th>
			<th>Merek : 
				<input type="text" name="merek" value="<?php if(!empty($merek)) echo $merek;?>"/>
			</th>
			<th>Tipe : 
				<input type="text" name="tipe" value="<?php if(!empty($tipe)) echo $tipe;?>"/>
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
			<th>Merek</th>
			<th>Tipe</th>
			<th>Kategori</th>
			<th>Stok</th>
		</thead>
		<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_stok_laporan'; ?>">
		<tbody>
				<?php 
				$y = 0;
				$x = 1;
					foreach($data as $d){
					$y = $y+1;
				?>
				<tr>
					<td><?php echo $x++; ?></td>
					<td><?php echo $d['nama']; ?></td>
					<td><?php echo $d['merek']; ?></td>
					<td><?php echo $d['tipe']; ?></td>
					<td><?php echo $d['kategori']; ?></td>
					<?php
					
					if(!empty($tanggal)){
						$this->db->where('YEAR(waktu)',date('Y', $tanggal));
						$this->db->where('MONTH(waktu)',date('m', $tanggal));
						$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
					}
							
					$this->db->where('id_item', $d['id']);	
					$this->db->where('id_cabang', $cabang);
					$stok_l = $this->db->get('stok_laporan')->result_array();
					if(empty($stok_l)){
						$this->db->where('MONTH(waktu)',date('m', $tanggal));
						$this->db->order_by("waktu", "desc");	
						$this->db->where('id_item', $d['id']);	
						$this->db->where('id_cabang', $cabang);
						$stok_l = $this->db->get('stok_laporan')->result_array();
					}	
					?>
					<td>
					<input type="hidden" name="waktu" value="<?php echo date('Y-m-d',(int)$tanggal); ?>"/>
					<input type="text" name="stok<?php echo $y; ?>" value="<?php if(!empty($stok_l)) echo $stok_l[0]['stok'];?>" style="width:100px"/>
					<input type="hidden" name="id_cabang" value="<?php if(empty($cabang)){ echo $id_username; } else { echo $cabang; } ?>" />
					<input type="hidden" name="id_item<?php echo $y; ?>" value="<?php if(!empty($d['id'])) echo $d['id']; ?>" />
					<input type="hidden" name="id_stok_laporan<?php echo $y; ?>" value="<?php if(!empty($stok_l)) echo $stok_l[0]['id']; ?>" />		
					</td>
				</tr>	
				<?php
					}
				?>
			<tr>
				<td><input type="hidden" name="jumlah_data" value="<?php echo count($data); ?>" /></td>	
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<th><input type="submit" value="Simpan"/></th>
			</tr>
			</tbody>
		</form>	
	</table>
	
</div>

<div class="text-center">
			<?php
				if($posisi > 0){
					$page = $this->uri->segment(4) - 10;
					$href = base_url().'index.php/welcome/'.$this->uri->segment(2).'/'.$link.'/'.$page;
					if(empty($data) && $this->uri->segment(4)){
						echo '<a href ="'.$href.'"><button class="btn btn-info btn-fill btn-wd">previous</button></a>';
					}	
				} else {
					$page = $this->uri->segment(3) - 10;
					$href = base_url().'index.php/welcome/'.$this->uri->segment(2).'/'.$page;
					if(empty($data) && $this->uri->segment(3)){
						echo '<a href ="'.$href.'"><button class="btn btn-info btn-fill btn-wd">previous</button></a>';
					}	
				}
				echo $this->pagination->create_links();
			?>
</div>
</section>	