<header class="header bg-light dker bg-gradient">
  <p>DAFTAR SUB PENGGUNA</p>
</header>
<section class="scrollable wrapper">
<div class="doc-buttons">
	<a href ="<?php echo base_url().'index.php/welcome/tambah_sub_pengguna/'; ?>"><button class="btn btn-info btn-fill btn-wd">Tambah Pengguna</button></a>
		<div class="content table-responsive table-full-width">
			<table class="table table-striped m-b-none" style="width:100%">
			<thead>
				<th>No</th>
				<th>Username</th>
				<th>Name</th>
				<th>Cabang</th>
				<th>Status</th>
				<th>Hak ases</th>
				<th>Cabang</th>
				<th>Detail</th>
				<th>Ubah Password</th>
				<th>Slip Gaji</th>
				<th>Actions</th>
			</thead>
			<tbody>
				<?php
					$data_page = $data;
					$x = $this->uri->segment('3') + 1;
					foreach($data as $d){ 
					include 'tbhslip_gaji.php';	
				?>
				<form method="POST" action="<?php echo base_url().'index.php/welcome/update_pengguna/'.$d['id'] ?>">
				<tr>
					<td><?php echo $x++; ?></td>
					<td><?php echo $d['username']; ?></td>
					<td><?php echo $d['name']; ?></td>
					<td>
						<?php 
						$this->db->where('id', $d['cabang']); 
						$user = $this->db->get('user')->result_array();
						echo $user[0]['username']; 
						?>
					</td>
					<td>
					<select name="status" value="">
					<?php
					$status = array('Nonaktif','aktif');
					$count_status = count($status);
					for($c=0; $c<$count_status; $c+=1){ ?>
						<option <?php if($c == $d['status']) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $status[$c] ?></option>";
					<?php
						 }	
					?>
					</select>
					</td>
					<td>
					<select name="hakakses" value="">
					<?php
					$hakakses = array('Admin','Cabang','Karyawan','Marketing');
					$count_hakakses = count($hakakses);
					for($e=0; $e<$count_hakakses; $e+=1){ ?>
						<option <?php if($e == $d['hakakses']) { ?> selected="selected" <?php } ?>  value=<?php echo $e; ?> > <?php echo $hakakses[$e] ?></option>";
					<?php
						 }	
					?>
					</select>
					</td>	
					<td>
						<select name="cabang">
						<?php	
						$this->db->where('hakakses !=', 0);
						$this->db->where('hakakses !=', 3);
						$this->db->where('hakakses !=', 2);
						$user = $this->db->get('user')->result_array();
						foreach($user as $c){ 
						?>
						<option <?php if( $d['cabang'] === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
						<?php } ?>
						</select>	
					</td>	
					<td><a href="<?php echo base_url().'index.php/welcome/setting_pengguna/'.$d['id'];?>"><span class="fa fa-wrench"></span><span class="icon-name"> Ubah Password</span></a></td>
					<td><a href="<?php echo base_url().'index.php/welcome/detail_user/'.$d['id'];?>"><span class="fa fa-gear"></span><span class="icon-name"> Detail User</span></a></td>
					<td><a data-toggle="modal" data-target="#modal-gaji<?=$d['id'];?>" class="btn btn-success btn-circle" data-popup="tooltip" data-placement="top" title="Edit Data">Setting Gaji</a></td>
					<td>
					<input type="submit" value="Ubah" class="btn btn-info"/>		
					<a href="<?php echo base_url().'index.php/welcome/delete/user/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
					</td>
				</tr>
				</form>			
				<?php } ?>
			</tbody>
		</table>
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
</div>
</section>	