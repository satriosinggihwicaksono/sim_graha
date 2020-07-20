<?php 
	$id = $this->uri->segment(3);
	$deskripsi = $transaksi[0]['deskripsi'];
	$deskripsi = explode(',',$deskripsi);
?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
						<div class="row">
							<div class="col-md-9">
								<div class="form-group">
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbhitem">Tambah Daftar Item</button><br>
									<form method="POST" action="<?php echo base_url().'index.php/transaksi/search_detail_transaksi/'; ?>">
										Kongsi : <input type='text' name='kongsi' value="<?php echo $kongsi; ?>"/> <input type='hidden' name='id' value="<?php echo $transaksi[0]['id']; ?>"/>
										<input type="submit" value="KONGSI" />
									</form>	
									<form method="POST" action="<?php echo base_url().'index.php/transaksi/search_detail_transaksi/'; ?>">
										Cari SN : <input type='text' name='search' value="<?php echo $search; ?>"/> <input type='hidden' name='id' value="<?php echo $transaksi[0]['id']; ?>"/>
										<input type="submit" value="SEARCH" />
									</form>	
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label> <?php echo date('Y-m-d',strtotime($transaksi[0]['waktu'])); ?> </label> <br>
									<label>Transaksi <?php echo $transaksi[0]['nota']; ?></label>
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ubhtransaksi<?php echo $transaksi[0]['id']; ?>">Ubah</button>	
									<br>
									<label>Pembeli : <?php if(!empty($deskripsi[0])) echo $deskripsi[0]; ?></label><br>
									<label>Alamat : <?php if(!empty($deskripsi[0])) echo $deskripsi[1]; ?></label><br>
									<label>Handphone : <?php if(!empty($deskripsi[0])) echo $deskripsi[2]; ?></label>
								</div>
							</div>
						</div>
				</div>
				<?php if(!empty($search)){ ?>
				<div class="bsc-tbl">
					<table class="table table-sc-ex" style="width:50%">
						<thead style='background-color:#2196F3'>
							<tr>
								<th>Nama</th>
								<th style="text-align:center;">Serial</th>
								<th style="text-align:center; width:100px;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$this->db->where('id_transaksi',$transaksi[0]['id']);	
								$removes = $this->db->get('sub_transaksi')->result_array();
								foreach($removes as $r){
									$this->db->where('id !=',$r['id_serial']);
								}
								$this->db->where('status',0);
								if(!$check_admin){
									$this->db->where('cabang',$transaksi[0]['cabang']);
								}	

								$this->db->like('serial',$search);
								$searching = $this->db->get('serial')->result_array();
								if(!empty($searching)){
									foreach($searching as $s){	
									$item = $this->komputer->cek($s['id_item'],'id','item');			
									if(!empty($item)){
									$harga = $this->komputer->cek($item[0]['id'],'id_item','harga');
										if(!empty($harga)){
											$harga_jual = $harga[0]['harga_jual'];
										} else {
											$harga_jual = 0;
										}	
									}
							?>
							<tr>
								<td><?php if(!empty($item)) echo $item[0]['nama'].' '. $item[0]['warna'].' '.$item[0]['tipe'].' ('.$this->komputer->namaCabang($s['cabang']).')'; ?></td>
								<td style="text-align:center;"><?php if(!empty($searching)) echo $s['serial']; ?></td>
								<td style="text-align:center; width:100px;">
									<?php if(!empty($item)){ ?>
									<a href="<?php echo base_url().'index.php/transaksi/add_sub_transaksi/'.$transaksi[0]["id"].'/'.$item[0]["id"].'/'.$s["id"].'/'.$harga_jual;?>">
									<span class="btn btn-success"> Tambah</span></a>
									<?php } ?>
								</td>
							</tr>
							<?php } } else {
									$this->db->where('serial',0);
									$this->db->like('nama',$search);
									$item_search = $this->db->get('item')->result_array();
									if(!empty($item_search)){
										foreach($item_search as $i){
											$harga = $this->komputer->cek($i['id'],'id_item','harga');
											if(!empty($harga)){
												$harga_jual = $harga[0]['harga_jual'];
											} else {
												$harga_jual = 0;
											}	
							?>
							<tr>
								<td><?php echo $i['nama'].' '. $i['warna'].' '.$i['tipe']; ?></td>
								<td style="text-align:center;"></td>
								<td style="text-align:center; width:100px;">
									<a href="<?php echo base_url().'index.php/transaksi/add_sub_transaksi/'.$transaksi[0]["id"].'/'.$i["id"].'/0/'.$harga_jual;?>">
									<span class="btn btn-success"> Tambah</span></a>
								</td>
							</tr>
							<?php	
										}	
									}
								}
							?>
						</tbody>
					</table>
				</div>
				<?php	 
						} 
				?>	
				
				<?php if(!empty($kongsi)){ ?>
				<div class="bsc-tbl">
					<table class="table table-sc-ex" style="width:30%">
						<thead style='background-color:#2196F3'>
							<tr>
								<th>Nama</th>
								<th style="text-align:center; width:100px;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$this->db->like('nama',$kongsi);
								$item = $this->db->get('item')->result_array();
								if(!empty($item)){
								foreach($item as $i){
								include 'tbh_kongsi.php';
							?>
							
							<tr>
								<td><?php echo $i['nama'].' '. $i['warna'].' '.$i['tipe']; ?></td>
								<td style="text-align:center; width:100px;">
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tbh_kongsi<?php echo $i['id']; ?>">Tambah</button>	
								</td>
							</tr>
							<?php 
								}
							?> 
						</tbody>
					</table>
				</div>
				<?php		} 
						} 
				?>	
				
				<div class="breadcomb-area">
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFEB3B'>
							<tr>
								<th style="text-align:center; width:50px;">No</th>
								<th>Nama</th>
								<th>Serial</th>
								<th style="text-align:center; width:50px;">Unit</th>
								<th>Harga</th>
								<th>Total</th>
								<th style="text-align:center; width:200px;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php  
								$total = array();
								$x = 1;
								foreach($sub_transaksi as $st){
								$serial = $this->komputer->cek($st['id_serial'],'id','serial'); 
								$total_harga = $st['harga'] * $st['unit'];
								$total[] = $total_harga;
								if(!empty($serial[0]['status']) != 1){
									$color = '#FFFAE6';
								} else {
									$color = '#F2FFE6';
								}
							?>
							<form method="POST" action="<?php echo base_url().'index.php/transaksi/ubah_sub_transaksi/'.$st['id']; ?>">
							<tr style="background-color:<?php echo $color; ?>">
								<td ><?php echo $x++; ?></td>
								<td>
									<?php 
										$nama = $this->komputer->cek($st['id_item'],'id','item'); 
										if(!empty($nama)){
											$nama_item = $nama[0]['nama'];
											if(empty($nama_item)) $nama_item = '';
											$warna = $nama[0]['warna'];
											if(empty($warna)) $warna = '';
											$tipe = $nama[0]['tipe'];
											if(empty($tipe)) $tipe = '';
											echo $nama_item.' '.$warna.' '.$tipe;
										}	
									?>
								</td>
								<td>
								<?php if(!empty($serial)) echo $serial[0]['serial']; ?>
								</td>
								<?php 
								if(!empty($nama[0]['serial']) == 1){
									$status = 'disabled';
								} else {
									$status = '';
								}
								?>
								<td style="text-align:center; width:50px;"><input name="unit" value="<?php echo $st['unit'];?>" style="width:50px;"/></td>
								<td><input type="text" id="inputku" name="harga" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" value="<?php echo $this->komputer->format($st['harga']); ?>" style="width:150px; direction: rtl;"/></td>
								<td style="text-align:right;"><?php echo $this->komputer->format($total_harga);?></td>
								<td>
									<input type="submit" value="Ubah"/>
									<a href='<?php echo base_url().'index.php/transaksi/delete_sub_transaksi/'.$st['id'].'/'.$st['id_serial']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								</td>
							</tr>
							</form>	
								<?php } ?>
							<tr>
								<td colspan='5' style="text-align:left;"><h3>TOTAL</h3></td>
								<td style="text-align:right;"><?php echo $this->komputer->format(array_sum($total)); ?></td>
							<tr>
							<form method="POST" action="<?php echo base_url().'index.php/transaksi/bayar_sub_transaksi/'.$transaksi[0]['id']; ?>">	
							<tr>
								<td colspan='5' style="text-align:left;"><h3>BAYAR</h3></td>
								<td style="text-align:right">
									<input type="text" id="inputku" name="bayar" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" value="<?php echo $this->komputer->format($transaksi[0]['bayar']); ?>" style="width:150px; direction: rtl;"/>
									<input name="waktu" type="hidden"  value="<?php echo date('Y-m-d', strtotime($transaksi[0]['waktu'])); ?>"/>
									<input name="total" type="hidden"  value="<?php echo array_sum($total); ?>"/>
								</td>	
								<td><input type="submit" value="BAYAR"/></td>
							<tr>	
							</form>	
							<tr>
								<td colspan='5' style="text-align:left;"><h3>KEKURANGAN</h3></td>
								<td style="text-align:right;"><?php echo $this->komputer->format($transaksi[0]['bayar'] - array_sum($total)); ?></td>
							<tr>		
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>	
	<div class="row">
		<div class="col-lg-9">
		</div>	
		<div class="col-lg-3">
			<div class="recent-items-wp notika-shadow sm-res-mg-t-30">
				<div class="rc-it-ltd">
					<div class="recent-items-ctn">
						<div class="text-align:center;">
							<h2><a href="<?php echo base_url().'index.php/printer/printTransaksi/'.$transaksi[0]['id'];?>" onclick="window.open('<?php echo base_url().'index.php/printer/printTransaksi/'.$transaksi[0]['id'];?>', 'newwindow', 'width=600, height=700'); return false;"><button onclick="demo.showNotification('top','left')" class="fa fa-print"> CETAK</button></a></h2>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>	
