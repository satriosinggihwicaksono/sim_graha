<?php 
$link = $this->uri->segment(3);
$posisi=strpos($link,"%5E");
if($posisi > 0){
	$t = explode("%5E",$link);
	$cabang = (int)$t[0];
	$month = (int)$t[1];
	$year = (int)$t[2];
	$tanggal = (int)$t[3];
} else {
	$cabang = '';
	$month = date('m');
	$year = date('Y');
	$tanggal = '';
}
?>
<header class="header bg-light dker bg-gradient">
  <p>ABSENT</p>
</header>
<section class="scrollable wrapper">
<form method="POST" action="<?php echo base_url().'index.php/welcome/search_absent/'; ?>">
			<td>
				<select name="month">
				<?php
				$year = date('Y',$date);
				$month = date('m',$date);
				$month_array = array("Month","January","February","March","April","May","June","July","August","September","October","November","December");
				$count_month=count($month_array);
				for($c=1; $c<$count_month; $c+=1){ ?>
					<option <?php if($c == $month) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $month_array[$c] ?></option>";
				<?php
				}
				?>
			</select>
			<select name="year">
				<?php
				for($i=2019; $i<=2040; $i++){ ?>
					<option <?php if($i == $year) { ?> selected="selected" <?php } ?>  value=<?php echo $i; ?> > <?php echo $i ?></option>";
				<?php
				}
				$total = array();
				foreach($data as $d){
						$sum = $d["SUM(total_price)"];
						if($sum != NULL){
							$total[] = $sum;
					}
				}	
				?>
			</select>
			</td>
			<?php if($check_admin) { ?>
			<td>
				<select name="cabang" value="">
					<?php	
					$this->db->where('hakakses !=',1);
					$this->db->where('hakakses !=', 0);
					$this->db->where('hakakses !=', 3);
					$user = $this->db->get('user')->result_array();
					foreach($user as $c){ 
					?>
					<option <?php if( $cabang === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
					<?php } ?>
				</select>
			</td>
			<?php } else { ?>
			<td>
				<select name="cabang" value="">
					<?php	
						$this->db->where('cabang', $id_cabang);
						$this->db->where('id !=', $id_username);
						$this->db->where('hakakses !=', 0);
					$user = $this->db->get('user')->result_array();
					foreach($user as $c){ 
					?>
					<option <?php if( $cabang === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
					<?php } ?>
				</select>
			</td>
			<?php } ?>
			<td><button type="submit" class="btn btn-info btn-fill btn-wd">cari</button></td>
		</form>	

	<hr>
				<?php 
						if($data == 'no_result'){
							return false;
						}
						$this->db->where('id_user',$cabang);
						$this->db->where('MONTH(date)',$month);
						$this->db->where('YEAR(date)',$year);
						$total_absent = $this->db->get('absent')->num_rows();
				?>
<?php if(!empty($cabang)){ ?>	
<h4><?php echo 'Nama karyawan: '.$this->mymodel->namaCabang($cabang); ?></h4>
<h4><?php echo 'Total Masuk: '.$total_absent; ?></h4>

<div class="card card-plain">
		<table class="table table-striped m-b-none" style="width:30%">
			<thead>
				<th>No</th>
				<th>tanggal</th>
				<th>Lembur</th>
				<th>Status</th>
				<th>Actions</th>
			</thead>
			<tbody>
				<?php
				$no = 1;
				$x = 1;
				$y = 1;
				$tgl = 1;
				foreach($data as $d){
					$tgl_remove = strtotime(date('Y-m-'.$tgl));
					$a = $x++;
					if($d['date'] != NULL) {
						$timestamp = strtotime($d['date']);
						$absent = '<a href="'.base_url().'index.php/welcome/remove_absent/'.$tgl_remove.'/'.$cabang.'" onclick="return confirm("Are you sure delete this item?");" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</a>';
						$time = '<span class="fa  fa-chevron-down" style="color:green"></span></span>';
						$this->db->where('id_user', $cabang);
						$this->db->where('YEAR(date)',date("Y", $timestamp));
						$this->db->where('MONTH(date)',date("m", $timestamp));
						$this->db->where('DAYOFMONTH(date)',date("d", $timestamp));
						$absen = $this->db->get('absent')->result_array();
						$lembur = $absen[0]['lembur'];
						if($lembur == 0){
							$lembur_button = '<a href="'.base_url().'index.php/welcome/add_lembur/'.$absen[0]['id'].'/'.$absen[0]['lembur'].'" class="btn btn-success"><i class="fa fa-clock-o"></i> Add Lembur</a>';
						} else {
							$lembur_button =  '<a href="'.base_url().'index.php/welcome/add_lembur/'.$absen[0]['id'].'/'.$absen[0]['lembur'].'" class="btn btn-danger"><i class="fa fa-clock-o"></i> Hapus Lembur</a>';
						}
					} else if($a == date('d',time()) && date('Y-m',$date) == date('Y-m',time())){
						$absent = '<input type="submit" value="Absent"/>';
						$time = 'Waiting for Absent'; 
					} else {
						$absent = '<input type="submit" value="Absent"/>';
						$time = '<span class="fa fa-times" style="color:red"><span class="ti-close"></span></span>';
					}
					
				?>
				
				<tr>
					<td><?php echo $no++; ?></td>
					<td><?php echo date($y++.'-m-Y',$date); ?></td>
					<td><?php if($d['date'] != NULL) echo $lembur_button; ?></td>
					<form method="POST" action="<?php echo base_url().'index.php/welcome/absent_action/'; ?>">
					<input type="hidden" name="cabang" value="<?php echo $cabang; ?>"/>
					<input type="hidden" name="tanggal" value="<?php echo date('Y-m-'.$tgl++); ?>"/>		
					<td><?php echo $time; ?></td>
					<td><?php echo $absent; ?></td>
					</form>	
				</tr>
				<?php
					}
				?>
			</tbody>
		</table>
</div>	
	<?php } ?>	
</section>		