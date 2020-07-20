<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
		$cabang = (int)$t[0];
		$urutan = (int)$t[1];
		$name = $t[2];
		$name = str_replace("%20"," ",$name);
		$category = (int)$t[3];
	} else {
		$cabang = $id_username;
		$category = '';
		$name = '';
		$urutan = 0;
}
?>

<header class="header bg-light dker bg-gradient">
  <p>DAFTAR INVENTARIS</p>
</header>

<section class="scrollable wrapper">

<div class="doc-buttons">
	<div class="content table-responsive table-full-width">
		<table class="table table-striped m-b-none" style="width:100%">
			<thead>
				<form method="POST" action="<?php echo base_url().'index.php/welcome/search_inventaris/';?>">
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
							<option  value=''></option>
							<?php
							$this->db->where('tipe',1);
							$categories = $this->db->get('kategori_item')->result_array();
							foreach($categories as $c){ ?>
							<option <?php if($category == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['kategori']; ?></option>
							<?php } ?>
						</select>
					</th>
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
								echo '<input type="hidden" name="cabang" value="'.$id_username.'" />';
							}	
						?>	
					</th>	
					<th><button type="submit" class="btn btn-primary btn-fill btn-wd"><i class="ti-zoom-in"></i> Cari</button></th>
				</form>
			</thead>
		</table>	
		<table class="table table-striped m-b-none" style="width:100%">
			<thead>
				<th>No</th>
				<th>Nama</th>
				<th>Kategori</th>
				<th>Harga</th>
				<th>Unit</th>
				<th>Total</th>
				<th>Actions</th>
			</thead>
			<tbody>
				<?php
					$data_page = $data;
					$x = $this->uri->segment('4') + 1;
					foreach($data as $d){
						$d = get_object_vars($d);
						$id = $d['id'];
						$this->db->where('id_cabang', $cabang);
						$this->db->where('id_item', $id);
						$harga_item = $this->db->get('harga_inventaris')->result_array();
						if($harga_item){
							$total = $harga_item[0]['unit'] * $harga_item[0]['harga'];
						}	
				?>
				<form method="POST" action="<?php echo base_url().'index.php/welcome/harga_inventaris/'.$d['id'] ?>">
				<tr>
					<td><?php echo $x++; ?></td>
					<td><?php echo $d['nama']; ?></td>
					<td><?php if(!empty($d['kategori'])) echo $this->mymodel->namaKategori($d['kategori']); ?></td>
					<td>
						<input name="harga" type="text" value="<?php if($harga_item) echo $harga_item[0]['harga']; ?>"/>
						<input name="id_cabang" type="hidden" value="<?php echo $cabang; ?>" />
					</td>
					<td>
						<input name="unit" type="text" value="<?php if($harga_item) echo $harga_item[0]['unit']; ?>" />
					</td>
					<td>
						<?php if($harga_item) echo $total; ?>
					</td>
					<td>
					<div class="icon-container">
					<input type="submit" value="Tambah input" class="btn btn-info"/>
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
</div>
</section>