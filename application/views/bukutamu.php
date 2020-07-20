<header class="header bg-light dker bg-gradient">
  <p>Daftar Costumer</p>
</header>
<section class="scrollable wrapper">
<?php 
$array = array('','Perorangan','Badan usaha','Pemerintah');
$link = $this->uri->segment(3);		
$posisi = explode("%5E",$link);
$posisi = count($posisi);	
if($posisi > 1){
	$t = explode("%5E",$link);
	$nama = str_replace("%20"," ",$t[0]);
	$waktu = (int)$t[1];
	$telepon = $t[2];
	$cabang = (int)$t[3];
	$month = $t[4];
	$year = $t[5];
	$tipe = $t[6];
} else {
	$nama = '';
	$waktu = '';
	$telepon = '';
	$cabang = '';
	$month = '';
	$year = '';
	$tipe = '';
}
	
$id_cabang = $this->mymodel->getIdCabang($username);	
$this->db->where('id', $id_cabang); 
$sub_cabang = $this->db->get('user')->result_array();	
?>	
<div class="doc-buttons">
	<a data-toggle="modal" data-target="#modal-tbhbukutamu" data-popup="tooltip" data-placement="top" title="Tambah Costumer"><li class="btn btn-default fa fa-plus" style="color:red"> Tambah Costumer</a>    
</div>	
<div class="doc-buttons">
	<div class="content table-responsive table-full-width">
		<table class="table table-striped m-b-none" style="width:100%">
			<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_bukutamu/'; ?>">
				<th>Nama <input type="text" name="nama" value="<?php if($link) echo $nama; ?>" /></th>
				<th>Tanggal <input type="date" name="waktu" value="<?php if(!empty($waktu)) echo date('Y-m-d', $waktu); ?>"/></th>
				<th>Bulan : 
				<select name="month">
					<?php
					$total_raws = array();
					foreach($data_raw as $r){
							$sum_raws = $r["SUM(total_raw)"];
							if($sum_raws != NULL){
								$total_raws[] = $sum_raws;
						}
					}	
					$month_array = array("","January","February","March","April","May","June","July","August","September","October","November","December");
					$count_month=count($month_array);
					for($c=0; $c<$count_month; $c+=1){ ?>
						<option <?php if($c == $month) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $month_array[$c] ?></option>";
					<?php
					}
					?>
				</select>
				</th>	
				<th>Tahun : 
				<select name="year">
					<option></option>
					<?php
					for($i=2019; $i<=2040; $i++){ ?>
						<option <?php if($i == $year) { ?> selected="selected" <?php } ?>  value=<?php echo $i; ?> > <?php echo $i ?></option>";
					<?php }	?>
				</select>
				</th>
				<th>Nomer HP <input type="text" name="telepon" value="<?php if($link) echo $telepon; ?>"/></th>
				<th>
					<label>Tipe</label>
					<?php 
						$count = count($array);
					?>
					<select name="tipe">
						<?php for($a=0;$a<$count;$a++){?>
						<option <?php if($a == $tipe){echo "selected='selected'";} ?> value="<?=$a?>"><?=$array[$a]?></option>
						<?php } ?>
					</select>
				</th>
				<th>
				<?php 
					if($check_admin || $check_marketing){ 
					if(empty($cabang)){
						$cabang = 0;
					}
				?>	
				<select name="cabang" value="">
					<option value='0';>SEMUA</option>
					<?php	
					$this->db->where('hakakses !=', 0);
					$this->db->where('hakakses !=', 2); 	
					$this->db->where('hakakses !=', 3);
					$user = $this->db->get('user')->result_array();
					foreach($user as $c){ 
					?>
					<option <?php if( $cabang == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
					<?php } ?>
				</select>
				<?php } else {
					echo '<input type="text" name="cabang" class="form-control border-input" value="'.$sub_cabang[0]['username'].'" disabled>';
				}
				?>	
				</th>
				<th><button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Cari</button></th>
			</form>
		</thead>
	</table>
</div>
	
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
	<thead>
		<th>No</th>
		<th>Tanggal</th>
		<th>Nama</th>
		<th>Alamat</th>
		<th>Nomor HP</th>
		<th>Cabang</th>
		<th>Tipe</th>
		<th>Order</th>
		<th>Actions</th>
	</thead>
	<tbody>
		<?php
		include 'tambah_bukutamu.php';
		$x = $this->uri->segment('4') + 1;
		foreach($data as $d){  
		$d = get_object_vars($d);	
		?>
		<form method="POST" action="<?php echo base_url().'index.php/welcome/rubah_bukutamu/'.$d['id'] ?>">
		<tr>
			<td><?php echo $x++; ?></td>
			<td><?php echo date('d/m/Y', strtotime($d['waktu'])); ?></td>
			<td><?php echo $d['nama']; ?></td>
			<td><?php echo $d['alamat']; ?></td>
			<td>
				<?php 
					if(!$check_admin && !$check_marketing){
						echo $this->mymodel->decrypt($d['telepon']);
					} else {
						echo $d['telepon'];
					}	
				?>
			</td>
			<td>
			<?php
			if($check_admin){
				?>
				<select name="cabang" value="">
					<?php	
					$this->db->where('hakakses !=', 2);
					$this->db->where('hakakses !=', 0);
 					$this->db->where('hakakses !=', 3);
					$user = $this->db->get('user')->result_array();
					foreach($user as $c){ 
					?>
					<option <?php if( $d['cabang'] === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
					<?php } ?>
				</select>
			<?php } else {
					echo $this->mymodel->namaCabang($d['cabang']);
				}
			?>
			</td>	
			<td>
				<?php
				$tipe = $d['tipe'];
				if($tipe == 1){
					echo 'Perorangan';
				} elseif($tipe == 2){
					echo 'Badan Usaha';
				} elseif($tipe == 3){
					echo 'Istansi';
				} else {
					echo 'Unidentified';
				}
				?>
			</td>
			<td>
				<a data-toggle="modal" data-target="#modal-tbhorderbukutamu<?=$d['id'];?>" data-popup="tooltip" data-placement="top" title="Tambah Bukutamu" class="btn btn-warning"><i class="fa fa-shopping-cart"></i> Preorder</a>
			</td>
			<td><a href="<?php echo base_url().'index.php/welcome/detail_order_bukutamu/'.$d['id'];?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Detail Transaksi</a></td>
			<td>
			<a data-toggle="modal" data-target="#modal-ubahbukutamu<?=$d['id'];?>" data-popup="tooltip" class="btn btn-info btn-circle" data-placement="top" title="Edit Data"><i class="fa fa-wrench"></i> Ubah</a>		
			<a href="<?php echo base_url().'index.php/welcome/delete/bukutamu/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
			</td>
		</tr>
		</form>	
		
		<?php 
		include 'ubah_bukutamu.php';
		include 'tambah_order.php';
			} 
		?>
	</tbody>
</table>
</div>
<br>	
<div class="text-center">
			<?php
				if($posisi > 1){
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