</div>

<!-- Ubah Transaksi -->

<div class="modal fade" id="ubhtransaksi<?php echo $transaksi[0]['id']; ?>" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'index.php/transaksi/ubah_transaksi/'.$transaksi[0]['id'] ?>">
			<div class="modal-body">
				<div class="row">
					<label>Tanggal</label>
					<input type="date" name="waktu" placeholder="" value="<?php if(!empty($transaksi[0]['waktu'])) echo date('Y-m-d',strtotime($transaksi[0]['waktu'])); ?>">
				</div>
				<div class="row">
					<label>Nota :</label>
					<input type="text" class="form-control border-input" name="nota" placeholder="" value="<?php if(!empty($transaksi[0]['nota'])) echo $transaksi[0]['nota']; ?>" style="width:200px;">
				</div>
				<div class="row">
					<label>Pembeli :</label>
					<input type="text" class="form-control border-input" name="pembeli" placeholder="" value="<?php if(!empty($deskripsi)) echo $deskripsi[0]; ?>" style="width:200px;">
				</div>
				<div class="row">
					<label>Alamat :</label>
					<input type="text" class="form-control border-input" name="alamat" placeholder="" value="<?php if(!empty($deskripsi)) echo $deskripsi[1]; ?>" style="width:300px;">
				</div>
				<div class="row">
					<label>Telepone :</label>
					<input name="keterangan" class="form-control border-input" value="<?php if(!empty($deskripsi)) echo $deskripsi[2]; ?>" style="width:300px;"/>
				</div>
				<div class="row">
					<label>Cabang :</label>
					<?php if($check_admin){ ?>
					<select name="cabang" >
						<option></option>
						<?php
						$cabang = $this->db->get('cabang')->result_array();
						foreach($cabang as $c){ ?>
							<option <?php if($transaksi[0]['cabang'] == $c['id']){ echo 'selected="selected"'; } ?> value='<?php echo $c['id']; ?>'><?php echo $c['nama']; ?></option>
						<?php } ?>
					</select>
					<?php } else {
						echo '<input class="form-control border-input" value="'.$this->komputer->namaCabang($transaksi[0]['cabang']).'"  style="width:200px;" disabled/>';
						echo '<input type="hidden" name="cabang" value="'.$transaksi[0]['cabang'].'" />';
					}
					?>
				</div>
				<?php if($check_admin){ ?>
				<div class="row">
					<label>Seles :</label>
					<select name="seles" >	
						<?php
						$this->db->where('hakakses',2);
						$user = $this->db->get('user')->result_array();
						foreach($user as $u){ ?>
							<option <?php if($transaksi[0]['id_user'] == $u['id']){ echo 'selected="selected"'; } ?> value='<?php echo $u['id']; ?>'><?php echo $u['name']; ?></option>
						<?php } ?>
					</select>
				</div>
				<?php } ?>
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Ubah Transaksi</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>

