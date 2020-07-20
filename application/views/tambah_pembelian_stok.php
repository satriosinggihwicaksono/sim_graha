<?php 
$this->db->where('id', $this->uri->segment(3));
$stok_masuk = $this->db->get('stok_masuk')->result_array();
$nama_cabang = $this->mymodel->namaCabang($stok_masuk[0]['id_cabang']);
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
		$urutan = (int)$t[0];
		$code = (int)$t[1];
		$name = $t[2];
		$name = str_replace("%20"," ",$name);
		$category = (int)$t[3];
	} else {
		$code = '';
		$category = '';
		$name = '';
		$urutan = 0;
}

if($this->uri->segment(4)){
	$name = $this->uri->segment(4);
	$name = str_replace("%20"," ",$name);
}		
foreach($stok_masuk as $d){
$bayar = $d['bayar'];
include 'ubah_pembelian_stok.php';	
}
?>
<header class="header bg-light dker bg-gradient">
  <p>Pembelian stok</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
	<div class="col-sm-3">
	<table class="table table-striped m-b-none">
		<thead>
			<th>Code</th>
			<th>Unit</th>
			<th>actions</th>
		</thead>
		<tr>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_sub_item_masuk/'; ?>">
				<td><input type="text" name="code" value="" style="width:60px"/></td>
				<input type="hidden" name="id_stok_masuk" value="<?php echo $this->uri->segment(3); ?>" style="width:60px"/>
				<input type="hidden" name="id_cabang" value="<?php echo $stok_masuk[0]['id_cabang']; ?>" style="width:60px"/>
				<td><input type="text" onkeypress="return isNumberKey(event)" name="unit" value="" style="width:60px"/></td>
				<td><button type="submit" class="btn btn-info btn-fill btn-wd">Tambah item</button></td>
			</form>
		</tr>	
	</table>
	</div>
	<div class="col-sm-5">
		<br>
		<label><b>Search Item</b></label> <br>
		<form method="POST" action="<?php echo base_url().'index.php/welcome/search_item_pembelian_stok/'; ?>">
		<div class="input-group">
			<input type="hidden" name="id_pembelian" value="<?php echo $this->uri->segment(3); ?>">		
			<input type="text" name="search" class="form-control" placeholder="Search" value="<?php echo $name ?>">
				<span class="input-group-btn">
					<button type="submit" class="btn btn-info btn-icon"><i class="fa fa-search"></i></button>
				</span>
		</div>	
		</form>		
	</div>
	<div class="col-sm-1"></div>
	<div class="col-sm-3">
	Tanggal : <?php echo date('d-m-Y', strtotime($stok_masuk[0]['waktu'])); ?> <br>
	Deskripsi : <?php echo $stok_masuk[0]['deskripsi']; ?>	<br>
	Cabang : <?php echo $nama_cabang; ?><br>
	<a data-toggle="modal" data-target="#modal-ubah_pembelian_stok<?=$d['id'];?>" data-popup="tooltip" class="btn btn-info btn-circle" data-placement="top" title="Edit Data"><i class="fa fa-wrench"></i> Ubah</a>	
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
					$this->db->where('id_item', $s['id']);
					$harga_pokok = $this->db->get('harga_item')->result_array();
				?>
				<tr>
				<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_sub_item_masuk/'; ?>">
					<td><?php echo $s['nama'].'/'.$s['merek'].'/'.$s['tipe']; ?></td>
					<input type="hidden" name="code" value="<?php echo $s['code']; ?>"/>
					<input type="hidden" name="harga" value="<?php if(!empty($harga_pokok)) echo $harga_pokok[0]['harga_pokok']; ?>"/>
					<input type="hidden" name="id_cabang" value="<?php echo $stok_masuk[0]['id_cabang']; ?>" style="width:60px"/>
					<input type="hidden" name="id_stok_masuk" value="<?php echo $this->uri->segment(3); ?>" style="width:60px"/>
					<td><input type="text" onkeypress="return isNumberKey(event)" name="unit" value="" style="width:60px"/></td>
					<td><button type="submit" class="btn btn-info btn-fill btn-wd">Tambah item</button></td>
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
			<th>Merek</th>	
			<th>Type</th>
			<th>Harga</th>	
			<th>Unit</th>
			<th>Total</th>	
			<th>Actions</th>
		</thead>
		<tbody>
			<?php 
				$x = 1;
				$total_harga = array();
				foreach($data as $d){
				$this->db->where('id', $d['id_item']);
				$item = $this->db->get('item')->result_array();
				$id_item = $d['id_item'];
				$this->db->where('id_item',$id_item);
				$harga_item = $this->db->get('harga_item')->result_array();
				if($d['harga'] == 0 && !empty($harga_item[0]['harga_pokok'])){
					$harga_pokok =  $harga_item[0]['harga_pokok'];
				} else {
					$harga_pokok = $d['harga'];
				}	
				$unit =  $d['unit'];
				$total_item = $harga_pokok * $unit;
				$total_harga[] = $total_item;
			?>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/ubah_pembelian_stok/'.$d['id'] ?>">
			<tr>
				<td><?php echo $x++; ?></td>
				<td><?php echo $item[0]['nama']; ?></td>
				<td><?php echo $item[0]['merek']; ?></td>
				<td><?php echo $item[0]['tipe']; ?></td>
				<td><input name="harga" type="text" value="<?php echo $harga_pokok; ?>" /></td>
				<td><input name="unit" type="text" value="<?php echo $unit;?>" /></td>
				<td><?php echo $this->mymodel->format($total_item); ?></td>
				<td>
				<input type="submit" value="<?php echo 'Ubah'; ?>" class="btn btn-info"/>	
				<a href="<?php echo base_url().'index.php/welcome/deleteItem/sub_stok_masuk/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
				</td>
			</tr>
			</form>	
			<?php 
				}
				$total_harga = array_sum($total_harga);
				 if(!empty($bayar)){
				 	$kembalian = $bayar - $total_harga;
				 }
			?>
		</tbody>
	</table>
	<table class="table table-striped m-b-none" style="width:40%">
		<thead>
			<th yle="width:10px;"></th>
			<th></th>
			<th></th>
		</thead>
		<tbody>
			<tr>
				<td>Total Harga</td>
				<td colspan='2'><input type="text" name="pay" value="<?php echo $this->mymodel->format($total_harga); ?>" style="direction: rtl; border: 0; font-size: 23px; background: transparent; color: #000000;" disabled></td>
			<tr>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/bayar_pembelian_stok/'.$this->uri->segment(3); ?>">	
			<tr>
				<td>Bayar</td>
				<input type="hidden" name="waktu" value="<?php echo date('Y-m-d', strtotime($stok_masuk[0]['waktu'])); ?>"/>
				<td><input type="text" name="bayar" value="<?php if(!empty($bayar)) echo $bayar; ?>" style="direction: rtl; border: 0; font-size: 23px; background: transparent; color: #000000;"></td>
				<td><input type='submit' value='bayar' class="btn btn-success"/></td>
			<tr>
			</form>		
			<tr>
				<td>Kembalian</td>
				<td style="text-align:right;"><span type="text" style="direction: rtl; border: 0; font-size: 23px;"><?php if(!empty($bayar))  echo $this->mymodel->format($kembalian); ?></span></td>
				<td></td>
			<tr>
		</tbody>
	</table>
</div>
</section>	