<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Mymodel extends CI_Model
{

	public function getItem($item)
	{
		$data = $this->db->query('select * from '.$item.'');
		return $data->result_array();
	}

	public function getIduser(){
		$username = $this->session->userdata('username');
		$this->db->where('username',$username);
		$get = $this->db->get('user')->result_array();
		return $get[0]['id'];
	}
	
	public function getIdCabang(){
		$username = $this->session->userdata('username');
		$this->db->where('username',$username);
		$get = $this->db->get('user')->result_array();
		return $get[0]['cabang'];
	}
	
	
	public function getId($tipe){
		$username = $this->session->userdata('username');
		$this->db->where('username',$username);
		$get = $this->db->get('user')->result_array();
		$this->db->where('tipe',$tipe);
		$this->db->where('status',0);
		$this->db->where('cabang',$get[0]['id']);
		$get = $this->db->get('total_transaksi');
		return $get->result_array();
	}
	
	public function getPaymentAvg($session,$timestamp){
		$this->db->where('cashier_name',$session);
		$this->db->where('status',1);
		$this->db->where('YEAR(time)',date("Y", $timestamp));
		$this->db->where('MONTH(time)',date("m", $timestamp));
		$this->db->where('DAYOFMONTH(time)',date("d", $timestamp));
		$this->db->order_by('id','desc');
		$data_result = $this->db->get('payment_averange');
		return $data_result;
	}
	
	function ubah($data, $id){
        $this->db->where('id',$id);
        $this->db->update('pesan', $data);
        return TRUE;
    }
	
	public function getSession($username){
		$this->db->where('username',$username);
		$get = $this->db->get('user')->result_array();
		$get = $get[0];
		return $get;
	}
	
	public function isAdmin($username){
		$this->db->where('username',$username);
		$this->db->where('hakakses',0);
		$get = $this->db->get('user')->num_rows();
		return $get;
	}
	
	public function isMarketing($username){
		$this->db->where('username',$username);
		$this->db->where('hakakses',3);
		$get = $this->db->get('user')->result_array();
		return $get;
	}
	
	public function isCabang($username){
		$this->db->where('username',$username);
		$this->db->where('hakakses',1);
		$get = $this->db->get('user')->result_array();
		return $get;
	}
	
	public function insertItem($table,$item)
	{
		$ins = $this->db->insert($table,$item);
		return $ins;
	}
	
	public function updateItem($table,$data,$where)
	{
		$ins = $this->db->update($table,$data,$where);
		return $ins;
	}
	
	public function deleteItem($table,$where)
	{
		$del = $this->db->delete($table,$where);
		return $del;
	}
	
	public function getCode($code)
	{
		$this->db->where('code',$code);
		return $this->db->get('item')->result_array();
	}
	
	public function check($table,$field,$item)
	{
		return $this->db->get_where($table,[$field => $item]);
	}
	
	public function checkStok($id_item,$status)
	{
		$this->db->where('id_item',$id_item);
		$this->db->where('status',$status);
		return $this->db->get('proses_stok')->result_array();
	}
	
	public function format($numbers){
		$numbers = number_format($numbers);	
		$numbers = str_replace(',', '.', $numbers);	
		return $numbers;
	 }
	
	public function currency(){	
		$currency = 'Rp';
		return $currency;
	 }
	
	public function save($id){
		$pass = password_hash($this->input->post('new_password'), PASSWORD_DEFAULT);
		$data = array (
			'password' => $pass
		);
		$this->db->where('id',$id);
		$this->db->update('user', $data);
	}
	
	public function month($number){
		$month = array("January","February","March","April","May","June","July","August","September","October","November","December");
		return $month[$number];
	}
	
	public function encrypt($str) {
	$hasil = '';	
    $kunci = '979a218e0632df2935317f98d47956c7';
    for ($i = 0; $i < strlen($str); $i++) {
        $karakter = substr($str, $i, 1);
        $kuncikarakter = substr($kunci, ($i % strlen($kunci))-1, 1);
        $karakter = chr(ord($karakter)+ord($kuncikarakter));
        $hasil .= $karakter;
        
    }
    return urlencode(base64_encode($hasil));
	}

	public function decrypt($str) {
		$str = base64_decode(urldecode($str));
		$hasil = '';
		$kunci = '979a218e0632df2935317f98d47956c7';
		for ($i = 0; $i < strlen($str); $i++) {
			$karakter = substr($str, $i, 1);
			$kuncikarakter = substr($kunci, ($i % strlen($kunci))-1, 1);
			$karakter = chr(ord($karakter)-ord($kuncikarakter));
			$hasil .= $karakter;

		}
		return $hasil;
	}
	
	public function namaCabang($id){
		$this->db->where('id', $id);
		$user = $this->db->get('user')->result_array();
		return $user[0]['username'];
	}
	
	public function namaKategori($id){
		$this->db->where('id', $id);
		$kategori = $this->db->get('kategori_item')->result_array();
		return $kategori[0]['kategori'];
	}
	
	public function namaSupplier($id){
		$this->db->where('id', $id);
		$user = $this->db->get('supplier')->result_array();
		return $user[0]['supplier'];
	}
	
	public function total_kulak($timestamp){
			$total_sub = array();
			$this->db->where('YEAR(waktu)',date('Y',$timestamp));
			$this->db->where('MONTH(waktu)',date('m',$timestamp));
			$this->db->where('tipe',1);
			$this->db->where('supplier !=',0);
			$this->db->where('status',1);
			$data = $this->db->get('stok_masuk')->result_array();
			foreach($data as $d){
				$id = $d['id'];
				$this->db->where('id_stok_masuk',$id);
				$sub_stok_masuk = $this->db->get('sub_stok_masuk')->result_array();
				foreach($sub_stok_masuk as $stm){
					$unit = $stm['unit'];
					$harga = $stm['harga'];
					$total_sub[] = $unit * $harga;
				}
			}
			return array_sum($total_sub);
	}
	
	public function total_nonkulak($timestamp){
			$total_sub = array();
			$this->db->where('YEAR(waktu)',date('Y',$timestamp));
			$this->db->where('MONTH(waktu)',date('m',$timestamp));
			$this->db->where('tipe',1);
			$this->db->where('supplier',0);
			$data = $this->db->get('stok_masuk')->result_array();
			foreach($data as $d){
				$id = $d['id'];
				$this->db->where('id_stok_masuk',$id);
				$sub_stok_masuk = $this->db->get('sub_stok_masuk')->result_array();
				foreach($sub_stok_masuk as $stm){
					$unit = $stm['unit'];
					$harga = $stm['harga'];
					$total_sub[] = $unit * $harga;
				}
			}
			return array_sum($total_sub);
	}
	
	public function total_kulak_terkini($timestamp){
			$total_sub = array();
			
			$first_date = strtotime(date('Y-m-01',$timestamp));
			$this->db->where('waktu >=', date('Y-m-d', $first_date));
			$this->db->where('waktu <=', date('Y-m-d',$timestamp));
			$this->db->where('tipe',1);
			$this->db->where('status',1);
			$this->db->where('supplier !=',0);
			$data = $this->db->get('stok_masuk')->result_array();
			foreach($data as $d){
				$id = $d['id'];
				$this->db->where('id_stok_masuk',$id);
				$sub_stok_masuk = $this->db->get('sub_stok_masuk')->result_array();
				foreach($sub_stok_masuk as $stm){
					$unit = $stm['unit'];
					$harga = $stm['harga'];
					$total_sub[] = $unit * $harga;
				}
			}
			return array_sum($total_sub);
	}
	
	public function total_nonkulak_terkini($timestamp){
			$total_sub = array();
			
			$first_date = strtotime(date('Y-m-01',$timestamp));
			$this->db->where('waktu >=', date('Y-m-d', $first_date));
			$this->db->where('waktu <=', date('Y-m-d',$timestamp));
			$this->db->where('tipe',1);
			$this->db->where('status',1);
			$this->db->where('supplier',0);
			$data = $this->db->get('stok_masuk')->result_array();
			foreach($data as $d){
				$id = $d['id'];
				$this->db->where('id_stok_masuk',$id);
				$sub_stok_masuk = $this->db->get('sub_stok_masuk')->result_array();
				foreach($sub_stok_masuk as $stm){
					$unit = $stm['unit'];
					$harga = $stm['harga'];
					$total_sub[] = $unit * $harga;
				}
			}
			return array_sum($total_sub);
	}
	
	public function total_kulak_hariini($timestamp){
			$total_sub = array();
			$this->db->where('YEAR(waktu)',date('Y',$timestamp));
			$this->db->where('MONTH(waktu)',date('m',$timestamp));
			$this->db->where('DAYOFMONTH(waktu)',date('d',$timestamp));
			$this->db->where('tipe',1);
			$this->db->where('status',1);
			$this->db->where('supplier !=',0);
			$data = $this->db->get('stok_masuk')->result_array();
			foreach($data as $d){
				$id = $d['id'];
				$this->db->where('id_stok_masuk',$id);
				$sub_stok_masuk = $this->db->get('sub_stok_masuk')->result_array();
				foreach($sub_stok_masuk as $stm){
					$unit = $stm['unit'];
					$harga = $stm['harga'];
					$total_sub[] = $unit * $harga;
				}
			}
			return array_sum($total_sub);
	}
	
	public function total_nonkulak_hariini($timestamp){
			$total_sub = array();
			$this->db->where('YEAR(waktu)',date('Y',$timestamp));
			$this->db->where('MONTH(waktu)',date('m',$timestamp));
			$this->db->where('DAYOFMONTH(waktu)',date('d',$timestamp));
			$this->db->where('tipe',1);
			$this->db->where('status',1);
			$this->db->where('supplier',0);
			$data = $this->db->get('stok_masuk')->result_array();
			foreach($data as $d){
				$id = $d['id'];
				$this->db->where('id_stok_masuk',$id);
				$sub_stok_masuk = $this->db->get('sub_stok_masuk')->result_array();
				foreach($sub_stok_masuk as $stm){
					$unit = $stm['unit'];
					$harga = $stm['harga'];
					$total_sub[] = $unit * $harga;
				}
			}
			return array_sum($total_sub);
	}
	
	public function total_omest($id,$timestamp){
		$last_month = date('t', $timestamp);
		$total_bayar = array();
		$total_omset = array();
		$y = 1;
		$t = 1;
		for($x = 1; $x<=$last_month; $x++){
			$this->db->where('YEAR(tgl_masuk)',date('Y',$timestamp));
			$this->db->where('MONTH(tgl_masuk)',date('m',$timestamp));
			$this->db->where('DAYOFMONTH(tgl_masuk)',$y++);
			$this->db->where('id_cabang',$id);
			$data_raws = $this->db->get('pesan')->result_array();
			$array_bayar = array();
			if(empty($data_raws)){
				$bayar = 0;
				$total_bayar[$t++] = $bayar;
			} else {
				foreach($data_raws as $r){
							$bayar = $r['bayar'];
							if(!empty($bayar)){
								$nominal = explode(",",$bayar);
								$bayar = (int)$nominal[1];
							} else {
								$bayar = 0;
							}
					$array_bayar[] = $bayar;
				}
				$total_bayar[$t++] = $array_bayar;
			}
		}
		return $total_bayar;
	
	}
	
	public function total_omest_hari($id,$timestamp){
		$last_month = date('d', $timestamp);
		$total_bayar = array();
		$total_omset = array();
		$y = 1;
		$t = 1;
		for($x = 1; $x<=$last_month; $x++){
			$this->db->where('YEAR(tgl_masuk)',date('Y',$timestamp));
			$this->db->where('MONTH(tgl_masuk)',date('m',$timestamp));
			$this->db->where('DAYOFMONTH(tgl_masuk)',$y++);
			$this->db->where('id_cabang',$id);
			$data_raws = $this->db->get('pesan')->result_array();
			$array_bayar = array();
			if(empty($data_raws)){
				$bayar = 0;
				$total_bayar[$t++] = $bayar;
			} else {
				foreach($data_raws as $r){
							$bayar = $r['bayar'];
							if(!empty($bayar)){
								$nominal = explode(",",$bayar);
								$bayar = (int)$nominal[1];
							} else {
								$bayar = 0;
							}
					$array_bayar[] = $bayar;
				}
				$total_bayar[$t++] = $array_bayar;
			}
		}
		return $total_bayar;
	
	}
	
	public function omset_hari_ini($id,$timestamp){
		$total_bayar = array();
		$total_omset = array();
		$t = 1;
			$this->db->where('YEAR(tgl_masuk)',date('Y',$timestamp));
			$this->db->where('MONTH(tgl_masuk)',date('m',$timestamp));
			$this->db->where('DAYOFMONTH(tgl_masuk)',date('d',$timestamp));
			$this->db->where('id_cabang',$id);
			$data_raws = $this->db->get('pesan')->result_array();
			$array_bayar = array();
			if(empty($data_raws)){
				$bayar = 0;
				$total_bayar[$t++] = $bayar;
			} else {
				foreach($data_raws as $r){
							$bayar = $r['bayar'];
							if(!empty($bayar)){
								$nominal = explode(",",$bayar);
								$bayar = (int)$nominal[1];
							} else {
								$bayar = 0;
							}
					$array_bayar[] = $bayar;
				}
				$total_bayar[$t++] = $array_bayar;
			}
		return $total_bayar;
	
	}
	
	public function total_kas($id,$timestamp){
		$saldo_array = array();
			$first_date = strtotime('2019-01-01');
			$second_date = $timestamp;
			
			if(date('Y',$timestamp) == date('Y') && date('m',$timestamp) == date('m')){
				$first_date = mktime(0, 0, 0, date('m',$timestamp), 1, date('Y',$timestamp));
				$this->db->where('YEAR(waktu)',date('Y'));
				$this->db->where('MONTH(waktu)',date('m'));
				$this->db->where('id_cabang',$id);
				$cek = $this->db->get('total_kas')->result_array();
				if(!empty($cek)){
					$saldo_array[] = $cek[0]['saldo'];
				} else {
					$saldo_array[] = 0;
				}	
			}
			
			$this->db->where('id_cabang', $id);
			$this->db->where('waktu >=', date('Y-m-d', $first_date));
			$this->db->where('waktu <=', date('Y-m-d',$second_date));
			$saldo = $this->db->get('kas')->result_array();
			foreach($saldo as $s){
						if($s['status'] == 2){
							$saldo_array[] = $s['saldo'];
						} else {
							$saldo_array[] = $s['saldo'] * -1;
						}
					}	   
			$total_saldo = array_sum($saldo_array);
			return $total_saldo;
	}
	
	public function total_stok_cabang($id){
		$this->db->where('id_cabang', $id);	
		$stok_awal = $this->db->get('stok')->result_array();
		$total_stok_awal = array();
		foreach($stok_awal as $sa){
		   if(!empty($sa['stok'])){  
			$total_stok_awal[] = $sa['stok'];
		   }	
		}
		$total_stok_awal = array_sum($total_stok_awal);
		
		$this->db->where('id_cabang', $id);	
		$stok_masuk = $this->db->get('sub_stok_masuk')->result_array();
		$total_stok_masuk = array();
		foreach($stok_masuk as $sm){
		   if(!empty($sm['stok'])){ 
			    $total_stok_masuk[] = $sm['unit'];
		   }
		}
		$total_stok_masuk = array_sum($total_stok_masuk);
		
		$this->db->where('id_cabang', $id);	
		$stok_keluar = $this->db->get('sub_stok_keluar')->result_array();
		$total_stok_keluar = array();
		foreach($stok_keluar as $sk){
			$total_stok_keluar[] = $sk['unit'];
		}
		$total_stok_keluar = array_sum($total_stok_keluar);
		
		
		$this->db->where('id_cabang', $id);	
		$this->db->where('status', 5);	
		$this->db->where('id_pesan!=', 0);	
		$penjualan = $this->db->get('pesan')->result_array();
		if($penjualan){
			$stok_penjualan = array();
			foreach ($penjualan as $p){
				$id_total_transaksi = $p['id'];
				$this->db->where('id_total_transaksi', $id_total_transaksi);
				$sub_transaksi = $this->db->get('sub_transaksi')->result_array();

				if(!empty($sub_transaksi)){
					$stok_penjualan[] = (int)$sub_transaksi[0]['unit'];
				}	
			}	
				$total_stok_penjualan = array_sum($stok_penjualan);
		}	
		
		$mutasi_keluar = array();
		$this->db->where('id_from', $id);
		$stok_mutasi = $this->db->get('mutasi')->result_array();
		foreach($stok_mutasi as $m){
			$id_mutasi = $m['id'];
			$this->db->where('id_mutasi', $id_mutasi);
			$sub_stok_mutasi = $this->db->get('sub_mutasi')->result_array();
			foreach($sub_stok_mutasi as $s_m){
				$s_m_unit = $s_m['unit'];
				$mutasi_keluar[] = $s_m_unit; 
			}
		}
		$mutasi_keluar = array_sum($mutasi_keluar);

		$mutasi_masuk = array();
		$this->db->where('id_to', $id);
		$stok_mutasi = $this->db->get('mutasi')->result_array();
		foreach($stok_mutasi as $m){
			$id_mutasi = $m['id'];
			$this->db->where('id_mutasi', $id_mutasi);
			$sub_stok_mutasi = $this->db->get('sub_mutasi')->result_array();
			foreach($sub_stok_mutasi as $s_m){
				$s_m_unit = $s_m['unit'];
				$mutasi_masuk[] = $s_m_unit; 
			}
		}
		$mutasi_masuk = array_sum($mutasi_masuk);
		
		if(empty($total_stok_masuk)){
			$total_stok_masuk = 0;
		}
		if(empty($total_stok_awal)){
			$total_stok_awal = 0;
		}

		if(empty($total_stok_keluar)){
			$total_stok_keluar = 0;
		}

		if(empty($total_stok_penjualan)){
			$total_stok_penjualan = 0;
		}
		if(empty($stok_laporan)){
			$stok_laporan = 0;
		}

		if(empty($mutasi_keluar)){
			$mutasi_keluar = 0;
		}

		if(empty($mutasi_masuk)){
			$mutasi_masuk = 0;
		}

		$total = (($total_stok_awal + $total_stok_masuk + $mutasi_masuk) - ($total_stok_keluar + $total_stok_penjualan + $mutasi_keluar));
		return $total;
	}
	
	public function total_harga_cabang($id){
		$total = array();
		$item = $this->db->get('item')->result_array();
		foreach($item as $i){
			$this->db->where('id_item', $i['id']);
			$this->db->where('id_cabang', $id);	
			$stok_awal = $this->db->get('stok')->result_array();
			$total_stok_awal = array();
			foreach($stok_awal as $sa){
			    if(!empty($sa['stok'])){
			    	$total_stok_awal[] = $sa['stok'];
			    }	
			}
			$total_stok_awal = array_sum($total_stok_awal);
			
			$this->db->where('id_item', $i['id']);
			$this->db->where('id_cabang', $id);	
			$stok_masuk = $this->db->get('sub_stok_masuk')->result_array();
			$total_stok_masuk = array();
			foreach($stok_masuk as $sm){
			   if(!empty($sm['stok'])){  
				    $total_stok_masuk[] = $sm['unit'];
			   }    
			}
			$total_stok_masuk = array_sum($total_stok_masuk);

			$this->db->where('id_item', $i['id']);
			$this->db->where('id_cabang', $id);	
			$stok_keluar = $this->db->get('sub_stok_keluar')->result_array();
			$total_stok_keluar = array();
			foreach($stok_keluar as $sk){
				if(!empty($sk['stok'])){
					$total_stok_keluar[] = $sk['unit'];
				}	
			}
			$total_stok_keluar = array_sum($total_stok_keluar);

			$this->db->where('id_pesan !=',0);	
			$this->db->where('id_cabang', $id);	
			$this->db->where('status', 0);	
			$penjualan = $this->db->get('pesan')->result_array();
			if($penjualan){
				$stok_penjualan = array();
				foreach ($penjualan as $p){
					$this->db->where('id_item', $i['id']);
					$id_total_transaksi = $p['id'];
					$this->db->where('id_total_transaksi', $id_total_transaksi);
					$sub_transaksi = $this->db->get('sub_transaksi')->result_array();

					if(!empty($sub_transaksi)){
						$stok_penjualan[] = (int)$sub_transaksi[0]['unit'];
					}	
				}	
					$total_stok_penjualan = array_sum($stok_penjualan);
			}	

			$mutasi_keluar = array();
			$this->db->where('id_from', $id);
			$stok_mutasi = $this->db->get('mutasi')->result_array();
			foreach($stok_mutasi as $m){
				$id_mutasi = $m['id'];
				$this->db->where('id_item', $i['id']);
				$this->db->where('id_mutasi', $id_mutasi);
				$sub_stok_mutasi = $this->db->get('sub_mutasi')->result_array();
				foreach($sub_stok_mutasi as $s_m){
					$s_m_unit = $s_m['unit'];
					$mutasi_keluar[] = $s_m_unit; 
				}
			}
			$mutasi_keluar = array_sum($mutasi_keluar);

			$mutasi_masuk = array();
			$this->db->where('id_to', $id);
			$stok_mutasi = $this->db->get('mutasi')->result_array();
			foreach($stok_mutasi as $m){
				$id_mutasi = $m['id'];
				$this->db->where('id_item', $i['id']);
				$this->db->where('id_mutasi', $id_mutasi);
				$sub_stok_mutasi = $this->db->get('sub_mutasi')->result_array();
				foreach($sub_stok_mutasi as $s_m){
					$s_m_unit = $s_m['unit'];
					$mutasi_masuk[] = $s_m_unit; 
				}
			}
			
			$this->db->where('id_item',$i['id']);
			$harga_item = $this->db->get('harga_item')->result_array();
			if(!empty($harga_item)){
				$harga_per_item = $harga_item[0]['harga_pokok'];
			} else {
				$harga_per_item = 0;
			}	
			$mutasi_masuk = array_sum($mutasi_masuk);
			
				if(empty($total_stok_masuk)){
				$total_stok_masuk = 0;
			}
			if(empty($total_stok_awal)){
				$total_stok_awal = 0;
			}

			if(empty($total_stok_keluar)){
				$total_stok_keluar = 0;
			}

			if(empty($total_stok_penjualan)){
				$total_stok_penjualan = 0;
			}
			if(empty($stok_laporan)){
				$stok_laporan = 0;
			}

			if(empty($mutasi_keluar)){
				$mutasi_keluar = 0;
			}

			if(empty($mutasi_masuk)){
				$mutasi_masuk = 0;
			}

			$total[] = (($total_stok_awal + $total_stok_masuk + $mutasi_masuk) - ($total_stok_keluar + $total_stok_penjualan + $mutasi_keluar)) * $harga_per_item;	
		}	
		$total = array_sum($total);
		return $total;
	}
}