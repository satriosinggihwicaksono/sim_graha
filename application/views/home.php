 <section id="content">
          <section class="vbox">          
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.html"><i class="fa fa-home"></i> Home</a></li>
              </ul>
              <div class="m-b-md">
                <h3 class="m-b-none">Home</h3>
				 <small><?php echo date('D,d M Y'); ?></small>  
              </div>
              <section class="panel panel-default">
                <div class="row m-l-none m-r-none bg-light lter">
                  <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x text-info"></i>
                      <i class="fa fa-male fa-stack-1x text-white"></i>
                    </span>
                    <a class="clear" href="#">
					  <?php 
						$tanggal = strtotime(date('Y-m-d'));
						$bulan_kemarin = mktime(0, 0, 0, date("m", $tanggal)-1, 1, date("Y", $tanggal));
						$bulan_kemarin = strtotime(date('Y-m-t',$bulan_kemarin));
						$total_pendapatan = $this->mymodel->total_omest_hari($id_username,$tanggal);
						$total = array();
						foreach($total_pendapatan as $d){
							if(gettype($d) == 'array'){
								$d = array_sum($d);
								} else {
									$d =0;
								}
							$total[] = $d;
						}
						$total_pendapatan = array_sum($total);
						if($check_admin){
							$id_username = 0;
						}	
						$total_sebelum = $this->mymodel->total_omest_hari($id_username,$bulan_kemarin);
						$total_sebelum_omset = array();	
						foreach($total_sebelum as $t){
							if(gettype($t) == 'array'){
								$t = array_sum($t);
								} else {
									$t =0;
								}
							$total_sebelum_omset[] = $t;
						}	
						$total_sebelum_pendapatan = array_sum($total_sebelum_omset);
						if(!$check_admin){
							$this->db->where('id_cabang',$id_username);
						}	
						$omzet = $this->db->get('omset')->result_array();
						if(!empty($omzet)){
						$target = $omzet[0]['target'];
							if(!empty($target)){
								$presentasi_omset = ($total_pendapatan / $target) * 100;
							}	else {
								$presentasi_omset = 0;
							}
						}
						if(!$check_admin){
							$this->db->where('cabang',$id_username);
						}
						$bukutamu = $this->db->get('bukutamu')->num_rows();	
					  ?>	
                      <span class="h3 block m-t-xs"><strong><?php echo $bukutamu; ?></strong></span>
                      <small class="text-muted text-uc">Bukutamu</small>
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x text-warning"></i>
                      <i class="fa fa-book fa-stack-1x text-white"></i>
                      <span class="easypiechart pos-abt" data-percent="100" data-line-width="4" data-track-Color="#fff" data-scale-Color="false" data-size="50" data-line-cap='butt' data-animate="2000" data-target="#bugs" data-update="3000"></span>
                    </span>
                    <a class="clear" href="#">
                      <span class="h3 block m-t-xs"><strong id="bugs"><?php echo $this->mymodel->total_kas($id_username,$tanggal); ?></strong></span>
                      <small class="text-muted text-uc">Kas</small>
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-3 padder-v b-r b-light">                     
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x text-success"></i>
                      <i class="fa fa-money fa-stack-1x text-white"></i>
                      <span class="easypiechart pos-abt" data-percent="100" data-line-width="4" data-track-Color="#f5f5f5" data-scale-Color="false" data-size="50" data-line-cap='butt' data-animate="3000" data-target="#firers" data-update="5000"></span>
                    </span>
                    <a class="clear" href="#">
                      <span class="h3 block m-t-xs"><strong id="firers"><?php echo $total_pendapatan; ?></strong></span>
                      <small class="text-muted text-uc">Pemasukan</small>
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x icon-muted"></i>
                      <i class="fa fa-clock-o fa-stack-1x text-white"></i>
                    </span>
                    <a class="clear" href="#">
                      <span class="h3 block m-t-xs"><strong><?php echo date('H:i:s'); ?></strong></span>
                      <small class="text-muted text-uc">Waktu</small>
                    </a>
                  </div>
                </div>
              </section>
              <div class="row">
                <div class="col-md-4">
                  <section class="panel panel-default">
                    <header class="panel-heading font-bold">Data Omset</header>
                    <div class="bg-light dk wrapper">
                      <span class="pull-right"><?php echo date('D') ?></span>
                      <span class="h4">Target Omset<br>
                        <small class="text-muted"><?php if(!empty($omzet)) echo $this->mymodel->format($omzet[0]['target']).'('.$presentasi_omset.'%)'; ?></small>
                      </span>
                      <div class="text-center m-b-n m-t-sm">
                          <div class="sparkline" data-type="line" data-height="65" data-width="100%" data-line-width="2" data-line-color="#dddddd" data-spot-color="#bbbbbb" data-fill-color="" data-highlight-line-color="#fff" data-spot-radius="3" data-resize="true" values="280,320,220,385,450,320,345,250,250,250,400"></div>
                          <div class="sparkline inline" data-type="bar" data-height="45" data-bar-width="6" data-bar-spacing="6" data-bar-color="#65bd77">10,9,11,10,11,10,12,10,9,10,11,9</div>
                      </div>
                    </div>
                    <div class="panel-body">
                      <div>
                        <span class="text-muted">Total Omset:</span>
                        <span class="h3 block"><?php echo $this->mymodel->format($total_pendapatan); ?></span>
                      </div>
                      <div class="line pull-in"></div>
                      <div class="row m-t-sm">
                        <div class="col-xs-4">
                          <small class="text-muted block">Omset Sebelum</small>
                          <span><?php echo $this->mymodel->format($total_sebelum_pendapatan); ?></span>
                        </div>
                        <div class="col-xs-4">
                          <small class="text-muted block">Omset Sesudah</small>
                          <span><?php echo $this->mymodel->format($total_pendapatan); ?></span>
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
				<div class="col-md-4">
                  <section class="panel b-light">
                    <header class="panel-heading bg-primary dker no-border"><strong>Calendar</strong></header>
                    <div id="calendar" class="bg-primary m-l-n-xxs m-r-n-xxs"></div>
                    <div class="list-group">
                      <a href="#" class="list-group-item text-ellipsis">
                        <span class="badge bg-danger">7:30</span> 
                        Meet a friend
                      </a>
                      <a href="#" class="list-group-item text-ellipsis"> 
                        <span class="badge bg-success">9:30</span> 
                        Have a kick off meeting with .inc company
                      </a>
                      <a href="#" class="list-group-item text-ellipsis">
                        <span class="badge bg-light">19:30</span>
                        Milestone release
                      </a>
                    </div>
                  </section>                  
                </div>  
              </div>
            </section>
          </section>
          <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
        </section>