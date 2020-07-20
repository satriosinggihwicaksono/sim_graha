<!DOCTYPE html>
<?php
	$username = $this->session->userdata('username');
	$id_username = $this->mymodel->getIduser($username);
	$id_cabang = $this->mymodel->getIdCabang($username);
	$check_admin = $this->mymodel->isAdmin($username);
	$check_marketing = $this->mymodel->isMarketing($username);
	$check_cabang = $this->mymodel->isCabang($username);
	$this->db->where('username',$username);
	$this->db->where('hakakses',0);
	$cek_admin = $this->db->get('user')->num_rows();
	$session = $this->mymodel->getSession($username);
	$message = $this->session->flashdata('message');	
	$this->db->where('id', $id_cabang); 
	$sub_cabang = $this->db->get('user')->result_array();


?>
<html lang="en" class="app">
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="<?php echo base_url();?>assets/images/logo.png">	
  <title>GrahaCCTV | Sistem Informasi Management</title>
  <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/animate.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/js/calendar/bootstrap_calendar.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/app.css" type="text/css" />	
  <style>
	.pesan{
		display: none;
		position: fixed;
		width: 250px;
		top: 10px;
		right: 10px;
		padding: 5px 10px;
		text-align: center;
		z-index: 100000;
	}
  </style>
 	
  <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js"></script>
    <script src="js/ie/respond.min.js"></script>
    <script src="js/ie/excanvas.js"></script>
  <![endif]-->
</head>
<body>
<?php	  
if($message){ ?>
	  <div class="pesan panel bg bg-success text-sm m-b-none">
		<div class="panel-body">
		  <p class="m-b-none"><?php echo $message; ?></p>
		</div>
	  </div>
<?php }
		$message = '';
