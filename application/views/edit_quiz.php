 <div class="container">
<?php
function catChuoi($s,$n=50)
{
  if(strlen($s)<=$n)
    return $s;
  $arr=explode(" ",$s);
  $re=array();
  $i=0;
  $l=0;
  while($i<count($arr))
  {
    if(($l+strlen($arr[$i])+1)>$n)
      return implode(" ",$re)."...";
    $re[]=$arr[$i++];
    $l+=strlen($arr[$i])+1;
  }
  return implode(" ",$re)."...";
}

?>

   
 <h3><?php echo $title;?></h3>
   
 

  <div class="row">
     <form method="post" action="<?php echo site_url('quiz/update_quiz/'.$quiz['quid']);?>">
	
<div class="col-md-12">
<br> 
 <div class="login-panel panel panel-default">
		<div class="panel-body"> 
	
	
	

			<?php 
		if($this->session->flashdata('message')){
			echo $this->session->flashdata('message');	
		}
		?>	
		
		<?php if($quiz['with_login']==0){ ?>
		
		 			<div class="form-group">	 
					<label for="inputEmail" ><?php echo $this->lang->line('open_quiz_url');?></label> 
					<input type="text"  onClick="this.select()" value="<?php echo site_url('quiz/quiz_detail/'.$quiz['quid'].'/'.urlencode($quiz['quiz_name'])); ?>" class="form-control"  >
			</div>
			
		<?php 
		}
		?>
		 			<div class="form-group">	 
					<label for="inputEmail" ><?php echo $this->lang->line('quiz_name');?></label> 
					<input type="text"  name="quiz_name"  value="<?php echo $quiz['quiz_name'];?>" class="form-control" placeholder="<?php echo $this->lang->line('quiz_name');?>"  required autofocus>
			</div>
				<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('description');?></label> 
					<textarea   name="description"  class="form-control tinymce_textarea" ><?php echo $quiz['description'];?></textarea>
			</div>
			
		<!--
			<div class="form-group">	 
						<label for="title">Ngày bắt đầu được thi</label> 	
							<div class='input-group date' id='startdate'>
								<input type='text' id="txtstartdate" name="txtstartdate" class="form-control" />
								<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar">
										</span>
								</span>
						</div>
				</div>
				<div class="form-group">	 
						<label for="title"><?php echo $this->lang->line('end_date');?></label> 	
						<div class='input-group date' id='enddate'>
						
							<input type='text' id="txtenddate" name="txtenddate" class="form-control" />
							<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar">
									</span>
							</span>
					</div>
				</div>
		-->
				<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('duration');?></label> 
					<input type="text" name="duration"  value="<?php echo $quiz['duration'];?>" class="form-control" placeholder="<?php echo $this->lang->line('duration');?>"  required  >
			</div>
				<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('maximum_attempts');?></label> 
					<input type="text" name="maximum_attempts"  value="<?php echo $quiz['maximum_attempts'];?>" class="form-control" placeholder="<?php echo $this->lang->line('maximum_attempts');?>"   required >
			</div>
				<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('pass_percentage');?></label> 
					<input type="text" name="pass_percentage" value="<?php echo $quiz['pass_percentage'];?>" class="form-control" placeholder="<?php echo $this->lang->line('pass_percentage');?>"   required >
			</div>
				<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('correct_score');?></label> 
					<input type="text" name="correct_score"  value="<?php echo $quiz['correct_score'];?>" class="form-control" placeholder="<?php echo $this->lang->line('correct_score');?>"   required >
			</div>
				<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('incorrect_score');?></label> 
					<input type="text" name="incorrect_score"  value="<?php echo $quiz['incorrect_score'];?>" class="form-control" placeholder="<?php echo $this->lang->line('incorrect_score');?>"  required  >
			</div>
			<!--	<div class="form-group">	 
					<label for="inputEmail"  ><?php echo $this->lang->line('ip_address');?></label> 
					<input type="text" name="ip_address"  value="<?php echo $quiz['ip_address'];?>" class="form-control" placeholder="<?php echo $this->lang->line('ip_address');?>"    >
			</div> -->
				<div class="form-group">	 
					<label for="inputEmail" ><?php echo $this->lang->line('view_answer');?></label> <br>
					<input type="radio" name="view_answer"    value="1" <?php if($quiz['view_answer']==1){ echo 'checked'; } ?>  > <?php echo $this->lang->line('yes');?>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="view_answer"    value="0"   <?php if($quiz['view_answer']==0){ echo 'checked'; } ?>  > <?php echo $this->lang->line('no');?>
			</div>
			<!--<div class="form-group">	 
					<label for="inputEmail" ><?php echo $this->lang->line('open_quiz');?></label> <br>
					<input type="radio" name="with_login"    value="0"  <?php if($quiz['with_login']==0){ echo 'checked'; } ?>   > <?php echo $this->lang->line('yes');?>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="with_login"    value="1" <?php if($quiz['with_login']==1){ echo 'checked'; } ?>  > <?php echo $this->lang->line('no');?>
			</div>
			
						<?php 
			//if($this->config->item('webcam')==true){
				?>
				<div class="form-group">	 
					<label for="inputEmail" ><?php echo $this->lang->line('capture_photo');?></label> <br>
					<input type="radio" name="camera_req"    value="1"   <?php if($quiz['camera_req']==1){ echo 'checked'; } ?>  > <?php echo $this->lang->line('yes');?>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="camera_req"    value="0"   <?php if($quiz['camera_req']==0){ echo 'checked'; } ?>    > <?php echo $this->lang->line('no');?>
			</div>
			
						<?php 
			//}else{
				?>
				<input type="hidden" name="camera_req" value="0">
				
				<?php 
		//	}
			?>
			-->
			
				<div class="form-group">	 
					<label   ><?php echo $this->lang->line('select_group');?></label> <br>
					 <?php 
					foreach($group_list as $key => $val){
						?>
						
						 <input type="checkbox" name="gids[]" value="<?php echo $val['gid'];?>" <?php if(in_array($val['gid'],explode(',',$quiz['gids']))){ echo 'checked'; } ?> > <?php echo $val['group_name'];?> &nbsp;&nbsp;&nbsp;
						<?php 
					}
					?>
					 
			</div>
		<!--	<div class="form-group">	 
					<label   ><?php echo $this->lang->line('quiz_template');?></label> <br>
					<select name="quiz_template">
					<option value="Default" <?php if($quiz['quiz_template']=="Default"){ echo 'selected';} ?> >Default</option>
					<option value="Advance"  <?php if($quiz['quiz_template']=="Advance"){ echo 'selected';} ?> >Advance</option>
					
					</select>
