<!DOCTYPE html>
<?php 
$id = $this->uri->segment(3);
$this->db->where('id',$id);
$mutasi = $this->db->get('mutasi')->result_array();
$id_from = $mutasi[0]['id_from'];
$id_to = $mutasi[0]['id_to'];
$tanggal = strtotime($mutasi[0]['waktu']);

$this->db->where('id_mutasi',$id);
$sub_mutasi = $this->db->get('sub_mutasi')->result_array();
?>
<html>
	<head>
		<title>Cetak Mutasi</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css" type="text/css" />
		<script>
			window.print();
		</script>
	</head>
	<body>
		<div style="padding:20px 20px 20px 20px">
			<div class="row">
				<?php 
					$this->db->where('id_user',$id_from);
					$user_from = $this->db->get('detail_user')->result_array();
				?>
				<div class="col-md-3">
					<img src="<?php echo base_url();?>assets/images/logo.png" class="m-r-sm" style="width:150px; float: left; margin: 10px;">
					<h3><?php echo $this->mymodel->namaCabang($id_from); ?></h3>
					<h4><?php echo $user_from[0]['alamat']; ?></h4>
					<h4><?php echo $user_from[0]['telepon']; ?></h4>
				</div>	
				<div class="col-md-4">
				</div>
				<?php 
					$this->db->where('id_user',$id_to);
					$user_to = $this->db->get('detail_user')->result_array();
				?>
				<div class="col-md-5">
					<table>
						<tr>
							<td><h3>No Transaksi</h3></td>
							<td><h3> : </h3></td>
							<td><h3><?php echo date('d/m/Y/', $tanggal).$this->mymodel->namaCabang($id_from).'/'.$this->mymodel->namaCabang($id_to).'/'.$id; ?></h3></td>
						<tr>
						<tr>
							<td><h3>Tanggal</h3></td>
							<td><h3> : </h3></td>
							<td><h3><?php echo date('d/m/Y', $tanggal); ?></h3></td>
						<tr>
						<tr>
							<td><h3>Alamat</h3></td>
							<td><h3> : </h3></td>
							<td><h3><?php echo $user_to[0]['alamat']; ?></h3></td>
						<tr>	
					</table>	
				</div>	
			</div>	
		<br>	
		<div class="row">
			<div style="text-align:center">
				<h3>Surat Jalan Keluar Barang</h3>
			</div>	
		</div>	
		<br>	
		<table class="table table-striped m-b-none" style="width:100%">	
			<thead>
				<th><h3>Kode Barang</h3></th>
				<th><h3>Nama Barang</h3></th>
				<th><h3>Jumlah</h3></th>	
			</thead>
			<tbody>
				<?php 
				$total_unit = array();
				foreach($sub_mutasi as $d){
				$this->db->where('id', $d['id_item']);
				$this->db->where('status', 0);
				$item = $this->db->get('item')->result_array();
				$unit = $d['unit'];
				$total_unit[] = $unit;
				?>
				<tr>	
					<td><h3><?php echo $item[0]['code']; ?></h3></td>
					<td><h3><?php echo $item[0]['nama'].' '.$item[0]['merek'].' '.$item[0]['tipe']; ?></h3></td>
					<td><h3><?php echo $unit; ?></h3></td>
				</tr>
				<?php } ?>
				<tr>	
					<td><h3>Keterangan</h3></td>
					<td style="text-align:right"><h3>Total item : <?php echo array_sum($total_unit); ?></h3></td>
					<td></td>
				</tr>	
			</tbody>
	</table>
	<div class="row">
		<div class="col-md-6" style="text-align:center">
		<h3>
			Hormat Kami
			<br>
			<br>
			<br>
			<br>
			(...................)
		</h3>	
		</div>
		<div class="col-md-6" style="text-align:center">
		<h3>
			Penerima
			<br>
			<br>
			<br>
			<br>
			(...................)
		</h3>	
		</div>
	</div>	
	</div>		
		<!-- Bootstrap -->
		<script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
	</body>	
</html>