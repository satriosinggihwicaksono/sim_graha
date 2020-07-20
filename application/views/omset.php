<header class="header bg-light dker bg-gradient">
  <p>OMSET</p>
</header>
<section class="scrollable wrapper">
	
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_omset' ?>">
			<th>Cabang : 
			<?php if($check_admin) { ?>	
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
				
			<th><button type="submit" class="btn btn-primary btn-fill btn-wd"><i class="fa fa-search"></i> Cari</button></th>
			</form>	
		</thead>
	</table>
</div>
<?php 
$total = array();	
foreach($total_bayar as $d){
	if(gettype($d) == 'array'){
		$d = array_sum($d);
		} else {
			$d =0;
		}
	$total[] = $d;
}	
$total_pendapatan = array_sum($total);	
if($check_admin){
	$this->db->where('id_cabang',$cabang);
} else {
	$this->db->where('id_cabang',$id_username);
}	
$day = 1;
$waktu = $year.'-'.$month.'-'.$day;
$date = strtotime($waktu);
$omzet = $this->db->get('omset')->result_array();
if(!empty($omzet)){
	foreach($omzet as $d){
		$id = $d['id'];
		$id_cabang = $d['id_cabang'];
		$target = $d['target'];
		$waktu = $d['waktu'];
	}	
	if(!empty($target)){
		$presentasi_omset = ($total_pendapatan / $target) * 100;
	}	else {
		$presentasi_omset = 0;
	}
	if($total_pendapatan >= $target){
		$bonus_omset = ($target * 0.01) + (($total_pendapatan - $target) * 0.02); 
	}	
}	
include 'addomset.php';	
?>
<section class="panel panel-default" style="width: 100%">
	<header class="panel-heading bg-danger lt no-border">
		<div class="clearfix">
			<div class="clear">
				<div class="h3 m-t-xs m-b-xs text-white">
					<i class="fa fa-circle text-white pull-right text-xs m-t-sm"></i>
					CABANG <?php echo $nama;?>
				</div>
			</div>                
		</div>
	</header>
<div class="list-group no-radius alt">
	<a class="list-group-item" href="#">
		<span class="badge bg-success"><?php if($total_bayar) echo $this->mymodel->format($total_pendapatan); ?></span>
		<i class="fa fa-dollar icon-muted"></i> TOTAL OMSET</a>
	<div class="list-group-item" href="#">
		<?php if(!empty(($omzet))) {?>
		<a data-toggle="modal" data-target="#modal-omset" class="badge bg-info">
			<?php if($target) echo $this->mymodel->format($target); ?>
		</a>	
		<?php } else { ?>
			<a data-toggle="modal" data-target="#modal-omset" class="btn btn-danger btn-circle badge" data-popup="tooltip" data-placement="top" title="Edit Data">Add</a>
		<?php } ?>
	<i class="fa fa-level-up icon-muted"></i> TARGET
	</div>
	
	<a class="list-group-item" href="#">
		<?php
			if(!empty($total_pendapatan)){
			if($total_pendapatan >= $target) { ?>
			<span class="badge bg-light"><?php echo $this->mymodel->format($bonus_omset);  ?></span>
		<?php 
				}
			} 
		?>
		<i class="fa fa-money icon-muted"></i>  BONUS OMSET</a>
	
	<a class="list-group-item" href="#">
		<?php if(!empty(($omzet))) {?>
			<span class="badge bg-light"><?php echo $presentasi_omset.' %';  ?></span>
		<?php } ?>
		<i class="fa fa-signal icon-muted"></i> PENCAPAIAN</a>
</div>
</section>	
	
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">	
		<thead>
			<th>Tanggal</th>
			<th>Service / Penjualan</th>
			<th>Actions</th>
		</thead>
		<tbody>
				<?php
				$x = 1;
				$z = 1;
				$y =1;
				$total = array();
					foreach($total_bayar as $d){
				?>
				<tr>
					<td><?php echo $x++.'-'.$month.'-'.$year;?></td>
					<td>
						<?php 
							if(gettype($total_bayar[$z++]) == "array"){
								
								echo $this->mymodel->format(array_sum($d));
							} else {
								$data = 0;
								echo $this->mymodel->format($data);
							}
						$total[] = $d;
						?>
					</td>
					<td>
					<div class="icon-container">	
					<a href="<?php echo base_url().'index.php/welcome/order/'.$cabang.'^0^0^^'.strtotime($y++.'-'.$month.'-'.$year).'^0'.'^^'; ?>" target="_blank"><span class="ti-video-camera"></span><span class="icon-name">Detail</span></a>
					</div>
					</td>
				</tr>
				<?php
					}
				?>
			</tbody>
	</table>
</div>
</section>	