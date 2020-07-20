<!DOCTYPE html>
<html>
	<head></head>
	<body>
		<table>
			<thead>
				<tr>
					<th>No</th>
					<th>Tanggal</th>
					<th>Nama</th>
					<th>Alamat</th>
					<th>Cabang</th>
					<th>Bayar</th>
				</tr>
			</thead>
			<tbody>
					<?php 
						$x = 1;
						$date = strtotime(date('Y-m-d'));
						$last_month = date('t', $date);
						for($y=1; $y<=$last_month; $y++){
					?>
					<tr>
						<td><?=$x++?></td>
						<td><?=$y.date('-m-Y')?></td>
						<?php 
							$full_total = array();
							foreach($cabang as $c){ 
						?>
						<td>
							<?php 
								$this->db->select('bayar');
								$this->db->where('id_cabang',$c['id']);
								$this->db->where('YEAR(tgl_masuk)',date('Y', $date));
								$this->db->where('MONTH(tgl_masuk)',date('m', $date));
								$this->db->where('DAYOFMONTH(tgl_masuk)',$y);
								$order = $this->db->get('pesan')->result_array();
								$total = array();
								foreach($order as $o){
									if($o != ","){
										$nominal = explode(",",$o['bayar']);
										$total[] = $nominal[1];
									}
								}
								$total_sub = array_sum($total);
								echo $this->mymodel->format($total_sub);
							?>
						</td>
							<?php 
								$full_total[$c['id']] = $total_sub;
							}?>
					</tr>
					<?php 
				}
				?>
				<tr>
					<td>Total</td>
					<td></td>
					<?php foreach($cabang as $c){ ?>
					<td><?=$this->mymodel->format($full_total[$c['id']]);?></td>
					<?php } ?>
				</tr>
			</tbody>
		</table>
	</body>
</html>