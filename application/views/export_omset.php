<!DOCTYPE html>
<html>
	<head>
	<style type="text/css">
		body {
			font-family: "Lucida Sans Unicode", "Lucida Grande", "Segoe Ui";
		}
		
		/* Table */
		.demo-table {
			border-collapse: collapse;
			font-size: 13px;
		}
		.demo-table th, 
		.demo-table td {
			border: 1px solid #e1edff;
			padding: 7px 17px;
		}
		.demo-table .title {
			caption-side: bottom;
			margin-top: 12px;
		}
		
		/* Table Header */
		.demo-table thead th {
			background-color: #508abb;
			color: #FFFFFF;
			border-color: #6ea1cc !important;
			text-transform: uppercase;
		}
		
		/* Table Body */
		.demo-table tbody td {
			color: #353535;
		}
		.demo-table tbody td:first-child,
		.demo-table tbody td:last-child,
		.demo-table tbody td:nth-child(4) {
			text-align: right;
		}
		.demo-table tbody tr:nth-child(odd) td {
			background-color: #f4fbff;
		}
		.demo-table tbody tr:hover td {
			background-color: #ffffa2;
			border-color: #ffff0f;
			transition: all .2s;
		}
		
		/* Table Footer */
		.demo-table tfoot th {
			background-color: #e5f5ff;
		}
		.demo-table tfoot th:first-child {
			text-align: left;
		}
		.demo-table tbody td:empty
		{
			background-color: #ffcccc;
		}
		</style>
	</head>
	<body>
		<table class="demo-table">
			<thead>
				<tr>
					<th>No</th>
					<th>Tanggal</th>
					<?php
						$this->db->where('hakakses',1); 
						$cabang =$this->db->get('user')->result_array();
						foreach($cabang as $c){
					?>
					<th><?=$c['username']?></th>
					<?php } ?>
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
			</tbody>
			<tfoot>
				<tr>
					<th>Total</th>
					<th></th>
					<?php foreach($cabang as $c){ ?>
					<th><?=$this->mymodel->format($full_total[$c['id']]);?></th>
					<?php } ?>
				</tr>
			</tfoot>
		</table>
	</body>
</html>