?> 	
  <section class="vbox">
    <header class="bg-dark dk header navbar navbar-fixed-top-xs">
      <div class="navbar-header aside-md">
        <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html">
          <i class="fa fa-bars"></i>
        </a>
        <a href="#" class="navbar-brand" data-toggle="fullscreen"><img src="<?php echo base_url();?>assets/images/logo.png" class="m-r-sm">GRAHACOM CCTV</a>
        <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user">
          <i class="fa fa-cog"></i>
        </a>
      </div>  
      <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user">
        <li class="hidden-xs">
          <a href="#" class="dropdown-toggle dk" data-toggle="dropdown">
            <i class="fa fa-bell"></i>
            <span class="badge badge-sm up bg-danger m-l-n-sm count">2</span>
          </a>
          <section class="dropdown-menu aside-xl">
            <section class="panel bg-white">
              <header class="panel-heading b-light bg-light">
                <strong>You have <span class="count">2</span> notifications</strong>
              </header>
              <div class="list-group list-group-alt animated fadeInRight">
                <a href="#" class="media list-group-item">
                  <span class="pull-left thumb-sm">
                    <img src="<?php echo base_url();?>assets/images/avatar.png" alt="John said" class="img-circle">
                  </span>
                  <span class="media-body block m-b-none">
                    Use awesome animate.css<br>
                    <small class="text-muted">10 minutes ago</small>
                  </span>
                </a>
                <a href="#" class="media list-group-item">
                  <span class="media-body block m-b-none">
                    1.0 initial released<br>
                    <small class="text-muted">1 hour ago</small>
                  </span>
                </a>
              </div>
              <footer class="panel-footer text-sm">
                <a href="#" class="pull-right"><i class="fa fa-cog"></i></a>
                <a href="#notes" data-toggle="class:show animated fadeInRight">See all the notifications</a>
              </footer>
            </section>
          </section>
        </li>
        <li class="dropdown hidden-xs">
          <a href="#" class="dropdown-toggle dker" data-toggle="dropdown"><i class="fa fa-fw fa-search"></i></a>
          <section class="dropdown-menu aside-xl animated fadeInUp">
            <section class="panel bg-white">
              <form role="search">
                <div class="form-group wrapper m-b-none">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search">
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-info btn-icon"><i class="fa fa-search"></i></button>
                    </span>
                  </div>
                </div>
              </form>
            </section>
          </section>
        </li>
        <li class="dropdown"> 
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="thumb-sm avatar pull-left">
              <img src="<?php echo base_url();?>assets/images/avatar_default.jpg">
            </span>
         	<?php echo $username; ?> <b class="caret"></b>
          </a>
          <ul class="dropdown-menu animated fadeInRight">
            <span class="arrow top"></span>
            <li>
              <a href="<?php echo base_url().'index.php/welcome/detail_user/'.$session['id'];?>">Profile</a>
            </li>  
			<li>
              <a href="<?php echo base_url().'index.php/welcome/setting_pengguna/'.$session['id'];?>">Settings</a>
            </li>
            <li>
              <a href="<?php echo base_url().'index.php/auth/logout'; ?>" >Logout</a>
            </li>
          </ul>
        </li>
      </ul>      
    </header>
    <section>
      <section class="hbox stretch">
        <!-- .aside -->
        <aside class="bg-dark lter aside-md hidden-print" id="nav">          
          <section class="vbox">
            <section class="w-f scrollable">
              <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                
                <!-- nav -->
                <nav class="nav-primary hidden-xs">
                  <ul class="nav">  
                    <li >
                      <a href="#order"  >
                        <i class="fa fa-exchange icon">
                          <b class="bg-success"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Order</span>
                      </a>
                      <ul class="nav lt">
                        <li >
                          <a href="<?php if(!$check_admin && !$check_marketing){ echo base_url().'index.php/welcome/order/'.$id_username.'^0^'.date('m').'^'.date('Y').'^^0^0^^'; } else { echo base_url().'index.php/welcome/order/0^0^'.date('m').'^'.date('Y').'^^0^0^^'; } ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Order</span>
                          </a>
                        </li>
						<?php if($check_cabang || $check_admin){ ?>  
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/omset/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Omest</span>
                          </a>
                        </li>  
						 <?php } ?>
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/hutang_order/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Hutang Costumer</span>
                          </a>
                        </li>
						<?php if($check_admin){ ?>    
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/followup/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Follow Up</span>
                          </a>
                        </li>    
						<?php } ?>  
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/export_excel/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Export Excel</span>
                          </a>
                        </li>   
                      </ul>
                    </li>
					<?php if($check_cabang || $check_admin){  ?>  
					<li >
                      <a href="#kas"  >
                        <i class="fa fa-money icon">
                          <b class="bg-warning"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Kas</span>
                      </a>
                      <ul class="nav lt">
                        <li >
                          <a href="<?php echo base_url().'index.php/welcome/kas/'.$id_cabang.'^'.date('m').'^'.date('Y').'^'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Kas</span>
                          </a>
                        </li>
                      </ul>
                    </li>
					<?php 
					   }
				  	?>
					<li >
                      <a href="#harga_barang"  >
                        <i class="fa fa-money icon">
                          <b class="bg-warning"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Harga Barang</span>
                      </a>
                      <ul class="nav lt">
                        <li >
                          <a href="<?php echo base_url().'index.php/welcome/harga_barang_item/'.$id_cabang; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Harga Barang</span>
                          </a>
                        </li>
					<?php 
						if($cek_admin){
					?>    
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/setting_harga_item/'.$id_cabang; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Setting Harga Item</span>
                          </a>
                        </li> 
					<?php 
						}
					?>  	  
                      </ul>
                    </li>   
				 	<?php 
						if($cek_admin){
					?>   
					<li >
                      <a href="#item"  >
                        <i class="fa fa-suitcase">
                          <b class="bg-warning"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Item</span>
                      </a>
                      <ul class="nav lt">
                        <li >
                          <a href="<?php echo base_url().'index.php/welcome/daftar_barang/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Daftar Barang</span>
                          </a>
                        </li>  
						 <li >
                          <a href="<?php echo base_url().'index.php/welcome/daftar_barang_nonkulak/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Daftar Barang Non Kulak</span>
                          </a>
                        </li>    
                        <li >
                          <a href="<?php echo base_url().'index.php/welcome/kategori/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Daftar Kategori Barang</span>
                          </a>
                        </li> 
                      </ul>
                    </li>  
					<li >
                      <a href="#tool"  >
                        <i class="fa fa-gavel icon">
                          <b class="bg-warning"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Peralatan</span>
                      </a>
                      <ul class="nav lt">
                        <li >
                          <a href="<?php echo base_url().'index.php/welcome/peralatan/'.$id_username.'^0^^'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Peralatan</span>
                          </a>
                        </li>
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/daftar_barang_perlengkapan/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Daftar Perlengkapan</span>
                          </a>
                        </li>   
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/kategori_perlengkapan/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Daftar Kategori Perlengkapan</span>
                          </a>
                        </li>   
                      </ul>
                    </li>
					<li >
                      <a href="#inventaris"  >
                        <i class="fa fa-hdd-o icon">
                          <b class="bg-warning"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Inventaris</span>
                      </a>
                      <ul class="nav lt">
                        <li >
                          <a href="<?php echo base_url().'index.php/welcome/inventaris/'.$id_username.'^0^^'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Inventaris</span>
                          </a>
                        </li>
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/daftar_barang_inventaris/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Daftar Inventaris</span>
                          </a>
                        </li>   
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/kategori_inventaris/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Daftar Kategori Inventaris</span>
                          </a>
                        </li>   
                      </ul>
                    </li>    
					<li >
                      <a href="#pembelian_stok"  >
                        <i class="fa fa-dollar icon">
                          <b class="bg-warning"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Pembelian Stok</span>
                      </a>
                      <ul class="nav lt">
                        <li >
                          <a href="<?php echo base_url().'index.php/welcome/pembelian_stok/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Pembelian stok</span>
                          </a>
                        </li>
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/supplier/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Supplier</span>
                          </a>
                        </li>  
                      </ul>
                    </li>
					<?php 
						}
						  if($check_cabang || $check_admin){ 
					?>   
                    <li >
                      <a href="#persediaan"  >
                        <i class="fa fa-briefcase icon">
                          <b class="bg-primary"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Parsediaan</span>
                      </a>
                      <ul class="nav lt">
						<?php 
					  		if($cek_admin){
					  	?> 
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/stok/'; ?>">                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Stok Awal Barang</span>
                          </a>
                        </li>
						 <?php
							}
						  ?> 
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/stok_item_masuk/'; ?>">                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Stok Item Masuk</span>
                          </a>
                        </li> 
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/stok_item_keluar/'; ?>">                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Stok Item Keluar</span>
                          </a>
                        </li> 
						<?php 
							if($cek_admin){
						?>     
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/mutasi/'; ?>">                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Mutasi</span>
                          </a>
                        </li>
						<?php 
							}
						?>     
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/stok_laporan/'.$id_cabang.'^'.strtotime(date('Y-m-d')).'^'.'^'.'^^^'; ?>">                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Laporan Stok</span>
                          </a>
                        </li>
						     
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/stok_opname_harian/'.$id_username.'^'.strtotime(date('Y-m-d')).'^^'; ?>">                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Stok Opname Harian</span>
                          </a>
                        </li>
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/stok_opname/'.$id_username.'^'.strtotime(date('Y-m-d')).'^^'; ?>">                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Stok Opname</span>
                          </a>
                        </li>  
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/return_item/'; ?>">                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Return Item</span>
                          </a>
                        </li>    
                      </ul>
                    </li>
						  <?php }
					  	if($check_cabang || $check_admin){ 
					  ?>
					  <li > 
					<a href="#pengguna"  >
                        <i class="fa fa-user icon">
                          <b class="bg-info"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Daftar Pengguna</span>
                      </a>	
						<ul class="nav lt">
							<?php 
					  			if($cek_admin){;
					  		?>
							<li >
							  <a href="<?php echo base_url().'index.php/welcome/pengguna/'; ?>" >                                                        
								<i class="fa fa-angle-right"></i>
								<span>Cabang</span>
							  </a>
							</li>
							<li >
							  <a href="<?php echo base_url().'index.php/welcome/sub_pengguna/'; ?>" >                                                        
								<i class="fa fa-angle-right"></i>
								<span>Karyawan</span>
							  </a>
							</li>
							<li >
							  <a href="<?php echo base_url().'index.php/welcome/slip_gaji/'; ?>" >                                                        
								<i class="fa fa-angle-right"></i>
								<span>Slip Gaji</span>
							  </a>
                        	</li>  
							<?php } ?>	
							<li >
							  <a href="<?php echo base_url().'index.php/welcome/absent/'; ?>" >                                                        
								<i class="fa fa-angle-right"></i>
								<span>Absent</span>
							  </a>
                        	</li>
						  </ul>	
						</li>
					<?php } ?>
					 <li >	 
                      <a href="#bukutamu"  >
                        <i class="fa fa-columns icon">
                          <b class="bg-warning"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Daftar Costumer</span>
                      </a>
                      <ul class="nav lt">
                        <li >
                          <a href="<?php echo base_url().'index.php/welcome/bukutamu/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Bukutamu list</span>
                          </a>
                        </li>
                      </ul>
                    </li>
					  <?php 
						  if($cek_admin){
					  ?>
					  <li >
                      <a href="#laporan"  >
                        <i class="fa fa-file-text icon">
                          <b class="bg-warning"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Laporan</span>
                      </a>
                      <ul class="nav lt">
                        <li >
                          <a href="<?php echo base_url().'index.php/welcome/laporan_kas/'.strtotime(date('Y-m-d')); ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Laporan Omset / Kas</span>
                          </a>
                        </li>
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/export_laporan/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Laporang Keseluruhan</span>
                          </a>
                        </li> 
						<li >
                          <a href="<?php echo base_url().'index.php/welcome/laporan_hutang/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Laporan Utang Piutang</span>
                          </a>
                        </li>   
                      </ul>
                    </li>
            <!-- Export Excel -->
                      <li >
                      <a href="#excel"  >
                        <i class="fa fa-file icon">
                          <b class="bg-warning"></b>
                        </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                        <span>Export Excel</span>
                      </a>
                      <ul class="nav lt">
                        <li >
                          <a href="<?php echo base_url().'index.php/welcome/export_stok'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Laporan Stok Opname</span>
                          </a>
                        </li>
						            <li >
                          <a href="<?php echo base_url().'index.php/welcome/export_omset/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Laporang Omset</span>
                          </a>
                        </li> 
						            <li >
                          <a href="<?php echo base_url().'index.php/welcome/export_order/'; ?>" >                                                        
                            <i class="fa fa-angle-right"></i>
                            <span>Laporan Daftar Order</span>
                          </a>
                        </li>   
                      </ul>
                    </li>        
					  <?php } ?> 
					  
                  </ul>
                </nav>
                <!-- / nav -->
              </div>
            </section>
            <footer class="footer lt hidden-xs b-t b-dark">
              <a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-dark btn-icon">
                <i class="fa fa-angle-left text"></i>
                <i class="fa fa-angle-right text-active"></i>
              </a>
            </footer>
          </section>
        </aside>
		  
        <!-- /.aside -->
        <section id="content">
          <section class="vbox">          
				<?php
					if(!empty($this->uri->segment(2))){
					$page = $this->uri->segment(2);
					switch ($page) {
							case 'penjualan':
								include 'penjualan.php';
								break;
							case 'stok_opname':
								include 'stok_opname.php';
								break;
							case 'stok_opname_harian':
								include 'stok_opname_harian.php';
								break;
							case 'mutasi':
								if(!$cek_admin) redirect('welcome');
								include 'mutasi.php';
								break;
							case 'followup':
								if(!$cek_admin) redirect('welcome');
								include 'followup.php';
								break;
							case 'stok_laporan':
								include 'stok_laporan.php';
								break;
							case 'stok_item_masuk':
								include 'stok_item_masuk.php';
								break;
							case 'pembelian_stok':
								if(!$cek_admin) redirect('welcome');
								include 'pembelian_stok.php';
								break;
							case 'tambah_pembelian_stok':
								include 'tambah_pembelian_stok.php';
								break;
							case 'stok_item_keluar':
								include 'stok_item_keluar.php';
								break;
							case 'tambah_item_masuk':
								include 'tambah_item_masuk.php';
								break;
							case 'tambah_item_keluar':
								include 'tambah_item_keluar.php';
								break;
							case 'detail_return_item':
								include 'detail_return_item.php';
								break;
							case 'tambah_item_mutasi':
								include 'tambah_item_mutasi.php';
								break;
							case 'absent':
								include 'absent.php';
								break;
							case 'export_excel':
								include 'export_excel.php';
								break;
							case 'kategori':
								if(!$cek_admin) redirect('welcome');
								include 'kategori.php';
								break;
							case 'kategori_perlengkapan':
								if(!$cek_admin) redirect('welcome');
								include 'kategori_perlengkapan.php';
								break;
							case 'kategori_inventaris':
								if(!$cek_admin) redirect('welcome');
								include 'kategori_inventaris.php';
								break;
							case 'supplier':
								if(!$cek_admin) redirect('welcome');
								include 'supplier.php';
								break;
							case 'stok':
								if(!$cek_admin) redirect('welcome');
								include 'stok.php';
								break;
							case 'proses_stok':
								include 'proses_stok.php';
								break;
							case 'bukutamu':
								include 'bukutamu.php';
								break;
							case 'tambah_bukutamu':
								include 'tambah_bukutamu.php';
								break;
							case 'ubah_bukutamu':
								include 'ubah_bukutamu.php';
								break;
							case 'detail_order_bukutamu':
								include 'detail_order_bukutamu.php';
								break;
							case 'tambah_order':
								include 'tambah_order.php';
								break;
							case 'return_item':
								include 'return_item.php';
								break;
							case 'detail_order':
								include 'detail_order.php';
								break;
							case 'addnominal':
								include 'addnominal.php';
								break;
							case 'tambah_new_order':
								include 'tambah_new_order.php';
								break;
							case 'order':
								include 'order.php';
								break;
							case 'harga_barang_item':
								include 'harga_barang_item.php';
								break;
							case 'setting_harga_item':
							if(!$cek_admin) redirect('welcome');
								include 'setting_harga_item.php';
								break;
							case 'hutang_order':
								include 'hutang_order.php';
								break;
							case 'kas':
								include 'kas.php';
								break;
							case 'laporan_kas':
								if(!$cek_admin) redirect('welcome');
								include 'laporan_kas.php';
								break;
							case 'laporan_hutang':
								if(!$cek_admin) redirect('welcome');
								include 'laporan_hutang.php';
								break;
							case 'laporan':
								if(!$cek_admin) redirect('welcome');
								include 'laporan.php';
								break;
							case 'omset':
								include 'omset.php';
								break;
							case 'search_order':
								include 'order.php';
								break;
							case 'agenda':
								include 'agenda.php';
								break;
							case 'ubah_status':
								include 'ubah_status.php';
								break;
							case 'pengguna':
								if(!$cek_admin) redirect('welcome');
								include 'pengguna.php';
								break;
							case 'sub_pengguna':
								if(!$cek_admin) redirect('welcome');
								include 'sub_pengguna.php';
								break;
							case 'detail_user':
								include 'detail_user.php';
								break;
							case 'tambah_pengguna':
								if(!$cek_admin) redirect('welcome');
								include 'tambah_pengguna.php';
								break;
							case 'tambah_sub_pengguna':
								if(!$cek_admin) redirect('welcome');
								include 'tambah_sub_pengguna.php';
								break;
							case 'setting_pengguna':
								include 'setting_pengguna.php';
								break;
							case 'daftar_barang':
								if(!$cek_admin) redirect('welcome');
								include 'daftar_barang.php';
								break;
							case 'daftar_barang_nonkulak':
								if(!$cek_admin) redirect('welcome');
								include 'daftar_barang_nonkulak.php';
								break;
							case 'peralatan':
								if(!$cek_admin) redirect('welcome');
								include 'peralatan.php';
								break;
							case 'daftar_barang_perlengkapan':
								if(!$cek_admin) redirect('welcome');
								include 'daftar_barang_perlengkapan.php';
								break;
							case 'inventaris':
								if(!$cek_admin) redirect('welcome');
								include 'inventaris.php';
								break;
							case 'daftar_barang_inventaris':
								if(!$cek_admin) redirect('welcome');
								include 'daftar_barang_inventaris.php';
								break;
							case 'slip_gaji':
								if(!$cek_admin) redirect('welcome');
								include 'slip_gaji.php';
								break;
							 default:
        						include 'home.php';
						} 
					} else {
						include 'home.php';
					}
					?>
          </section>
          <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
        </section>
        <aside class="bg-light lter b-l aside-md hide" id="notes">
          <div class="wrapper">Notification</div>
        </aside>
      </section>
    </section>
  </section>
  <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
  <!-- App -->
  <script src="<?php echo base_url();?>assets/js/app.js"></script>
  <script src="<?php echo base_url();?>assets/js/app.plugin.js"></script>
  <script src="<?php echo base_url();?>assets/js/slimscroll/jquery.slimscroll.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/charts/easypiechart/jquery.easy-pie-chart.js"></script>
  <script src="<?php echo base_url();?>assets/js/charts/sparkline/jquery.sparkline.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/charts/flot/jquery.flot.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/charts/flot/jquery.flot.tooltip.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/charts/flot/jquery.flot.resize.js"></script>
  <script src="<?php echo base_url();?>assets/js/charts/flot/jquery.flot.grow.js"></script>
  <script src="<?php echo base_url();?>assets/js/charts/flot/demo.js"></script>
  <script src="<?php echo base_url();?>assets/js/calendar/bootstrap_calendar.js"></script>
  <script src="<?php echo base_url();?>assets/js/calendar/demo.js"></script>
  <script src="<?php echo base_url();?>assets/js/sortable/jquery.sortable.js"></script>
  <script src="<?php echo base_url();?>assets/js/jquery-1.11.1.js"></script>	
<script src="<?php echo base_url();?>assets/js/calendar/bootstrap_calendar.js"></script>	
  <script>
	$(document).ready(function(){setTimeout(function(){$(".pesan").fadeIn('slow');}, 500);});
	setTimeout(function(){$(".pesan").fadeOut('slow');}, 3000);
  </script>
</body>
	
<!-- datatable
	============================================ -->
<script src="<?php echo base_url().'assets/';?>js/demo/datatables-demo.js"></script>
<script src="<?php echo base_url().'assets/';?>vendor/datatables/jquery.dataTables.js"></script>		
</html>