<?php 
$link = $this->uri->segment(3);
if(!empty($link)){
	$posisi=strpos($link,'%5E',1);
} else {
	$posisi = 0;
}
if($posisi > 0){
	$t = explode('%5E',$link);
	$cabang = $t[0];
	$month = $t[1];
	$year = $t[2];
	$tanggal = $t[3];
	$seles = $t[4];
} else {
	$cabang = 0;
	$month = date('m');
	$year = date('Y');
	$tanggal = '';
	$seles = '';
}
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="normal-table-list">
			<div class="basic-tb-hd">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Laporan Penjualan</label>
						</div>
					</div>
				</div>
			</div>
			<div class="breadcomb-area">
			<div class="bsc-tbl">
				<table class="table table-sc-ex">
					<thead style='background-color:#FFFFFFF'>
						<form method="POST" action="<?php echo base_url().'index.php/laporan/search_penjualan/'; ?>">
						<tr>
							<th>Cabang :
								<?php if(!empty($check_admin)){ ?>
								<select name="cabang" >
									<option value='0'>SEMUA</option>
									<?php
									$this->db->where('id !=', 0);
									$cabang_result = $this->db->get('cabang')->result_array();
									foreach($cabang_result as $c){ ?>
										<option <?php if($cabang == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
									<?php } ?>
								</select>
								<?php } else {
									echo '<input value="'.$nama_cabang.'" disabled/>';
									echo '<input  type="hidden" name="cabang" value="'.$id_cabang.'" />';
								}
								?>
							</th>
							<th>Tanggal : <input name="tanggal" type="date" value="<?php if(date('Y',strtotime($tanggal)) == -0001) echo date('Y-m-d',$tanggal); ?>"/> </th>
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
							<th>Seles :
								<select name="seles" >
									<option></option>
									<?php
									$this->db->where('hakakses',2);
									$user = $this->db->get('user')->result_array();
									foreach($user as $u){ ?>
										<option <?php if($seles == $u['id']){ echo 'selected="selected"'; } ?> value='<?php echo $u['id']; ?>'><?php echo $u['name']; ?></option>
									<?php } ?>
								</select>

							</th>
							<th><button type="submit" class="btn btn-warning">Cari</button></th>
						</tr>
						</form>	
					</thead>
				</table>
			</div>
			<div class="bsc-tbl">
				<table class="table table-sc-ex">
					<thead style='background-color:#99ccff'>
						<tr style='background-color:yellow'>
							<th style="text-align:center; width:100px;">Tanggal</th>
							<th style="text-align:center;">Selesai</th>
							<th style="text-align:center;">Nota</th>
							<th style="text-align:center;">Nama</th>
							<th style="text-align:center;">Telepon</th>
							<th style="text-align:center;">Keterangan</th>
							<th style="text-align:center;">Cabang</th>
							<th style="text-align:center;">Teknisi</th>
						</tr>
					</thead>
					<tbody>	
						<?php foreach ($data as $d){
							$deskripsi = $d['deskripsi'];
							$deskripsi = explode(',',$deskripsi);
						?>
						<tr style="text-align:center; background-color:#90EE90">
							<td><?php echo date('d/m/Y',strtotime($d['waktu'])); ?></td>
							<td><?php if(date('Y',strtotime($d['waktu_con'])) != -0001) echo date('d/m/Y',strtotime($d['waktu_con'])); ?></td>
							<td><?php echo $d['nota']; ?></td>
							<td><?php echo $deskripsi[0]; ?></td>
							<td><?php echo $deskripsi[1]; ?></td>
							<td><?php echo $deskripsi[2]; ?></td>
							<td><?php echo $this->komputer->namaCabang($d['cabang']); ?></td>
							<td><?php echo $this->komputer->namaUser($d['teknisi']); ?></td>
						</tr>
						<tr style='background-color:#99ccff'>
							<td>No</td>
							<td colspan='4'>Keterangan</td>
							<td>Unit</td>
							<td colspan='2'>Total</td>
						</tr>
						<?php
							$x = 1;
							$total_sub = array();
							$this->db->where('id_service',$d['id']);
							$sub_service = $this->db->get('sub_service')->result_array();
							foreach($sub_service as $s){
							$item = $this->komputer->cek($s['id_item'],'id','item');	
							$serial = $this->komputer->cek($s['id_serial'],'id','serial');
							$total_service[] = $s['unit'] * $s['harga'];	
						 ?>
						<tr>
							<td><?php echo $x++; ?></td>
							<td colspan='4'>
								<?php if($s['id_item'] == 0){
									echo $s['service']; 
								} else {
									if(!empty($item)) echo $item[0]['nama'].' '. $item[0]['warna'].' '.$item[0]['tipe'];
								} 
								if($s['id_serial'] != 0){
									 echo $serial[0]['serial'];
								} 
								$total_sub[] = $s['unit'] * $s['harga'];
								?>
							</td>
							<td><?php echo $s['unit']; ?></td>
							<td colspan='2' style="text-align:right;"><?php echo $this->komputer->format($s['unit'] * $s['harga']); ?></td>
						</tr>	
						<?php } ?>
						<tr style="background-color:#F5DEB3;">
							<td colspan='7'>TOTAL</td>
							<td style="text-align:right;"><?php echo $this->komputer->format(array_sum($total_sub)); ?></td>
						</tr>
						<?php	
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="text-center">
	<?php

		if($posisi > 0){
			$page = $this->uri->segment(4) - 10;
			$href = base_url().'index.php/penjualan/'.$this->uri->segment(2).'/'.$link.'/'.$page;
		} else {
			$page = $this->uri->segment(3) - 10;
			$href = base_url().'index.php/penjualan/'.$this->uri->segment(2).'/'.$page;
		}
		echo $this->pagination->create_links();
		?>
</div>