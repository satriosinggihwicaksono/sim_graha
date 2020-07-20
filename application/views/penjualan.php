<?php 
$this->db->where('id', $this->uri->segment(3));
$stok_keluar = $this->db->get('pesan')->result_array();
$nama_cabang = $this->mymodel->namaCabang($stok_keluar[0]['id_cabang']);
$name = $this->uri->segment(4);
$name = str_replace('%20',' ',$name);

$nominal = $stok_keluar[0]['bayar'];
if(!empty($nominal)){
	$nominal = explode(',',$nominal);
	$bayar = $nominal[1];
}
?>
<header class="header bg-light dker bg-gradient">
  <p>REKAP TRANSAKSI ORDER</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
	<div class="col-sm-3">
	<table class="table table-striped m-b-none">
		<thead>
			<th>Biaya Service</th>
			<th>actions</th>
		</thead>
		<tr>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_sub_service/'; ?>">
				<input type="hidden" name="id_pesan" value="<?php echo $this->uri->segment(3); ?>"/>
				<td><input type="text" onkeypress="return isNumberKey(event)" name="service" value="" style="width:150px" /></td>
				<td><button type="submit" class="btn btn-info btn-fill btn-wd">Tambah Service</button></td>
			</form>
		</tr>	
	</table>
	</div>	
	<div class="col-sm-5">
		<br>
		<label><b>Search Item</b></label> <br>
		<form method="POST" action="<?php echo base_url().'index.php/welcome/search_penjualan/'; ?>">
		<div class="input-group">
			<input type="hidden" name="id_pesan" value="<?php echo $this->uri->segment(3); ?>">		
			<input type="text" name="search" class="form-control" placeholder="Search" value="<?php echo $name; ?>">
				<span class="input-group-btn">
					<button type="submit" class="btn btn-info btn-icon"><i class="fa fa-search"></i></button>
				</span>
		</div>	
		</form>		
	</div>
	<div class="col-sm-1"></div>
	<div class="col-sm-3">
	Tanggal : <?php echo date('d-m-Y', strtotime($stok_keluar[0]['tgl_masuk'])); ?> <br>
	Keterangan : <?php echo $stok_keluar[0]['keterangan']; ?>	<br>
	Cabang : <?php echo $nama_cabang; ?><br>
	</div>	
</div>	
	
<?php 
	if(!empty($this->uri->segment(4))){
	$this->db->like('nama',$name);
	$searching = $this->db->get('item')->result_array();
?>	
	<div class="content table-responsive table-full-width">
		<table class="table table-striped m-b-none" style="width:40%">
			<thead>
				<th>Nama</th>
				<th>Unit</th>
				<th>actions</th>
			</thead>
				<?php 
					foreach($searching as $s){
				?>
				<tr>
				<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_penjualan/'; ?>">
					<td><?php echo $s['nama'].' '.$s['merek'].' '.$s['tipe']; ?></td>
					<input type="hidden" name="code" value="<?php echo $s['code']; ?>"/>
					<input type="hidden" name="id_pesan" value="<?php echo $this->uri->segment(3); ?>" style="width:50px"/>
					<td><input type="text" onkeypress="return isNumberKey(event)" name="unit" value="" style="width:50px"/></td>
					<td><button type="submit" class="btn btn-info btn-fill btn-wd">Tambah</button></td>
				</form>
				</tr>	
			<?php } ?>	
		</table>
	</div>
<?php
	}
?>	
	
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">	
			<thead>
			<th>No.</th>
			<th>Nama</th>
			<th>Unit</th>	
			<th>Harga</th>	
			<th>Total</th>	
			<th>Actions</th>
		</thead>
		<tbody>
			<?php
				$total = array();
				$total_unit = array();
				$x = 1;
				foreach($data as $d){
				$harga = $d['harga_jual'];
				$unit = $d['unit'];
				$sub_total = $harga * $unit;
				$total_unit[] = $unit;
				$total[] = $sub_total;
				$this->db->where('id', $d['id_item']);
				$item = $this->db->get('item')->result_array();
				if(!empty($item)){	
					$full_name = $item[0]['nama'].' '.$item[0]['merek'].' '.$item[0]['tipe'];
				} else {
					$full_name = 'Service';
				}	
					
				if(!empty($item)){
					$warna = '#ffffcc';
				} else {
					$warna = '#90EE90';
				}	
			?>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/ubah_penjualan/'.$d['id'] ?>">
			<tr>
				<td style="background-color:<?php echo $warna; ?>;"><?php echo $x++; ?></td>
				<td style="background-color:<?php echo $warna; ?>;"><?php echo $full_name; ?></td>
				<td style="background-color:<?php echo $warna; ?>;">
					<?php if(!empty($item)){ ?>
					<input name="unit" type="text" value="<?php echo $unit ?>" style="width:50px" />
					<?php } ?>
				</td>
				<td style="background-color:<?php echo $warna; ?>;"><input name="harga" type="text" value="<?php echo $harga ?>" /></td>
				<td style="text-align:right; background-color:<?php echo $warna; ?>;"><?php echo $sub_total; ?></td>
				<td style="background-color:<?php echo $warna; ?>;">
				<input type="submit" value="<?php echo 'Ubah'; ?>" class="btn btn-info"/>	
				<a href="<?php echo base_url().'index.php/welcome/deleteItem/sub_transaksi/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger">Hapus</a>
				</td>
			</tr>
			</form>	
			<?php 
				}
			?>
			<tr>
				<td colspan="2" style="text-align:left; background-color:#F5DEB3;">
					<b>TOTAL</b>
				</td>
				<td colspan="1" style="background-color:#F5DEB3;"><?php echo array_sum($total_unit); ?></td>
				<td colspan="1" style="text-align:center; background-color:#F5DEB3;"></td>
				<td style="text-align:right; background-color:#F5DEB3;"><b><?php echo array_sum($total); ?></b></td>
				<td style="background-color:#F5DEB3;"></td>
			</tr>
			<form action="<?php echo base_url().'index.php/welcome/update_addnominal/'.$this->uri->segment(3); ?>" method="post">
			<tr>
			<input type="hidden" name="nominal" value="<?php if($total) echo array_sum($total); ?>" />
			<td colspan="4" style="text-align:left; background-color:#F0F8FF;">
				<b>BAYAR</b>
			</td>
			<td style="text-align:right; background-color:#F0F8FF;"><input name="bayar" type="text" value="<?php if(!empty($nominal)) echo $bayar ?>" /></td>
			<td style="background-color:#F0F8FF;"><input type="submit" value="Bayar"/></td>
			</tr> 
			</form>	 
		</tbody>
	</table>
</div>
</section>	