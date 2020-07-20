<?php 
$link = $this->uri->segment(3);
?>
<header class="header bg-light dker bg-gradient">
  <p>DETAIL ORDER</p>
</header>
<section class="scrollable wrapper">


<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">	
			<thead>
			<th>No</th>
			<th>Tanggal Masuk</th>
			<th>Nama</th>
			<th>Ket.Order</th>
			<th>Nomor HP</th>
			<th>Nota</th>
			<th>Nominal</th>	
			<th>Cabang</th>
			<th>Status</th>
			<th>Closing</th>
			<th>Detail</th>
			<th>Actions</th>
		</thead>
		<tbody>
			<?php
			
			$x = $this->uri->segment('4') + 1;
			foreach($data as $d){  
			$d = get_object_vars($d);
			$nota = $d['nota'];
			$nominal = $d['bayar'];
				if($nominal){
					$nominal = explode(",",$nominal);
					$bayar_nominal = $nominal[1];
					$total_nominal = (int)$nominal[0];
					if($d['id_pesan'] == 0){
						$id_pesan = $d['id'];
						$this->db->where('id', $id_pesan);
					} else {
						$id_pesan = $d['id_pesan'];
						$this->db->where('id_pesan', $id_pesan);
					} 
					$pesan = $this->db->get('pesan')->result_array();
					$total_bayar = array();
					foreach($pesan as $p){
						$data_bayar = $p['bayar'];
						$anggaran = explode(",",$data_bayar);
						$total_bayaran = $anggaran[1];
						$total_bayar[] = $total_bayaran;
						$this->db->where('id', $p['id_pesan']); 
						$induk = $this->db->get('pesan')->result_array();
						if((int)$p['id_pesan'] != 0 && !empty($induk)){
							$bayar_induk = explode(",",$induk[0]['bayar']);
							$tanggal_induk = strtotime($p['tgl_masuk']);
						}	
					}
					
					if(!empty($bayar_induk)){
						$bayar_induk = $bayar_induk[1];
					} else {
						$bayar_induk = 0;
					}
					
					$total_bayar = array_sum($total_bayar); 
					$kondisi = ($total_nominal - $total_bayar) - $bayar_induk;
				}
			$this->db->where('id',$d['id_bukutamu']);	
			$bukutamu = $this->db->get('bukutamu')->result_array();
			$tgl_masuk = $d['tgl_masuk']; $tgl_masuk = new DateTime($tgl_masuk); $tgl_masuk = $tgl_masuk->getTimeStamp();
			$this->db->order_by('id', 'desc'); $this->db->where('id_pesan',$d['id']); $status_order = $this->db->get('proses_pesan')->result_array();	
			$tgl_estimasi = date('Y-m-d', strtotime('3 days',$tgl_masuk));
			$tgl = date('Y-m-d');
			$tgl_closing = strtotime($d['tgl_closing']);
	
			if($tgl >= $tgl_estimasi && empty($status_order)){
				$status = '#F2FE77';
			} else {
				$status = '';
			}
			?>
			<tr>
				<td style="background-color: <?php echo $status; ?>;"><?php echo $x++; ?></td>
				<td style="background-color: <?php echo $status; ?>;"><?php echo date("j-m-Y", $tgl_masuk); ?></td>
				<td style="background-color: <?php echo $status; ?>;"><?php if($bukutamu) echo $bukutamu[0]['nama']; ?></td>
				<td style="background-color: <?php echo $status; ?>;"><?php echo $d['keterangan'] ?></td>
				<td style="background-color: <?php echo $status; ?>;">
					<?php	
						if($bukutamu && !$check_admin) echo $this->mymodel->decrypt($bukutamu[0]['telepon']);
						if($bukutamu && $check_admin) echo $bukutamu[0]['telepon'];
					?>
				</td>
				<td style="background-color: <?php echo $status; ?>;">
					<?php if(!empty($d['nota'])) { ?>
						<a data-toggle="modal" data-target="#modal-edit<?=$d['id'];?>" class="btn btn-warning btn-circle" data-popup="tooltip" data-placement="top" title="Edit Data"><?php echo $d['nota']; ?></a>
					<?php
					} else { ?>
						<a data-toggle="modal" data-target="#modal-edit<?=$d['id'];?>" class="btn btn-danger btn-circle" data-popup="tooltip" data-placement="top" title="Edit Data">Add</a>
					<?php
					}
					?>
				</td>
				<td style="background-color: <?php echo $status; ?>;">
					<?php 
					if(empty($nominal[0]) || $nominal[0] == 0){ ?>
						<a data-toggle="modal" data-target="#modal-nominal<?=$d['id'];?>" class="btn btn-dark btn-circle" data-popup="tooltip" data-placement="top" title="Edit Data">Nominal</a>
					<?php } elseif($kondisi <= 0){ ?>
						<a data-toggle="modal" data-target="#modal-hutang<?=$d['id'];?>" data-popup="tooltip" data-placement="top" title="Edit Data"><li class="fa fa-check" style="color:green"></li></a><a data-toggle="modal" data-target="#modal-nominal<?=$d['id'];?>" data-popup="tooltip" data-placement="top" title="Edit Data"> <?php if($nominal) echo $this->mymodel->format($total_nominal); ?></a>													
					<?php } elseif($kondisi >= 0) { ?>
					<a data-toggle="modal" data-target="#modal-hutang<?=$d['id'];?>" data-popup="tooltip" data-placement="top" title="Edit Data"><li class="fa fa-times" style="color:red"></li></a>
					<a data-toggle="modal" data-target="#modal-nominal<?=$d['id'];?>" data-popup="tooltip" data-placement="top" title="Edit Data"> <?php if($nominal) echo $this->mymodel->format($total_nominal); ?></a>													
					<?php
					} else { 
					?>
						<a data-toggle="modal" data-target="#modal-nominal<?=$d['id'];?>" class="btn btn-dark btn-circle" data-popup="tooltip" data-placement="top" title="Edit Data">Nominal</a>
					<?php } ?>
				</td>
				<td style="background-color: <?php echo $status; ?>;">
					<?php
					if($bukutamu) {
						$this->db->where('id', $d['id_cabang']); 
						$user = $this->db->get('user')->result_array();
					}
					?>
				<?php echo $user[0]['username']; ?>	
				</td>
				<td style="background-color: <?php echo $status; ?>;">	
				<?php
				if(empty($status_order[0]['status'])){
					$order_status = '<span class="fa fa-minus-circle" style="color:red"> UNPROCESS</span>';
				} elseif($status_order[0]['status'] == 2) {
					$order_status = '<span class="fa fa-check-square" style="color:blue"> NEGOISASI</span>';
				} elseif($status_order[0]['status'] == 3) {
					$order_status = '<span class="fa fa-times" style="color:red"> CANCLE</span>';
				} elseif($status_order[0]['status'] == 4) {
					$order_status = '<span class="fa  fa-chevron-down" style="color:green"> DEAL</span>';
				} elseif($status_order[0]['status'] == 1) {
					$order_status = '<span class="fa fa-refresh" style="color:brown"> PROSES</span>';
				}
				
				$this->db->where('id_pesan',$d['id']);
				$proses_pesan = $this->db->get('proses_pesan')->result_array();
				?>
				<a data-toggle="modal" data-target="#modal-status<?=$d['id'];?>" data-popup="tooltip" data-placement="top" title="<?php if(!empty($proses_pesan)) echo $proses_pesan[0]['keterangan']; ?>"><?php echo $order_status; ?></a>	
				</td>
				<td style="background-color: <?php echo $status; ?>;">
					<?php if($tgl_closing < 0){ ?>
					<a href="<?php echo base_url().'index.php/welcome/proses_closing/'.$d['id'];?>" class="btn btn-warning"> Selesai</a>	
					<?php } else { ?>
					<a href="<?php echo base_url().'index.php/welcome/hapus_proses_closing/'.$d['id'];?>" class="btn btn-danger"> Urungkan</a>
					<?php } ?>
				</td>
				<td style="background-color: <?php echo $status; ?>;">
					<a href="<?php echo base_url().'index.php/welcome/detail_order/'.$d['id'];?>" class="btn btn-success"><i class="fa fa-eye"></i> Detail</a>	
				</td>
				<td style="background-color: <?php echo $status; ?>;">
				<a href="<?php echo base_url().'index.php/welcome/deletePesan/pesan/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
				</td>
			</tr>
			<?php
				include 'addnota.php';
				include 'addnominal.php';
				include 'ubah_status.php';
				include 'hutang.php';
				} 
			?>
		</tbody>
	</table>
</div>
<br>
<div class="text-center">
			<?php
				$page = $this->uri->segment(4) - 10;
				$href = base_url().'index.php/welcome/'.$this->uri->segment(2).'/'.$link.'/'.$page;	
				echo $this->pagination->create_links();
			?>
</div>	
</section>	
<?php 
include 'tambah_order_popup.php';
?>