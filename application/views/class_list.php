 <div class="container">

   
 <h3><?php echo $title;?></h3>
 <div class="row">
    <div class="col-lg-6">
      <form method="post" action="<?php echo site_url('classroom');?>">
        <div class="input-group">
          <input type="text" class="form-control" name="search" placeholder="<?php echo $this->lang->line('search');?>...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><?php echo $this->lang->line('search');?></button>
          </span>
        </div><!-- /input-group -->
	    </form>
    </div><!-- /.col-lg-6 -->
  </div><!-- /.row -->

  <div class="row">
 
<div class="col-md-12">
<br> 
			<?php 
		if($this->session->flashdata('message')){
			echo $this->session->flashdata('message');	
		}
		?>	
		<div id="message"></div>
		
		 <form method="post" action="<?php echo site_url('classroom/insert_class/');?>">
	
<table class="table table-bordered">
<tr>
 <th><?php echo $this->lang->line('classid');?></th>
 <th><?php echo $this->lang->line('faculty_name');?></th>
<th align="center"><?php echo $this->lang->line('action');?> </th>
</tr>
<tr>
 	<td>
 		<input type="text" class="form-control"   name="classid" value="" placeholder="<?php echo $this->lang->line('classid');?>"  required >
	</td>
	<td>
		 <select name="faculty" id="faculty" class="form-control" >
		 	<option value="">Hãy chọn khoa</option>
			 <?php
			 foreach($faculty_list as $val)
			 		echo '<option value="'.$val['facultyid'].'">'.$val['facultyname'].'</option>';
			 ?>
		 </select>
	</td>
	<td align="center">
		<button class="btn btn-default" type="submit"><?php echo $this->lang->line('add_new');?></button>
	</td>
</tr>
<?php 
if(count($class_list)==0){
	?>
<tr>
 <td colspan="3"><?php echo $this->lang->line('no_record_found');?></td>
</tr>	
	
	
	<?php
}

foreach($class_list as $key => $val){
?>
<tr>
 <td><input type="text"   class="form-control"  value="<?php echo $val['classid'];?>"  ></td>
 <td><input type="text"   class="form-control"  value="<?php echo $val['facultyname'];?>"  ></td>
<td style="padding-top:15px" align="center">
 
<a href="javascript:remove_entry('<?php echo 'classroom/remove_class/'.$val['classid'];?>')"><img src="<?php echo base_url('images/cross.png');?>"></a>

</td>
</tr>

<?php 
}
?>

</table>
</form>
</div>

</div>


<?php
if(($limit-($this->config->item('number_of_rows')))>=0){ $back=$limit-($this->config->item('number_of_rows')); }else{ $back='0'; } ?>

<a href="<?php echo site_url('classroom/index/'.$back);?>"  class="btn btn-primary"><?php echo $this->lang->line('back');?></a>
&nbsp;&nbsp;
<?php
 $next=$limit+($this->config->item('number_of_rows'));  ?>

<a href="<?php echo site_url('classroom/index/'.$next);?>"  class="btn btn-primary"><?php echo $this->lang->line('next');?></a>
<br/><br/><br/>
</div>

