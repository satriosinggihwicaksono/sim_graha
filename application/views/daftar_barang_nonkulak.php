<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
		$urutan = (int)$t[0];
		$code = $t[1];
		$name = $t[2];
		$name = str_replace("%20"," ",$name);
		$category = $t[3];
	} else {
		$code = '';
		$category = '';
		$name = '';
		$urutan = 0;
}

include 'tbhbarang.php';
?>

<header class="header bg-light dker bg-gradient">
  <p>DAFTAR BARANG</p>
</header>

<section class="scrollable wrapper">
	
<div class="doc-buttons">
	<a data-toggle="modal" data-target="#modal-tbhbarang" data-popup="tooltip" data-placement="top" title="Tambah Bukutamu"><li class="btn btn-default fa fa-plus" style="color:red"> Tambah Barang</a>    
</div>

<div class="doc-buttons">
	<div class="content table-responsive table-full-width">
		<table class="table table-striped m-b-none" style="width:100%">
			<thead>
				<?php 
				
				if(!empty($category)) $category = $category[0];
				?>
				<form method="POST" action="<?php echo base_url().'index.php/welcome/search_barang_nonkulak/';?>">
					<th>Code <input type="text" name="code" value="<?php echo $code; ?>" style="width:70px"/></th>
					<th>Nama <input type="text" name="nama" value="<?php echo $name; ?>" style="width:150px"/></th>
					<th>
					<select name="urutan">
						<?php
						$urutan_array = array("","Berdasarkan Nama","Berdasarkan Kategori");
						$count_urutan=count($urutan_array);
						
						for($c=0; $c<$count_urutan; $c+=1){ ?>
							<option <?php if($c == $urutan) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $urutan_array[$c] ?></option>";
						<?php
						}
						?>
					</select>
					</th>
					<th>Kategori 
						<select name="kategori" value="">
							<option  value=''>Pilih</option>
							<?php
							$this->db->where('tipe',0);
							$categories = $this->db->get('kategori_item')->result_array();
							foreach($categories as $c){ ?>
							<option <?php if($category == $c['kategori']) { ?> selected="selected" <?php } ?> value='<?php echo $c['kategori']; ?>'><?php echo $c['kategori']; ?></option>
							<?php } ?>
						</select>
					</th>
					<th><button type="submit" class="btn btn-primary btn-fill btn-wd"><i class="ti-zoom-in"></i> Cari</button></th>
				</form>
			</thead>
		</table>	
		<table class="table table-striped m-b-none" style="width:100%">
			<thead>
				<th>No</th>
				<th>Code</th>
				<th>Nama</th>
				<th>Harga Pokok</th>
				<th>Actions</th>
			</thead>
			<tbody>
				<?php
					$data_page = $data;
					$x = $this->uri->segment('4') + 1;
					foreach($data as $d){
						$d = get_object_vars($d);
						include 'harga_barang.php';
						$id = $d['id'];
						$this->db->where('id_item', $id); 
						$harga_item = $this->db->get('harga_item')->result_array();
				?>
				<form method="POST" action="<?php echo base_url().'index.php/welcome/ubah_barang/'.$d['id'] ?>">
				<tr>
					<td><?php echo $x++; ?></td>
					<td><input type="text" name="code" value="<?php echo $d['code']; ?>" style="width:50px"/></td>
					<td><input type="text" name="nama" value="<?php echo $d['nama']; ?>" style="width:150px"/></td>
					<td>
						<a data-toggle="modal" data-target="#modal-hargaitem<?=$d['id'];?>" class="btn btn-warning btn-circle" data-popup="tooltip" data-placement="top" title="Edit Data"><?php echo $this->mymodel->format($t_harga); ?> </a>
					</td>
						<input type="hidden" name="id_item" value="<?php if($harga_item) echo $harga_item[0]['id_item']; ?>" onkeypress="return isNumberKey(event)" style="width:130px"/>
					<td>
					<div class="icon-container">
					<input type="submit" value="Ubah" class="btn btn-info"/>		
					<a href="<?php echo base_url().'index.php/welcome/deleteItem/item/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
					</div>
					</td>
				</tr>
				</form>		
				<?php } ?>
				<tr>
					<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_barang/'; ?>">	
					<td><h6>Add</h6></td>	
					<td><input type="text" class="form-control border-input" name="code" value="" style="width:70px"/></td>						
					<td><input type="text" class="form-control border-input" name="nama" value="" style="width:150px"></td>
					<td><input type="text" class="form-control border-input" name="harga_pokok" value="" style="width:110px"/></td>	
					<input type="hidden" class="form-control border-input" name="merek" value="" style="width:130px"/>	
					<input type="hidden" class="form-control border-input" name="tipe" value="" style="width:130px"/>
					<input type="hidden" name="status" value="1"/>	
					<td><button type="submit" class="btn btn-warning btn-fill btn-wd">Tambah Barang</button></td>
					</form>
				</tr>
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
</div>
</section>