</div>-->


			
			

			
			

		<hr>
<br><br><br>
<?php 
if($quiz['question_selection']=='0'){
 
?>
<h4><?php echo $this->lang->line('questions_added_into_quiz');?></h4>
<a href="<?php echo site_url('quiz/add_question/'.$quiz['quid']);?>" class="btn btn-danger"  ><?php echo $this->lang->line('add_question_into_quiz');?></a>
  
<table class="table table-bordered" style="margin-top:10px;">
<tr>
   <th>#</th>
 <th><?php echo $this->lang->line('question');?></th>
<th><?php echo $this->lang->line('question_type');?></th>
<th><?php echo $this->lang->line('category_name');?></th>
<th><?php echo $this->lang->line('level_name');?></th>
<th> <?php echo $this->lang->line('correct');?> </th>
<th><?php echo $this->lang->line('incorrect');?> </th>

<th><?php echo $this->lang->line('action');?> </th>
</tr>
<?php 
if(count($questions)==0){
	?>
<tr>
 <td colspan="6"><?php echo $this->lang->line('no_question_added');?></td>
</tr>	
	
	
	<?php
}
foreach($questions as $key => $val){
?>
<tr>
 <td><?php echo $val['qid'];?></td>
 <td><?php //echo substr(strip_tags($val['question']),0,50);
	echo catChuoi(strip_tags($val['question']),50);
?></td>
<td><?php echo $val['question_type'];?></td>
<td><?php echo $val['category_name'];?></td>
<td><?php echo $val['level_name'];?></td>
<td><input type="text" style="width:60px;" name="i_correct[]" value="<?php if(isset($quiz['correct_score'][$key])){ echo explode(',',$quiz['correct_score'])[$key]; }else{ echo '1'; } ?>"> </td>
<td><input type="text" style="width:60px;" name="i_incorrect[]" value="<?php if(isset($quiz['incorrect_score'][$key])){ echo explode(',',$quiz['incorrect_score'])[$key]; }else{ echo '0'; } ?>"> </td>
<td>
 
 <a href="<?php echo site_url('quiz/remove_qid/'.$quiz['quid'].'/'.$val['qid']);?>" title="<?php echo $this->lang->line('remove_from_quiz');?>"><img src="<?php echo base_url('images/cross.png');?>"></a>
 <?php 
 if($key==0){
	 ?>
	 <img src="<?php echo base_url();?>images/empty.png" title="">
	 <?php 
 }else{
	 ?>
 <a href="javascript:cancelmove('Up','<?php echo $quiz['quid'];?>','<?php echo $val['qid'];?>','<?php echo $key+1;?>');"><img src="<?php echo base_url();?>images/up.png" title="<?php echo $this->lang->line('up');?>"></a>
<?php 
 }
  
if($key==(count(explode(',',$quiz['qids']))-1)){ 
?>
<?php 
}else{
	?>
<a href="javascript:cancelmove('Down','<?php echo $quiz['quid'];?>','<?php echo $val['qid'];?>','<?php echo $key+1;?>');"><img src="<?php echo base_url();?>images/down.png" title="<?php echo $this->lang->line('down');?>"></a>
<?php 
}
?>
</td>
</tr>

<?php 
}
?>
</table>

<?php
}else{
	
	?>
<h4><?php echo $this->lang->line('questions_added_into_quiz');?></h4><br> 
	
	<?php 
if(count($qcl)==0){
	   echo $this->lang->line('no_question_added').'<br><br>'; 

}	
	foreach($qcl as $k => $vall){
		
		?>
		
						<div class="form-group">	 
				 	<select   name="cid[]" >
					<option value="0"><?php echo $this->lang->line('select');?> <?php echo $this->lang->line('category_name');?></option>
					<?php 
					foreach($category_list as $key => $val){
						?>
						
						<option value="<?php echo $val['cid'];?>"   <?php if($val['cid']==$vall['cid']){ echo 'selected'; } ?>  ><?php echo $val['category_name'];?></option>
						<?php 
					}
					?>
					</select>
			 	<!--<select  name="lid[]" >
				<option value="0"><?php echo $this->lang->line('select');?> <?php echo $this->lang->line('level_name');?></option>
					<?php 
					foreach($level_list as $key => $val){
						?>
						
						<option value="<?php echo $val['lid'];?>"   <?php if($val['lid']==$vall['lid']){ echo 'selected'; } ?> ><?php echo $val['level_name'];?></option>
						<?php 
					}
					?>
					</select>
					-->
					  <?php echo $this->lang->line('no_questions_added');?>
					  <select name="noq[]">
					  <option value="<?php echo $vall['noq'];?>"><?php echo $vall['noq'];?></option>
					  <option value="0">0</option>
					  </select>
					  
<?php echo $this->lang->line('correct');?> <input type="text" name="i_correct[]" style="width:40px;" value="<?php echo $vall['i_correct'];?>"> | <?php echo $this->lang->line('incorrect');?> <input type="text" style="width:40px;" name="i_incorrect[]" value="<?php echo $vall['i_incorrect'];?>"> 


			</div>
<hr>
		
		
		
		<?php 
	}
	?>
					<div class="form-group">	 
				 	<select   name="cid[]" id="cid" onChange="no_q_available(1);">
					<option value="0"><?php echo $this->lang->line('select');?> <?php echo $this->lang->line('category_name');?></option>
					<?php 
					foreach($category_list as $key => $val){
						?>
						
						<option value="<?php echo $val['cid'];?>"   ><?php echo $val['category_name'];?></option>
						<?php 
					}
					?>
					</select>
			 	<!--<select  name="lid[]" onChange="no_q_available(this.value);">
				<option value="0"><?php echo $this->lang->line('select');?> <?php echo $this->lang->line('level_name');?></option>
					<?php 
					foreach($level_list as $key => $val){
						?>
						
						<option value="<?php echo $val['lid'];?>"   ><?php echo $val['level_name'];?></option>
						<?php 
					}
					?>
					</select>-->
					 <br><br>
					  <?php echo $this->lang->line('no_questions_available');?>
					 <span id="no_q_available"></span>
					 <br>
					 <br>
<?php echo $this->lang->line('correct_score');?> <input type="text" name="i_correct[]" style="width:40px;" value="1"> | <?php echo $this->lang->line('incorrect_score');?><input type="text" style="width:40px;" name="i_incorrect[]" value="0"> 

			</div>

	
	
	
	<?php 
	
}

?>	
			 

 
	<button class="btn btn-success" type="submit"><?php echo $this->lang->line('submit');?></button>
 <br><br><br>
 <?php //echo $this->lang->line('open_quiz_warning');?>
		</div>
</div>
 
 
 
 
</div>
      </form>
</div>
</div>

<script>
$(function () {

	// alert(start_date);
	$('#startdate').datetimepicker({
			format: 'YYYY-MM-DD H:m:s',
			defaultDate:'<?php echo $quiz['start_date'];?>',
			inline: false,
      sideBySide: true,
			useCurrent: false,
			
	});
});
$(function () {
	
	$('#enddate').datetimepicker({
			
			format: 'YYYY-MM-DD H:m:s',
			defaultDate:'<?php echo  $quiz['end_date'];?>',
			
			inline: false,
      sideBySide: true
			
	});
});
</script>