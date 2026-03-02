 <div class="container">

   
 <h3><?php echo $title;?></h3>


  <div class="row">
 
<div class="col-md-8">
<br> 
			<?php 
		if($this->session->flashdata('message')){
			echo $this->session->flashdata('message');	
		}
		?>	
		<div id="message"></div>
<form method="post" action="<?php echo site_url('faculty/insert_faculty/');?>">	
<table class="table table-bordered">

<tr>
 	<th >Mã khoa</th>
	<th>Tên khoa</th>
	
	<th ><?php echo $this->lang->line('action');?> </th>
</tr>

<?php 
if(count($notification_list)==0){
	?>
<tr>
 <td colspan="3"><?php echo $this->lang->line('no_record_found');?></td>
</tr>	
	
	
	<?php
}

foreach($faculty_list as $key => $val){
?>
<tr>
 	<td><?php echo $val['facultyid'];?></td>
	<td><?php echo $val['facultyname'];?></td>
	<td align="center">
		<a href="<?php echo site_url('faculty/remove_faculty/'.$val['facultyid']);?>"><img src="<?php echo base_url('images/cross.png');?>"></a>
	</td>
</tr>

<?php 
}
?>
<tr>
	<td>
		<input type="text"   class="form-control"   name="facultyid" value="" placeholder="Mã khoa"  required >		
	</td>
 <td>
 <input type="text"   class="form-control"   name="facultyname" value="" placeholder="Tên khoa"  required ></td>
<td>
<button class="btn btn-default" type="submit"><?php echo $this->lang->line('add_new');?></button>
 
</td>
</tr>
</table>
</form>
</div>

</div>



</div>
