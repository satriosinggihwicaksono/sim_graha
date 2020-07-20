<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
	$cabang = (int)$t[0];
	$month = (int)$t[1];
	$year = (int)$t[2];
	$tanggal = (int)$t[3];
} else {
	$cabang = '';
	$month = date('m');
	$year = date('Y');
	$tanggal = '';
}
include 'tbhitemkeluar.php';
?>
<header class="header bg-light dker bg-gradient">
  <p>STOK ITEM KELUAR</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
	<div class="content table-responsive table-full-width">
		
		<div class="doc-buttons">
	<a data-toggle="modal" data-target="#modal-tbhitemkeluar" data-popup="tooltip" data-placement="top" title="Tambah Order"><li class="btn btn-default fa fa-plus" style="color:red"> Tambah Item Keluar</a>
</div>	
	
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_stok_item_keluar' ?>">
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
					echo '<input type="text" name="cabang" value="'.$username.'" disabled/>';
				}	
			?>	
			</th>	
			<th>Tanggal : 
				<input type="date" name="tanggal" value="<?php if(!empty($tanggal)) echo date('Y-m-d', $tanggal)?>"/>
			</th>	
			<th>Bulan : 
			<select name="month">
				<?php
				$date = $timestamp;
				$total_raws = array();
				foreach($data_raw as $r){
						$sum_raws = $r["SUM(total_raw)"];
						if($sum_raws != NULL){
							$total_raws[] = $sum_raws;
					}
				}	
				$month_array = array("Month","January","February","March","April","May","June","July","August","September","October","November","December");
				$count_month=count($month_array);
				for($c=1; $c<$count_month; $c+=1){ ?>
					<option <?php if($c == $month) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $month_array[$c] ?></option>";
				<?php
				}
				?>
			</select>
			</th>	
			
			<th>Tahun : 
			<select name="year">
				<?php
				for($i=2019; $i<=2040; $i++){ ?>
					<option <?php if($i == $year) { ?> selected="selected" <?php } ?>  value=<?php echo $i; ?> > <?php echo $i ?></option>";
				<?php }	?>
			</select>
			</th>	
				
			<th><button type="submit" class="btn btn-primary btn-fill btn-wd"><i class="fa fa-search"></i> Cari</button></th>
			</form>	
		</thead>
	</table>
</div>
		<table class="table table-striped m-b-none" style="width:100%">
			<thead>
				<th>No</th>
				<th>Tanggal</th>
				<th>Deskripsi</th>
				<th>Cabang</th>
				<th>Actions</th>
			</thead>
			<tbody>
				<?php 
				$x = $this->uri->segment('4') + 1;
				foreach($data as $d){
				include 'ubah_item_keluar.php';
				?>
				<tr>
					<td><?php echo $x++; ?></td>
					<td><?php echo date('d/m/Y',strtotime($d['waktu'])); ?></td>
					<td><?php echo $d['deskripsi']; ?></td>
					<td><?php echo $this->mymodel->namaCabang($d['id_cabang']); ?></td>
					<td>
						<a data-toggle="modal" data-target="#modal-ubah_stok_keluar<?=$d['id'];?>" data-popup="tooltip" class="btn btn-info btn-circle" data-placement="top" title="Edit Data"><i class="fa fa-wrench"></i> Ubah</a>
						<a href="<?php echo base_url().'index.php/welcome/tambah_item_keluar/'.$d['id'];?>" class="btn btn-success"> Item Keluar</a>		
						<a href="<?php echo base_url().'index.php/welcome/deleteItemKeluar/stok_keluar/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
					</td>
				</tr>			
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
</section>	