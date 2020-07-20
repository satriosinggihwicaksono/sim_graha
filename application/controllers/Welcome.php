<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('pdf');
		$this->load->helper('form');
		$this->load->library('javascript');
		$this->load->library('pagination');
		$this->load->library('user_agent');
		$this->load->helper('url');
    	$this->load->library('form_validation');
		if(!$this->session->logged_in && $this->uri->segment(2) != 'export_laporan'){
			redirect('auth');
		} elseif($this->uri->segment(2) == 'export_laporan'){
			return true;
		}
	}
	
	public function index()
	{
		$this->load->view('index');
	}
	
	public function delete($table,$id)
	{	
		$del = $this->mymodel->deleteItem($table,array('id' => $id));
		if($del >= 1){
			$this->session->set_flashdata('message','<b>Notification</b> data telah dihapus');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat dihapus');
			redirect($this->agent->referrer());
		}
	}
	
	public function delete_bukutamu($table,$id)
	{	
		$del = $this->mymodel->deleteItem($table,array('id' => $id));
		$this->db->where('id_bukutamu',$id);
		$data_result = $this->db->get('pesan')->num_rows();
		foreach($data_result as $d){
			$del = $this->mymodel->deleteItem('proses_pesan',array('id_pesan' => $d['id']));
		}
		$del = $this->mymodel->deleteItem('pesan',array('id_bukutamu' => $id));
		if($del >= 1){
			$this->session->set_flashdata('message','<b>Notification</b> data telah dihapus');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat dihapus');
			redirect($this->agent->referrer());
		}
	}
	
	public function deleteItem($table,$id)
	{	
			$delete = $this->mymodel->deleteItem('harga_item',array('id_item' => $id));
		if($delete >= 1){
			$del = $this->mymodel->deleteItem($table,array('id' => $id));
			$this->session->set_flashdata('message','<b>Notification</b> data telah dihapus');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat dihapus');
			redirect($this->agent->referrer());
		}
	}
	
	public function deletePesan($table,$id)
	{	
			$delete = $this->mymodel->deleteItem($table,array('id' => $id));
		if($delete >= 1){
			$del = $this->mymodel->deleteItem('proses_pesan',array('id_pesan' => $id));
			$del = $this->mymodel->deleteItem('kas',array('id_trans' => $id));
			$this->session->set_flashdata('message','<b>Notification</b> data telah dihapus');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat dihapus');
			redirect($this->agent->referrer());
		}
	}
	
	public function deleteItemMasuk($table,$id)
	{	
			$delete = $this->mymodel->deleteItem($table,array('id' => $id));
		if($delete >= 1){
			$del = $this->mymodel->deleteItem('sub_stok_masuk',array('id_stok_masuk' => $id));
			$this->session->set_flashdata('message','<b>Notification</b> data telah dihapus');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat dihapus');
			redirect($this->agent->referrer());
		}
	}
	
	public function deleteItemKeluar($table,$id)
	{	
			$delete = $this->mymodel->deleteItem($table,array('id' => $id));
		if($delete >= 1){
			$del = $this->mymodel->deleteItem('sub_stok_keluar',array('id_stok_keluar' => $id));
			$this->session->set_flashdata('message','<b>Notification</b> data telah dihapus');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat dihapus');
			redirect($this->agent->referrer());
		}
	}
	
	public function deleteItemMutasi($table,$id)
	{	
			$delete = $this->mymodel->deleteItem($table,array('id' => $id));
		if($delete >= 1){
			$del = $this->mymodel->deleteItem('sub_mutasi',array('id_mutasi' => $id));
			$this->session->set_flashdata('message','<b>Notification</b> data telah dihapus');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat dihapus');
			redirect($this->agent->referrer());
		}
	}
	
	public function deleteProsesitem($table,$id)
	{		
		
		$this->db->where('id',$id); 
		$status_order = $this->db->get('proses_pesan')->result_array();
		$id_pesan = $status_order[0]['id_pesan'];
		$delete = $this->mymodel->deleteItem($table,array('id' => $id));
		$this->db->order_by('id', 'desc'); 
		$this->db->where('id_pesan',$id_pesan); 
		$status_order = $this->db->get('proses_pesan')->result_array();
		if(empty($status_order)){
			$id_proses_pesan = 0;
		} else {
			$id_proses_pesan = $status_order[0]['status'];
		}
		if($delete >= 1){
			$data = array(
				'status' => $id_proses_pesan,
			);
			$update = $this->mymodel->updateItem('pesan',$data,array('id' => $id_pesan));
			$this->session->set_flashdata('message','<b>Notification</b> data telah dihapus');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat dihapus');
			redirect($this->agent->referrer());
		}
	}
	
	public function bukutamu()
	{	
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIdCabang($username);
		$check_admin = $this->mymodel->isAdmin($username);
		$check_marketing = $this->mymodel->isMarketing($username);
	
		$link = $this->uri->segment(3);
		$posisi = explode("%5E",$link);
		$posisi = count($posisi);
		if($posisi > 1){
			$t = explode("%5E",$link);
			$nama = str_replace("%20"," ",$t[0]);
			$timestamp = $t[1];
			$telepon = $t[2];
			$cabang = (int)$t[3];
			$month = (int)$t[4];
			$year = (int)$t[5];
			$tipe = (int)$t[6];
		}	

		if(!empty($nama)) $this->db->like('nama',$nama);
		if(!empty($telepon)) $this->db->where('telepon',$telepon);
		
		if(!empty($cabang)){ 
			$this->db->where('cabang',$cabang);
		} 
		
		if(!$check_admin && !$check_marketing) {
			$this->db->where('cabang',$id_username);
		}
		if(!empty($tipe)) {
			$this->db->where('tipe',$tipe);
		}
		
		$this->db->order_by("id", "desc");
		
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		
		if(!empty($timestamp)){
			$this->db->where('YEAR(waktu)',date("Y", $timestamp));
			$this->db->where('MONTH(waktu)',date("m", $timestamp));
			$this->db->where('DAYOFMONTH(waktu)',date("d", $timestamp));
		}
		$data_result = $this->db->get('bukutamu')->num_rows();
		
		if($posisi > 1){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/bukutamu/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/bukutamu/';
		}	
		
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		$this->db->order_by("id", "desc");
		
		if(!empty($year) && $year != 0) $this->db->where('YEAR(waktu)', $year);
		if(!empty($month) && $month != 0) $this->db->where('MONTH(waktu)', $month);
		
		if(!empty($timestamp)){
			$this->db->where('YEAR(waktu)',date("Y", $timestamp));
			$this->db->where('MONTH(waktu)',date("m", $timestamp));
			$this->db->where('DAYOFMONTH(waktu)',date("d", $timestamp));
		}
		
		if(!empty($nama)) $this->db->like('nama',$nama);
		if(!empty($telepon)) $this->db->where('telepon',$telepon);
		
		if(!empty($cabang)){ 
			$this->db->where('cabang',$cabang);
		}
		
		if(!empty($tipe)) {
			$this->db->where('tipe',$tipe);
		}

		if(!$check_admin && !$check_marketing) {
			$this->db->where('cabang',$id_username);
		} 
		$this->db->order_by("waktu", "asc");
		$query = $this->db->get('bukutamu',$config['per_page'],$from)->result();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_bukutamu()
	{	
		$nama = $this->input->post('nama');
		$waktu = strtotime($this->input->post('waktu'));
		$telepon = $this->input->post('telepon');
		$cabang = $this->input->post('cabang');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$tipe = $this->input->post('tipe');
		
		$link = $nama.'^'.$waktu.'^'.$telepon.'^'.$cabang.'^'.(int)$month.'^'.(int)$year.'^'.$tipe;
		redirect('welcome/bukutamu/'.$link);
	}
	
	public function tambah_bukutamu()
	{
		$this->load->view('index');
	}
	
	public function ubah_bukutamu($id)
	{	
		$this->db->where('id',$id);
		$query = $this->db->get('bukutamu')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function tambah_item_masuk($id)
	{	
		$this->db->where('id_stok_masuk',$id);
		$query = $this->db->get('sub_stok_masuk')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function tambah_item_mutasi($id)
	{	
		$this->db->where('id_mutasi',$id);
		$query = $this->db->get('sub_mutasi')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function tambah_pembelian_stok($id)
	{	
		$this->db->where('id_stok_masuk',$id);
		$query = $this->db->get('sub_stok_masuk')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function tambah_item_keluar($id)
	{	
		$this->db->where('id_stok_keluar',$id);
		$query = $this->db->get('sub_stok_keluar')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function penjualan($id)
	{	
		$this->db->where('id_total_transaksi',$id);
		$query = $this->db->get('sub_transaksi')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function ubah_penjualan($id){
		
		
		$data = array(
			'unit' => $this->input->post('unit'),
			'harga_jual' => $this->input->post('harga'),
		);
		
		$ins = $this->mymodel->updateItem('sub_transaksi',$data, array('id' => $id));
		
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>data sudah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>data tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_sub_item_masuk()
	{	
		$code = strtoupper($this->input->post('code'));
		$item = $this->mymodel->getCode($code);
		if(empty($item)){
			$this->session->set_flashdata('message','<b>Notification</b>Code Item tidak ditemukan');
			redirect($this->agent->referrer());
		}
		
		$data = array(
			'id_item' => $item[0]['id'],
			'id_cabang' => $this->input->post('id_cabang'),
			'id_stok_masuk' => $this->input->post('id_stok_masuk'),
			'unit' => $this->input->post('unit'),
			'harga' => $this->input->post('harga'),
		);
		$ins = $this->mymodel->insertItem('sub_stok_masuk',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Sub Stok Masuk sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Sub stok masuk tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_sub_mutasi()
	{	
		$code = strtoupper($this->input->post('code'));
		$item = $this->mymodel->getCode($code);
		if(empty($item)){
			$this->session->set_flashdata('message','<b>Notification</b>Code Item tidak ditemukan');
			redirect($this->agent->referrer());
		}
		
		$data = array(
			'id_item' => $item[0]['id'],
			'id_mutasi' => $this->input->post('id_mutasi'),
			'unit' => $this->input->post('unit'),
		);
		$ins = $this->mymodel->insertItem('sub_mutasi',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Sub Mutasi sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Sub stok Mutasi tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_sub_item_keluar()
	{	
		$code = strtoupper($this->input->post('code'));
		$item = $this->mymodel->getCode($code);
		if(empty($item)){
			$this->session->set_flashdata('message','<b>Notification</b>Code Item tidak ditemukan');
			redirect($this->agent->referrer());
		}
		
		$data = array(
			'id_item' => $item[0]['id'],
			'id_cabang' => $this->input->post('id_cabang'),
			'id_stok_keluar' => $this->input->post('id_stok_keluar'),
			'unit' => $this->input->post('unit'),
		);
		$ins = $this->mymodel->insertItem('sub_stok_keluar',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Sub Stok keluar sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Sub stok keluar tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_sub_stok_masuk($id)
	{	
		$data = array(
			'unit' => $this->input->post('unit'),
		);
		$ins = $this->mymodel->updateItem('sub_stok_masuk',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Sub Stok Masuk sudah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Sub stok masuk tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_sub_stok_mutasi($id)
	{	
		$data = array(
			'unit' => $this->input->post('unit'),
		);
		$ins = $this->mymodel->updateItem('sub_mutasi',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Sub Mutasi sudah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Sub Mutasi tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_pembelian_stok($id)
	{	
		$data = array(
			'harga' => $this->input->post('harga'),
			'unit' => $this->input->post('unit'),
		);
		$ins = $this->mymodel->updateItem('sub_stok_masuk',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Sub Stok Masuk sudah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Sub stok masuk tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function rubah_bukutamu($id){
		$data = array(
			'nama' => $this->input->post('nama'),
			'alamat' => $this->input->post('alamat'),
			'telepon' => $this->input->post('telepon'),
			'tipe' => $this->input->post('tipe'),
			'cabang' => $this->input->post('cabang'),
			'waktu' => $this->input->post('waktu'),
			'pengimput' => $this->input->post('pengimput'),
		);
		$ins = $this->mymodel->updateItem('bukutamu',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> bukutamu telah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> bukutamu tidak dapat dirubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function add_bukutamu()
	{
		$this->form_validation->set_rules('telepon','Telepon','required|is_unique[bukutamu.telepon]');
		if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('message','<b>Notification</b> Nomer HP sudah ada, ganti yang lain');
			redirect($this->agent->referrer());
		}	
		$data = array(
			'telepon' => $this->input->post('telepon'),
			'nama' => $this->input->post('nama'),
			'tipe' => $this->input->post('tipe'),
			'alamat' => $this->input->post('alamat'),
			'cabang' => $this->input->post('cabang'),
			'pengimput' => $this->input->post('pengimput'),
		);
		$ins = $this->mymodel->insertItem('bukutamu',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>bukutamu sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>bukutamu tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function add_itemmasuk()
	{
		
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'cabang' => $this->input->post('cabang'),
			'deskripsi' => $this->input->post('deskripsi'),
		);
		$ins = $this->mymodel->insertItem('stok_masuk',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>bukutamu sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>bukutamu tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function order()
	{	
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$check_admin = $this->mymodel->isAdmin($username);
		$check_marketing = $this->mymodel->isMarketing($username);
		$id_cabang = $this->mymodel->getIdCabang($username);
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$status = (int)$t[1];
			$month = (int)$t[2];
			$year = (int)$t[3];
			$tanggal = (int)$t[4];
			$urutan = (int)$t[5];
			$kondisi = (int)$t[6];
			$nama = $t[7];
			$tipe = $t[8];
		}	
		
		$this->db->select('*');
		$this->db->join('bukutamu','pesan.id_bukutamu = bukutamu.id');
		
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("pesan.tgl_masuk", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("pesan.tgl_masuk", "desc");
			} elseif($urutan == 3){
				$this->db->order_by("pesan.id", "desc");
			}
		}

		if(!empty($tipe)) {
			$this->db->where('bukutamu.tipe',$tipe);
		}

		if(!empty($status) && $status != 0) $this->db->where('status',$status);
		if(!empty($kondisi) && $kondisi != 0) $this->db->where('kondisi',$kondisi);
		
		if(!empty($cabang) && $cabang != 0) {
			$this->db->where('id_cabang',$cabang);
		}
		
		if(!$check_admin && !$check_marketing) { 
			$this->db->where('id_cabang',$id_cabang);
		}	

		if(!empty($tipe)) {
			$this->db->where('tipe',$tipe);
		}
		
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(tgl_masuk)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(tgl_masuk)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(tgl_masuk)',date('Y', $tanggal));
			$this->db->where('MONTH(tgl_masuk)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(tgl_masuk)',date('d', $tanggal));
		}
		
		if(!empty($nama)) $this->db->like('nama',$nama);
		$data_result = $this->db->get('pesan')->num_rows();
		
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/order/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/order/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);	
		
		$this->db->select('bukutamu.tipe,pesan.presentase,pesan.id,pesan.tgl_masuk,pesan.tgl_closing,pesan.nota,pesan.id_cabang,pesan.sum_trans,pesan.status,pesan.id_bukutamu,pesan.seles,pesan.teknisi,pesan.keterangan,pesan.input,pesan.bayar,pesan.id_pesan,pesan.followup,pesan.kondisi,pesan.input,bukutamu.nama,bukutamu.alamat,bukutamu.telepon');
		$this->db->join('bukutamu','pesan.id_bukutamu = bukutamu.id');
		
		if(!empty($tipe)) {
			$this->db->where('bukutamu.tipe',$tipe);
		}
		
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("pesan.tgl_masuk", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("pesan.tgl_masuk", "desc");
			} elseif($urutan == 3){
				$this->db->order_by("pesan.id", "desc");
			}
		}
		
		if(!empty($nama)) $this->db->like('nama',$nama);
		if(!empty($status) && $status != 0) $this->db->where('status',$status);
		if(!empty($kondisi) && $kondisi != 0) $this->db->where('kondisi',$kondisi);
		
		if(!empty($cabang) && $cabang != 0) {
			$this->db->where('id_cabang',$cabang);
		}
		
		if(!$check_admin && !$check_marketing) { 
			$this->db->where('id_cabang',$id_cabang);
		}	
		
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(tgl_masuk)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(tgl_masuk)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(tgl_masuk)',date('Y', $tanggal));
			$this->db->where('MONTH(tgl_masuk)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(tgl_masuk)',date('d', $tanggal));
		}
		
		$query = $this->db->get('pesan',$config['per_page'],$from)->result();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function followup()
	{	
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$check_admin = $this->mymodel->isAdmin($username);
		$check_marketing = $this->mymodel->isMarketing($username);
		$id_cabang = $this->mymodel->getIdCabang($username);
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$status = (int)$t[1];
			$month = (int)$t[2];
			$year = (int)$t[3];
			$urutan = (int)$t[4];
			$kondisi = (int)$t[5];
		}	
		
		$this->db->select('*');
		$this->db->join('bukutamu','pesan.id_bukutamu = bukutamu.id');
		
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("tgl_masuk", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("tgl_masuk", "desc");
			} elseif($urutan == 3){
				$this->db->order_by("id", "desc");
			}
		}
		
		if(!empty($status) && $status != 0) $this->db->where('status',$status);
		if(!empty($kondisi) && $kondisi != 0) $this->db->where('kondisi',$kondisi);
		
		if(!empty($cabang) && $cabang != 0) {
			$this->db->where('followup',$cabang);
		}
		
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(tgl_masuk)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(tgl_masuk)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(tgl_masuk)',date('Y', $tanggal));
			$this->db->where('MONTH(tgl_masuk)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(tgl_masuk)',date('d', $tanggal));
		}
		$data_result = $this->db->get('pesan')->num_rows();

		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/followup/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/followup/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);	
		
		$this->db->select('pesan.id,pesan.tgl_masuk,pesan.tgl_closing,pesan.nota,pesan.id_cabang,pesan.sum_trans,pesan.status,pesan.id_bukutamu,pesan.seles,pesan.teknisi,pesan.keterangan,pesan.input,pesan.bayar,pesan.id_pesan,pesan.followup,pesan.kondisi,pesan.input,bukutamu.nama,bukutamu.alamat,bukutamu.telepon');
		$this->db->join('bukutamu','pesan.id_bukutamu = bukutamu.id');
		
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("tgl_masuk", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("tgl_masuk", "desc");
			} elseif($urutan == 3){
				$this->db->order_by("id", "desc");
			}
		}
		
		if(!empty($status) && $status != 0) $this->db->where('status',$status);
		if(!empty($kondisi) && $kondisi != 0) $this->db->where('kondisi',$kondisi);
		
		if(!empty($cabang) && $cabang != 0){
			$this->db->where('followup',$cabang);
		}
		
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(tgl_masuk)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(tgl_masuk)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(tgl_masuk)',date('Y', $tanggal));
			$this->db->where('MONTH(tgl_masuk)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(tgl_masuk)',date('d', $tanggal));
		}

		if(!$check_admin && !$check_marketing) $this->db->where('id_cabang',$id_cabang);
		$query = $this->db->get('pesan',$config['per_page'],$from)->result();	
		$data['data'] = $query;
		if($check_admin){
			$data_result = 0;
		}
		$data['count'] = $data_result;
		$this->load->view('index',$data);
	}
	
	public function hutang_order()
	{	
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$check_admin = $this->mymodel->isAdmin($username);
		if(!$check_admin){
			$this->db->where('id_cabang',$id_username);
		}	
		$this->db->where('YEAR(tgl_masuk)',2019);
		$this->db->select('pesan.id,pesan.tgl_masuk,pesan.tgl_closing,pesan.nota,pesan.id_cabang,pesan.sum_trans,pesan.status,pesan.id_bukutamu,pesan.seles,pesan.teknisi,pesan.keterangan,pesan.input,pesan.bayar,pesan.id_pesan,pesan.followup,pesan.kondisi,pesan.input,bukutamu.nama,bukutamu.alamat,bukutamu.telepon');
		$this->db->join('bukutamu','pesan.id_bukutamu = bukutamu.id');
		
		$orders = $this->db->get('pesan')->result_array();
		$order_array = array();
		foreach($orders as $o){
			$trans = $o['bayar'];
			if($trans != 0 && $o['id_pesan'] == 0){
				$trans = explode(',',$trans);
				$nominal = $trans[0];
				$bayar = $trans[1];
				if($nominal != $bayar){
					$order_array[] = $o;
				}
			}
		}

		$filter_order = array();
		foreach($order_array as $oa){
			$bayar = array();
			$id = $oa['id'];
			$trans2 = $oa['bayar'];
			$trans2 = explode(',',$trans2);
			$bayar2 = $trans2[1];
			$bayar[] = $bayar2;
			$this->db->where('id_pesan',$id);
			$orders2 = $this->db->get('pesan')->result_array();
			if(!empty($orders2)){
				foreach($orders2 as $o2){
					$trans3 = $o2['bayar'];
					$trans3 = explode(',',$trans3);
					$bayar3 = $trans3[1];
					$bayar[] = $bayar3;
				}	
				if($trans2[0] > array_sum($bayar)){
					$filter_order[] = $oa;
				}
			} else {
				$filter_order[] = $oa;
			}
		}
		$data['data'] = $filter_order;
		$this->load->view('index',$data);
	}
	
	public function omset()
	{	
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$check_admin = $this->mymodel->isAdmin($username);
		$id_cabang = $this->mymodel->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$month = (int)$t[1];
			$year = (int)$t[2];
		} else {
			$cabang = 0;
		}
		$this->db->where('id',$cabang);
		$nama = $this->db->get('user')->result_array();
		if(!empty($nama)){
		 	$nama = $nama[0]['name'];
		} else {
			$nama = 'KESELURUHAN';
		}
		$time = strtotime(date('Y-m-d'));
		if(empty($monthy) && empty($year)){
			$month = date('m',$time);
			$year = date('Y', $time); 
		}
		
		$day = 1;
		$date = $year.'-'.$month.'-'.$day;
		$timestamp = strtotime($date);
		$last_month = date('t', $timestamp);
		$total_bayar = array();
		$total_omset = array();
		$y = 1;
		$t = 1;
		for($x = 1; $x<=$last_month; $x++){
			$this->db->where('YEAR(tgl_masuk)',$year);
			$this->db->where('MONTH(tgl_masuk)',$month);
			$this->db->where('DAYOFMONTH(tgl_masuk)',$y++);
		
			if(!empty($cabang)){
				$this->db->where('id_cabang',$cabang);
			}
			
			if(!$check_admin){
				$this->db->where('id_cabang',$id_cabang);
			}
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
		
		$item['total_bayar'] = $total_bayar;
		$item['month'] = $month;
		$item['nama'] = $nama;
		$item['year'] = $year;
		$item['date'] = $timestamp;
		$item['cabang'] = $cabang;
		$this->load->view('index',$item);
	}
	
	public function search_omset(){
		$cabang = $this->input->post('cabang');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$link = $cabang.'^'.$month.'^'.$year;
		redirect('welcome/omset/'.$link);	
	}
	
	public function update_omset(){
		$id = $this->input->post('id');
		$item = array(
			'target' => $this->input->post('target'),
			'id_cabang' => $this->input->post('id_cabang'),
		);	
		if(empty($id)){
			$data = array(
				'target' => $this->input->post('target'),
				'id_cabang' => $this->input->post('id_cabang'),
				'waktu' => $this->input->post('waktu'),
			);
			
			$tambah = $this->mymodel->insertItem('omset',$data);
		}
		$ins = $this->mymodel->updateItem('omset',$item, array('id' => $id));	
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Omset sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Omset tidak dapat ditambahkan');
			redirect($this->agent->referrer());
		}
	}
	
	public function search_order()
	{	
		$cabang = $this->input->post('cabang');
		$status = $this->input->post('status');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$urutan = $this->input->post('urutan');
		$kondisi = $this->input->post('kondisi');
		$nama = $this->input->post('nama');
		$tanggal = strtotime($this->input->post('tanggal'));
		$tipe = $this->input->post('tipe');
		$link = $cabang.'^'.$status.'^'.$month.'^'.$year.'^'.$tanggal.'^'.$urutan.'^'.$kondisi.'^'.$nama.'^'.$tipe;
		redirect('welcome/order/'.$link);	
	}
	
	public function search_followup()
	{	
		$cabang = $this->input->post('cabang');
		$status = $this->input->post('status');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$urutan = $this->input->post('urutan');
		$kondisi = $this->input->post('kondisi');
		$link = $cabang.'^'.$status.'^'.$month.'^'.$year.'^'.$urutan.'^'.$kondisi;
		redirect('welcome/followup/'.$link);	
	}
	
	public function detail_order_bukutamu($id)
	{
		
		$this->db->where('id_bukutamu', $id);
		$data_result = $this->db->get('pesan')->num_rows();
		$config['base_url'] = base_url().'index.php/welcome/detail_order_bukutamu';
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$from = $this->uri->segment(4);
		$this->pagination->initialize($config);	
		$this->db->where('id_bukutamu', $id);	
		$query = $this->db->get('pesan',$config['per_page'],$from)->result();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_detail_bukutamu()
		{
			$id_bukutamu = $this->input->post('id_bukutamu');
			$cabang = $this->input->post('cabang');
			$status = $this->input->post('status');
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$urutan = $this->input->post('urutan');
			$kondisi = $this->input->post('kondisi');
			$tanggal = strtotime($this->input->post('tanggal'));
			$link = $cabang.'^'.$status.'^'.$month.'^'.$year.'^'.$tanggal.'^'.$urutan.'^'.$kondisi.'^'.$id_bukutamu;
			redirect('welcome/detail_order_bukutamu/'.$link);	
		}
		
	
	public function detail_order($id)
	{	
		$this->db->where('id',$id);
		$query = $this->db->get('pesan')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function tambah_order($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('bukutamu')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);$this->db->where('id',$id);
		$query = $this->db->get('pesan')->result_array();
		$data['data'] = $query;
	}
	
	public function tambah_new_order()
	{
		$this->load->view('index');
	}
	
	public function ubah_order($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('pesan')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function add_order()
	{
		$data = array(
			'tgl_masuk' => $this->input->post('tgl_masuk'),
			'tgl_pemasangan' => $this->input->post('tgl_pemasangan'),
			'nota' => $this->input->post('nota'),
			'id_cabang' => $this->input->post('id_cabang'),
			'id_bukutamu' => $this->input->post('id_bukutamu'),
			'seles' => $this->input->post('seles'),
			'teknisi' => $this->input->post('teknisi'),
			'keterangan' => $this->input->post('keterangan'),
		);
		$ins = $this->mymodel->insertItem('pesan',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>data preorder sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>data preorder tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tbhitemmasuk()
	{
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'deskripsi' => $this->input->post('deskripsi'),
			'id_cabang' => $this->input->post('cabang'),
		);
		$ins = $this->mymodel->insertItem('stok_masuk',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Daftar Item masuk ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Daftar Item masuk ditambahkan tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tbhpembelianitemmasuk()
	{
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'tipe' => $this->input->post('tipe'),
			'supplier' => $this->input->post('supplier'),
			'deskripsi' => $this->input->post('deskripsi'),
			'id_cabang' => $this->input->post('cabang'),
		);
		$ins = $this->mymodel->insertItem('stok_masuk',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Daftar Item masuk ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Daftar Item masuk ditambahkan tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function bayar_pembelian_stok($id)
	{
		$data = array(
			'bayar' => $this->input->post('bayar'),
			'waktu' => $this->input->post('waktu'),
		);
		
		$total_nominal = array();
		$this->db->where('id_stok_masuk',$id);
		$total = $this->db->get('sub_stok_masuk')->result_array();
		if(!empty($total)){
			foreach($total as $t){
				$total_nominal[] = $t['harga'];
			}
			$total_nominal = array_sum($total_nominal);
			if($total_nominal <= $this->input->post('bayar')){
				$data['status'] = 1;
			}
		}
		
		$ins = $this->mymodel->updateItem('stok_masuk',$data, array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Berhasil Melakukan Pembayaran');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Pembayaran gagal');
			redirect($this->agent->referrer());
		}
	}
	
	public function pelunasan($id)
	{
		$data = array(
			'bayar' => $this->input->post('pelunasan'),
			'waktu' => strtotime($this->input->post('waktu')),
			'id_stok_masuk' => $id,
		);
		
		$ins = $this->mymodel->insertItem('pelunasan',$data);
		if((int)$this->input->post('pelunasan') >= (int)$this->input->post('kekurangan')){
			$ins = $this->mymodel->updateItem('stok_masuk',array('status' => 1),array('id'=>$id));
		} else {
			$ins = $this->mymodel->updateItem('stok_masuk',array('status' => 0),array('id'=>$id));
		}
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Berhasil Melakukan Pembayaran');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Pembayaran gagal');
			redirect($this->agent->referrer());
		}
	}
	
	public function hutang($id)
	{
		$this->db->where('id', $id);
		$pesan = $this->db->get('pesan')->result_array();
		$bayar = $this->input->post('nominal').','.$this->input->post('bayar'); 
		$data = array(
			'tgl_masuk' => $this->input->post('tgl_masuk'),
			'nota' => $pesan[0]['nota'],
			'id_cabang' => $pesan[0]['id_cabang'],
			'id_bukutamu' => $pesan[0]['id_bukutamu'],
			'bayar' => $bayar,
			'seles' => $pesan[0]['seles'],
			'teknisi' => $pesan[0]['teknisi'],
			'keterangan' => $pesan[0]['keterangan'],
			'id_pesan' => $this->input->post('id_pesan'),
		);
	
		$ins = $this->mymodel->insertItem('pesan',$data);
		if($ins){
			$id_baru = $this->db->insert_id();
			$id_pesan = $this->input->post('id_pesan');
			$nominal = $bayar;
			$nominal = explode(",",$nominal);
			$bayar_nominal = $nominal[1];
			$total_nominal = (int)$nominal[0];
			$this->db->where('id', $id_baru);
			$this->db->where('id_pesan', $id_pesan);
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
			
			$data = array(
				'saldo' => $bayar_nominal,
				'status' => 2,
				'id_trans' => $id_baru,
				'waktu' => $this->input->post('tgl_masuk'),
				'id_cabang' => $pesan[0]['id_cabang'],
				'deskripsi' => $pesan[0]['nota'],
			);
			$add = $this->mymodel->insertItem('kas',$data);
			
			if($kondisi <= 0){ 
				$data = array(
					'status' => 4,
					'id_pesan' => $id_baru,
					'keterangan' => $pesan[0]['nota'],
				);

				$ins = $this->mymodel->insertItem('proses_pesan',$data);
			}
			$this->session->set_flashdata('message','<b>Notification</b>data preorder sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>data preorder tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function rubah_order()
	{
		
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$check_admin = $this->mymodel->isAdmin($username);
		$id_bukutamu = $this->input->post('id_bukutamu');
		$telepon  = $this->input->post('telepon');
		
		if(!$check_admin){
			$no_telepon = $this->mymodel->decrypt($telepon);

			$this->db->where('id', $id_bukutamu);
			$this->db->where('telepon', $no_telepon);
			$check_bukutamu = $this->db->get('bukutamu')->result_array();
			if($check_bukutamu){
				$telepon = $check_bukutamu[0]['telepon'];
			}
		}
		
		$data = array(
			'telepon' => $telepon,
			'nama' => $this->input->post('nama'),
			'tipe' => $this->input->post('tipe'),
			'alamat' => $this->input->post('alamat'),
			'pengimput' => $this->input->post('pengimput'),
			'cabang' => $this->input->post('id_cabang'),
		);
		$bayaran = $this->input->post('bayar');
		$total = $this->input->post('total');
		$bayar = $total.','.$bayaran;
		
		$id_pesan = $this->input->post('id_pesan');
		$bukutamu = $this->mymodel->updateItem('bukutamu',$data, array('id' => $id_bukutamu));
		
		$followup = $this->input->post('followup');
		if(empty($followup)){
			$followup = 0;
		}	
		if($bukutamu){
		$data = array(
			'tgl_masuk' => $this->input->post('tgl_masuk'),
			'nota' => $this->input->post('nota'),
			'id_cabang' => $this->input->post('id_cabang'),
			'id_bukutamu' => $id_bukutamu,
			'bayar' => $bayar,
			'followup' => $followup,
			'seles' => $this->input->post('seles'),
			'teknisi' => $this->input->post('teknisi'),
			'input' => $this->input->post('pengimput'),
			'sum_trans' => $this->input->post('nama_transaksi'),
			'keterangan' => $this->input->post('keterangan'),
			'kondisi' => $this->input->post('kondisi'),
		);
		$ins = $this->mymodel->updateItem('pesan',$data,array('id' => $id_pesan));	
		
			if(!empty($this->input->post('nota'))){
			
			$data = array(
				'status' => 4,
				'id_pesan' => $id_pesan,
				'keterangan' => $this->input->post('nota'),
			);

				$tambah = $this->mymodel->insertItem('proses_pesan',$data);
			}
			
			if(!empty($nominal) && !empty($bayaran)){
				$cek = explode(",",$bayar);
				$checking = $cek[0] - $cek[1];
					if($checking <= 0){
						$data = array(
							'status' => 4,
							'id_pesan' => $id_pesan,
							'keterangan' => '',
						);

						$tambah_nominal = $this->mymodel->insertItem('proses_pesan',$data);
					}
			}
			
			if($ins){
			$bayar = $this->input->post('bayar');
			if($bayar >= 0 && $ins){
				$this->db->where('id_trans', $id_pesan);
				$kas = $this->db->get('kas')->result_array();
				if($kas){
					$data = array(
						'waktu' => $this->input->post('tgl_masuk'),
						'id_cabang' => $this->input->post('id_cabang'),
						'saldo' => $bayar,
						'deskripsi' => 'TRANSAKSI '.$this->input->post('nota'),
					);
						$add = $this->mymodel->updateItem('kas',$data, array('id' => $kas[0]['id']));
						if($bayar == 0 ){
							$del = $this->mymodel->deleteItem('kas',array('id_trans' => $id_pesan));
						}
					} elseif($bayar > 0) {
						$data = array(
							'saldo' => $bayar,
							'status' => 2,
							'id_trans' => $id_pesan,
							'waktu' => $this->input->post('tgl_masuk'),
							'id_cabang' => $this->input->post('id_cabang'),
							'deskripsi' => 'TRANSAKSI '.$this->input->post('nota'),
						);
						$add = $this->mymodel->insertItem('kas',$data);
				}

			}
				$this->session->set_flashdata('message','<b>Notification</b>data preorder sudah dirubah');
				redirect($this->agent->referrer());
			} else {
				$this->session->set_flashdata('message','<b>Notification</b>data preorder tidak dapat dirubah');
				redirect($this->agent->referrer());
			}
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>data preorder tidak dapat dirubah');
			redirect($this->agent->referrer());
		}
	}
	
	
	public function add_new_order()
	{
		$id = $this->input->post('id_bukutamu');
		$telepon = $this->input->post('telepon');
		$this->db->where('telepon',$telepon);
		$cek_telepon = $this->db->get('bukutamu')->num_rows();	
		if($cek_telepon > 0 && $this->input->post('telepon') != '-' && empty($id)){
			redirect('welcome/bukutamu/^^'.$telepon.'^0^0^0');
		}

		$data = array(
			'telepon' => $telepon,
			'nama' => $this->input->post('nama'),
			'alamat' => $this->input->post('alamat'),
			'tipe' => $this->input->post('tipe'),
			'cabang' => $this->input->post('cabang'),
			'waktu' => $this->input->post('tgl_masuk'),
		);
		
		if($cek_telepon == 0 || $this->input->post('telepon') == '-' && empty($id)) {
			$bukutamu = $this->mymodel->insertItem('bukutamu',$data);
		} 
		
		$followup = $this->input->post('followup');
		if(empty($followup)){
			$followup = 0;
		}
		
		if(empty($id)) $id = $this->db->insert_id();
		if($id){
		$data = array(
			'tgl_masuk' => $this->input->post('tgl_masuk'),
			'id_cabang' => $this->input->post('cabang'),
			'nota' => $this->input->post('nota'),
			'id_bukutamu' => $id,
			'followup' => $followup,
			'keterangan' => $this->input->post('keterangan'),
			'sum_trans' => $this->input->post('nama_transaksi'),
			'input' => $this->input->post('pengimput'),
			'kondisi' => $this->input->post('kondisi'),
			
		);
		
		if(!empty($this->input->post('seles'))){
			$data['seles'] = $this->input->post('seles');
		}
		if(!empty($this->input->post('nota'))){
			$data['status'] = 4;
		}	
		if(!empty($this->input->post('teknisi'))){
			$data['teknisi'] = $this->input->post('teknisi');
		}	

		$ins = $this->mymodel->insertItem('pesan',$data);	
			if($ins){
				$id_pesan = $this->db->insert_id();
				$this->db->where('id', $id_pesan);
				$pesan = $this->db->get('pesan')->result_array();
				
				$this->session->set_flashdata('message','<b>Notification</b>data preorder sudah ditambahkan');
				redirect($this->agent->referrer());
			} else {
				$this->session->set_flashdata('message','<b>Notification</b> Nomer HP sudah ada, ganti yang lain');
				redirect($this->agent->referrer());
			}
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>data preorder tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function proses_order($id)
	{	
		$data = array(
			'status' => 1,
			'id_pesan' => $id,
			'keterangan' => 'Preorder sedang di proses',
		);
		$ins = $this->mymodel->insertItem('proses_pesan',$data);
		if($ins){
			$item = array(
				'status' => 1,
			);
			$ins = $this->mymodel->updateItem('pesan', $item, array('id' => $id));
			$this->session->set_flashdata('message','<b>Notification</b>status pre order sudah dirubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>status pre order tidak dapat dirubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_proses_order()
	{	
		$this->form_validation->set_rules('status','status','required');
		if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('message','<b>Notification</b> Nomer HP sudah ada, ganti yang lain');
			redirect($this->agent->referrer());
		}	
		
		$keterangan = $this->input->post('keterangan');
		$status = $this->input->post('status');
		$id = $this->input->post('id_pesan');
		
		if($status == 0){
			$this->session->set_flashdata('message','<b>Notification</b>pilih status terlebih dahulu');
			redirect($this->agent->referrer());
		}
		$data = array(
			'status' => $status,
			'id_pesan' => $id,
			'keterangan' => $keterangan,
		);
		
		$ins = $this->mymodel->insertItem('proses_pesan',$data);
		if($ins){
		$item = array(
			'status' => $status,
		);	
		$ins = $this->mymodel->updateItem('pesan',$item, array('id' => $id));	
			$this->session->set_flashdata('message','<b>Notification</b>proses order sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>proses order tidak dapat ditambahkan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_status($id)
	{	
		$this->db->where('id_pesan',$id);
		$data_result = $this->db->get('proses_pesan')->result_array();
		$data['data'] = $data_result;
		$this->load->view('index',$data);
	}
	
	public function addnota($id)
	{
		$this->db->where('id',$id);
		$data_result = $this->db->get('pesan')->result_array();
		$data['data'] = $data_result;
		$this->load->view('index',$data);
	}
	
	public function addnominal($id)
	{
		$this->db->where('id',$id);
		$data_result = $this->db->get('pesan')->result_array();
		$data['data'] = $data_result;
		$this->load->view('index',$data);
	}
	
	public function update_addnota($id){
		$this->db->where('id', $id);
		$pesan = $this->db->get('pesan')->result_array();
		$item = array(
			'nota' => $this->input->post('nota'),
		);	
		if(!empty($this->input->post('nota'))){
			$data = array(
				'status' => 4,
				'id_pesan' => $id,
				'keterangan' => $this->input->post('nota'),
			);
			
			$tambah = $this->mymodel->insertItem('proses_pesan',$data);
		}
		$ins = $this->mymodel->updateItem('pesan',$item, array('id' => $id));	
		if($ins){
			$this->db->where('id_trans', $id);
			$kas = $this->db->get('kas')->result_array();
			
			if($kas){
			$data = array(
				'waktu' => date('Y-m-d',strtotime($pesan[0]['tgl_masuk'])),
				'deskripsi' => 'TRANSAKSI '.$this->input->post('nota'),
			);
				$add = $this->mymodel->updateItem('kas',$data, array('id' => $kas[0]['id']));
			} 
			$this->session->set_flashdata('message','<b>Notification</b>Nota sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Nota tidak dapat ditambahkan');
			redirect($this->agent->referrer());
		}
	}

	public function update_presentase($id){
		$data = array(
			'presentase' => $this->input->post('presentase'),
		);
			$add = $this->mymodel->updateItem('pesan',$data, array('id' => $id));
		if($data){	 
			$this->session->set_flashdata('message','<b>Notification</b>Presentase sudah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Presentase tidak dapat ditambahkan');
			redirect($this->agent->referrer());
		}
	}
	
	public function update_addnominal($id){
		$nominal = $this->input->post('nominal');
		$bayar = $this->input->post('bayar');
		$nominal = $nominal.','.$bayar;
		
		$item = array(
			'bayar' => $nominal,
		);	
		$this->db->where('id', $id);
		$pesan = $this->db->get('pesan')->result_array();
		if(!empty($pesan)){
			$saldo = $pesan[0]['bayar'];
			$t = explode(",",$saldo);
			$tgl = date('Y-m-d', strtotime($pesan[0]['tgl_masuk']));
			if(!empty($pesan[0]['nota'])){
				$nota = $pesan[0]['nota'];
			} else {
				$nota ='';
			}	
		}
		$ins = $this->mymodel->updateItem('pesan',$item, array('id' => $id));
		if(!empty($nominal) && !empty($bayar)){
			$cek = explode(",",$nominal);
			$checking = $cek[0] - $cek[1];
		
		if($checking <= 0){
				$data = array(
					'status' => 4,
					'id_pesan' => $id,
					'keterangan' => $nota,
				);

				$ins = $this->mymodel->insertItem('proses_pesan',$data);
			}
		}
		$this->db->where('id_trans', $id);
		$kas = $this->db->get('kas')->result_array();
		if($bayar >= 0 && $kas){
			
				$data = array(
					'saldo' => $bayar,
				);
					$add = $this->mymodel->updateItem('kas',$data, array('id' => $kas[0]['id']));
					if($bayar == 0 ){
						$del = $this->mymodel->deleteItem('kas',array('id_trans' => $id));
					}
				} else {
					$data = array(
						'saldo' => $bayar,
						'status' => 2,
						'id_trans' => $pesan[0]['id'],
						'waktu' => $tgl,
						'id_cabang' => $pesan[0]['id_cabang'],
						'deskripsi' => 'TRANSAKSI '.$pesan[0]['nota'],
					);
					$add = $this->mymodel->insertItem('kas',$data);
			
		}
		if($add){
			$this->session->set_flashdata('message','<b>Notification</b>Nota sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Nota tidak dapat ditambahkan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_pengguna()
	{
		$this->load->view('index');
	}
	
	public function tambah_sub_pengguna()
	{
		$this->load->view('index');
	}
	
	public function pengguna()
	{
		$this->db->where('hakakses !=',2);
		$data_result = $this->db->get('user')->num_rows();
		$config['base_url'] = base_url().'index.php/welcome/pengguna';
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$from = $this->uri->segment(3);
		$this->pagination->initialize($config);
		$this->db->where('hakakses !=',2);
		$query = $this->db->get('user',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function sub_pengguna()
	{
		$this->db->where('hakakses !=',0);
		$this->db->where('hakakses != ',1);
		$data_result = $this->db->get('user')->num_rows();
		$config['base_url'] = base_url().'index.php/welcome/sub_pengguna';
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$from = $this->uri->segment(3);
		$this->pagination->initialize($config);
		$this->db->where('hakakses !=',0);
		$this->db->where('hakakses != ',1);
		$query = $this->db->get('user',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function detail_user($id)
	{
		$this->db->where('id_user',$id);
		$user = $this->db->get('detail_user')->result_array();	
		$data['data'] = $user;
		$this->load->view('index',$data);
	}
	
	public function add_pengguna()
	{
		$cabang = $this->input->post('cabang');
		
		$this->form_validation->set_rules('username','Username','required|is_unique[user.username]');
		$this->form_validation->set_rules('name','Name','required|is_unique[user.name]');
		$this->form_validation->set_rules('password','Password','required');
		$this->form_validation->set_rules('repassword','Repassword','required|matches[password]');
		if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('message','<b>Notification</b>username telah terdaftar atau password tidak sama');
			redirect($this->agent->referrer());
		} else {	
			$data = array(
				'username' => $this->input->post('username'),
				'name' => $this->input->post('name'),
				'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
				'status' => 1,
				'hakakses' => 2
			);

			if($cabang){
				$data['cabang'] = $cabang;
			}
			$ins = $this->mymodel->insertItem('user',$data);
			$this->session->set_flashdata('message','<b>Notification</b> pendaftaran sukses!');
			if($cabang){
				redirect($this->agent->referrer());
			} else {
				redirect($this->agent->referrer());
			}	
		}
	}
	
	public function add_detail_pengguna()
	{	
		$id = $this->input->post('id_detail');
		$data = array(
			'alamat' => $this->input->post('alamat'),
			'email' => $this->input->post('email'),
			'id_user' => $this->input->post('id_user'),
			'telepon' => $this->input->post('telepon'),
		);
		if($id){
			$ins = $this->mymodel->updateItem('detail_user',$data, array('id'=> $id));
			$this->session->set_flashdata('message','<b>Notification</b> pendaftaran sukses!');
			redirect($this->agent->referrer());
		} else {
			$ins = $this->mymodel->insertItem('detail_user',$data);
			$this->session->set_flashdata('message','<b>Notification</b> pendaftaran sukses!');
			redirect($this->agent->referrer());
		}
	}
	
	public function setting_pengguna($id)
	{	
		$this->db->where('id',$id);
		$query = $this->db->get('user')->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function update_password_pengguna($id)
	{	
		$username = $this->input->post('username');
		$check = $this->mymodel->check('user','username',$username);
		$password = $this->input->post('old_password');
		$this->form_validation->set_rules('new_password','New password','required|alpha_numeric');
		$this->form_validation->set_rules('repassword','Repassword','required|matches[new_password]');
		if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('message','password tidak sesuai' );
			redirect($this->agent->referrer());
		} else {
			$verify = password_verify($password, $check->row()->password);
			if($verify){
				$this->mymodel->save($id);
				$this->session->set_flashdata('message','Password berhasil dirubah' );
				redirect($this->agent->referrer());
			} else {
				$this->session->set_flashdata('message','Password lama tidak sesuai' );
				redirect($this->agent->referrer());
			}
		}
	}
	
	public function update_pengguna($id){	
		$hakakses = $this->input->post('hakakses');
		$status = $this->input->post('status');
		$cabang = $this->input->post('cabang');
		$data = array(
			'hakakses' => $hakakses,
			'status' => $status,
			'cabang' => $cabang,
		);
		$ins = $this->mymodel->updateItem('user',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> user telah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> user tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function absent()
	{	
		$link = $this->uri->segment(3);
			$posisi=strpos($link,"%5E");
			if($posisi > 0){
				$t = explode("%5E",$link);
				$id_cabang = (int)$t[0];
				$date = $t[1];
				$month = $t[2];
				$year = $t[3];
			} else {
				$id_cabang = '';
				$date = '';
				$month = '';
				$year = '';
			}
		
		if(empty($id_cabang)){
			$username = $this->session->userdata('username');
			$id_cabang = $this->mymodel->getIduser($username);
		}
		$date = $this->input->post('date');
		if($date){
			$timestamp = strtotime($date);
		} else {
			$timestamp = time();
		}
		$last_month = date('t', $timestamp);
		$absent = array();
		for($x = 1; $x<=$last_month; $x++){
			$this->db->where('id_user', $id_cabang);
			$this->db->where('YEAR(date)',date("Y", $timestamp));
			$this->db->where('MONTH(date)',date("m", $timestamp));
			$this->db->where('DAYOFMONTH(date)',$x);
			$data = $this->db->get('absent')->result_array();
			if(empty($data)){
				$absent[] = 0;
			} else {
				foreach($data as $d){
					$absent[]['date'] = $d['date'];
				}
			}
		}
		
		$this->db->where('id',$id_cabang);
		$data = $this->db->get('user')->result_array();
		if($data){
			$username = $data[0]['username'];
		}
		$check_admin = $this->mymodel->isAdmin($username);
		if(!$check_admin){
			$item['data'] = $absent;
		} else {
			$item['data'] = 'no_result';
		}
		
		$item['date'] = $timestamp;
		$item['user_input'] = $id_cabang;
		$this->load->view('index',$item);
	}
	
	public function search_absent(){
		$cabang = $this->input->post('cabang');
		$date = $this->input->post('date');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$link = $cabang.'^'.$date.'^'.$month.'^'.$year;
		redirect('welcome/absent/'.$link);	
	}
	
	public function absent_action(){
		$tanggal = $this->input->post('tanggal');
		$timestamp = strtotime($tanggal);
		$cabang = $this->input->post('cabang');
		$this->db->where('id_user', $cabang);
		$this->db->where('YEAR(date)',date("Y", $timestamp));
		$this->db->where('MONTH(date)',date("m", $timestamp));
		$this->db->where('DAYOFMONTH(date)',date("d", $timestamp));
		$check_absent = $this->db->get('absent')->num_rows();
		
		if($check_absent > 0){
			$this->session->set_flashdata('message','<b>Notification</b> kamu sudah melakukan absensi');
			redirect($this->agent->referrer());
		} 
	
		$data = array(
			'id_user' => $cabang, 
			'date' => $tanggal,
		);
		
		$ins = $this->mymodel->insertItem('absent',$data);
		
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> telah melakukan absensi');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> tidak dapat melakukan absensi');
			redirect($this->agent->referrer());
		}
	}
	
	public function remove_absent($timestamp,$id){
	$this->db->where('id_user', $id);
	$this->db->where('YEAR(date)',date("Y", $timestamp));
	$this->db->where('MONTH(date)',date("m", $timestamp));
	$this->db->where('DAYOFMONTH(date)',date("d", $timestamp));
	$data = $this->db->get('absent')->result_array();

	$delete = $this->mymodel->deleteItem('absent',array('id' => $data[0]['id']));
		if($delete >= 1){	
			$this->session->set_flashdata('message','<b>Notification</b> data telah dihapus');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat dihapus');
			redirect($this->agent->referrer());
		}
	}
	
	public function daftar_barang()
	{
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$urutan = (int)$t[0];
			$code = $t[1];
			$name = $t[2];
			$name = str_replace("%20"," ",$name);	
			$category = $t[3];
			$merek = $t[4];
			$merek = str_replace("%20"," ",$merek);
			$tipe = $t[5];
			$tipe = str_replace("%20"," ",$tipe);
		} else {
			$code = '';
			$category = '';
			$name = '';
			$merek = '';
			$tipe = '';
		}
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($code)) $this->db->where('code',$code);
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name);
		if(!empty($merek)) $this->db->like('merek',$merek);
		if(!empty($tipe)) $this->db->like('tipe',$tipe);
		$this->db->where('status',0);
		$data_result = $this->db->get('item')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/daftar_barang/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/daftar_barang/';
		}
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($code)) $this->db->where('code',$code);
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name); 
		if(!empty($merek)) $this->db->like('merek',$merek);
		if(!empty($tipe)) $this->db->like('tipe',$tipe);
		$this->db->where('status',0);
		$query = $this->db->get('item',$config['per_page'],$from)->result();	
		if(!empty($urutan)) $data['urutan'] = $urutan;
		$data['data'] = $query;
		$data['name'] = $name;
		$data['code'] = $code;
		
		$this->load->view('index',$data);
	}
	
	public function daftar_barang_nonkulak()
	{
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$urutan = (int)$t[0];
			$code = $t[1];
			$name = $t[2];
			$name = str_replace("%20"," ",$name);	
			$category = $t[3];
		} else {
			$code = '';
			$category = '';
			$name = '';
		}
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		$this->db->where('status',1);
		if(!empty($code)) $this->db->where('code',$code);
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name);
		$data_result = $this->db->get('item')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/daftar_barang/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/daftar_barang/';
		}
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		$this->db->where('status',1);
		if(!empty($code)) $this->db->where('code',$code);
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name); 
		$query = $this->db->get('item',$config['per_page'],$from)->result();	
		if(!empty($urutan)) $data['urutan'] = $urutan;
		$data['data'] = $query;
		$data['name'] = $name;
		$data['code'] = $code;
		
		$this->load->view('index',$data);
	}
	
	public function peralatan()
	{
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$urutan = (int)$t[1];
			$name = $t[2];
			$name = str_replace("%20"," ",$name);	
			$category = (int)$t[3];
		} else {
			$category = '';
			$name = '';
			$cabang = $id_username;
		}
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name);
		$this->db->where('tipe',1);
		$data_result = $this->db->get('perlengkapan')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/peralatan/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/peralatan/';
		}
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name); 
		$this->db->where('tipe',1);
		$query = $this->db->get('perlengkapan',$config['per_page'],$from)->result();
		if(!empty($urutan)) $data['urutan'] = $urutan;
		$data['data'] = $query;
		$data['name'] = $name;
		
		$this->load->view('index',$data);
	}
	
	public function inventaris()
	{
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$urutan = (int)$t[1];
			$name = $t[2];
			$category = (int)$t[3];
			$name = str_replace("%20"," ",$name);	
		} else {
			$category = '';
			$name = '';
			$cabang = $id_username;
		}
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name);
		$this->db->where('tipe',2);
		$data_result = $this->db->get('perlengkapan')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/inventaris/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/inventaris/';
		}
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name); 
		$this->db->where('tipe',2);
		$query = $this->db->get('perlengkapan',$config['per_page'],$from)->result();
		if(!empty($urutan)) $data['urutan'] = $urutan;
		$data['data'] = $query;
		$data['name'] = $name;
		
		$this->load->view('index',$data);
	}
	
	
	public function daftar_barang_perlengkapan()
	{
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$urutan = (int)$t[0];
			$name = $t[1];
			$name = str_replace("%20"," ",$name);
			$category = (int)$t[2];
		} else {
			$category = '';
			$name = '';
		}
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name);
		$this->db->where('tipe',1);
		$data_result = $this->db->get('perlengkapan')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/daftar_barang_perlengkapan/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/daftar_barang_perlengkapan/';
		}
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name);
		$this->db->where('tipe',1);
		$query = $this->db->get('perlengkapan',$config['per_page'],$from)->result();	
		if(!empty($urutan)) $data['urutan'] = $urutan;
		$data['data'] = $query;
		$data['name'] = $name;
		$this->load->view('index',$data);
	}
	
	public function daftar_barang_inventaris()
	{
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$urutan = (int)$t[0];
			$name = $t[1];
			$name = str_replace("%20"," ",$name);
			$category = (int)$t[2];
		} else {
			$category = '';
			$name = '';
		}
		if(!empty($urutan)){
			if($urutan == 2){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name);
		$this->db->where('tipe',2);
		$data_result = $this->db->get('perlengkapan')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/daftar_barang_inventaris/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/daftar_barang_inventaris/';
		}
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name);
		$this->db->where('tipe',2);
		$query = $this->db->get('perlengkapan',$config['per_page'],$from)->result();	
		if(!empty($urutan)) $data['urutan'] = $urutan;
		$data['data'] = $query;
		$data['name'] = $name;
		$this->load->view('index',$data);
	}
	
	function harga_perlengkapan($id){
		$this->db->where('id_item',$id);
		$this->db->where('id_cabang',$this->input->post('id_cabang'));
		$checking = $this->db->get('harga_perlengkapan')->result_array();
		
		$data = array(
			'id_item' => $id,
			'harga' => $this->input->post('harga'),
			'unit' => $this->input->post('unit'),
			'id_cabang' => $this->input->post('id_cabang'),
		);
		
		if($checking){
			$ins = $this->mymodel->updateItem('harga_perlengkapan',$data, array('id' => $checking[0]['id']));
		} else {
			$ins = $this->mymodel->insertItem('harga_perlengkapan',$data);
		}
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> Harga Perlengkapan sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> Harga Perlengkapan tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	function harga_inventaris($id){
		$this->db->where('id_item',$id);
		$this->db->where('id_cabang',$this->input->post('id_cabang'));
		$checking = $this->db->get('harga_inventaris')->result_array();
		
		$data = array(
			'id_item' => $id,
			'harga' => $this->input->post('harga'),
			'unit' => $this->input->post('unit'),
			'id_cabang' => $this->input->post('id_cabang'),
		);
		
		if($checking){
			$ins = $this->mymodel->updateItem('harga_inventaris',$data, array('id' => $checking[0]['id']));
		} else {
			$ins = $this->mymodel->insertItem('harga_inventaris',$data);
		}
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> Harga Iventaris sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> Harga Iventaris tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function search_barang_perlengkapan(){
		$nama = $this->input->post('nama');
		$urutan = $this->input->post('urutan');
		$kategori = $this->input->post('kategori');
		$link = $urutan.'^'.$nama.'^'.$kategori;
		redirect('welcome/daftar_barang_perlengkapan/'.$link);	
	}
	
	public function search_barang_inventaris(){
		$nama = $this->input->post('nama');
		$urutan = $this->input->post('urutan');
		$kategori = $this->input->post('kategori');
		$link = $urutan.'^'.$nama.'^'.$kategori;
		redirect('welcome/daftar_barang_inventaris/'.$link);	
	}
	
	public function search_peralatan(){
		$cabang = $this->input->post('cabang'); 
		$nama = $this->input->post('nama');
		$urutan = $this->input->post('urutan');
		$kategori = $this->input->post('kategori');
		$link = $cabang.'^'.$urutan.'^'.$nama.'^'.$kategori.'';
		redirect('welcome/peralatan/'.$link);	
	}
	
	public function search_inventaris(){
		$cabang = $this->input->post('cabang'); 
		$nama = $this->input->post('nama');
		$urutan = $this->input->post('urutan');
		$kategori = $this->input->post('kategori');
		$link = $cabang.'^'.$urutan.'^'.$nama.'^'.$kategori.'';
		redirect('welcome/inventaris/'.$link);	
	}
	
	public function search_stok(){
		$cabang = $this->input->post('cabang');
		$code = $this->input->post('code');
		$nama = $this->input->post('nama');
		$urutan = $this->input->post('urutan');
		$kategori = $this->input->post('kategori');
		$merek = $this->input->post('merek');
		$tipe = $this->input->post('tipe');
		$link = $urutan.'^'.$code.'^'.$nama.'^'.$kategori.'^'.$cabang.'^'.$merek.'^'.$tipe;
		redirect('welcome/stok/'.$link);	
	}
	
	public function search_slip_gaji(){
		$cabang = $this->input->post('cabang');
		$tgl_awal = strtotime($this->input->post('tgl_awal'));
		$tgl_akhir = strtotime($this->input->post('tgl_akhir'));
		$link = $cabang.'^'.$tgl_awal.'^'.$tgl_akhir;
		redirect('welcome/slip_gaji/'.$link);	
	}
	
	public function search_barang(){
		$code = $this->input->post('code');
		$nama = $this->input->post('nama');
		$urutan = $this->input->post('urutan');
		$kategori = $this->input->post('kategori');
		$merek = $this->input->post('merek');
		$tipe = $this->input->post('tipe');
		$link = $urutan.'^'.$code.'^'.$nama.'^'.$kategori.'^'.$merek.'^'.$tipe;
		redirect('welcome/daftar_barang/'.$link);	
	}
	
	public function search_barang_nonkulak(){
		$code = $this->input->post('code');
		$nama = $this->input->post('nama');
		$urutan = $this->input->post('urutan');
		$kategori = $this->input->post('kategori');
		$link = $urutan.'^'.$code.'^'.$nama.'^'.$kategori;
		redirect('welcome/daftar_barang_nonkulak/'.$link);	
	}
	
	public function tambah_barang()
	{
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$status = $this->input->post('status');
		$this->form_validation->set_rules('code','Code','required|is_unique[item.code]');
		if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('message','<b>Notification</b> menu code sudah ada, ganti yang lain');
			redirect($this->agent->referrer());
		}	
		$code = strtoupper($this->input->post('code'));
		$data = array(
			'code' => $code,
			'nama' => $this->input->post('nama'),
			'kategori' => $this->input->post('kategori'),
			'merek' => $this->input->post('merek'),
			'tipe' => $this->input->post('tipe'),
			'satuan' => $this->input->post('satuan'),
			
		);
		if(!empty($status)){
			$data['status'] = 1;
		}
		$ins = $this->mymodel->insertItem('item',$data);
		$id = $this->db->insert_id();
		
		$data_item = array(
			'id_item' => $id,
			'harga_pokok' => $this->input->post('harga_pokok'),
			'waktu' => date('Y-m-d'),
		);
		$ins = $this->mymodel->insertItem('harga_item',$data_item);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> menu sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> menu tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_barang_perlengkapan()
	{
		$data = array(
			'nama' => $this->input->post('nama'),
			'kategori' => $this->input->post('kategori'),
			'tipe' => $this->input->post('tipe'),
		);
			
		$ins = $this->mymodel->insertItem('perlengkapan',$data);
		
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> perlengkapan sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> perlengkapan tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_barang_inventaris()
	{
		$data = array(
			'nama' => $this->input->post('nama'),
			'kategori' => $this->input->post('kategori'),
			'tipe' => $this->input->post('tipe'),
		);
			
		$ins = $this->mymodel->insertItem('perlengkapan',$data);
		
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> Iventaris sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> Invetaris tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_barang($id)
	{
		
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$id_cabang = $this->mymodel->getIdCabang($username);
		
		$code = strtoupper($this->input->post('code'));
		$this->db->where('code',$code);
		$data_result = $this->db->get('item')->num_rows();
		
		$data = array(
			'nama' => $this->input->post('nama'),
			'kategori' => $this->input->post('kategori'),
			'merek' => $this->input->post('merek'),
			'tipe' => $this->input->post('tipe'),
			'satuan' => $this->input->post('satuan'),
		);
		
		if($data_result != 1){
			$data['code'] = $code;
		}
		
		$ins = $this->mymodel->updateItem('item',$data,array('id' => $id));
		if($ins){
		
		$this->session->set_flashdata('message','<b>Notification</b> data sudah ditambahkan');
		redirect($this->agent->referrer());
			
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_barang_perlengkapan($id)
	{
		$data = array(
			'nama' => $this->input->post('nama'),
			'kategori' => $this->input->post('kategori'),
		);
		
		$ins = $this->mymodel->updateItem('perlengkapan',$data,array('id' => $id));
		if($ins){
		
		$this->session->set_flashdata('message','<b>Notification</b> perlengkapan sudah diubah');
		redirect($this->agent->referrer());
			
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> perlengkapan tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_barang_inventaris($id)
	{
		$data = array(
			'nama' => $this->input->post('nama'),
			'kategori' => $this->input->post('kategori'),
		);
		
		$ins = $this->mymodel->updateItem('perlengkapan',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> inventaris sudah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> inventaris tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_kategori($id){
		$category = $this->input->post('kategori');
		$url = $this->input->post('url');
		if($url == 'kategori_perlengkapan'){
			$tipe = 1;
		} elseif($url == 'kategori_inventaris') {
			$tipe = 2;
		} else {
			$tipe = 0;
		}
		$data = array(
			'kategori' => $category,
			'tipe' => $tipe,		 
		);
		$ins = $this->mymodel->updateItem('kategori_item',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> kategori telah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> kategori tidak dapat dirubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function kategori()
	{
		$this->db->where('tipe',0);
		$data_result = $this->db->get('kategori_item')->num_rows();
		$config['base_url'] = base_url().'index.php/welcome/kategori';
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$from = $this->uri->segment(3);
		$this->pagination->initialize($config);	
		$this->db->where('tipe',0);
		$query = $this->db->get('kategori_item',$config['per_page'],$from)->result();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function kategori_perlengkapan()
	{
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$nama = $t[0];
		} else {
			$nama = '';
		}
		$this->db->where('tipe',1);
		if(!empty($nama)) $this->db->like('kategori', $nama);
		$data_result = $this->db->get('kategori_item')->num_rows();
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/kategori_perlengkapan/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/kategori_perlengkapan/';
		}	
		$this->pagination->initialize($config);
		$this->db->where('tipe',1);
		if(!empty($nama)) $this->db->like('kategori', $nama);
		$query = $this->db->get('kategori_item',$config['per_page'],$from)->result();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_kategori_perlengkapan()
	{	
		$nama = $this->input->post('nama');

		$link = $nama.'^';
		redirect('welcome/kategori_perlengkapan/'.$link);
	}
	
	public function search_kategori_inventaris()
	{	
		$nama = $this->input->post('nama');

		$link = $nama.'^';
		redirect('welcome/kategori_inventaris/'.$link);
	}
	
	public function kategori_inventaris()
	{	
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$nama = $t[0];
		} else {
			$nama = '';
		}
		$this->db->where('tipe',2);
		if(!empty($nama)) $this->db->like('kategori', $nama);
		$data_result = $this->db->get('kategori_item')->num_rows();
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/kategori_perlengkapan/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/kategori_perlengkapan/';
		}	
		$this->pagination->initialize($config);
		$this->db->where('tipe',2);
		if(!empty($nama)) $this->db->like('kategori', $nama);
		$query = $this->db->get('kategori_item',$config['per_page'],$from)->result();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function supplier()
	{
		$data_result = $this->db->get('supplier')->num_rows();
		$config['base_url'] = base_url().'index.php/welcome/supplier';
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$from = $this->uri->segment(3);
		$this->pagination->initialize($config);	
		$query = $this->db->get('supplier',$config['per_page'],$from)->result();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function tambah_kategori(){
		$this->form_validation->set_rules('kategori','Kategori','required|is_unique[kategori_item.kategori]');
		if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('message','<b>Notification</b> kategori sudah ada');
			redirect($this->agent->referrer());
		}
		$category = $this->input->post('kategori');
		$url = $this->input->post('url');
		if($url == 'kategori_perlengkapan'){
			$tipe = 1;
		} elseif($url == 'kategori_inventaris') {
			$tipe = 2;
		} else {
			$tipe = 0;
		}
		$data = array(
			'kategori' => $category,
			'tipe' => $tipe,
		);
		$ins = $this->mymodel->insertItem('kategori_item',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> kategori telah disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> kategori tidak dapat tersimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_supplier(){
		$this->form_validation->set_rules('supplier','Supplier','required|is_unique[supplier.supplier]');
		if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('message','<b>Notification</b> supplier sudah ada');
			redirect($this->agent->referrer());
		}	
		$category = $this->input->post('supplier');
		$ins = $this->mymodel->insertItem('supplier',array('supplier' => $category));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> Supplier telah disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> Supplier tidak dapat tersimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function stok(){
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$id_cabang = $this->mymodel->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$urutan = (int)$t[0];
			$code = $t[1];
			$name = $t[2];
			$name = str_replace("%20"," ",$name);
			$category = $t[3];
			$category = str_replace("%20"," ",$category);
			$cabang = (int)$t[4];
			$merek = $t[5];
			$merek = str_replace("%20"," ",$merek);
			$tipe = $t[6];
			$tipe = str_replace("%20"," ",$tipe);
		} else {
			$code = '';
			$category = '';
			$name = '';
			$merek = '';
			$tipe = '';
			$cabang = $id_username;
		}
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($code)) $this->db->like('code',$code);
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name);
		if(!empty($merek)) $this->db->like('merek',$merek);
		if(!empty($tipe)) $this->db->like('tipe',$tipe);
		$this->db->where('status',0);
		$data_result = $this->db->get('item')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/stok/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/stok/';
		}
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		if(!empty($urutan)){
			if($urutan == 1){
				$this->db->order_by("nama", "asc");
			} elseif($urutan == 2){
				$this->db->order_by("kategori", "desc");
			} 
		}
		if(!empty($code)) $this->db->like('code',$code);
		if(!empty($category)) $this->db->where('kategori',$category);
		if(!empty($name)) $this->db->like('nama',$name);
		if(!empty($merek)) $this->db->like('merek',$merek);
		if(!empty($tipe)) $this->db->like('tipe',$tipe);
		$this->db->where('status',0);
		$query = $this->db->get('item',$config['per_page'],$from)->result_array();	
		if(!empty($urutan)) $data['urutan'] = $urutan;
		$data['data'] = $query;
		
		$this->load->view('index',$data);
	}
	
	public function stok_opname()
	{	
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$tanggal = (int)$t[1];
			$nama = $t[2];
			$nama = str_replace("%20"," ",$nama);
			$kategori = $t[3];
		} else {
			$cabang = 0;
			$nama = '';
			$kategori = '';
		}
		$this->load->library('pagination');
		$this->db->like('nama', $nama);
		if(!empty($kategori))$this->db->where('kategori', $kategori);
		$this->db->where('status',0);
		$data_result = $this->db->get('item')->num_rows();
		
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/stok_opname/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/stok_opname/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 20;
		$this->pagination->initialize($config);
		$this->db->like('nama', $nama);
		if(!empty($kategori))$this->db->where('kategori', $kategori);
		$this->db->where('status',0);
		$query = $this->db->get('item',$config['per_page'],$from)->result_array();
		
		$data['data'] = $query;
		if(!empty($cabang)) $data['cabang'] = $cabang;
		if(!empty($tanggal)) $data['tanggal'] = $tanggal;
		$this->load->view('index',$data);
	}
	
	public function stok_opname_harian()
	{	
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$tanggal = (int)$t[1];
			$nama = $t[2];
			$nama = str_replace("%20"," ",$nama);
			$kategori = $t[3];
		} else {
			$cabang = 0;
			$nama = '';
			$kategori = '';
		}
		$this->load->library('pagination');
		$this->db->like('nama', $nama);
		if(!empty($kategori))$this->db->where('kategori', $kategori);
		$this->db->where('status',0);
		$data_result = $this->db->get('item')->num_rows();
		
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/stok_opname_harian/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/stok_opname_harian/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 20;
		$this->pagination->initialize($config);
		$this->db->like('nama', $nama);
		if(!empty($kategori))$this->db->where('kategori', $kategori);
		$this->db->where('status',0);
		$query = $this->db->get('item',$config['per_page'],$from)->result_array();
		
		$data['data'] = $query;
		if(!empty($cabang)) $data['cabang'] = $cabang;
		if(!empty($tanggal)) $data['tanggal'] = $tanggal;
		$this->load->view('index',$data);
	}
	
	public function search_stok_opname()
	{	
		$cabang = $this->input->post('cabang');
		$tanggal = strtotime($this->input->post('tanggal'));
		$nama = $this->input->post('nama');
		$kategori = $this->input->post('kategori');
		$link = $cabang.'^'.$tanggal.'^'.$nama.'^'.$kategori;
		redirect('welcome/stok_opname/'.$link);	
	}
	
	public function search_stok_opname_harian()
	{	$cabang = $this->input->post('cabang');
		$tanggal = strtotime($this->input->post('tanggal'));
		$nama = $this->input->post('nama');
		$kategori = $this->input->post('kategori');
		$link = $cabang.'^'.$tanggal.'^'.$nama.'^'.$kategori;
		redirect('welcome/stok_opname_harian/'.$link);	
	}
	
	public function stok_laporan()
	{	
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$tanggal = (int)$t[1];
			$jumlah = (int)$t[2];
			$nama = $t[3];
			$nama = str_replace("%20"," ",$nama);
			$kategori = $t[4];
			$merek = $t[5];
			$merek = str_replace("%20"," ",$merek);
			$tipe = $t[6];
			$tipe = str_replace("%20"," ",$tipe);
		} else {
			$cabang = 0;
			$jumlah = 10;
		}
		$this->load->library('pagination');
		if(!empty($nama)) $this->db->like('nama',$nama);
		if(!empty($merek)) $this->db->like('merek',$merek);
		if(!empty($tipe)) $this->db->like('tipe',$tipe);
		if(!empty($kategori)) $this->db->where('kategori',$kategori);
		$this->db->where('status',0);
		$data_result = $this->db->get('item')->num_rows();
		
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/stok_laporan/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/stok_laporan/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = $jumlah;
		$this->pagination->initialize($config);
		if(!empty($nama)) $this->db->like('nama',$nama);
		if(!empty($kategori)) $this->db->where('kategori',$kategori);
		if(!empty($merek)) $this->db->like('merek',$merek);
		if(!empty($tipe)) $this->db->like('tipe',$tipe);
		$this->db->where('status',0);
		$query = $this->db->get('item',$config['per_page'],$from)->result_array();
		
		$data['data'] = $query;
		if(!empty($cabang)) $data['cabang'] = $cabang;
		if(!empty($tanggal)) $data['tanggal'] = $tanggal;
		$this->load->view('index',$data);
	}
	
	public function search_stok_laporan()
	{	
		$cabang = $this->input->post('cabang');
		$tanggal = strtotime($this->input->post('tanggal'));
		$jumlah = $this->input->post('jumlah');
		$nama = $this->input->post('nama');
		$kategori = $this->input->post('kategori');
		$merek = $this->input->post('merek');
		$tipe = $this->input->post('tipe');
		$link = $cabang.'^'.$tanggal.'^'.$jumlah.'^'.$nama.'^'.$kategori.'^'.$merek.'^'.$tipe;
		redirect('welcome/stok_laporan/'.$link);	
	}
	
	public function mutasi()
	{	
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$check_admin = $this->mymodel->isAdmin($username);
		$id_cabang = $this->mymodel->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$month = (int)$t[1];
			$year = (int)$t[2];
			$tanggal = (int)$t[3];
		} else {
			$cabang = 0;
		}
		
		$date = $this->input->post('date');
		if(empty($date)) $date = date('Y-m-d');
		$date = new DateTime($date);
		$timestamp = $date->getTimeStamp();
		$this->load->library('pagination');
		
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(waktu)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(waktu)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		if(!empty($cabang) && $cabang != 0) $this->db->where('id_cabang',$cabang);
		$data_result = $this->db->get('mutasi')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/mutasi/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/mutasi/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		if(!empty($cabang) && $cabang != 0) $this->db->where('id_cabang',$cabang);
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(waktu)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(waktu)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		$query = $this->db->get('mutasi',$config['per_page'],$from)->result_array();
		if(!empty($tanggal)) $data['tanggal'] = $tanggal;
		if(!empty($month)) $data['month'] = $month;
		if(!empty($year)) $data['year'] = $year;
		if(!empty($cabang)) $data['cabang'] = $cabang;
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_mutasi(){
		$cabang = $this->input->post('cabang');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$tanggal = strtotime($this->input->post('tanggal'));
		$link = $cabang.'^'.$month.'^'.$year.'^'.$tanggal;
		redirect('welcome/mutasi/'.$link);	
	}
	
	public function tambah_stok(){
		$id_item = $this->input->post('id_item');
		$cabang = $this->input->post('cabang');
		$stok = $this->input->post('stok');
		$this->db->where('id_item', $id_item);
		$harga_item = $this->db->get('harga_item')->result_array();
		foreach($harga_item as $h){
			$id_harga_item = $h['id'];
		}
		$data = array(
			'id_item' => $id_item,
			'id_cabang' => $cabang,
			'id_harga' => $id_harga_item,
			'stok' => $stok,
		);
		$ins = $this->mymodel->insertItem('stok',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>data stok telah disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>data stok tidak dapat tersimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function harga_pokok_baru(){
		$id_item = $this->input->post('id_item');
		$waktu = $this->input->post('waktu');
		
		$data = array(
			'id_item' => $id_item,
			'harga_pokok' => $this->input->post('harga_pokok'),
			'waktu' => $waktu,
		);
		$ins = $this->mymodel->insertItem('harga_item',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>data stok telah disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>data stok tidak dapat tersimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_stok_laporan(){
		
		$id_cabang = $this->input->post('id_cabang');
		$waktu = $this->input->post('waktu');
		$jumlah_data = $this->input->post('jumlah_data');
		
		for($x=1;$x<=$jumlah_data;$x++){
			$id_item_input = 'id_item'.$x;
			$stok_input = 'stok'.$x;
			$id_stok_laporan = 'id_stok_laporan'.$x; 
			
			$id_stok = $this->input->post($id_stok_laporan);
			$this->db->where('id',$id_stok);
			$stok = $this->db->get('stok_laporan')->num_rows();
			
			$data = array(
				'id_item' => $this->input->post($id_item_input),
				'id_cabang' => $id_cabang,
				'waktu' => $waktu,
				'stok' => $this->input->post($stok_input),
			);
		
			if($stok > 0){
				$ins = $this->mymodel->updateItem('stok_laporan',$data, array('id' => $id_stok));
			} else {
				$ins = $this->mymodel->insertItem('stok_laporan',$data);
			}
		}
		
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>data laporan stok telah disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>data laporan stok tidak dapat tersimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_stok_laporan($id_item){
		$id_stok_laporan = $this->input->post('id_stok_laporan'); 
		$id_cabang = $this->input->post('id_cabang');
		$stok = $this->input->post('stok');
		$waktu = $this->input->post('waktu');
		
		$data = array(
			'waktu' => $waktu,
			'stok' => $stok,
		);
		
		$ins = $this->mymodel->updateItem('stok_laporan',$data, array('id' => $id_stok_laporan));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>data laporan stok telah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>data laporan stok tidak dapat terubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_stok($id){
		$stok = $this->input->post('stok');
		$item = array(
			'stok' => $stok,
		);	
		$ins = $this->mymodel->updateItem('stok',$item, array('id' => $id));	
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Stok dirubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Stok tidak dapat ditambahkan');
			redirect($this->agent->referrer());
		}
	}
	
	public function proses_stok(){
		
		$this->load->view('index');
	}
	
	public function tambah_proses_stok(){
		$id_item = $this->input->post('id_item');
		$from_cabang = $this->input->post('from_cabang');
		$to_cabang = $this->input->post('to_cabang');
		$stok = $this->input->post('stok');
		$tanggal = $this->input->post('tanggal');
		$status = $this->input->post('status');
		$data = array(
			'id_item' => $id_item,
			'stok' => $stok,
			'status' => $status,
			'from_cabang' => $from_cabang,
			'tanggal' => $tanggal,
			'to_cabang' => $to_cabang,
		);
		$ins = $this->mymodel->insertItem('proses_stok',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>data kirim stok telah disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>data kirim stok tidak dapat tersimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_total_transaksi(){
		$username = $this->session->userdata('username');
		$cabang = $this->input->post('cabang');
		if(empty($cabang)){
			$id_cabang = $this->mymodel->getIdCabang($username);
		} else {
			$id_cabang = $cabang;
		}	
		if($this->input->post('tgl_order')){
			$tgl_order = strtotime($this->input->post('tgl_order'));
			$tgl_instalasi = strtotime($this->input->post('tgl_instalasi'));
			$telepon = $this->input->post('telepon');
			$toko = $this->input->post('toko');
			$alamat = $this->input->post('alamat');
			$kota = $this->input->post('kota');
			$deskripsi = $tgl_order.','.$tgl_instalasi.','.$telepon.','.$toko.','.$alamat.','.$kota;
		} else {
			$deskripsi = "";
		}
		
		$tipe = $this->input->post('tipe');
		$id = $this->input->post('cabang');
		if(empty($id)){
			$id = $id_cabang; 
		}
		$check = $this->mymodel->getId($tipe);
		if($check){
			$this->session->set_flashdata('message','<b>Notification</b> transaksi sudah ditambahkan');
			redirect($this->agent->referrer());		
		} else {
			$nota = $this->input->post('nota');
			if(empty($nota)){
				$this->session->set_flashdata('message','<b>Notification</b> inputan pembeli belum diisi');
				redirect($this->agent->referrer());
			} else {
				$data = array(
					'nota' => $nota,
					'tipe' => $tipe,
					'cabang' => $id,
					'waktu' => $this->input->post('tgl_order'),
					'deskripsi' => $deskripsi,
				);
				$ins = $this->mymodel->insertItem('total_transaksi',$data);
				if($ins){
					redirect('welcome/transaksi/'.$this->db->insert_id());
				} else {
					redirect($this->agent->referrer());
				}
			}
		}
	}
	
	public function tambah_service(){
		if($this->input->post('tgl_masuk')){
			$telepon = $this->input->post('telepon');
			$alamat = $this->input->post('alamat');
			$nama = $this->input->post('nama');
			$nota = $this->input->post('nota');
			$jenis = $this->input->post('jenis');
			$garansi = $this->input->post('garansi');
			$perlengkapan = $this->input->post('perlengkapan');
			$keluhan = $this->input->post('keluhan');
			$kondisi = $this->input->post('kondisi');
			$deskripsi = $nama.','.$telepon.','.$alamat.','.$jenis.','.$garansi.','.$perlengkapan.','.$keluhan;
		} else {
			$deskripsi = "";
		}
		
		$tipe = $this->input->post('tipe');
		$id = $this->mymodel->getIduser();
		$check = $this->mymodel->getId($tipe);
		if($check){
			$this->session->set_flashdata('message','<b>Notification</b> transaksi sudah ditambahkan');
			redirect($this->agent->referrer());		
		} else {
			$nota = $this->input->post('nota');
			if(empty($nota)){
				$this->session->set_flashdata('message','<b>Notification</b> inputan pembeli belum diisi');
				redirect($this->agent->referrer());
			} else {
				$data = array(
					'nota' => $nota,
					'tipe' => $tipe,
					'status' => $kondisi,
					'cabang' => $this->input->post('cabang'),
					'waktu' => date("Y-m-d"),
					'deskripsi' => $deskripsi,
				);
				$ins = $this->mymodel->insertItem('total_transaksi',$data);
				if($ins){
					redirect($this->agent->referrer());
				} else {
					redirect($this->agent->referrer());
				}
			}
		}
	}
	
	public function tambah_sub_transaksi(){
		$code = strtoupper($this->input->post('code'));
		$unit = $this->input->post('unit');
		$id_cabang = $this->input->post('id_cabang');
		$id_total_transaksi = $this->input->post('id_total_transaksi');
		$pays = $this->mymodel->getCode($code);
		$id_page = $this->input->post('id');
	
		$this->db->where('id_total_transaksi', $id_total_transaksi);
		$this->db->where('id_item', $pays[0]['id']);
		$check = $this->db->get('sub_transaksi')->num_rows();
		if( empty($pays)){
			$this->session->set_flashdata('message','<b>Notification</b> menu code tidak ada');
			redirect($this->agent->referrer());
		} elseif($check === 1) {
			$this->session->set_flashdata('message','<b>Notification</b> menu code telah ditambahkan');
			redirect($this->agent->referrer());	
		} else {
			foreach($pays as $pay)
			$id_item = $pay['id'];
			$this->db->where('id_item', $id_item);
			$harga_item = $this->db->get('harga_item')->result_array();
			
			foreach($harga_item as $h){
				$harga_pokok = $h['harga_pokok'];
			}
			if(empty($unit)){
				$unit = 1;
			}

			$data = array(
				'id_item' => $id_item,
				'unit' => $unit,
				'id_total_transaksi ' => $id_total_transaksi,
				'harga_jual ' => 0,
			);
			$ins = $this->mymodel->insertItem('sub_transaksi',$data);
			if($ins){
			$this->db->where('id_total_transaksi', $id_total_transaksi);
			$total_raw = $this->db->get('sub_transaksi')->result_array();
			$total_raw = $total_raw[0];
			$result_avg = $this->db->query('select SUM(harga_jual) from sub_transaksi where id_total_transaksi LIKE '.$id_total_transaksi.'')->result_array();
			foreach ($result_avg as $avg){
				$avg_harga_jual = $avg['SUM(harga_jual)'];
			}
			$this->db->where('id',$id_total_transaksi);
			$get = $this->db->get('total_transaksi')->result_array();
			$get = $get[0];
			
			$data = array(
				'total_harga' => $avg_harga_jual,
				'waktu' => $get['waktu'],
			);	
			$ins = $this->mymodel->updateItem('total_transaksi',$data,array('id' => $id_total_transaksi));		
				$this->session->set_flashdata('message','<b>Notification</b> order telah ditambah');
				redirect($this->agent->referrer());
			} else {
				$this->session->set_flashdata('message','<b>Notification</b> order tidak dapat disimpan');
				redirect($this->agent->referrer());
			}
		}
	}
	
	public function tambah_penjualan(){
		$code = strtoupper($this->input->post('code'));
		$unit = $this->input->post('unit');
		$id_pesan = $this->input->post('id_pesan');
		$pays = $this->mymodel->getCode($code);
		$data = array(
				'id_item' => $pays[0]['id'],
				'unit' => $unit,
				'id_total_transaksi ' => $id_pesan,
				'harga_jual ' => 0,
		);
		$ins = $this->mymodel->insertItem('sub_transaksi',$data);
		
		if($ins){	
			$this->session->set_flashdata('message','<b>Notification</b> order telah ditambah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> order tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_sub_service(){
		$id_pesan = $this->input->post('id_pesan');
		$biaya = $this->input->post('service');
		$data = array(
				'id_item' => 2,
				'unit' => 1,
				'id_total_transaksi ' => $id_pesan,
				'harga_jual ' => $biaya,
		);
		$ins = $this->mymodel->insertItem('sub_transaksi',$data);
		
		if($ins){	
			$this->session->set_flashdata('message','<b>Notification</b> order telah ditambah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> order tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	
	public function proses_closing($id){	
		$tgl = date('Y-m-d');
		$data = array(
			'tgl_closing' => $tgl
		);
		
		$ins = $this->mymodel->updateItem('pesan',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> data telah disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function hapus_proses_closing($id){	
		$tgl = 0000-00-00;
		
		$data = array(
			'tgl_closing' => $tgl
		);
		
		$ins = $this->mymodel->updateItem('pesan',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b> data telah disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	
	public function kas()
	{
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$check_admin = $this->mymodel->isAdmin($username);
		$id_cabang = $this->mymodel->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$month = (int)$t[1];
			$year = (int)$t[2];
			$tanggal = (int)$t[3];
		} else {
			$cabang = 0;
		}
		
		$this->db->where('YEAR(waktu)',$year);
		$this->db->where('MONTH(waktu)',$month);
		$this->db->where('id_cabang',$cabang);
		$cek = $this->db->get('total_kas')->result_array();
		
		if(empty($cek)){
			$saldo_array = array();
			$first_date = strtotime('2019-01-01');
			$second_date = mktime(0, 0, 0, $month, 1, $year);
			if($check_admin){
				$this->db->where('id_cabang', $cabang);
			} else {
				$this->db->where('id_cabang', $id_cabang);
			}
			if($cabang != 3){
				$this->db->where('waktu >=', date('Y-m-d', $first_date));
				$this->db->where('waktu <', date('Y-m-d',$second_date));
			}
			if($cabang == 3){
				$this->db->where('YEAR(waktu)',date('Y', $year));
				$this->db->where('MONTH(waktu)',date('m', $month));
			}	

			$saldo = $this->db->get('kas')->result_array();
			foreach($saldo as $s){
				if($s['status'] == 2){
					$saldo_array[] = $s['saldo'];
				} else {
					$saldo_array[] = $s['saldo'] * -1;
				}
			}
			
			$total_saldo = array_sum($saldo_array);
			$saldo_awal_bulan = $total_saldo;
			
			if($month == date('m') && $year == date('Y')){
				$data = array(
					'id_cabang' => $cabang,
					'saldo' => $saldo_awal_bulan,
					'waktu' => date('Y-m-d'),
				);
				$add = $this->mymodel->insertItem('total_kas',$data);
			}
		} else {
			$id_saldo = $cek[0]['id'];
			$saldo_awal_bulan = $cek[0]['saldo'];
		}
		
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(waktu)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(waktu)',$month);
		
		
		if(!empty($cabang) && $cabang != 0 && empty($check_admin)) {
			$this->db->where('id_cabang',$id_cabang);
		}	
		if($check_admin) {
			$this->db->where('id_cabang',$cabang);
		}
		$this->db->order_by("waktu", "asc");
		$this->db->order_by('status', 'desc');
		$data_result = $this->db->get('kas')->num_rows();

		if(!empty($cabang) && $cabang != 0 && empty($check_admin)){
			$this->db->where('id_cabang',$id_cabang);
		}	
		
		if($check_admin) {
			$this->db->where('id_cabang',$cabang);
		}
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(waktu)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(waktu)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		$this->db->order_by("waktu", "asc");
		$this->db->order_by('status', 'desc');
		$query = $this->db->get('kas')->result_array();
		
		if(!empty($tanggal)) $data['tanggal'] = $tanggal;
		if(!empty($month)) $data['month'] = $month;
		if(!empty($year)) $data['year'] = $year;
		if(!empty($cabang)) $data['cabang'] = $cabang;
		
		$data['saldo_awal_bulan'] = $saldo_awal_bulan;
		$data['data'] = $query;
		if(!empty($id_saldo)) $data['id_saldo'] = $id_saldo;
		$this->load->view('index',$data);
	}
	
	public function laporan_kas()
	{
		$tanggal = $this->uri->segment(3);
		
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$check_admin = $this->mymodel->isAdmin($username);
		$id_cabang = $this->mymodel->getIdCabang($username);
		
		if(empty($tanggal)){
			$tanggal = strtotime(date('Y-m-d'));
		}		
		if(!empty($tanggal)) $data['tanggal'] = $tanggal;
		$this->load->view('index',$data);
	}
	
	public function laporan_hutang()
	{
		$this->load->view('index');
	}
	
	
	public function laporan()
	{
		$this->load->view('index');
	}
	
	public function search_laporan_kas(){
		
		$tanggal = strtotime($this->input->post('tanggal'));
		$link = $tanggal;
		redirect('welcome/laporan_kas/'.$link);	
	}
	
	public function search_item_mutasi(){
		$id_mutasi = $this->input->post('id_mutasi');
		$search = $this->input->post('search');
		redirect('welcome/tambah_item_mutasi/'.$id_mutasi.'/'.$search);	
	}
	
	public function search_item_masuk(){
		$id_item_masuk = $this->input->post('id_item_masuk');
		$search = $this->input->post('search');
		redirect('welcome/tambah_item_masuk/'.$id_item_masuk.'/'.$search);	
	}
	
	public function search_item_keluar(){
		$id_item_keluar = $this->input->post('id_item_keluar');
		$search = $this->input->post('search');
		redirect('welcome/tambah_item_keluar/'.$id_item_keluar.'/'.$search);	
	}
	
	public function search_penjualan(){
		$id_pesan = $this->input->post('id_pesan');
		$page = 'penjualan';
		$search = $this->input->post('search');
		redirect('welcome/'.$page.'/'.$id_pesan.'/'.$search);	
	}
	
	public function search_item_pembelian_stok(){
		$id_pembelian = $this->input->post('id_pembelian');
		$search = $this->input->post('search');
		redirect('welcome/tambah_pembelian_stok/'.$id_pembelian.'/'.$search);	
	}
	
	public function tambah_kas(){
		
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'id_cabang' => $this->input->post('id_cabang'),
			'deskripsi' => $this->input->post('deskripsi'),
			'status' => $this->input->post('status'),
			'saldo' => $this->input->post('saldo'),
		);
			
		$ins = $this->mymodel->insertItem('kas',$data);	
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Kas sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Kas tidak dapat ditambahkan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_mutasi(){
		
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'id_from' => $this->input->post('from'),
			'id_to' => $this->input->post('to'),
			'deskripsi' => $this->input->post('deskripsi'),
		);
			
		$ins = $this->mymodel->insertItem('mutasi',$data);	
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Mutasi sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Mutasi tidak dapat ditambahkan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_mutasi($id){
		
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'id_from' => $this->input->post('from'),
			'id_to' => $this->input->post('to'),
			'deskripsi' => $this->input->post('deskripsi'),
		);
			
		$ins = $this->mymodel->updateItem('mutasi',$data,array('id' => $id));	
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Mutasi sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Mutasi tidak dapat ditambahkan');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_stor(){
		$item = array(
			'waktu' => $this->input->post('waktu'),
			'tipe' => $this->input->post('tipe'),
		);
		
		$ins = $this->mymodel->insertItem('stor',$item);
		
		$id = $this->db->insert_id();
		
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'deskripsi' => $this->input->post('deskripsi'),
			'id_stor' => $id,
			'saldo' => $this->input->post('saldo'),
		);
		
		$data['id_cabang'] = $this->input->post('f_cabang');
		$data['status'] = 1;
		
		$f_ins = $this->mymodel->insertItem('kas',$data);
		
		$data['id_cabang'] = $this->input->post('t_cabang');
		$data['status'] = 2;
		
		$t_ins = $this->mymodel->insertItem('kas',$data);
		
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Stor kas sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Stor kas tidak dapat ditambahkan');
			redirect($this->agent->referrer());
		}
	}
	
	public function edit_stor(){
		$id_kredit = $this->input->post('id_kredit');
		$id_debit = $this->input->post('id_debit');
		$id_stor = $this->input->post('id_stor');
		
		$item = array(
			'waktu' => $this->input->post('waktu'),
			'tipe' =>  $this->input->post('tipe'),
		);
		
		$f_ins = $this->mymodel->updateItem('stor',$item, array('id' => $id_stor));
		
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'deskripsi' => $this->input->post('deskripsi'),
			'saldo' => $this->input->post('saldo'),
		);
		
		$data['id_cabang'] = $this->input->post('f_cabang');
		$data['status'] = 1;
		
		$f_ins = $this->mymodel->updateItem('kas',$data, array('id' => $id_kredit));
		
		$data['id_cabang'] = $this->input->post('t_cabang');
		$data['status'] = 2;
		
		$t_ins = $this->mymodel->updateItem('kas',$data, array('id' => $id_debit));
		
		if($t_ins){
			$this->session->set_flashdata('message','<b>Notification</b>Stor sudah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Stor tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function deleteStor($table,$id)
	{	
			$delete = $this->mymodel->deleteItem($table,array('id' => $id));
		if($delete >= 1){
			$delete = $this->mymodel->deleteItem('kas',array('id_stor' => $id));
			$this->session->set_flashdata('message','<b>Notification</b> data telah dihapus');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat dihapus');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_kas($id){
		
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'id_cabang' => $this->input->post('id_cabang'),
			'deskripsi' => $this->input->post('deskripsi'),
			'status' => $this->input->post('status'),
			'saldo' => $this->input->post('saldo'),
		);
			
		$ins = $this->mymodel->updateItem('kas',$data,array('id' => $id));	
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Kas sudah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Kas tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	
	
	public function search_kas(){
		
		$cabang = $this->input->post('cabang');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$tanggal = strtotime($this->input->post('tanggal'));
		$link = $cabang.'^'.$month.'^'.$year.'^'.$tanggal;
		redirect('welcome/kas/'.$link);	
	}
	
	public function stok_item_masuk()
	{
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$check_admin = $this->mymodel->isAdmin($username);
		$id_cabang = $this->mymodel->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$month = (int)$t[1];
			$year = (int)$t[2];
			$tanggal = (int)$t[3];
		} else {
			$cabang = 0;
		}
		
		$date = $this->input->post('date');
		if(empty($date)) $date = date('Y-m-d');
		$date = new DateTime($date);
		$timestamp = $date->getTimeStamp();
		$this->load->library('pagination');
		
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(waktu)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(waktu)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		if(!empty($cabang) && $cabang != 0) $this->db->where('id_cabang',$cabang);
		$this->db->where('tipe',0);
		$data_result = $this->db->get('stok_masuk')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/stok_item_masuk/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/stok_item_masuk/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		if(!empty($cabang) && $cabang != 0) $this->db->where('id_cabang',$cabang);
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(waktu)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(waktu)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		$this->db->where('tipe',0);
		$query = $this->db->get('stok_masuk',$config['per_page'],$from)->result_array();
		if(!empty($tanggal)) $data['tanggal'] = $tanggal;
		if(!empty($month)) $data['month'] = $month;
		if(!empty($year)) $data['year'] = $year;
		if(!empty($cabang)) $data['cabang'] = $cabang;
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function pembelian_stok()
	{
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$check_admin = $this->mymodel->isAdmin($username);
		$id_cabang = $this->mymodel->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$month = (int)$t[1];
			$year = (int)$t[2];
			$tanggal = (int)$t[3];
			$status = (int)$t[4];
			$desc = $t[5];
			$desc = str_replace("%20"," ",$desc);
			$supplier = $t[6];
		} else {
			$status = 5;
			$cabang = 0;
			$desc = '';
		}
		
		$date = $this->input->post('date');
		if(empty($date)) $date = date('Y-m-d');
		$date = new DateTime($date);
		$timestamp = $date->getTimeStamp();
		$this->load->library('pagination');
		
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(waktu)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(waktu)',$month);
		if($status != 5){
			$this->db->where('status',$status);
		}
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		if(!empty($supplier)) $this->db->where('supplier',$supplier);
		$this->db->where('tipe',1);
		$this->db->like('deskripsi',$desc);
		if(!empty($cabang) && $cabang != 0) $this->db->where('id_cabang',$cabang);
		$data_result = $this->db->get('stok_masuk')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/pembelian_stok/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/pembelian_stok/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		if(!empty($cabang) && $cabang != 0) $this->db->where('id_cabang',$cabang);
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(waktu)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(waktu)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		if($status != 5){
			$this->db->where('status',$status);
		}
		$this->db->order_by("waktu", "asc");
		$this->db->like('deskripsi',$desc);
		$this->db->where('tipe',1);
		if(!empty($supplier)) $this->db->where('supplier',$supplier);
		$query = $this->db->get('stok_masuk',$config['per_page'],$from)->result_array();
		if(!empty($tanggal)) $data['tanggal'] = $tanggal;
		if(!empty($month)) $data['month'] = $month;
		if(!empty($year)) $data['year'] = $year;
		if(!empty($cabang)) $data['cabang'] = $cabang;
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_stok_item_masuk(){
		$cabang = $this->input->post('cabang');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$tanggal = strtotime($this->input->post('tanggal'));
		$link = $cabang.'^'.$month.'^'.$year.'^'.$tanggal;
		redirect('welcome/stok_item_masuk/'.$link);	
	}
	
	public function search_pembelian_stok(){
		$cabang = $this->input->post('cabang');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$tanggal = strtotime($this->input->post('tanggal'));
		$status = $this->input->post('status');
		$desc = $this->input->post('desc');
		$supplier = $this->input->post('supplier');
		$link = $cabang.'^'.$month.'^'.$year.'^'.$tanggal.'^'.$status.'^'.$desc.'^'.$supplier;
		redirect('welcome/pembelian_stok/'.$link);	
	}
	
	public function stok_item_keluar()
	{
		$username = $this->session->userdata('username');
		$id_username = $this->mymodel->getIduser($username);
		$check_admin = $this->mymodel->isAdmin($username);
		$id_cabang = $this->mymodel->getIdCabang($username);
		
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$month = (int)$t[1];
			$year = (int)$t[2];
			$tanggal = (int)$t[3];
		} else {
			$cabang = 0;
		}
		
		$date = $this->input->post('date');
		if(empty($date)) $date = date('Y-m-d');
		$date = new DateTime($date);
		$timestamp = $date->getTimeStamp();
		$this->load->library('pagination');
		
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(waktu)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(waktu)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		
		if(!empty($cabang) && $cabang != 0) $this->db->where('id_cabang',$cabang);
		$data_result = $this->db->get('stok_keluar')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/stok_item_keluar/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/stok_item_keluar/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		if(!empty($cabang) && $cabang != 0) $this->db->where('id_cabang',$cabang);
		if(!empty($year) && $year != 0 && empty($tanggal))$this->db->where('YEAR(waktu)',$year);
		if(!empty($month) && $month != 0 && empty($tanggal))$this->db->where('MONTH(waktu)',$month);
		
		if(!empty($tanggal)){
			$this->db->where('YEAR(waktu)',date('Y', $tanggal));
			$this->db->where('MONTH(waktu)',date('m', $tanggal));
			$this->db->where('DAYOFMONTH(waktu)',date('d', $tanggal));
		}
		$this->db->order_by("waktu", "asc");
		$query = $this->db->get('stok_keluar',$config['per_page'],$from)->result_array();
		if(!empty($tanggal)) $data['tanggal'] = $tanggal;
		if(!empty($month)) $data['month'] = $month;
		if(!empty($year)) $data['year'] = $year;
		if(!empty($cabang)) $data['cabang'] = $cabang;
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_stok_item_keluar(){
		$cabang = $this->input->post('cabang');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$tanggal = strtotime($this->input->post('tanggal'));
		$link = $cabang.'^'.$month.'^'.$year.'^'.$tanggal;
		redirect('welcome/stok_item_keluar/'.$link);	
	}
	
	public function tbhitemkeluar()
	{
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'deskripsi' => $this->input->post('deskripsi'),
			'id_cabang' => $this->input->post('cabang'),
		);
		$ins = $this->mymodel->insertItem('stok_keluar',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Daftar Item masuk ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Daftar Item masuk ditambahkan tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubahitemkeluar($id)
	{
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'deskripsi' => $this->input->post('deskripsi'),
			'id_cabang' => $this->input->post('cabang'),
		);
		$ins = $this->mymodel->updateItem('stok_keluar',$data, array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Daftar Item keluar telah di ubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Daftar Item keluar ditambahkan tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubahitemmasuk($id)
	{
		$supplier = $this->input->post('supplier');
		$data = array(
			'waktu' => $this->input->post('waktu'),
			'deskripsi' => $this->input->post('deskripsi'),
			'id_cabang' => $this->input->post('cabang'),
		);
		$data['supplier'] = $supplier;
		$ins = $this->mymodel->updateItem('stok_masuk',$data, array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Daftar Item Masuk telah di ubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Daftar Item Masuk ditambahkan tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	
	public function slip_gaji()
	{
		$this->load->view('index');
	}
	
	public function export_excel()
	{
		$this->load->view('index');
	}

	public function export_laporan($tanggal = null)
	{
		$date = $this->input->post('tanggal');
		if($date){
			redirect('welcome/export_laporan/'.strtotime($date));
		}
		if($tanggal == null){
			$tanggal = strtotime(date('Y-m-d'));
		}
		$data['date'] = $tanggal;
		$this->load->view('export_laporan',$data);
	}
	
	public function export()
	{
		$cabang = $this->input->post('cabang');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		
		  include APPPATH.'third_party/PHPExcel/PHPExcel.php';           
		  $excel = new PHPExcel();
		  $excel->getProperties()->setCreator('My Notes Code')                 
			  ->setLastModifiedBy('My Notes Code')                 
			  ->setTitle("Data Order")                 
			  ->setSubject("Order")                 
			  ->setDescription("Laporan Semua Data Order")                 
			  ->setKeywords("Data Order");
		  
				   // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
			$style_col = array(
			  'font' => array('bold' => true), // Set font nya jadi bold
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			  ),
			  'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			  )
			);

			// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
			$style_row = array(
			  'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			  ),
			  'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			  )
			);

			$excel->setActiveSheetIndex(0)->setCellValue('A1', "Data Order"); // Set kolom A1 dengan tulisan "DATA SISWA"
			$excel->getActiveSheet()->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
		  

			  // Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A3', "NOREG");
		$excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(6);
		$excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE);  
		$excel->setActiveSheetIndex(0)->setCellValue('B3', "NAMA"); 
		$excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(6);
	  	$excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(TRUE);	
		$excel->setActiveSheetIndex(0)->setCellValue('C3', "NOMER HP");
		$excel->getActiveSheet()->getStyle('C3')->getFont()->setSize(6); 
		$excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(TRUE);  
		$excel->setActiveSheetIndex(0)->setCellValue('D3', "RENCANA ORDER");
		$excel->getActiveSheet()->getStyle('D3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(TRUE);  
		$excel->setActiveSheetIndex(0)->setCellValue('E3', "DEADLINE");
		$excel->getActiveSheet()->getStyle('E3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(TRUE);  
		$excel->setActiveSheetIndex(0)->setCellValue('F3', "SUMBERDATA");
		$excel->getActiveSheet()->getStyle('F3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(TRUE);  
		$excel->setActiveSheetIndex(0)->setCellValue('G3', "STATUS");
		$excel->getActiveSheet()->getStyle('G3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(TRUE);  
		$excel->setActiveSheetIndex(0)->setCellValue('H3', "TGL.STATUS");
		$excel->getActiveSheet()->getStyle('H3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(TRUE);  
		$excel->setActiveSheetIndex(0)->setCellValue('I3', "KET.STATUS");
		$excel->getActiveSheet()->getStyle('I3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('I3')->getFont()->setBold(TRUE);  
		$excel->setActiveSheetIndex(0)->setCellValue('J3', "NO NOTA");
		$excel->getActiveSheet()->getStyle('J3')->getFont()->setSize(6);
		$excel->getActiveSheet()->getStyle('J3')->getFont()->setBold(TRUE);
		$excel->setActiveSheetIndex(0)->setCellValue('K3', "KET. BAYAR");
		$excel->getActiveSheet()->getStyle('K3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('K3')->getFont()->setBold(TRUE);  	
		$excel->setActiveSheetIndex(0)->setCellValue('L3', "NOMINAL");
		$excel->getActiveSheet()->getStyle('L3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('L3')->getFont()->setBold(TRUE);
		$excel->setActiveSheetIndex(0)->setCellValue('M3', "BAYAR");
		$excel->getActiveSheet()->getStyle('M3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('M3')->getFont()->setBold(TRUE);  
		$excel->setActiveSheetIndex(0)->setCellValue('N3', "MKT");
		$excel->getActiveSheet()->getStyle('N3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('N3')->getFont()->setBold(TRUE);
		$excel->setActiveSheetIndex(0)->setCellValue('O3', "TEKNISI");
		$excel->getActiveSheet()->getStyle('O3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('O3')->getFont()->setBold(TRUE);  
		$excel->setActiveSheetIndex(0)->setCellValue('P3', "CLOSING");
		$excel->getActiveSheet()->getStyle('P3')->getFont()->setSize(6);  
		$excel->getActiveSheet()->getStyle('P3')->getFont()->setBold(TRUE);

		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('L3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('M3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('N3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('O3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('P3')->applyFromArray($style_col);
		
		$numrow = 4;
		
		if($cabang != 'SEMUA') $this->db->where('id_cabang', $cabang);
		if($month) $this->db->where('MONTH(tgl_masuk)', $month);
		if($year) $this->db->where('YEAR(tgl_masuk)', $year);
			
		$this->db->order_by("tgl_masuk", "asc");
		$orders = $this->db->get('pesan')->result_array();
		foreach($orders as $o){
			$id_bukutamu = $o['id_bukutamu'];
			$status = $o['status'];
			$tgl_masuk = strtotime($o['tgl_masuk']);
			$seles = $o['seles'];
			$teknisi = $o['teknisi'];
			$nota = $o['nota'];
			$keterangan = $o['keterangan'];
			$cabang = $o['id_cabang'];
			$tgl_closing = strtotime($o['tgl_closing']);
			$kondisi = $o['kondisi'];
			$this->db->where('id_pesan',$o['id']); 
			$status_order = $this->db->get('proses_pesan')->result_array();
			if(!empty($status_order)){
				$status = $status_order[0]['status'];
			} else {
				$status = 0;
			}	
			if($tgl_closing < 0){
			 $tgl_closing = "";
			} else {
				$tgl_closing = date('d-m-Y',$tgl_closing);
			}
			$tgl_estimasi = date('Y-m-d', strtotime('3 days',$tgl_masuk));
			
		if($cabang == 4){
			$nocbg = '01';
		} elseif($cabang == 5){
			$nocbg = '02';
		} elseif($cabang == 1){
			$nocbg = '03';
		} elseif($cabang == 6){
			$nocbg = '04';
		} elseif($cabang == 7){
			$nocbg = '05';
		} elseif($cabang == 8){
			$nocbg = '09';
		}
			
		if(!empty($o['bayar'])){
					$nominal = explode(",",$o['bayar']);
					$total_nominal = (int)$nominal[0];
					$bayar_nominal = (int)$nominal[1];
						if($o['id_pesan'] == 0){
							$id_pesan = $o['id'];
						} else {
							$id_pesan = $o['id_pesan'];
						}
					$this->db->where('id_pesan', $id_pesan); 
					$pesan = $this->db->get('pesan')->result_array();
					$bayar_nominal = array();
					foreach($pesan as $p){
						$data_bayar = $p['bayar'];
						$anggaran = explode(",",$data_bayar);
						$bayar_nominal[] = $anggaran[1];
					}
					$bayar_nominal = array_sum($bayar_nominal); 
					$kondisi = $total_nominal - $bayar_nominal;
					
					$explode_bayar = explode(",",$o['bayar']);
					$bayar_nominal = (int)$explode_bayar[1];
			
						if($kondisi <= 0){
							$ket_bayar = 'LUNAS';
						} else {
							$ket_bayar = 'BELUM LUNAS';
						}
			
					} else {
						$total_nominal = '';
						$bayar_nominal = '';
						$ket_bayar = '';

		}
		$nama_cabang = $this->mymodel->namaCabang($cabang);
		$noreg = date('Y',$tgl_masuk).date('m',$tgl_masuk).date('d',$tgl_masuk).$nocbg.$o['id'].'/'.$nama_cabang;
		
		$this->db->where('id',$id_bukutamu);
		$bukutamu = $this->db->get('bukutamu')->result_array();
		foreach($bukutamu as $b){
			$nama = $b['nama'];
			$alamat = $b['alamat'];
			$telepon = $b['telepon'];
		}
		
		if($o['sum_trans'] == 0){
			$sm_trans = 'Datang Langsung';
		} else {
			$sm_trans = 'Online';
		}
		
		$this->db->where('id',$o['input']);
		$input = $this->db->get('user')->result_array();
		if(!empty($input)){
			$sumberdata = $input[0]['username'].'/'.$sm_trans;
		} else {
			$sumberdata = $sm_trans;
		}
		if(empty($status)){
			$order_status = 'UNPROCESS';
		} elseif($status == 2) {
			$order_status = 'NEGOISASI';
		} elseif($status == 3) {
			$order_status = 'CANCLE';
		} elseif($status == 4) {
			$order_status = 'DEAL';
		} elseif($status == 1) {
			$order_status = 'PROSES';
		}
			
		$this->db->where('id_pesan',$o['id']); 
		$status_order = $this->db->get('proses_pesan')->result_array();
		if($status_order){
			foreach($status_order as $so){
				$catatan = $so['keterangan'];
				$tgl_status = strtotime($so['tanggal']);
				$tgl_status = date('d-m-Y',$tgl_status);
			}
		} else {
			$catatan = '';
			$tgl_status = '';
		}
		$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $noreg);
		$excel->getActiveSheet()->getStyle('A'.$numrow)->getFont()->setSize(6);
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $nama);
		$excel->getActiveSheet()->getStyle('B'.$numrow)->getFont()->setSize(6);	
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $telepon);
		$excel->getActiveSheet()->getStyle('C'.$numrow)->getFont()->setSize(6);	
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $keterangan);
		$excel->getActiveSheet()->getStyle('D'.$numrow)->getFont()->setSize(6);	
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $tgl_estimasi);
		$excel->getActiveSheet()->getStyle('E'.$numrow)->getFont()->setSize(6);
		$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $sumberdata);
		$excel->getActiveSheet()->getStyle('F'.$numrow)->getFont()->setSize(6);	
		$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $order_status);
		$excel->getActiveSheet()->getStyle('G'.$numrow)->getFont()->setSize(6);	
		$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $tgl_status);
		$excel->getActiveSheet()->getStyle('H'.$numrow)->getFont()->setSize(6);	
		$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $catatan);
		$excel->getActiveSheet()->getStyle('I'.$numrow)->getFont()->setSize(6);	
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $nota);
		$excel->getActiveSheet()->getStyle('J'.$numrow)->getFont()->setSize(6);	
		$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $ket_bayar);
		$excel->getActiveSheet()->getStyle('K'.$numrow)->getFont()->setSize(6);	
		$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $total_nominal);
		$excel->getActiveSheet()->getStyle('L'.$numrow)->getFont()->setSize(6);
		$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $bayar_nominal);
		$excel->getActiveSheet()->getStyle('M'.$numrow)->getFont()->setSize(6);
		$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $teknisi);
		$excel->getActiveSheet()->getStyle('N'.$numrow)->getFont()->setSize(6);
		$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $seles);
		$excel->getActiveSheet()->getStyle('O'.$numrow)->getFont()->setSize(6);
		$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $tgl_closing);
		$excel->getActiveSheet()->getStyle('P'.$numrow)->getFont()->setSize(6);
		
		$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_row);
		$excel->getActiveSheet()->getStyle('P'.$numrow)->applyFromArray($style_row);	
		
			
		$numrow++; // Tambah 1 setiap kali looping
		
		}
		  
		 // Set width kolom
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(9);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(8);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(7); 
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(7);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(7);
		$excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
		$excel->getActiveSheet()->getColumnDimension('L')->setWidth(17);
		$excel->getActiveSheet()->getColumnDimension('M')->setWidth(6);
		$excel->getActiveSheet()->getColumnDimension('N')->setWidth(7);  
		  // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Laporan Data Order");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Data Order.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	  }  
	  
	  public function export_stok()
	  {
		 $date = strtotime(date('Y-m-d'));
		 $data['date'] = $date;
		 $this->load->view('export_stok',$data);
	  }
	  
	  public function export_omset()
	  {
		 $date = strtotime(date('Y-m-d'));
		 $data['date'] = $date;
		 $this->load->view('export_omset',$data);
	  }

	  public function export_order()
	  {
		 $date = strtotime(date('Y-m-d'));
		 $data['date'] = $date;
		 $this->load->view('export_order',$data);
	  }

	function edit() {
        $id = $this->uri->segment(3);
        $e = $this->db->where('id_profile', $id)->get('profile')->row();

        $kirim['id'] = $e->id_profile;
        $kirim['nama'] = $e->nama;
        $kirim['alamat'] = $e->alamat;

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($kirim));
    }
	
	function cetak_mutasi($id){
		$this->load->view('cetak_mutasi');
	}	
	
	function cetak_gaji(){
		$data['username'] = $this->input->post('username');
		$data['kehadiran'] = $this->input->post('kehadiran');
		$data['total_lembur'] = $this->input->post('total_lembur');
		$data['lembur_absent'] = $this->input->post('lembur_absent');
		$data['gaji_pokok'] = $this->input->post('gaji_pokok');
		$data['uang_makan'] = $this->input->post('uang_makan');
		$data['komunikasi'] = $this->input->post('komunikasi');
		$data['lain_lain'] = $this->input->post('lain_lain');
		$data['awal'] = $this->input->post('awal');
		$data['akhir'] = $this->input->post('akhir');
		$data['cabang'] = $this->input->post('cabang');
		$data['penjualan'] = $this->input->post('penjualan');
		$data['service'] = $this->input->post('service');
		$data['target'] = $this->input->post('target');
		$data['potongan'] = $this->input->post('potongan');
		$this->load->view('cetak_gaji',$data);
	}	
	
	public function add_slip_gaji()
	{
		$id_user = $this->input->post('id_user');
		$gaji_pokok = $this->input->post('gaji_pokok');
		$uang_makan = $this->input->post('uang_makan');
		$transport = $this->input->post('transport');
		$lain_lain = $this->input->post('lain_lain');
		$komunikasi = $this->input->post('komunikasi');
		$cek_id_user = $this->input->post('cek_id_user');
			
		$data = array(
			'id_user' => $id_user,
			'gaji_pokok' => $gaji_pokok,
			'uang_makan' => $uang_makan,
			'transport' => $transport,
			'lain_lain' => $lain_lain,
			'komunikasi' => $komunikasi,
		);
		
		if(empty($cek_id_user)){
			$ins = $this->mymodel->insertItem('gaji',$data);
		} else {
			$ins = $this->mymodel->updateItem('gaji',$data,array('id_user' => $cek_id_user));
		}
	
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Slip gaji sudah disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Slip gaji tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function add_lembur($id,$lembur)
	{	
		$this->db->where('id',$id);
		$absent = $this->db->get('absent')->result_array();
		
		if($lembur == 0){
			$lembur = 1;
		} else {
			$lembur = 0;
		}
		$data = array(
			'lembur' => $lembur,
			'date' => $absent[0]['date'],
		); 
		$ins = $this->mymodel->updateItem('absent',$data,array('id' => $id));			
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Lembur sudah di disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Lembur tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function harga_barang_item($cabang = 0)
	{	
		$this->db->where('status',0);
		$query = $this->db->get('item')->result_array();
		if(!empty($cabang)) $data['cabang'] = $cabang;
		$data['data'] = $query;
		$data['cabang'] = $cabang;
		$this->load->view('index',$data);
	}
	
	public function search_harga_barang_item(){
		$cabang = $this->input->post('cabang');
		redirect('welcome/harga_barang_item/'.$cabang);
	}
	
	public function setting_harga_item()
	{	
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$nama = $t[1];
			$nama = str_replace("%20"," ",$nama);
			$kategori = $t[2];
			$merek = $t[3];
			$merek = str_replace("%20"," ",$merek);
			$tipe = $t[4];
			$tipe = str_replace("%20"," ",$tipe);
			$total = (int)$t[5];
		} else {
			$cabang = '';
			$nama = '';
			$total = '';
			$merek = '';
			$tipe = '';
			$kategori = '';
		}
		
		if(!empty($nama)) $this->db->like('nama',$nama);
		if(!empty($merek)) $this->db->like('merek',$merek);
		if(!empty($tipe)) $this->db->like('tipe',$tipe);
		if(!empty($kategori)) $this->db->where('kategori',$kategori);
		$this->db->where('status',0);
		
		$data_result = $this->db->get('item')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/setting_harga_item/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/setting_harga_item/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = $total;
		$this->pagination->initialize($config);	
		
		if(!empty($nama)) $this->db->like('nama',$nama);
		if(!empty($merek)) $this->db->like('merek',$merek);
		if(!empty($tipe)) $this->db->like('tipe',$tipe);
		if(!empty($kategori)) $this->db->where('kategori',$kategori);
		$this->db->where('status',0);
		
		$query = $this->db->get('item',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function search_setting_harga_item(){
		$cabang = $this->input->post('cabang');
		$tanggal = strtotime($this->input->post('tanggal'));
		$jumlah = $this->input->post('jumlah');
		$nama = $this->input->post('nama');
		$kategori = $this->input->post('kategori');
		$merek = $this->input->post('merek');
		$tipe = $this->input->post('tipe');
		$link = $cabang.'^'.$nama.'^'.$kategori.'^'.$merek.'^'.$tipe.'^'.$jumlah;
		redirect('welcome/setting_harga_item/'.$link);
	}
	
	
	public function tambah_harga_barang_item($id_cabang){
	
		$total = $this->input->post('total');
		for($x=1;$x<=$total;$x++){
			$harga_jual = 'harga_'.$x;
			$id_item = 'id_item_'.$x;
			$id = 'id_'.$x;
			if(empty($this->input->post($harga_jual))){
				$harga_jual_item = 0;
			} else {
				$harga_jual_item = $this->input->post($harga_jual);
			}
			$data = array(
				'id_item' => $this->input->post($id_item),
				'id_cabang' => $id_cabang,
				'harga_pokok' => 0,
				'harga_jual' => $harga_jual_item,
			);
			
			if(!empty($this->input->post($id))){
				$ins = $this->mymodel->updateItem('harga_item',$data, array('id' => $this->input->post($id)));
			} else {
				$ins = $this->mymodel->insertItem('harga_item',$data);
			}
		}
		
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>data laporan stok telah disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>data laporan stok tidak dapat tersimpan');
			redirect($this->agent->referrer());
		}
	}
	
		public function return_item()
	{	
		$link = $this->uri->segment(3);
		$posisi=strpos($link,"%5E");
		if($posisi > 0){
			$t = explode("%5E",$link);
			$cabang = (int)$t[0];
			$keterangan = $t[1];
			$month = (int)$t[2];
			$year = (int)$t[3];
			$id_supplier = $t[4];
		} 
		
		if(!empty($cabang)) $this->db->where('id_cabang',$cabang);
		if(!empty($keterangan)) $this->db->where('keterangan',$keterangan);
		if(!empty($year)) $this->db->where('YEAR(waktu)',$year);
		if(!empty($month)) $this->db->where('MONTH(waktu)',$month);
		if(!empty($id_supplier)) $this->db->where('id_supplier',$id_supplier);
			
		$data_result = $this->db->get('service')->num_rows();
		if($posisi > 0){
			$from = $this->uri->segment(4);
			$config['base_url'] = base_url().'index.php/welcome/return_item/'.$link;
		} else {
			$from = $this->uri->segment(3);
			$config['base_url'] = base_url().'index.php/welcome/return_item/';
		}	
		$config['total_rows'] = $data_result;
		$config['per_page'] = $data_result;
		$this->pagination->initialize($config);	
		
		
		if(!empty($cabang)) $this->db->where('id_cabang',$cabang);
		if(!empty($keterangan)) $this->db->where('keterangan',$keterangan);
		if(!empty($year)) $this->db->where('YEAR(waktu)',$year);
		if(!empty($month)) $this->db->where('MONTH(waktu)',$month);
		if(!empty($id_supplier)) $this->db->where('id_supplier',$id_supplier);
			
		$query = $this->db->get('service',$config['per_page'],$from)->result_array();	
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function tambah_return_item(){
		$waktu = $this->input->post('waktu');
		$supplier = $this->input->post('supplier');
		$cabang = $this->input->post('cabang');
		$keterangan = $this->input->post('keterangan');
	
		$data = array(
			'waktu' => $waktu,
			'id_supplier' => $supplier,
			'id_cabang' => $cabang,
			'keterangan' => $keterangan,
		);
		
		$ins = $this->mymodel->insertItem('service',$data);
		
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Service telah disimpan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Service tidak dapat tersimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_return_item($id){
		$waktu = $this->input->post('waktu');
		$waktu_con = $this->input->post('waktu_con');
		$supplier = $this->input->post('supplier');
		$cabang = $this->input->post('cabang');
		$keterangan = $this->input->post('keterangan');
	
		$data = array(
			'waktu' => $waktu,
			'waktu_con' => $waktu_con,
			'id_supplier' => $supplier,
			'id_cabang' => $cabang,
			'keterangan' => $keterangan,
		);
		
		$ins = $this->mymodel->updateItem('service',$data,array('id' => $id));

		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Service telah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Service tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function search_return_item()
	{
		$cabang = $this->input->post('cabang');
		$keterangan = $this->input->post('keterangan');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$supplier = $this->input->post('supplier');	
		$link = $cabang.'^'.$keterangan.'^'.(int)$month.'^'.(int)$year.'^'.$supplier;
		redirect('welcome/return_item/'.$link);
	}
	
	public function search_return_item_stok(){
		$id_item_keluar = $this->input->post('id_return_item');
		$search = $this->input->post('search');
		redirect('welcome/detail_return_item/'.$id_item_keluar.'/'.$search);	
	}
	
	public function detail_return_item($id)
	{	
		$this->db->where('id_service',$id);
		$query = $this->db->get('sub_service')->result_array();
		$data['data'] = $query;
		$this->load->view('index',$data);
	}
	
	public function deleteItemReturn($table,$id)
	{	
		$delete = $this->mymodel->deleteItem($table,array('id' => $id));
		if($delete >= 1){
			$del = $this->mymodel->deleteItem('sub_service',array('id_service' => $id));
			$this->session->set_flashdata('message','<b>Notification</b> data telah dihapus');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b> data tidak dapat dihapus');
			redirect($this->agent->referrer());
		}
	}
	
	public function tambah_sub_return_item()
	{	
		
		$code = strtoupper($this->input->post('code'));
		$item = $this->mymodel->getCode($code);
		
		if(empty($item)){
			$this->session->set_flashdata('message','<b>Notification</b>Code Item tidak ditemukan');
			redirect($this->agent->referrer());
		}
		
		$data = array(
			'id_item' => $item[0]['id'],
			'id_service' => $this->input->post('id_service'),
			'unit' => $this->input->post('unit'),
		);
		$ins = $this->mymodel->insertItem('sub_service',$data);
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Sub Service sudah ditambahkan');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Sub Service tidak dapat disimpan');
			redirect($this->agent->referrer());
		}
	}
	
	public function ubah_sub_service($id)
	{	
		$data = array(
			'unit' => $this->input->post('unit'),
		);
		$ins = $this->mymodel->updateItem('sub_service',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Sub Service sudah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Sub Service tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
	
	public function konfirmasi_return_item($id){
		$this->db->where('id',$id);
		$check = $this->db->get('service')->result_array();
		$data = array(
			'waktu' => $check[0]['waktu'],
			'waktu_con' => date('Y-m-d'),
		);
		$ins = $this->mymodel->updateItem('service',$data,array('id' => $id));
		if($ins){
			$this->session->set_flashdata('message','<b>Notification</b>Sub Service sudah diubah');
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('message','<b>Notification</b>Sub Service tidak dapat diubah');
			redirect($this->agent->referrer());
		}
	}
}