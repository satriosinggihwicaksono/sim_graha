<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="normal-table-list">
				<div class="basic-tb-hd">
					<form method="POST" action="<?php echo base_url().'index.php/kas/tambah_tempat_kas' ?>">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Tempat Kas</label>
									<div class="nk-int-st">
										<input type="text" class="form-control border-input" style="width:40%;" name="tempat_kas" placeholder="" value="">
									</div>
								</div>
							</div>
						</div>
						<button type="submit" class="btn btn-info btn-fill btn-wd">Tambah Tempat Kas</button>
					</form>
				</div>
				<div class="bsc-tbl">
					<table class="table table-sc-ex">
						<thead style='background-color:#FFEB3B'>
							<tr>
								<th>No</th>
								<th>Tempat Kas</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php  
								$x = $this->uri->segment('3') + 1;
								foreach($data as $k){
							?>
							<tr>
								<td style="width:2%;"><?php echo $x++ ?></td>
								<form method="POST" action="<?php echo base_url().'index.php/kas/update_tempat_kas/'.$k['id'] ?>">
								<td style="width:25%;">
									<div class="nk-int-st">
										<input type='text' class="form-control input-sm" name='tempat_kas' value='<?php echo $k['nama']; ?>' />
									</div>
								</td>
								<td style="width:25%;">
									<button type='submit' class="btn btn-info fa fa-wrench"> Ubah</button>	
									<a href='<?php echo base_url().'index.php/persedian_page/delete_item/tempat_kas/'.$k['id']; ?>' onclick="return confirm('Are you sure delete this item?');"><span class="btn btn-danger fa fa-trash-o"> Hapus</span></a>
								</td>
								</form>	
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="text-center">
		<?php
			$page = $this->uri->segment(3) - 10;
			$href = base_url().'index.php/persedian_page/'.$this->uri->segment(2).'/'.$page;
			echo $this->pagination->create_links();
		?>
	</div>
</div>	