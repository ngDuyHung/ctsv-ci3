 <script>
 	class_lst="";

	function select_faculty(facultyid) {
		$("#class_slt").html(class_lst);
		if(facultyid!="-")
		{
			options=$("#class_slt option");
			for(i=1;i<options.length;i++)
				if(options[i].getAttribute('faculty')!=facultyid)
					options[i].remove();
		}
		//$("#class_slt option[faculty!="+facultyid+"]").css('display','none');
	}
 </script>
 
 <div class="container">

   
 <h3><?php echo $title;?></h3>
   
 

  <div class="row">
     <form method="post" action="<?php echo site_url('user/insert_user/');?>">>
	
<div class="col-md-8">
<br> 
 <div class="login-panel panel panel-default">
		<div class="panel-body"> 
			<?php 
			if($this->session->flashdata('message')){
				echo $this->session->flashdata('message');	
			}
			?>	
		
			<div class="form-group">	 
					<label for="studentid" class="sr-only"><?php echo $this->lang->line('studentid');?></label> 
					<input type="text" id="studentid" name="studentid" class="form-control" placeholder="<?php echo $this->lang->line('studentid');?>" pattern="(DH|CD)\d{8}" required autofocus>
			</div>
			<div class="form-group">	 
					<label for="last_name" class="sr-only"><?php echo $this->lang->line('last_name');?></label> 
					<input type="text" id="last_name"  name="last_name"  class="form-control" placeholder="<?php echo $this->lang->line('last_name');?>" required>
			</div>
			<div class="form-group">	 
					<label for="first_name" class="sr-only"><?php echo $this->lang->line('first_name');?></label> 
					<input type="text" id="first_name" name="first_name"  class="form-control" placeholder="<?php echo $this->lang->line('first_name');?>"  required>
			</div>

			<div class="form-group">	 
					<!--<label for="birthdate" class="sr-only"><?php echo $this->lang->line('birthdate');?></label> 
					<input type="date" id="birthdate" name="birthdate" class="form-control" placeholder="Ngày sinh (yyyy-mm-dd)" style="line-height: 20px;">
					-->
					
					<!--<input type='text' class="form-control" id='datetimepicker4' />-->
					<div class='input-group date' id='datetimepickerbirthdate'>
						<input type='text' class="form-control" />
						<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar">
								</span>
						</span>
				</div>
			</div>
			<div class="form-group">	 
					<label for="inputEmail" class="sr-only"><?php echo $this->lang->line('email_address');?></label> 
					<input type="email" id="inputEmail" name="email" class="form-control" placeholder="<?php echo $this->lang->line('email_address');?>">
			</div>
			<div class="form-group">	  
					<label for="inputPassword" class="sr-only"><?php echo $this->lang->line('password');?></label>
					<input type="password" id="inputPassword" name="password"  class="form-control" placeholder="<?php echo $this->lang->line('password');?>" required >
			 </div>
				<div class="form-group">	 
					<label for="contact_no" class="sr-only"><?php echo $this->lang->line('contact_no');?></label> 
					<input type="text" id="contact_no" name="contact_no"  class="form-control" placeholder="<?php echo $this->lang->line('contact_no');?>">
			</div>
			<div class="form-group">	 
					<label for="academic_year" class="sr-only"><?php echo $this->lang->line('academic_year');?></label> 
					<input type="text"  name="academic_year"  class="form-control" placeholder="<?php echo $this->lang->line('academic_year');?> (yyyy-yyyy)" pattern="\d{4}-\d{4}" >
			</div>
			<div class="form-group">	 
					<label>Hệ đào tạo</label> 
					<select class="form-control" name="type" id="type">
						<option value="-">Chọn hệ đào tạo</option>
						<option value="Đại học">Đại học</option>
					<option value="Cao đẳng">Cao đẳng</option>
					</select>
			</div>
		
			<div class="form-group">	 
					<label   ><?php echo $this->lang->line('faculty_name');?></label> 
					<select class="form-control" name="faculty" id="faculty" onchange="select_faculty(this.value)">
					<option value="-">Chọn khoa</option>
					<?php 
					foreach($faculty_list as $val){
						?>
						
						<option value="<?php echo $val['facultyid'];?>"><?php echo $val['facultyname'];?></option>
						<?php 
					}
					?>
					
					</select>
			</div>
			<div class="form-group">	 
			
					<label   ><?php echo $this->lang->line('classid');?></label> 
					<select class="form-control" name="class_lst" id="class_slt" >
					<option value="-">Chọn lớp</option>
					<?php 	
					$class_lst = '<option value="-">Chọn lớp</option>';				
					foreach($class_list as $val){
						$class_lst .='<option value="'.$val['classid'].'" faculty="'.$val['facultyid'].'">'.$val['classid'].'</option>';
						?>
						
						<option value="<?php echo $val['classid'];?>" faculty="<?php echo $val['facultyid'];?>"><?php echo $val['classid'];?></option>
						<?php 
					}

					?>
					</select>
					<script>
						class_lst='<?php echo $class_lst;?>';
					</script>
			</div>
			
				<div class="form-group">	 
					<label   ><?php echo $this->lang->line('select_group');?></label> 
					<select class="form-control" name="gid" id="gid" onChange="getexpiry();">
					<?php 
					foreach($group_list as $key => $val){
						?>
						
						<option value="<?php echo $val['gid'];?>"><?php echo $val['group_name'];?> </option>
						<?php 
					}
					?>
					</select>
			</div>
			<div class="form-group" style="display:none">	 
					<label for="inputEmail"  >Ngày hết hạn</label> 
					<input type="text" name="subscription_expired"  id="subscription_expired" class="form-control" placeholder="<?php echo $this->lang->line('subscription_expired');?>"    autofocus>
			</div>

				<div class="form-group">	 
					<label   ><?php echo $this->lang->line('account_type');?></label> 
					<select class="form-control" name="su">
						<option value="0"><?php echo $this->lang->line('user');?></option>
						<option value="1"><?php echo $this->lang->line('administrator');?></option>
						<option value="-1" <?php if($result['su']==-1){ echo 'selected';}?>  >Người hỗ trợ</option>
					</select>
			</div>

 
	<button class="btn btn-default" type="submit"><?php echo $this->lang->line('submit');?></button>
 
		</div>
</div>
 
 
 
 
</div>
      </form>
</div>

 



</div>

<script>
getexpiry();

// document.getElementById('studentid').oninvalid = function(event) {
//     event.target.setCustomValidity('Mã sinh viên không hợp lệ');
// }
// document.getElementById('last_name').oninvalid = function(event) {
//     event.target.setCustomValidity('Họ lót không được để trống');
// }
// document.getElementById('first_name').oninvalid = function(event) {
//     event.target.setCustomValidity('Tên không được để trống');
// }
// document.getElementById('inputEmail').oninvalid = function(event) {
//     event.target.setCustomValidity('Email không hợp lệ');
// }
$(function () {
            $('#datetimepickerbirthdate').datetimepicker({
                viewMode: 'years',
								format: 'YYYY-MM-DD',
								maxDate: moment()
            		
            });
        });
</script>