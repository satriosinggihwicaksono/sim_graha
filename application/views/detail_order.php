<?php

foreach($data as $d){
	$id_pesan = $d['id'];
	$id_cabang = $d['id_cabang'];
	$id_bukutamu = $d['id_bukutamu'];
	$status = $d['status'];
	$seles = $d['seles'];
	$teknisi = $d['teknisi'];
	$keterangan = $d['keterangan'];
	$nota = $d['nota'];
	$status = $d['status'];
	$tgl_masuk = $d['tgl_masuk']; $tgl_masuk = new DateTime($tgl_masuk); $tgl_masuk = $tgl_masuk->getTimeStamp();
	$nominal = $d['bayar'];
}

if(!empty($nominal)){
	$nominal = explode(",",$d['bayar']);
	$total_nominal = (int)$nominal[0];
	$bayar_nominal = (int)$nominal[1];
	$kondisi = $total_nominal - $bayar_nominal;
	if(empty($nominal[0]) || $nominal[0] == 0){
		$ket_bayar = 'Tidak ada Nominal';
	} elseif($kondisi <= 0){
		$ket_bayar = 'LUNAS';
	} elseif($kondisi >= 0) {
		$ket_bayar = 'BELUM LUNAS';
	} else {
		$ket_bayar = 'Tidak ada Nominal';
	}

} else {
	$total_nominal = '';
	$bayar_nominal = '';
	$ket_bayar = '';

}

if(!empty($nota)){
	$this->db->where('nota', $nota);
	$total_transaksi = $this->db->get('pesan')->result_array();
	if(!empty($total_transaksi)){
		$deskripsi = $total_transaksi[0]['deskripsi'];
		$t = explode(",",$deskripsi);
		$tgl_order = $t[0];
		$tgl_instalasi = $t[1];
		$telepon = $t[2];
		$toko = $t[3];
		$alamat = $t[4];
		$kota = $t[5];

		$id_total_transaksi = $total_transaksi[0]['id'];

		$this->db->where('id_total_transaksi', $id_total_transaksi);
		$sub_transaksi = $this->db->get('sub_transaksi')->result_array();
	}	
}

$this->db->where('id',$id_cabang);
$cabang = $this->db->get('user')->result_array();

foreach($cabang as $c){
	$nama_cabang = $c['username'];
}

$this->db->where('id',$id_bukutamu);
$bukutamu = $this->db->get('bukutamu')->result_array();
foreach($bukutamu as $b){
	$nama = $b['nama'];
}

$this->db->where('id_user',$id_cabang);
$detail_cabang = $this->db->get('detail_user')->result_array();

foreach($detail_cabang as $d){
	$email_cabang = $d['email'];
	$alamat_cabang = $d['alamat'];
	if(!$check_admin){
		$telepon_cabang = $this->mymodel->decrypt($d['telepon']);
	} else {
		$telepon_cabang = $d['telepon'];
	}
}

$this->db->where('status',$status);
$this->db->where('id_pesan',$id_pesan);
$proses_pesan = $this->db->get('proses_pesan')->result_array();
foreach($proses_pesan as $p){
	$id_pesanan = $p['id'];
	$keterangan_pesan = $p['keterangan'];
}


