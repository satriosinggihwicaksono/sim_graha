<?php 
$this->db->where('id', $this->uri->segment(3));
$stok_mutasi = $this->db->get('mutasi')->result_array();
$from_cabang = $this->mymodel->namaCabang($stok_mutasi[0]['id_from']);
$to_cabang = $this->mymodel->namaCabang($stok_mutasi[0]['id_to']);
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
		$urutan = (int)$t[0];
		$code = (int)$t[1];
		$name = (string)$t[2];
		$category = (int)$t[3];
	} else {
		$code = '';
		$category = '';
		$name = '';
		$urutan = 0;
}
$name_search = $this->uri->segment(4);
$name_search = str_replace("%20"," ",$name_search);	
foreach($stok_mutasi as $d){
	include 'ubahmutasi.php';
}	
?>
<header class="header bg-light dker bg-gradient">
  <p>TRANSAKSI ORDER</p>
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
			<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_sub_mutasi/'; ?>">
				<td><input type="text" name="code" value="" style="width:60px"/></td>
				<input type="hidden" name="id_mutasi" value="<?php echo $this->uri->segment(3); ?>" style="width:60px"/>
				<td><input type="text" onkeypress="return isNumberKey(event)" name="unit" value="" style="width:60px"/></td>
				<td><button type="submit" class="btn btn-info btn-fill btn-wd">Tambah item</button></td>
			</form>
		</tr>	
	</table>
	</div>	
	<div class="col-sm-5">
		<br>
		<label><b>Search Item</b></label> <br>
		<form method="POST" action="<?php echo base_url().'index.php/welcome/search_item_mutasi/'; ?>">
		<div class="input-group">
			<input type="hidden" name="id_mutasi" value="<?php echo $this->uri->segment(3); ?>">		
			<input type="text" name="search" class="form-control" placeholder="Search" value="<?php echo $name_search; ?>">
				<span class="input-group-btn">
					<button type="submit" class="btn btn-info btn-icon"><i class="fa fa-search"></i></button>
				</span>
		</div>	
		</form>		
	</div>
	<div class="col-sm-1"></div>
	<div class="col-sm-3">
	Tanggal : <?php echo date('d-m-Y', strtotime($stok_mutasi[0]['waktu'])); ?> <br>
	Deskripsi : <?php echo $stok_mutasi[0]['deskripsi']; ?>	<br>
	<h4><?php echo $from_cabang; ?> <span class="fa fa-arrow-right"></span> <?php echo $to_cabang; ?><br></h4>
	<a data-toggle="modal" data-target="#modal-ubahmutasi<?=$d['id'];?>" data-popup="tooltip" class="btn btn-success btn-circle" data-placement="top" title="Edit Data"><i class="fa fa-wrench"></i> UBAH</a>	
	<a class="btn btn-warning btn-circle" href="<?php echo base_url().'index.php/welcome/cetak_mutasi/'.$d['id']; ?>" target="_BLANK"><span class="fa fa-print"></span> CETAK</a>
	</div>	
</div>	
	
<?php 
	if(!empty($this->uri->segment(4))){
	$this->db->like('nama',$name_search);
	$this->db->where('status', 0);
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
				<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_sub_mutasi/'; ?>">
					<td><?php echo $s['nama'].'/'.$s['merek'].'/'.$s['tipe']; ?></td>
					<input type="hidden" name="code" value="<?php echo $s['code']; ?>"/>
					<input type="hidden" name="id_mutasi" value="<?php echo $this->uri->segment(3); ?>" style="width:60px"/>
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
				$this->db->where('status', 0);	
				$item = $this->db->get('item')->result_array();	
			?>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/ubah_sub_stok_mutasi/'.$d['id']; ?>">
			<tr>
				<td><?php echo $x++; ?></td>
				<td><?php echo $item[0]['nama']; ?></td>
				<td><?php echo $item[0]['merek']; ?></td>
				<td><?php echo $item[0]['tipe']; ?></td>
				<td><input name="unit" type="text" value="<?php echo $d['unit']; ?>" /></td>
				<td>
				<input type="submit" value="<?php echo 'Ubah'; ?>" class="btn btn-info"/>	
				<a href="<?php echo base_url().'index.php/welcome/deleteItem/sub_mutasi/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
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