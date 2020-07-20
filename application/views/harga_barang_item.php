<link href="<?php echo base_url().'assets/';?>vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
<header class="header bg-light dker bg-gradient">
  <p>Harga Jual Barang Item</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
	<a data-toggle="modal" data-target="#modal-tbhorder" data-popup="tooltip" data-placement="top" title="Tambah Order"><li class="btn btn-default fa fa-plus" style="color:red"> Tambah Order</a>
</div>
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_harga_barang_item' ?>">
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
						<option <?php if($cabang == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
						<?php } ?>
			</select>
			<?php 
				} else {
					echo '<input type="text" value="'.$sub_cabang[0]['username'].'" disabled/>';
					echo '<input type="hidden" name="cabang" value="'.$sub_cabang[0]['id'].'" />';
				}	
			?>	
			</th>		
			<th><button type="submit" class="btn btn-primary btn-fill btn-wd"><i class="fa fa-search"></i> Cari</button></th>
			</form>	
		</thead>
	</table>
</div>
<br>	
	
<div class="card-body">
	<div class="content table-responsive table-full-width">
		<table class="table table-sc-ex" id="dataTable" style="width:100%">	
				<thead>
				<th>Nama</th>
				<th>Kategori</th>
				<th>Harga</th>
			</thead>
			<tbody>
				<?php 
					$z = 0;
					foreach($data as $d){
					$z++;
				?>
				<tr>
					<td><?php echo $d['nama'].' '.$d['merek'].' '.$d['tipe']; ?></td>
					<td><?php echo $d['kategori']; ?></td>
					<td>
					<?php
					$this->db->where('id_item',$d['id']);
					$this->db->where('id_cabang',$cabang);
					$harga = $this->db->get('harga_item')->result_array();	
					if(!empty($harga)) echo $this->mymodel->format($harga[0]['harga_jual']);	
					?>
					</td>
				</tr>
				<?php 
					}
				?>
			</tbody>
		</table>
	</div>
</div>
</section>	