<div class="modal fade" id="tbhitem" role="dialog">
	<div class="modal-dialog modal-large">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="POST" action="<?php echo base_url().'index.php/persedian_page/tambah_item' ?>">
			<div class="modal-body">
				<label>Kode</label>
				<input type="text" class="form-control border-input" name="kode" placeholder="" value="">
				
				<label>Nama</label>
				<input type="text" class="form-control border-input" name="nama" placeholder="" value="">
				
				<label>Merek</label>
				<input type="text" class="form-control border-input" name="merek" placeholder="" value="">
				
				<label>Warna</label>
				<input type="text" class="form-control border-input" name="warna" placeholder="" value="">
				
				<label>Tipe</label>
				<input type="text" class="form-control border-input" name="tipe" placeholder="" value="">
				
				<label>Kategori : </label>
					<select name="kategori" class="selectpicker">
						<?php
						$kategori = $this->db->get('kategori')->result_array();
						foreach($kategori as $k){ ?>
							<option value='<?php echo $k['id']; ?>'><?php echo $k['kategori']; ?></option>
						<?php } ?>
					</select>
				
				<label>Serial</label>
				<input type="checkbox" name="serial" value="1"/>	
				<br>

				<label>Harga Jual</label>
				<input type="text" class="form-control border-input" name="harga_jual" placeholder="" value="">
			</div>
			<hr>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Tambah Item</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
			</div>
			</form>	
		</div>
	</div>
</div>