?>
<section class="vbox bg-white">
	<header class="header b-b b-light hidden-print">
	  <button href="#" class="btn btn-sm btn-info pull-right" onClick="window.print();">Print</button>
	  <p>Invoice</p>
	</header>
	<section class="scrollable wrapper">
	  <img src="<?php echo base_url();?>assets/images/logo.png" width="70px" >
	  <div class="row">
		<div class="col-xs-6">
		  <h4>Grahacomp CCTV</h4>
		  <p><a href="http://www.apple.com">www.indonesiacctv.com</a></p>
		</div>
		<div class="col-xs-6 text-right">
		  <p class="h4"><?php echo $nota; ?></p>
		  <h5><?php if(!empty($deskripsi)) echo date("d-m-Y", $tgl_order); ?></h5>           
		</div>
	  </div>          
	  <div class="well m-t">
		<div class="row">
		  <div class="col-xs-6">
			<strong>Dari:</strong>
			<h4><?php echo $nama_cabang; ?></h4>
			<p>
			  <?php if($detail_cabang) echo $alamat_cabang; ?> <br>
			  indoensia<br>
			  Phone: <?php if($detail_cabang) echo $telepon_cabang; ?><br>
			  Email: <?php if($detail_cabang) echo $email_cabang; ?><br>
			</p>
		  </div>
		  <div class="col-xs-6">
			<strong>Tujuan:</strong>
			<h4><?php if(!empty($total_transaksi)) echo $toko; ?></h4>
			<p>
			  <?php if(!empty($total_transaksi)) echo $alamat; ?><br>
			  Indonesia<br>
			  Phone: <?php if(!empty($total_transaksi)) echo $telepon; ?><br>
			</p>
		  </div>
		</div>
	  </div>
	  <p class="m-t m-b">Order date: <strong><?php echo date("d-m-Y", $tgl_masuk); ?></strong><br>
		  Order status: 
		  
		  <?php
				if(empty($status)){
					$order_status = '<span class="fa fa-minus-circle" style="color:red"> UNPROCESS</span>';
				} elseif($status == 2) {
					$order_status = '<span class="fa fa-check-square" style="color:blue"> NEGOISASI</span>';
				} elseif($status == 3) {
					$order_status = '<span class="fa fa-times" style="color:red"> CANCLE</span>';
				} elseif($status == 4) {
					$order_status = '<span class="fa  fa-chevron-down" style="color:green"> DEAL</span>';
				} elseif($status == 1) {
					$order_status = '<span class="fa fa-refresh" style="color:brown"> PROSES</span>';
				}
				echo $order_status;
		?>	
		  <br>
		  Keterangan Order: <strong><?php if($nominal) echo $ket_bayar; ?></strong>
		  <br>
		  Nominal: <strong>Rp<?php if($nominal) echo $total_nominal; ?></strong>
		  <br>
		  Order ID: <strong>#<?php if($proses_pesan) echo $id_pesanan; ?></strong>
	  </p>
	  <div class="line"></div>
	  <table class="table">
		<thead>
		  <tr>
			<th width="60">QTY</th>
			<th>DESCRIPTION</th>
			<th width="140">UNIT PRICE</th>
			<th width="90">TOTAL</th>
		  </tr>
		</thead>
		<tbody>
		
		<?php 
		if(!empty($total_transaksi)){	
		foreach($sub_transaksi as $s){ 
		$harga_pcs = $s['harga_jual'] / $s['unit']; 
		$total_pembayaran = $total_transaksi[0]['total_harga'] - $total_transaksi[0]['total_potongan'];
		$kembalian = ($total_pembayaran - $total_transaksi[0]['bayar']) * -1;
		?>
		  <tr>
			<td><?php echo $s['unit']; ?></td>
			<td><?php echo $s['nama']; ?></td>
			<td><?php echo $harga_pcs; ?></td>
			<td><?php echo $s['harga_jual']; ?></td>
		  </tr>
		<?php } ?>	
		  <tr>
			<td colspan="3" class="text-right"><strong>Harga</strong></td>
			<td><?php if(!empty($total_transaksi)) echo $total_transaksi[0]['total_harga']; ?></td>
		  </tr>
		  <tr>
			<td colspan="3" class="text-right no-border"><strong>Potongan</strong></td>
			<td><?php if(!empty($total_transaksi)) echo $total_transaksi[0]['total_potongan']; ?></td>
		  </tr>
		  <tr>
			<td colspan="3" class="text-right no-border"><strong>Total</strong></td>
			<td><?php if(!empty($total_transaksi)) echo $total_pembayaran; ?></td>
		  </tr>
		  <tr>
			<td colspan="3" class="text-right no-border"><strong>Bayar</strong></td>
			<td><?php if(!empty($total_transaksi)) echo $total_transaksi[0]['bayar']; ?></td>
		  </tr>
		  <tr>
			<td colspan="3" class="text-right no-border"><strong>Kembalian</strong></td>
			<td><strong><?php if(!empty($total_transaksi)) echo $kembalian; ?></strong></td>
		  </tr>	
		<?php } ?>	
		</tbody>
	  </table>              
	</section>
</section>
