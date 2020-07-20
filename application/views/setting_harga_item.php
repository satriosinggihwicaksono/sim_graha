<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
	$cabang = (int)$t[0];
	$nama = $t[1];
	$nama = str_replace("%20"," ",$nama);
	$kategori = $t[2];
	$merek = $t[3];
	$merek = str_replace("%20"," ",$merek);
	$tipe = $t[4];
	$tipe = str_replace("%20"," ",$tipe);
	$total = (int)$t[5];
} else {
	$cabang = '';
	$nama = '';
	$total = 10;
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
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_setting_harga_item' ?>">
			<th>Cabang : 
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
			<th>Harga</th>
		</thead>
		<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_harga_barang_item/'.$cabang; ?>">
		<input name="total" type="hidden" value="<?php echo $total; ?>" />
		<tbody>
				<?php 
				$y = 0;
					foreach($data as $d){
					$y++;
					$this->db->where('id_item',$d['id']);
					$this->db->where('id_cabang',$cabang);
					$harga = $this->db->get('harga_item')->result_array();	
				?>
				<tr>
					<td><?php echo $y; ?></td>
					<td><?php echo $d['nama'].' '.$d['merek'].' '.$d['tipe']; ?></td>
					<td>
						<input type="hidden" name="id_item_<?php echo $y; ?>" value="<?php echo $d['id']; ?>"/>
						<input type="hidden" name="id_<?php echo $y; ?>" value="<?php if(!empty($harga)) echo $harga[0]['id']; ?>" />
						<input name="harga_<?php echo $y; ?>" type="text" value="<?php if(!empty($harga)) echo $harga[0]['harga_jual']; ?>"/>
					</td>
				</tr>	
				<?php
					}
				?>
			<tr>
				<td><input type="hidden" name="jumlah_data" value="<?php echo count($data); ?>" /></td>	
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