<section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.html"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Workset</li>
              </ul>
              <div class="m-b-md">
				 <h3 class="m-b-none">PROSES TRANSAKSI STOK</h3>
              </div>
<div class="doc-buttons">
	<a href="<?php echo base_url().'index.php/welcome/tambah_bukutamu/'; ?>" class="btn btn-s-md btn-default">Tambah Bukutamu</a>    
	<table class="table table-striped m-b-none" data-ride="datatables" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/bukutamu/'; ?>">
				<th>Nama <input type="text" name="nama" value="" style="width:70px"/></th>
				<th>Nomer HP <input type="text" name="telepon" value="" style="width:70px"/></th>
				<th>Cabang <input type="text" name="cabang" value="" style="width:70px"/></th>
				<th><button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Cari</button></th>
			</form>
		</thead>
	</table>
</div>

<table class="table table-striped m-b-none" data-ride="datatables" style="width:100%">
	<thead>
		<th>No</th>
		<th>Nama</th>
		<th>Alamat</th>
		<th>Nomor HP</th>
		<th>Cabang</th>
		<th>Transaksi</th>
		<th>Actions</th>
	</thead>
	<tbody>
		<?php 
		$x = $this->uri->segment('3') + 1;
		foreach($data as $d){  
		$d = get_object_vars($d);	
		?>
		<form method="POST" action="<?php echo base_url().'index.php/welcome/rubah_bukutamu/'.$d['id'] ?>">
		<tr>
			<td><?php echo $x++; ?></td>
			<td><input type="text" class="form-control border-input" name="nama" value="<?php echo $d['nama']; ?>" /></td>
			<td><input type="text" class="form-control border-input" name="alamat" value="<?php echo $d['alamat']; ?>" /></td>
			<td><input type="text" class="form-control border-input" name="telepon" value="<?php echo $d['telepon']; ?>" style="width:110px"/></td>
			<td><input type="text" class="form-control border-input" name="id_cabang" value="<?php echo $d['id_cabang']; ?>"/></td>
			<td><a href="<?php echo base_url().'index.php/welcome/tes/bukutamu/'.$d['id'];?>" class="btn btn-warning"><i class="fa fa-shopping-cart"></i> Transaksi</a></td>
			<td>
			<input type="submit" value="Ubah" class="btn btn-info"/>		
			<a href="<?php echo base_url().'index.php/welcome/delete/bukutamu/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
			</td>
		</tr>
		</form>	
		<?php } ?>
	</tbody>
</table>

<div class="text-center">
			<?php
				$page = $this->uri->segment(3) - 10;
				$href = base_url().'index.php/welcome/'.$this->uri->segment(2).'/'.$page;
				if(!empty($data) && $this->uri->segment(3)){
					echo '<a href ="'.$href.'"><button class="btn btn-info btn-fill btn-wd">previous</button></a>';
				}	
				echo $this->pagination->create_links();
			?>
</div>