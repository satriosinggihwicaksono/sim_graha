<?php 
$array = array('','Perorangan','Badan usaha','Pemerintah');
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
	$cabang = $t[0];
	$status = $t[1];
	$month = $t[2];
	$year = $t[3];
	$tanggal = $t[4];
	$urutan = $t[5];
	$kondisi = $t[6];
	$nama = $t[7];
	$tipe = $t[8];
} else {
	$cabang = '';
	$status = '';
	$month = '';
	$year = '';
	$tanggal = '';
	$urutan = '';
	$kondisi = '';
	$nama = '';
	$tipe = '';
}
?>
<header class="header bg-light dker bg-gradient">
  <p>ORDER</p>
</header>
<section class="scrollable wrapper">
	
<div class="doc-buttons">
	<a data-toggle="modal" data-target="#modal-tbhorder" data-popup="tooltip" data-placement="top" title="Tambah Order"><li class="btn btn-default fa fa-plus" style="color:red"> Tambah Order</a>
</div>
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/search_order' ?>">
			<th>Nama : <input name="nama" type="text" value="<?php if(!empty($nama)) echo $nama; ?>" /></th>
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
						<option <?php if( $cabang == $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
						<?php } ?>
			</select>
			<?php 
				} else {
					echo '<input type="text" value="'.$sub_cabang[0]['username'].'" disabled/>';
					echo '<input type="hidden" name="cabang" value="'.$sub_cabang[0]['id'].'" />';
				}	
			?>	
			</th>	
			<th>Urutan : 
			<select name="urutan">
						<?php
						$urutan_array = array("","Tgl terkecil","Tgl terbesar","data terbaru");
						$count_urutan=count($urutan_array);
						
						for($c=0; $c<$count_urutan; $c+=1){ ?>
							<option <?php if($c == $urutan) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $urutan_array[$c] ?></option>";
						<?php
						}
						?>
			</select>
			</th>	
			<th>Status : 
			<select name="status">
				<?php
				$status_array = array("Belum diproses","Proses","Negoisasi","Cancel","Deal");
				$count_status=count($status_array);
				
				for($c=0; $c<$count_status; $c+=1){ ?>
					<option <?php if($c == $status) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $status_array[$c] ?></option>";
				<?php
				}
				?>
			</select>
			</th>
			<th>Tanggal : 
				<input type="date" name="tanggal" value="<?php if(!empty($tanggal)) echo date('Y-m-d', $tanggal)?>"/>
			</th>	
			<th>Bulan : 
			<select name="month">
				<?php
				$total_raws = array();
				foreach($data_raw as $r){
						$sum_raws = $r["SUM(total_raw)"];
						if($sum_raws != NULL){
							$total_raws[] = $sum_raws;
					}
				}	
				$month_array = array("","January","February","March","April","May","June","July","August","September","October","November","December");
				$count_month=count($month_array);
				for($c=0; $c<$count_month; $c+=1){ ?>
					<option <?php if($c == $month) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $month_array[$c] ?></option>";
				<?php
				}
				?>
			</select>
			</th>	
			
			<th>Tahun : 
			<select name="year">
				<option></option>
				<?php
				for($i=2019; $i<=2040; $i++){ ?>
					<option <?php if($i == $year) { ?> selected="selected" <?php } ?>  value=<?php echo $i; ?> > <?php echo $i ?></option>";
				<?php }	?>
			</select>
			</th>	
			
			<!--
			<th>Credit / Cash : 
			<select name="kondisi">
						<?php
						$status_array = array("All","Kredit","Cash");
						$count_status=count($status_array);
						
						for($c=0; $c<$count_status; $c+=1){ ?>
							<option <?php if($c == $kondisi) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $status_array[$c] ?></option>";
						<?php
						}
						?>
			</select>
			</th>
			-->
			<th>
				<label>Tipe</label>
				<?php 
					$count = count($array);
				?>
				<select name="tipe">
					<?php for($a=0;$a<$count;$a++){?>
					<option <?php if($a == $tipe){echo "selected='selected'";} ?> value="<?=$a?>"><?=$array[$a]?></option>
					<?php } ?>
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
			<th>Tgl</th>
			<th>Nama</th>
			<th>Alamat</th>	
			<th>Ket.Order</th>
			<th>Nomor HP</th>
			<th>Nota</th>
			<th>Nominal</th>	
			<th>Cabang</th>
			<th>Presentase</th>
			<th>Status</th>
			<th>Actions</th>
		</thead>
		<tbody>
			<?php
			
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
						if(!empty($total_bayaran)){
						$total_bayar[] = $total_bayaran;
						$this->db->where('id', $p['id_pesan']); 
						$induk = $this->db->get('pesan')->result_array();
							if((int)$p['id_pesan'] != 0){
								$bayar_induk = explode(",",$induk[0]['bayar']);
								$tanggal_induk = strtotime($p['tgl_masuk']);
							}	
						}	
					}
					
					if(!empty($bayar_induk[1]) && (int)$bayar_induk[1] != 0){
						$bayar_induk = $bayar_induk[1];
					} else {
						$bayar_induk = 0;
					}
					
					$total_bayar = array_sum($total_bayar); 
					$kondisi = ($total_nominal - $total_bayar) - $bayar_induk;
				}
			$tgl_masuk = $d['tgl_masuk']; $tgl_masuk = new DateTime($tgl_masuk); $tgl_masuk = $tgl_masuk->getTimeStamp();
			$this->db->order_by('id', 'desc'); $this->db->where('id_pesan',$d['id']); $status_order = $this->db->get('proses_pesan')->result_array();	
			$tgl_estimasi = date('Y-m-d', strtotime('3 days',$tgl_masuk));
			$tgl = date('Y-m-d');
	
			if($tgl >= $tgl_estimasi && empty($status_order)){
				$status = '#F2FE77';
			} else {
				$status = '';
			}
			?>
			<tr>
				<td style="background-color: <?php echo $status; ?>;"><?php echo date("j", $tgl_masuk); ?></td>
				<td style="background-color: <?php echo $status; ?>;"><?php if($d['nama']) echo $d['nama']; ?></td>
				<td style="background-color: <?php echo $status; ?>;"><?php if($d['alamat']) echo $d['alamat']; ?></td>
				<td style="background-color: <?php echo $status; ?>;"><?php echo $d['keterangan'] ?></td>
				<td style="background-color: <?php echo $status; ?>;">
					<?php	
						if($d['telepon'] && !$check_admin && !$check_marketing) echo $this->mymodel->decrypt($d['telepon']);
						if($d['telepon'] && $check_admin || $check_marketing) echo $d['telepon'];
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
						$this->db->where('id', $d['id_cabang']); 
						$user = $this->db->get('user')->result_array();
					?>
				<?php echo $user[0]['username']; ?>	
				</td>
				<td>
					<a data-toggle="modal" data-target="#modal-presentase<?=$d['id'];?>" class="btn btn-success btn-circle" data-popup="tooltip" data-placement="top" title="Edit Data"><?=$d['presentase'].'%';?></a>
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
					<a data-toggle="modal" data-target="#modal-order<?=$d['id'];?>" data-popup="tooltip" class="btn btn-info btn-circle" data-placement="top" title="Edit Data"><i class="fa fa-wrench"></i> Ubah</a>		
					<a href="<?php echo base_url().'index.php/welcome/deletePesan/pesan/'.$d['id'];?>" onclick="return confirm('Are you sure delete this item?');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>
				</td>
			</tr>
			<?php
				include 'addnota.php';
				include 'addnominal.php';
				include 'addpresentase.php';
				include 'ubah_status.php';
				include 'ubah_order.php';
				include 'hutang.php';
				} 
			?>
		</tbody>
	</table>
</div>
<br>
	<div class="text-center">
			<?php
				if($posisi >= 1){
					$page = $this->uri->segment(4) - 10;
					$href = base_url().'index.php/welcome/'.$this->uri->segment(2).'/'.$link.'/'.$page;
					if(empty($data) && $this->uri->segment(4)){
						echo '<a href ="'.$href.'"><button class="btn btn-info btn-fill btn-wd">previous</button></a>';
					}	
				} else {
					$page = $this->uri->segment(3) - 10;
					$href = base_url().'index.php/welcome/'.$this->uri->segment(2).'/'.$page;
					if(empty($data) && $this->uri->segment(3)){
						echo '<a href ="'.$href.'"><button class="btn btn-info btn-fill btn-wd">previous</button></a>';
					}	
				}
				echo $this->pagination->create_links();
			?>
	</div>
</section>	
<?php 
include 'tambah_order_popup.php';
?>