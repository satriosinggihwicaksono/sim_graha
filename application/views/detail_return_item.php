<?php 
$this->db->where('id', $this->uri->segment(3));
$service = $this->db->get('service')->result_array();
$nama_cabang = $this->mymodel->namaCabang($service[0]['id_cabang']);
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
		$code = (int)$t[0];
		$name = $t[1];
	} else {
		$code = '';;
		$name = '';
}
?>
<header class="header bg-light dker bg-gradient">
  <p>REKAP TRANSAKSI ORDER</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
	<div class="col-sm-5">
		<br>
		<label><b>Search Item</b></label> <br>
		<form method="POST" action="<?php echo base_url().'index.php/welcome/search_return_item_stok/'; ?>">
		<div class="input-group">
			<input type="hidden" name="id_return_item" value="<?php echo $this->uri->segment(3); ?>">		
			<input type="text" name="search" class="form-control" placeholder="Search" value="<?php echo $this->uri->segment(4); ?>">
				<span class="input-group-btn">
					<button type="submit" class="btn btn-info btn-icon"><i class="fa fa-search"></i></button>
				</span>
		</div>	
		</form>		
	</div>
	<div class="col-sm-4">
	</div>	
	<div class="col-sm-3">
	Tanggal : <?php echo date('d-m-Y', strtotime($service[0]['waktu'])); ?> <br>
	Deskripsi : <?php echo $service[0]['keterangan']; ?>	<br>
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
				<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_sub_return_item/'; ?>">
					<td><?php echo $s['nama'].'/'.$s['merek'].'/'.$s['tipe']; ?></td>
					<input type="hidden" name="code" value="<?php echo $s['code']; ?>"/>
					<input type="hidden" name="id_service" value="<?php echo $this->uri->segment(3); ?>" style="width:60px"/>
					<input type="hidden" name="id_cabang" value="<?php echo $service[0]['id_cabang']; ?>" style="width:60px"/>
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
			<form method="POST" action="<?php echo base_url().'index.php/welcome/ubah_sub_service/'.$d['id'] ?>">
			<tr>
				<td><?php echo $x++; ?></td>
				<td><?php echo $item[0]['nama']; ?></td>
				<td><?php echo $item[0]['merek']; ?></td>
				<td><?php echo $item[0]['tipe']; ?></td>
				<td><input name="unit" type="text" value="<?php echo $d['unit']; ?>" /></td>
				<td>
				<input type="submit" value="<?php echo 'Ubah'; ?>" class="btn btn-info"/>	
				<a href="<?php echo base_url().'index.php/welcome/deleteItem/sub_service/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
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