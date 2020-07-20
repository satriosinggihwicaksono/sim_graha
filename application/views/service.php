<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
	$cabang = $t[0];
	$status = $t[1];
	$month = $t[2];
	$year = $t[3];
	$tanggal = $t[4];
	$urutan = $t[5];
	$kondisi = $t[6];
	$nama = $t[7];
} else {
	$cabang = '';
	$status = '';
	$month = '';
	$year = '';
	$tanggal = '';
	$urutan = '';
	$kondisi = '';
	$nama = '';
}
?>
<header class="header bg-light dker bg-gradient">
  <p>ORDER</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
	<a data-toggle="modal" data-target="#modal-tbhorder" data-popup="tooltip" data-placement="top" title="Tambah Order"><li class="btn btn-default fa fa-plus" style="color:red"> Tambah Order</a>
</div>
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_order' ?>">
			<th>Nama : <input name="nama" type="text" value="<?php if(!empty($nama)) echo $nama; ?>" /></th>
			<th>Cabang : 
			<?php if($check_admin || $check_marketing) { ?>	
			<select name="cabang">
						<option value="0">SEMUA</option>
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
			<?php 
				} else {
					echo '<input type="text" value="'.$sub_cabang[0]['username'].'" disabled/>';
					echo '<input type="hidden" name="cabang" value="'.$sub_cabang[0]['id'].'" />';
				}	
			?>	
			</th>	
			<th>Urutan : 
			<select name="urutan">
						<?php
						$urutan_array = array("","Tanggal terkecil","Tanggal terbesar","Berdasarkan data baru");
						$count_urutan=count($urutan_array);
						
						for($c=0; $c<$count_urutan; $c+=1){ ?>
							<option <?php if($c == $urutan) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $urutan_array[$c] ?></option>";
						<?php
						}
						?>
			</select>
			</th>	
			<th>Status : 
			<select name="status">
						<?php
						$status_array = array("Belum diproses","Proses","Negoisasi","Cancel","Deal");
						$count_status=count($status_array);
						
						for($c=0; $c<$count_status; $c+=1){ ?>
							<option <?php if($c == $status) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $status_array[$c] ?></option>";
						<?php
						}
						?>
			</select>
			</th>
			<th>Tanggal : 
				<input type="date" name="tanggal" value="<?php if(!empty($tanggal)) echo date('Y-m-d', $tanggal)?>"/>
			</th>	
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
			
			<th>Credit / Cash : 
			<select name="kondisi">
						<?php
						$status_array = array("All","Kredit","Cash");
						$count_status=count($status_array);
						
						for($c=0; $c<$count_status; $c+=1){ ?>
							<option <?php if($c == $kondisi) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $status_array[$c] ?></option>";
						<?php
						}
						?>
			</select>
			</th>
				
			<th><button type="submit" class="btn btn-primary btn-fill btn-wd"><i class="fa fa-search"></i> Cari</button></th>
			</form>	
		</thead>
	</table>
</div>

<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">	
			<thead>
			<th>Tgl Penyerahan</th>
			<th>Nama</th>
			<th>Alamat</th>	
			<th>Ket.Order</th>
			<th>Nomor HP</th>
			<th>Nota</th>
			<th>Nominal</th>	
			<th>Cabang</th>
			<th>Status</th>
			<th>Detail</th>
			<th>Actions</th>
		</thead>
		<tbody>
			<?php
				foreach($data as $d){  
			?>
			<tr>
				
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<br>
	<div class="text-center">
			<?php
				if($posisi >= 1){
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
</section>	