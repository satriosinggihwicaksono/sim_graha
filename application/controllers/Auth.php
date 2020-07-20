<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
    	$this->load->library('form_validation');
	}
	
	public function index()
	{
		$this->load->view('login');
	}
	
	public function register()
	{
		$this->load->view('login');
	}
	
	public function sigup()
	{
		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password','Password','required');

		if($this->form_validation->run() === FALSE ){
			$this->load->view('login');
		} else {
			$username = $this->input->post('username');
			$check = $this->mymodel->check('user','username',$username);
			$this->db->where('username',$username);
			$get_status = $this->db->get('user')->result_array();
			$get_status = $get_status[0];
			if($check->num_rows() === 1 && (int)$get_status['status'] === 1){
				$password = $this->input->post('password');
				$verify = password_verify($password, $check->row()->password);
			}	
			if($verify){
				$session['username'] = $check->row()->username;
				$session['logged_in'] = TRUE;
				$this->session->set_userdata($session);
				redirect('welcome');	
			} else if($get_status != NULL && $get_status['status'] != 1) {
				$this->session->set_flashdata('message','<b>Your account is banned');
				redirect('auth'); 
			} else if($check->num_rows() === 1 && !$verify) {
				$this->session->set_flashdata('message','<b>Your password wrong');
				redirect('auth');
			} else if($check->num_rows() != 1) {
				$this->session->set_flashdata('message','<b>Your account is not registered');
				redirect('auth'); 		
			} else {
				$this->session->set_flashdata('message','<b>Your account is not registered');
				redirect('auth'); 
			}
		}
	}
	
	public function registration()
	{
		$this->form_validation->set_rules('username','Username','required|is_unique[user.username]');
		$this->form_validation->set_rules('name','Name','required|is_unique[user.name]');
		$this->form_validation->set_rules('password','Password','required');
		$this->form_validation->set_rules('repassword','Repassword','required|matches[password]');
		$hakakses = $this->input->post('hakakses');
		if($this->form_validation->run() === FALSE ){
			$this->load->view('login');
		} else {	
			$data = array(
				'username' => $this->input->post('username'),
				'name' => $this->input->post('name'),
				'hakakses' => $hakakses, 
				'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
				'privilege'	=> '1',
			);
			$ins = $this->mymodel->insertItem('user',$data);
			redirect('auth/sigup');
		}
	}
	
	public function logout(){
		$session = $this->session->userdata('username');
		$this->session->sess_destroy();
		redirect('auth/sigup');
	}
}

