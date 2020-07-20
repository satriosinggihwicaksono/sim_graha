<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
		$urutan = (int)$t[0];
		$code = $t[1];
		$nama = $t[2];
		$nama = str_replace("%20"," ",$nama);
		$category = $t[3];
		$category = str_replace("%20"," ",$category);
		$cabang = (int)$t[4];
		$merek = $t[5];
		$merek = str_replace("%20"," ",$merek);
		$tipe = $t[6];
		$tipe = str_replace("%20"," ",$tipe);
	} else {
		$code = '';
		$category = '';
		$nama = '';
		$merek = '';
		$tipe = '';
		$urutan = 0;
		$cabang = $id_username;
}

?>
<header class="header bg-light dker bg-gradient">
  <p>STOK</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
	<div class="content table-responsive table-full-width">
		<table class="table table-striped m-b-none" style="width:100%">
			<thead>
				<form method="POST" action="<?php echo base_url().'index.php/welcome/search_stok/'; ?>">
					<th>
					<select name="cabang">
						<?php	
						$this->db->where('hakakses !=',2);
						$this->db->where('hakakses !=', 3);
						$user = $this->db->get('user')->result_array();
						foreach($user as $c){ 
						?>
						<option <?php if( $cabang == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
						<?php } ?>
					</select>
					</th>
					<th>Code <input type="text" name="code" value="<?php echo $code; ?>" style="width:70px"/></th>
					<th>Nama <input type="text" name="nama" value="<?php echo $nama; ?>" style="width:150px"/></th>
					<th>Merek <input type="text" name="merek" value="<?php echo $merek; ?>" style="width:150px"/></th>
					<th>Tipe <input type="text" name="tipe" value="<?php echo $tipe; ?>" style="width:150px"/></th>
					<th>Urutan 
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
						<select name="kategori" >
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
	</div>	
	<div class="content table-responsive table-full-width">
		<table class="table table-striped m-b-none">
			<thead>
				<th>No</th>
				<th>Code</th>
				<th>Nama</th>
				<th>Merek</th>
				<th>Tipe</th>
				<th>Kategori</th>
				<th>Stok</th>
				<th>Actions</th>
			</thead>
			<tbody>
				<?php
					$data_page = $data;
					$x = $this->uri->segment('4') + 1;
					foreach($data as $d){
						$id_item = $d['id'];
						$this->db->where('id_item', $id_item);	
						$harga_item = $this->db->get('harga_item')->result_array();
						foreach($harga_item as $h){
							$harga_pokok = $h['harga_pokok'];
						}
						$this->db->where('id_item', $id_item);	
						$this->db->where('id_cabang',$cabang); 
						$stok = $this->db->get('stok')->result_array();
						if($stok){
							$value = 'ubah';
							$action = 'ubah_stok/'.$stok[0]['id'];
						} else {
							$value = 'masukan';
							$action = 'tambah_stok';
						}
				?>
				<form method="POST" action="<?php echo base_url().'index.php/welcome/'.$action; ?>">
				<tr>
					<td><?php echo $x++; ?></td>
					<td><?php echo $d['code']; ?></td>
					<td><?php echo $d['nama']; ?></td>
					<td><?php echo $d['merek']; ?></td>
					<td><?php echo $d['tipe']; ?></td>
					<td><?php echo $d['kategori']; ?></td>
					<td>
						<input type="text" name="stok" value="<?php if($stok) echo $stok[0]['stok']; ?>" style="width:80px"/>
						<input type="hidden" name="id_item" value="<?php echo $d['id']; ?>" style="width:80px"/>
						<input type="hidden" name="cabang" value="<?php echo $cabang; ?>" style="width:80px"/>
					</td>
					<td>
					<div class="icon-container">
					<input type="submit" value="<?php echo $value; ?>" class="btn btn-info"/>	
					</div>
					</td>
				</tr>
				</form>			
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
		<?php
			$page = $this->uri->segment(4) - 10;
			$href = base_url().'index.php/welcome/'.$this->uri->segment(3).'/'.$page;
			if(empty($data_page) && $this->uri->segment(4)){
				echo '<a href ="'.$href.'"><button class="btn btn-info btn-fill btn-wd">previous</button></a>';
			}	
			echo $this->pagination->create_links();
		?>
	</div>
</div>
</section>	