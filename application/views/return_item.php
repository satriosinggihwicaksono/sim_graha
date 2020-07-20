<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
	$cabang = (int)$t[0];
	$keterangan = $t[1];
	$month = (int)$t[2];
	$year = (int)$t[3];
	$id_supplier = $t[4];
}  else {
	$cabang = '';
	$keterangan = '';
	$month = date('m');
	$year = date('Y');
	$id_supplier = '';
}
include 'tambah_return_item.php';
?>
<header class="header bg-light dker bg-gradient">
  <p>RETURN STOK</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
		<div class="doc-buttons">
	<a data-toggle="modal" data-target="#modal-tbhreturnitem" data-popup="tooltip" data-placement="top" title="Tambah Order"><li class="btn btn-default fa fa-plus" style="color:red"> Tambah Return Stok</a>
</div>	
	
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_return_item' ?>">
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
			<th>Deskripsi : 
				<input type="text" name="keterangan" value="<?php echo $keterangan; ?>"/>
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
				<option value="0"></option>
				<?php
				for($i=2019; $i<=2040; $i++){ ?>
					<option <?php if($i == $year) { ?> selected="selected" <?php } ?>  value=<?php echo $i; ?> > <?php echo $i ?></option>";
				<?php }	?>
			</select>
			</th>	
			<th>Supplier : 	
			<select name="supplier">
						<option value="0">SEMUA</option>
						<?php	
						$supplier = $this->db->get('supplier')->result_array();
						foreach($supplier as $sp){ 
						?>
						<option <?php if( $id_supplier == $sp['id']) { ?> selected="selected" <?php } ?> value='<?php echo $sp['id']; ?>'><?php echo $sp['supplier']; ?></option>
						<?php } ?>
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
				<th>No</th>
				<th>Tanggal Penyerahan</th>
				<th>Tanggal Pengambilan</th>
				<th>Supplier</th>
				<th>Cabang</th>
				<th>Keterangan</th>
				<th>Actions</th>
			</thead>
			<tbody>
				<?php
				$y = 1;
				foreach($data as $d){
				include 'ubah_return.php';	
				?>
				<tr>
					<td><?php echo $y++; ?></td>
					<td><?php echo date('d/m/Y',strtotime($d['waktu'])); ?></td>
					<td>
						<?php 
							if(date('Y',strtotime($d['waktu_con'])) == -00001){
								echo '<a href="'.base_url().'index.php/welcome/konfirmasi_return_item/'.$d['id'].'"><button type="submit" class="btn btn-primary btn-fill btn-wd">Konfirmasi</button></a>';
							} else {
								echo date('d/m/Y',strtotime($d['waktu_con'])); 
							} 
						?>
					</td>
					<td><?php echo $this->mymodel->namaSupplier($d['id_supplier']); ?></td>
					<td><?php echo $this->mymodel->namaCabang($d['id_cabang']); ?></td>
					<td><?php echo $d['keterangan']; ?></td>
					<td>
						<a data-toggle="modal" data-target="#modal-ubhreturn<?=$d['id'];?>" data-popup="tooltip" class="btn btn-info btn-circle" data-placement="top" title="Edit Data"><i class="fa fa-wrench"></i> Ubah</a>
						<a href="<?php echo base_url().'index.php/welcome/detail_return_item/'.$d['id'];?>" class="btn btn-success"> Detail</a>		
						<a href="<?php echo base_url().'index.php/welcome/deleteItemReturn/service/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
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