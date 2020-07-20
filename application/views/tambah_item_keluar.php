<?php 
$this->db->where('id', $this->uri->segment(3));
$stok_keluar = $this->db->get('stok_keluar')->result_array();
$nama_cabang = $this->mymodel->namaCabang($stok_keluar[0]['id_cabang']);
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
		$urutan = (int)$t[0];
		$code = (int)$t[1];
		$name = $t[2];
		$category = (int)$t[3];
	} else {
		$code = '';
		$category = '';
		$name = '';
		$urutan = 0;
}
foreach($stok_keluar as $d){
include 'ubah_item_keluar.php';
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
			<th>Code</th>
			<th>Unit</th>
			<th>actions</th>
		</thead>
		<tr>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_sub_item_keluar/'; ?>">
				<td><input type="text" name="code" value="" style="width:60px"/></td>
				<input type="hidden" name="id_stok_keluar" value="<?php echo $this->uri->segment(3); ?>" style="width:60px"/>
				<input type="hidden" name="id_cabang" value="<?php echo $stok_keluar[0]['id_cabang']; ?>" style="width:60px"/>
				<td><input type="text" onkeypress="return isNumberKey(event)" name="unit" value="" style="width:60px"/></td>
				<td><button type="submit" class="btn btn-info btn-fill btn-wd">Tambah item</button></td>
			</form>
		</tr>	
	</table>
	</div>	
	<div class="col-sm-5">
		<br>
		<label><b>Search Item</b></label> <br>
		<form method="POST" action="<?php echo base_url().'index.php/welcome/search_item_keluar/'; ?>">
		<div class="input-group">
			<input type="hidden" name="id_item_keluar" value="<?php echo $this->uri->segment(3); ?>">		
			<input type="text" name="search" class="form-control" placeholder="Search" value="<?php echo $this->uri->segment(4); ?>">
				<span class="input-group-btn">
					<button type="submit" class="btn btn-info btn-icon"><i class="fa fa-search"></i></button>
				</span>
		</div>	
		</form>		
	</div>
	<div class="col-sm-1"></div>
	<div class="col-sm-3">
	Tanggal : <?php echo date('d-m-Y', strtotime($stok_keluar[0]['waktu'])); ?> <br>
	Deskripsi : <?php echo $stok_keluar[0]['deskripsi']; ?>	<br>
	Cabang : <?php echo $nama_cabang; ?><br>
	<a data-toggle="modal" data-target="#modal-ubah_stok_keluar<?=$this->uri->segment(3);?>" data-popup="tooltip" class="btn btn-info btn-circle" data-placement="top" title="Edit Data"><i class="fa fa-wrench"></i> Ubah</a>	
	</div>	
</div>	
	
<?php 
	if(!empty($this->uri->segment(4))){
	$this->db->like('nama',$this->uri->segment(4));
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
				<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_sub_item_keluar/'; ?>">
					<td><?php echo $s['nama'].'/'.$s['merek'].'/'.$s['tipe']; ?></td>
					<input type="hidden" name="code" value="<?php echo $s['code']; ?>"/>
					<input type="hidden" name="id_stok_keluar" value="<?php echo $this->uri->segment(3); ?>" style="width:60px"/>
					<input type="hidden" name="id_cabang" value="<?php echo $stok_keluar[0]['id_cabang']; ?>" style="width:60px"/>
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
			<th>Unit</th>	
			<th>Actions</th>
		</thead>
		<tbody>
			<?php 
				$x = 1;
				foreach($data as $d){
				$this->db->where('id', $d['id_item']);
				$item = $this->db->get('item')->result_array();	
			?>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/ubah_sub_stok_keluar/'.$d['id'] ?>">
			<tr>
				<td><?php echo $x++; ?></td>
				<td><?php echo $item[0]['nama']; ?></td>
				<td><?php echo $item[0]['merek']; ?></td>
				<td><?php echo $item[0]['tipe']; ?></td>
				<td><input name="unit" type="text" value="<?php echo $d['unit']; ?>" /></td>
				<td>
				<input type="submit" value="<?php echo 'Ubah'; ?>" class="btn btn-info"/>	
				<a href="<?php echo base_url().'index.php/welcome/deleteItem/sub_stok_keluar/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
				</td>
			</tr>
			</form>	
			<?php 
				}
			?>
		</tbody>
	</table>
</div>
</section>	