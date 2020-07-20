<header class="header bg-light dker bg-gradient">
  <p>Laporan Utang Piutang</p>
</header>
<section class="scrollable wrapper">
<div class="content table-responsive table-full-width">
	<table class="table table-striped m-b-none" style="width:100%">	
		<thead style="background: #35A9DB;">
			<th style="color: #fff;">Supplier</th>
			<th style="color: #fff;">Total Hutang</th>
		</thead>	
		<tbody>
			<?php 
				$semua_hutang = array();
				$supplier = $this->db->get('supplier')->result_array();
				foreach($supplier as $s){
				$this->db->where('supplier',$s['id']);
				$this->db->where('status',0);
				$this->db->where('tipe',1);
				$data = $this->db->get('stok_masuk')->result_array();
				
				$total_nominal = array();
				$total_bayar = array();	
				
				foreach($data as $d){
					$this->db->where('id_stok_masuk',$d['id']);
					$pelunasan = $this->db->get('pelunasan')->result_array();
					foreach($pelunasan as $p){
						$total_bayar[] = $p['bayar'];
					}
					
					$bayar = $d['bayar'];	
					$total_bayar[] = $bayar;
					$this->db->where('id_stok_masuk',$d['id']);	
					$total_item = $this->db->get('sub_stok_masuk')->result_array();
					
					foreach($total_item as $t){
						$total_nominal[] = $t['harga'] * $t['unit'];
					}
				}
				$total_nominal = array_sum($total_nominal);
				$total_bayar = array_sum($total_bayar);
				
				$total_hutang = $total_nominal - $total_bayar;  
				$semua_hutang[] = $total_hutang;
	
			?>
			<tr>
				<td><a href="<?php echo base_url().'index.php/welcome/pembelian_stok/0^0^0^^0^^'.$s['id']; ?>"><?php echo $s['supplier']; ?></a></td>
				<td style="text-align:right"><?php echo $this->mymodel->format($total_hutang); ?></td>
			</tr>
			<?php 
				}
			?>
			<tr>
				<td style="background-color:#F5DEB3;"><b>TOTAL</b></td>
				<td style="text-align:right; background-color:#F5DEB3;"><b><?php echo $this->mymodel->format(array_sum($semua_hutang)); ?></b></td>
			</tr>
		</tbody>
	</table>
</div>
<br>

</section>	