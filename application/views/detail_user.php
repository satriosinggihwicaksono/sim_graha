<section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.html"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Workset</li>
              </ul>
              <div class="m-b-md">
				 <h3 class="m-b-none">DETAIL PENGGUNA</h3>
              </div>
<div class="doc-buttons">
	<?php
		$id = $this->uri->segment(3);
		$this->db->where('id',$id);
		$user = $this->db->get('user')->result_array();	
		foreach($user as $u){
			$name = $u['username']; 
		}
		if($data){
			foreach($data as $d){
				$alamat = $d['alamat'];
				$telepon = $d['telepon'];
				$id_detail = $d['id'];
				$email = $d['email'];
			}	
		}
	?>
	<form class="form-signin" method="post" action="<?php echo base_url().'index.php/welcome/add_detail_pengguna' ?>">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label>NAMA</label>
					<input name="name" id="name" class="form-control border-input" type="text" placeholder="" value="<?php echo $name; ?>" disabled> 
					<input name="id_detail" class="form-control border-input" type="hidden" placeholder="" value="<?php if($data) echo $id_detail; ?>">
					<input name="id_user" class="form-control border-input" type="hidden" placeholder="" value="<?php echo $id; ?>">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label>Alamat</label>
				<input name="alamat" id="name" class="form-control border-input" type="text" placeholder="" value="<?php if($data) echo $alamat; ?>">
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label>Telepone </label>
				<input name="telepon" class="form-control border-input" type="text" placeholder="" value="<?php if($data) echo $telepon; ?>">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label>Email </label>
				<input name="email" class="form-control border-input" type="text" placeholder="" value="<?php if($data) echo $email; ?>">
				</div>
			</div>
		</div>
		<br/>
		<input class="button-submit" type="submit" value="Submit">
	</form>
</div>	