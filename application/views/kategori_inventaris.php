<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
	$nama = $t[0];
} else {
	$nama = '';
}
?>
<header class="header bg-light dker bg-gradient">
  <p>KATEGORI INVENTARIS</p>
</header>
<section class="scrollable wrapper">
<div class="doc-buttons">
	<h4>Pencarian</h4>
	<form method="POST" action="<?php echo base_url().'index.php/welcome/search_kategori_inventaris' ?>">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Nama Kategori</label>
				<input type="text" class="form-control border-input" name="nama" placeholder="" value="<?php echo $nama; ?>">
			</div>
		</div>
	</div>
	<button type="submit" class="btn btn-success btn-fill btn-wd fa fa-search"> Pencarian</button>
	</form>
	
	<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_kategori' ?>">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Kategori Perlengkapan</label>
				<input type="text" class="form-control border-input" name="kategori" placeholder="" value="">
				<input type="hidden" name="url" value="<?php echo $this->uri->segment(2); ?>" />
			</div>
		</div>
	</div>
	<button type="submit" class="btn btn-info btn-fill btn-wd">Tambah Kategori Inventaris</button>
	</form>
	<div class="content table-responsive table-full-width">
		<table class="table table-striped m-b-none" style="width:60%">
			<thead>
				<th>No</th>
				<th>Nama</th>
				<th>Actions</th>
			</thead>
			<tbody>
				<?php
					$data_page = $data;
					if($posisi > 0){
						$x = $this->uri->segment('4') + 1;
					} elseif($posisi != "%5E" ) {
						$x = $this->uri->segment('3') + 1;
					} else {
						$x = 1;
					}
					foreach($data as $d){ 
					$d = get_object_vars($d);
				?>
				<form method="POST" action="<?php echo base_url().'index.php/welcome/ubah_kategori/'.$d['id'] ?>">
				<tr>
					<td><?php echo $x++; ?></td>
					<td><input type="text" name="kategori" style="width:130px" value="<?php echo $d['kategori']; ?>"/></td>
						<input type="hidden" name="url" value="<?php echo $this->uri->segment(2); ?>" />
					<td>
					<input type="submit" value="Ubah" class="btn btn-info"/>		
					<a href="<?php echo base_url().'index.php/welcome/delete/kategori_item/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
					</td>
				</tr>
				</form>			
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="text-center">
<?php
	if($posisi > 0){
		$page = $this->uri->segment(4) - 10;
		$href = base_url().'index.php/welcome/'.$this->uri->segment(2).'/'.$link.'/'.$page;
		if(!empty($data) && $this->uri->segment(4)){
			echo '<a href ="'.$href.'"><button class="btn btn-info btn-fill btn-wd">previous</button></a>';
		}	
	} elseif($posisi != "%5E") {
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