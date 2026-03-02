 <div class="container">

   
 <h3><?php echo $title;?></h3>
   
 

  <div class="row">
     <form method="post" action="<?php echo site_url('user/update_user/'.$uid);?>">
	
<div class="col-md-8">
<br> 
 <div class="login-panel panel panel-default">
		<div class="panel-body"> 	
			<?php 
		if($this->session->flashdata('message')){
			echo $this->session->flashdata('message');	
		}
		$user_data=$this->session->userdata('logged_in');
		//print_r($result);exit;
		?>	
		
				<div class="form-group">	 
				<?php echo $this->lang->line('group_name');?>: <?php echo $result['group_name'];?> 
				</div>
				
				
			<div class="form-group">	 
				<label for="studentid" >MSSV</label> 
				<input type="text" id="studentid" name="studentid" value="<?php echo $result['studentid'];?>" class="form-control" placeholder="MSSV" autofocus>
			</div>
			<div class="form-group">	 
				<label for="inputEmail" ><?php echo $this->lang->line('email_address');?></label> 
				<input type="email" id="inputEmail" name="email" value="<?php echo $result['email'];?>" class="form-control" placeholder="<?php echo $this->lang->line('email_address');?>" autofocus>
			</div>
			<div class="form-group">	  
					<label for="inputPassword" ><?php echo $this->lang->line('password');?></label>
					<input type="password" id="inputPassword" name="password"   value=""  class="form-control" placeholder="<?php echo $this->lang->line('password');?>"   >
			 </div>
			 <div class="form-group">	 
					<label for="inputEmail" ><?php echo $this->lang->line('last_name');?></label> 
					<input type="text"   name="last_name"  class="form-control"  value="<?php echo $result['last_name'];?>"  placeholder="<?php echo $this->lang->line('last_name');?>"   autofocus>
			</div>
				<div class="form-group">	 
					<label for="inputEmail" ><?php echo $this->lang->line('first_name');?></label> 
					<input type="text"  name="first_name"  class="form-control"  value="<?php echo $result['first_name'];?>"  placeholder="<?php echo $this->lang->line('first_name');?>"   autofocus>
			</div>
				
				<div class="form-group">	 
					<label for="inputEmail" ><?php echo $this->lang->line('contact_no');?></label> 
					<input type="text" name="contact_no"  class="form-control"  value="<?php echo $result['contact_no'];?>"  placeholder="<?php echo $this->lang->line('contact_no');?>"   autofocus>
			</div>
			<?php
			if($user_data['su']=='1')
			{
			?>
				<div class="form-group">	 
					<label for="facultyid">Khoa</label>
					<select class="form-control" name="facultyid"  onChange="choose_faculty();" id="facultyid">
					<option value="">Hãy chọn khoa</option> 
					<?php 
					foreach($faculty_list as $key => $val){
						?>
						
						<option value="<?php echo $val['facultyid'];?>" <?php if($result['facultyid']==$val['facultyid']){ echo 'selected';}?> ><?php echo $val['facultyname'];?> </option>
						<?php 
					}
					?>
					</select>
			</div>
		<?php
		}
		?>
		<?php
		
			if($user_data['su']=='1')
			{
				//print_r($class_list);exit;
				
			?>
				<div class="form-group">	 
					<label for="classid">Lớp</label>
					<select class="form-control" name="classid"   id="classid">
					<option value="">Hãy chọn lớp</option> 
					<?php 
					echo $result['classid'],"<br>";
					foreach($class_list as $key => $val){
						?>
						
						<option value="<?php echo $val['classid'];?>" <?php if($result['classid']==$val['classid']){ echo 'selected';}?> ><?php echo $val['classid'];?> </option> 
						<?php 
					}
					?>
					</select> 
			</div>
		<?php
		}
		?>
			<?php
			if($user_data['su']=='1')
			{
			?>
				<div class="form-group">	 
					<label   ><?php echo $this->lang->line('select_group');?></label> 
					<select class="form-control" name="gid"  onChange="getexpiry();" id="gid">
					<?php 
					foreach($group_list as $key => $val){
						?>
						
						<option value="<?php echo $val['gid'];?>" <?php if($result['gid']==$val['gid']){ echo 'selected';}?> ><?php echo $val['group_name'];?> </option>
						<?php 
					}
					?>
					</select>
			</div>
		<?php
		}
		?>
		<!--	<div class="form-group">	 
					<label for="subscription_expired"  ><?php echo $this->lang->line('subscription_expired');?></label> 
					<input type="text" name="subscription_expired"  id="subscription_expired" class="form-control" value="<?php if($result['subscription_expired']!='0'){ echo date('Y-m-d',$result['subscription_expired']); }else{ echo '0';} ?>" placeholder="<?php echo $this->lang->line('subscription_expired');?>"  value=""  autofocus>
			</div>
				-->

				<div class="form-group">	 
					<label   ><?php echo $this->lang->line('account_type');?></label> 
					<select class="form-control" name="su">
						<option value="0" <?php if($result['su']==0){ echo 'selected';}?>  ><?php echo $this->lang->line('user');?></option>
						<option value="1" <?php if($result['su']==1){ echo 'selected';}?>  ><?php echo $this->lang->line('administrator');?></option>
					</select>
			</div>

 				<div class="form-group">	 
					<label   ><?php echo $this->lang->line('account_status');?></label> 
					<select class="form-control" name="user_status">
						<option value="Active" <?php if($result['user_status']=='Active'){ echo 'selected';}?>  ><?php echo $this->lang->line('active');?></option>
						<option value="Inactive" <?php if($result['user_status']=='Inactive'){ echo 'selected';}?>  ><?php echo $this->lang->line('inactive');?></option>
					</select>
			</div>

 
	<button class="btn btn-default" type="submit"><?php echo $this->lang->line('submit');?></button>
 
		</div>
</div>
 
 
 
 
</div>
      </form>
</div>






 



</div>
