<div class="text-center">
	<h4>MENU</h4>
</div>
<?php 
$category = array();	
$data = $this->mymodel->getItem('item');
foreach ($data as $d){
	$category[] = $d['kategori'];
}

foreach($category as $c){
	$this->db->where('kategori', $c);
	$this->db->where('id', $d['id_item']);
	$foods = $this->db->get('item')->result_array();
?>
		<?php
		echo '</br>';
		echo '<b><span class="ti-angle-double-right"></span>'.$c.'</b>';
		foreach ($foods as $f){
			echo '</br>';
			$code = $f['code'];
			$link = base_url().'index.php/welcome/insertSubpayment2/'.$id.'/'.$code;
			echo '<p style="left:0; position: absolute; font-size: 12px;"><a href="'.$link.'">'.$f['nama'].'('.$code.')</a></p>';
			echo '<p style="right:0; position: absolute; font-size: 12px;">'.$this->mymodel->format($f['harga_jual']).'</p>';
			}
		}
?>