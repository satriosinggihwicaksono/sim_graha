<?php 
$serial = $this->uri->segment(3);

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
						<form method="POST" action="<?php echo base_url().'index.php/transaksi/search_daftar_penjualan/'; ?>">
						<tr>
							<th> Serial : <input name="serial"/></th>
							<th><button type="submit" class="btn btn-warning">Cari</button></th>
						</tr>
						</form>	
					</thead>
				</table>
			</div>
			<div class="bsc-tbl">
				<table class="table table-sc-ex">
					<thead style='background-color:#99ccff'>
						<tr>
							<th style="text-align:center; width:100px;">No</th>
							<th style="text-align:left;">Nama</th>
							<th style="text-align:center; width:25px;">Serial</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						$x = 1;
						foreach($data as $d){
					?>
					<tr>
						<td><?php echo $x++; ?></td>	
						<td><?php echo $d['nama'].' '.$d['tipe'].' '.$d['warna']; ?></td>	
						<td><a href="<?php echo base_url().'index.php/persedian_page/pencarian_sn/^'.$d['id_serial'].'^'; ?>"><?php echo $d['serial']; ?></a></td>	
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

		if(!empty($serial)){
			$page = $this->uri->segment(4) - 10;
			$href = base_url().'index.php/transaksi/'.$this->uri->segment(2).'/'.$serial.'/'.$page;
		} else {
			$page = $this->uri->segment(3) - 10;
			$href = base_url().'index.php/transaksi/'.$this->uri->segment(2).'/'.$page;
		}
		echo $this->pagination->create_links();
		?>
</div>