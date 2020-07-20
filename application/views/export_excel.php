<header class="header bg-light dker bg-gradient">
  <p>Export Excel</p>
</header>
<section class="scrollable wrapper">

<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">
		<thead>
			<form method="POST" action="<?php echo base_url().'index.php/welcome/export' ?>">
			<th>Cabang : 
			<?php if($check_admin || $check_marketing) { ?>	
			<select name="cabang">
						<option>SEMUA</option>
						<?php
						$this->db->where('hakakses !=', 2);
						$this->db->where('hakakses !=', 0);
						$this->db->where('hakakses !=', 3);
						$user = $this->db->get('user')->result_array();
						foreach($user as $c){ 
						?>
						<option value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
						<?php } ?>
			</select>
			<?php 
				} else {
					echo '<input type="text" value="'.$sub_cabang[0]['username'].'" disabled/>';
					echo '<input type="hidden" name="cabang" value="'.$sub_cabang[0]['id'].'" />';
				}	
			?>	
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
				$year = date('Y');
				$month = date('m');
				$month_array = array("Month","January","February","March","April","May","June","July","August","September","October","November","December");
				$count_month=count($month_array);
				for($c=1; $c<$count_month; $c+=1){ ?>
					<option <?php if($c == $month) { ?> selected="selected" <?php } ?>  value=<?php echo $c; ?> > <?php echo $month_array[$c] ?></option>";
				<?php
				}
				?>
			</select>
			</th>	
			
			<th>Tahun : 
			<select name="year">
				<?php
				for($i=2019; $i<=2040; $i++){ ?>
					<option <?php if($i == $year) { ?> selected="selected" <?php } ?>  value=<?php echo $i; ?> > <?php echo $i ?></option>";
				<?php }	?>
			</select>
			</th>	
				
			<th><button type="submit" class="btn btn-primary btn-fill btn-wd"><i class="fa fa-calendar"></i> Export</button></th>
			</form>	
		</thead>
	</table>
</div>
</section>	