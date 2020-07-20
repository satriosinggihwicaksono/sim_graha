<header class="header bg-light dker bg-gradient">
  <p>Supplier</p>
</header>
<section class="scrollable wrapper">
<div class="doc-buttons">
	<form method="POST" action="<?php echo base_url().'index.php/welcome/tambah_supplier' ?>">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Supplier</label>
				<input type="text" class="form-control border-input" name="supplier" placeholder="" value="">
			</div>
		</div>
	</div>
	<button type="submit" class="btn btn-info btn-fill btn-wd">Tambah Supplier</button>
	</form>
	<div class="content table-responsive table-full-width">
		<table class="table table-striped m-b-none" style="width:60%">
			<thead>
				<th>No</th>
				<th>Nama</th>
				<th>Actions</th>
			</thead>
			<tbody>
				<?php
					$data_page = $data;
					$x = $this->uri->segment('3') + 1;
					foreach($data as $d){ 
					$d = get_object_vars($d);
				?>
				<form method="POST" action="<?php echo base_url().'index.php/welcome/ubah_supplier/'.$d['id'] ?>">
				<tr>
					<td><?php echo $x++; ?></td>
					<td><input type="text" name="supplier" style="width:130px" value="<?php echo $d['supplier']; ?>"/></td>
					<td>
					<input type="submit" value="Ubah" class="btn btn-info"/>		
					<a href="<?php echo base_url().'index.php/welcome/delete/supplier/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
					</td>
				</tr>
				</form>			
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="text-center">
				<?php
					$page = $this->uri->segment(3) - 10;
					$href = base_url().'index.php/welcome/'.$this->uri->segment(2).'/'.$page;
					if(empty($data_page) && $this->uri->segment(3)){
						echo '<a href ="'.$href.'"><button class="btn btn-info btn-fill btn-wd">previous</button></a>';
					}	
					echo $this->pagination->create_links();
				?>
	</div>
</section>	