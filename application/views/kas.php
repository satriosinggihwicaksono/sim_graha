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
include 'addkas.php';
include 'addstor.php';
?>
<header class="header bg-light dker bg-gradient">
  <p>KAS</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
	<div class="row">
		<div class="col-md-2">
			<a data-toggle="modal" data-target="#modal-tbhkas" data-popup="tooltip" data-placement="top" title="Tambah Order"><li class="btn btn-default fa fa-plus" style="color:red"> Tambah Daftar Kas</a>
		</div>
		<div class="col-md-2">
			<a data-toggle="modal" data-target="#modal-tbhstorkas" data-popup="tooltip" data-placement="top" title="Tambah Order"><li class="btn btn-default fa fa-plus" style="color:red"> Stor Kas</a>
		</div>
	</div>	
		
</div>	
	
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_kas' ?>">
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
					echo '<input type="text" value="'.$this->mymodel->namaCabang($id_cabang).'" disabled/>';
					echo '<input type="hidden" name="cabang" value="'.$id_username.'" />';
				}	
			?>	
			</th>	
			<th>Tanggal : 
				<input type="date" name="tanggal" value="<?php if(!empty($tanggal)) echo date('Y-m-d', $tanggal)?>"/>
			</th>	
			<th>Bulan : 
			<select name="month">
				<?php
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

<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">	
			<thead>
			<th>Tanggal</th>	
			<th>Deskripsi</th>
			<th>Nominal</th>	
			<th>Saldo</th>
			<th>Actions</th>
		</thead>
		<tbody>
			<?php 
				$total_saldo_month = array();
				if($cabang != 3){
					$total_saldo_month[] = $saldo_awal_bulan;
				}	
				$total_debit_month = array();
				$total_kredit_month = array();
			?>
			<?php if($cabang != 3){ ?>
			<tr>
				<td style="background-color:#FFFACD;"></td>
				<td style="background-color:#FFFACD;"><b>Saldo Awal Bulan</b></td>
				<td style="background-color:#FFFACD;"></td>
				<td style="background-color:#FFFACD; ?>;"><b><?php echo $this->mymodel->format($saldo_awal_bulan); ?></b></td>
				<td style="background-color:#FFFACD;">
					<?php if(!empty($id_saldo)){ ?><a href="<?php echo base_url().'index.php/welcome/deleteItem/total_kas/'.$id_saldo;?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-info">
					Update</a>
					<?php } ?>
				</td>
			</tr>	
			<?php } ?>
			<?php
				foreach($data as $d){ 
				include 'editkas.php';
				include 'editstor.php';	
				if($d['status'] == 2){
					$status = '#90EE90';
				} else {
					$status = '#F5DEB3';
				}
			?>
			<tr>
				<td style="background-color: <?php echo $status; ?>;"><b><?php echo date('d/m',strtotime($d['waktu'])); ?></b></td>
				<td style="background-color: <?php echo $status; ?>;"><b><?php echo $d['deskripsi']; ?></b></td>
				<td style="background-color: <?php echo $status; ?>;"><b><?php echo $this->mymodel->format($d['saldo']); ?></b></td>
				<td style="background-color: <?php echo $status; ?>;"><b>
				<?php 	
					if($d['status'] == 2){
						$saldo_sub = (int)$d['saldo'];
						$total_debit_month[] = $d['saldo'];
					} else {
						$saldo_sub = $d['saldo'] * -1;
						$total_kredit_month[] = $d['saldo'];
					}
					
					$total_saldo_month[] = $saldo_sub;
					
					echo $this->mymodel->format(array_sum($total_saldo_month));
				?>
				</b>
				</td>
				<td style="background-color: <?php echo $status; ?>;">
					<?php if($d['id_stor'] == 0){ ?>
						<a data-toggle="modal" data-target="#modal-editkas<?=$d['id'];?>" data-popup="tooltip" class="btn btn-info btn-circle" data-placement="top" title="Edit Data"><i class="fa fa-wrench"></i> Ubah</a>		
						<a href="<?php echo base_url().'index.php/welcome/deleteItem/kas/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
					<?php  } else { ?>
						<a data-toggle="modal" data-target="#modal-editstor<?=$d['id_stor'];?>" data-popup="tooltip" class="btn btn-info btn-circle" data-placement="top" title="Edit Data"><i class="fa fa-wrench"></i> Ubah</a>		
						<a href="<?php echo base_url().'index.php/welcome/deleteStor/stor/'.$d['id_stor'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
					<?php } ?>
				</td>
			</tr>	
			<?php } ?>
		</tbody>
	</table>
</div>
<br>
	
<div class="row">	
<div class="col-md-4">
  <section class="panel panel-default">
	<header class="panel-heading font-bold">Saldo</header>
		<div class="panel-body">
		  <div>
			<span class="text-muted">Total:</span>
			<span class="h3 block"><?php echo $this->mymodel->format(array_sum($total_saldo_month)); ?></span>
		  </div>
		  <div class="line pull-in"></div>
		  <div class="row m-t-sm">
			<div class="col-xs-6">
			  <small class="text-muted block">Kredit</small>
			  <span><?php echo $this->mymodel->format(array_sum($total_kredit_month)); ?></span>
			</div>
			<div class="col-xs-6">
			  <small class="text-muted block">Debit</small>
			  <span><?php echo $this->mymodel->format(array_sum($total_debit_month)); ?></span>
			</div>
		</div>
	</div>
  </section>
	</div>
	</div>
</section>	