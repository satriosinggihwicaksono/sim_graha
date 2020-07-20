<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
	$cabang = (int)$t[0];
	$month = (int)$t[1];
	$year = (int)$t[2];
	$tanggal = (int)$t[3];
	$status = (int)$t[4];
	$desc = $t[5];
	$desc = str_replace("%20"," ",$desc);
	$id_supplier = $t[6];
} else {
	$cabang = '';
	$status = 5;
	$month = date('m');
	$year = date('Y');
	$tanggal = '';
	$desc = '';
	$id_supplier = '';
}
include 'tbh_pembelian_stok.php';
?>
<header class="header bg-light dker bg-gradient">
  <p>PEMBELIAN STOK</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
		<div class="doc-buttons">
	<a data-toggle="modal" data-target="#modal-tbhitemmasuk" data-popup="tooltip" data-placement="top" title="Tambah Order"><li class="btn btn-default fa fa-plus" style="color:red"> Tambah Pembelian Stok</a>
</div>	
	
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_pembelian_stok' ?>">
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
				<input type="text" name="desc" value="<?php echo $desc; ?>"/>
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
			<th>
				Status :
				<select name='status'>
					<option <?php if($status == 5){ ?> selected="selected" <?php } ?>  value='5'>SEMUA</option>
					<option <?php if($status == 1){ ?> selected="selected" <?php } ?> value='1'>LUNAS / CASH</option>
					<option <?php if($status == 0){ ?> selected="selected" <?php } ?> value='0'>HUTANG</option>
				</select>
			</th>
				
			<th><button type="submit" class="btn btn-primary btn-fill btn-wd"><i class="fa fa-search"></i> Cari</button></th>
			</form>	
		</thead>
	</table>
</div>
	<?php 
	 if($status == 0){
		 echo '<h4 style="color:red"><b>Status Hutang</b></h4>';
	 } elseif($status == 1) {
		echo '<h4 style="color:green"><b>Status Lunas</b></h4>';
	 } else {
		echo '<h4 style="color:blue"><b>Status Semua</b></h4>';
	 }
	?>
	<div class="content table-responsive table-full-width">
		<table class="table table-striped m-b-none" style="width:100%">
			<thead>
				<th>No</th>
				<th>Tanggal</th>
				<th>Deskripsi</th>
				<th>Supplier</th>
				<?php if($status == 0){ ?><th>Hutang</th> <?php } ?>
				<th>Nominal</th>
				<th>Pelnunasan</th>
				<th>Actions</th>
			</thead>
			<tbody>
				<?php 
				$x = $this->uri->segment('4') + 1;
				$total_all_kredit = array();
				$total_bayar = array();
				foreach($data as $d){
				$this->db->where('id_stok_masuk',$d['id']);
				$pelunasan = $this->db->get('pelunasan')->result_array();	
				$bayar = $d['bayar'];	
				$total_harga = array();	
				$this->db->where('id_stok_masuk',$d['id']);	
				$total_item = $this->db->get('sub_stok_masuk')->result_array();
				foreach($total_item as $t){
					$total_harga[] = $t['harga'] * $t['unit'];
				}		
			
				$total = array_sum($total_harga);	
				$total_all_kredit[] = $total;
				$total_bayar[] = $bayar;
				include 'ubah_pembelian_stok.php';
				include 'pelunasan.php';
					
				?>
				<tr>
					<td><?php echo $x++; ?></td>
					<td><?php echo date('d/m/Y',strtotime($d['waktu'])); ?></td>
					<td><?php echo $d['deskripsi']; ?></td>
					<td><?php if(!empty($d['supplier'])) { echo $this->mymodel->namaSupplier($d['supplier']); } else { echo 'Non Kulak'; } ?></td>
					<?php if($status == 0){ ?><td><?php echo $this->mymodel->format($hutang); ?></td> <?php } ?>
					<td><?php echo $this->mymodel->format($total); ?></td>
					<td><a data-toggle="modal" data-target="#pelunasan<?php echo $d['id']; ?>" data-popup="tooltip" data-placement="top" title="Pelunasan"><li class="btn btn-warning fa fa-plus"> Pelunasan</a></td>
					<td>
						<a data-toggle="modal" data-target="#modal-ubah_pembelian_stok<?=$d['id'];?>" data-popup="tooltip" class="btn btn-info btn-circle" data-placement="top" title="Edit Data"><i class="fa fa-wrench"></i> Ubah</a>
						<a href="<?php echo base_url().'index.php/welcome/tambah_pembelian_stok/'.$d['id'];?>" class="btn btn-success"> Item Masuk</a>		
						<a href="<?php echo base_url().'index.php/welcome/deleteItemMasuk/stok_masuk/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
					</td>
				</tr>			
				<?php 
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php if($status == 0){ ?>
<div class="row">	
	<div class="col-md-4">
	  <section class="panel panel-default">
		<header class="panel-heading font-bold">Kredit</header>
			<div class="panel-body">
			  <div>
				<span class="text-muted">Total Kredit:</span>
				<span class="h3 block"><?php echo $this->mymodel->format(array_sum($total_all_kredit) - array_sum($total_bayar)); ?></span>
			  </div>
			  <div class="line pull-in"></div>
			  <div class="row m-t-sm">
				<div class="col-xs-6">
				  <small class="text-muted block">Kredit</small>
				  <span><?php echo $this->mymodel->format(array_sum($total_all_kredit)); ?></span>
				</div>  
				<div class="col-xs-6">
				  <small class="text-muted block">Bayar</small>
				  <span><?php echo $this->mymodel->format(array_sum($total_bayar)); ?></span>
				</div>
			</div>
		</div>
	  </section>
	</div>
</div>
<?php 
	  }
?>
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