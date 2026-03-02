 <div class="container">

   
 <h3><?php echo $title;?></h3>


  <div class="row">

<div class="col-md-12">
<br> 
			<?php 
		if($this->session->flashdata('message')){
			echo $this->session->flashdata('message');	
		}
		?>	
		<div id="message"></div>
<form action="<?php echo site_url('quiz/insert_time/');?>" method="post">
<div class="row">
		<div class="col-md-3">
			<div class="form-group">	 
				<label><?php echo $this->lang->line('select_group');?></label> <br>
				<select name="group" id="group" class="form-control" onchange="choose_group(this.value)" required>
					<option value="">Chọn nhóm</option>
					<?php
					foreach ($group_list as $key => $group) {
						echo '<option value="',$group['gid'],'">',$group['group_name'],'</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">	 
				<label>Chọn khoa</label> <br>
				<select name="faculty" id="faculty" class="form-control" required>
					<option value="">Chọn khoa</option>
					<?php
					
					foreach ($faculty_list as $key => $faculty) {
						echo '<option value="',$faculty['facultyid'],'">',$faculty['facultyname'],'</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">	 
						<label for="title">Ngày bắt đầu được thi</label> 	
							<div class='input-group date' id='startdate'>
								<input type='text' id="txtstartdate" name="txtstartdate" class="form-control" required />
								<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar">
										</span>
								</span>
						</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">	 
						<label for="title"><?php echo $this->lang->line('end_date');?></label> 	
						<div class='input-group date' id='enddate'>
						
							<input type='text' id="txtenddate" name="txtenddate" class="form-control" required />
							<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar">
									</span>
							</span>
						</div>
			</div>
		</div>
		<br>
	<div class="row">
		<div class="col-sm-12" style="text-align:center">
			
				<input type="submit" value="Thêm" name="save" class="btn btn-success">
			
		</div>
	</div>
		<br>
</form>
</div>	
<table class="table table-bordered">
<tr>
 	<th>Nhóm</th>
	<th>Khoa</th>
	<th>Ngày giờ bắt đầu</th>
	<th>Ngày giờ kết thúc</th>
	<th>Hành động</th>
</tr>

<?php 
if(count($time_list)==0){
	?>
<tr>
 <td colspan="5"><?php echo $this->lang->line('no_record_found');?></td>
</tr>	
	
	
	<?php
}

foreach($time_list as $key => $val){
?>
<tr>
 	<td><?php echo $val['group_name'];?></td>
	<td><?php echo $val['facultyname'];?></td>
	<td><?php echo $val['start_date'];?></td>
	<td><?php echo $val['end_date'];?></td>
	<td align="center">
		<a href="<?php echo site_url('quiz/remove_time/'.$val['id']);?>"><img src="<?php echo base_url('images/cross.png');?>"></a>
	</td>
</tr>

<?php 
}
?>

</table>
</form>
</div>

</div>



</div>
<script>
$(function () {
	$('#startdate').datetimepicker({
			defaultDate: moment(),
			format: 'YYYY-MM-DD H:m:s',
			//minDate: moment(),
			inline: false,
      sideBySide: true,
			
			
	});
});
$(function () {
	
	$('#enddate').datetimepicker({
			
			format: 'YYYY-MM-DD H:m:s',
			minDate: moment(),
			inline: false,
      sideBySide: true
			
	});
});
function choose_group(gid) {
	var formData = {gid:gid};
	$.ajax({
		 type: "POST",
		 data : formData,
			url: base_url + "faculty/group_faculty/",
		success: function(data){
		//$('#no_q_available').html(data);
			console.log(data);
			},
		error: function(xhr,status,strErr){
			//alert(status);
			}	
		});
}
</script>