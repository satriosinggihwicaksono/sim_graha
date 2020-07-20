<!--- NOTA -->
<?php 
$this->db->where('status',1);
$this->db->where('id_stor', (int)$d['id_stor']);
$kredit_cabang = $this->db->get('kas')->result_array();


$this->db->where('status',2);
$this->db->where('id_stor', $d['id_stor']);
$debit_cabang = $this->db->get('kas')->result_array();

?>
<div class="row">
  <div id="modal-editstor<?=$d['id_stor'];?>" class="modal fade">
	<div class="modal-dialog">
	  <form action="<?php echo base_url().'index.php/welcome/edit_stor/'; ?>" method="post">
	  <div class="modal-content">
		<div class="modal-header bg-primary">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">STOR KAS</h4>
		</div>
		<div class="modal-body">
			  <div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Tanggal Kas</label><br>
						<input type="date" name="waktu" value="<?php echo date('Y-m-d', strtotime($d['waktu'])); ?>" />
						<input type="hidden" name="id_kredit" value="<?php echo $kredit_cabang[0]['id']; ?>" />
						<input type="hidden" name="id_debit" value="<?php echo $debit_cabang[0]['id']; ?>" />
						<input type="hidden" name="id_stor" value="<?php echo $d['id_stor']; ?>" />
					</div>
				</div>
				<?php if($check_admin){?>
				<div class="col-md-2">
					<div class="form-group">
						<label>Dari <li class ="fa fa-arrow-circle-right"></li></label><br>
							<select name="f_cabang">
								<?php	
								$this->db->where('hakakses !=', 2);
								$this->db->where('hakakses !=', 3);
								$user = $this->db->get('user')->result_array();
								foreach($user as $c){ 
								?>
								<option <?php if( $kredit_cabang[0]['id_cabang'] === $c['id']) { ?> selected="selected" <?php } ?> value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
								<?php } ?>
							</select>
							</div>
					</div>
							<?php } else { ?>
								<input type="hidden" name="f_cabang" class="form-control border-input" value="<?php echo $kredit_cabang[0]['id_cabang']; ?>">
							<?php } ?>
				<div class="col-md-2">
					<div class="form-group">
						<label>Ke</label><br>
						<select name="t_cabang">
							<?php	
							$this->db->where('hakakses', 0);
							$user = $this->db->get('user')->result_array();
							foreach($user as $c){ 
							?>
						<option <?php if( $debit_cabang[0]['id_cabang'] === $c['id']) { ?> selected="selected" <?php } ?>  value='<?php echo $c['id']; ?>'><?php echo $c['username']; ?></option>
						<?php } ?>
					</select>
					</div>
				</div>  
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Nominal</label><br>
						<input type="text" name="saldo" value="<?php echo $d['saldo']; ?>"/>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Cash / Transfer</label><br>
						<select name="tipe">
							<?php
								$this->db->where('id', $d['id_stor']);
								$stor = $this->db->get('stor')->result_array();
								$tipe = array('','Cash','Transfer');
								$count_tipe = count($tipe);
								for($x=1; $x < $count_tipe; $x+=1){
							?>
							<option <?php if($x == $stor[0]['tipe']) { ?> selected="selected" <?php } ?> value="<?php echo $x; ?>"><?php echo $tipe[$x]; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>	
			</div>
			<div class="row">
				<div class="col-md-7">
					<div class="form-group">
						<label>Keterangan</label><br>
						<textarea name="deskripsi" cols="35%"><?php echo $d['deskripsi']; ?></textarea>
					</div>
				</div>
			</div>	
		  <br>
		</div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-warning"><i class="icon-pencil5"></i> Add</button>
		  </div>
	</div> 
		</form>
	</div>	
  </div>
</div>
<!--- !NOTA -->