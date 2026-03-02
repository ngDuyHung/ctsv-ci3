 <div class="container">
 <?php 
 $logged_in=$this->session->userdata('logged_in');
		
		?>  
 
   
 <h3><?php echo $title;?></h3>
    <div class="row">
 
  <div class="col-lg-6">
    <form method="post" action="<?php echo site_url('notification/');?>">
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
		
 <a href="<?php echo site_url('notification/new_notification');?>" class="btn btn-success"><?php echo $this->lang->line('add_new');?></a><br><br>
<table class="table table-bordered">
<tr>
  <th>#</th>
  <th>Tiêu đề</th>
  
  <th>Người post</th>
  <th>Ngày có hiệu lực</th>
  <th>Ngày hết hiệu lực</th> 
  <th>Hành động</th>
</tr>
<?php 
if(count($notification_list)==0){
	?>
<tr>
 <td colspan="6"><?php echo $this->lang->line('no_record_found');?></td>
</tr>	
	
	
	<?php
}
foreach($notification_list as $key => $val){
?>
<tr>
  <td><?php echo $val['nid'];?></td>
  <td><a href="#<?php //echo $val['click_action'];?>" target="fcmclick"><?php echo $val['title'];?></a></td>
  
  <td><?php echo $val['last_name']," ",$val['first_name'];?></td>
  
  <td><?php echo $val['start_date'];?></td>
  <td><?php echo $val['end_date'];?></td>
  <td>
  <a href="<?php echo site_url('notification/edit_notification/'.$val['nid']);?>"><img src="<?php echo base_url('images/edit.png');?>"></a>
  <a href="javascript:remove_entry('notification/remove_notification/<?php echo $val['nid'];?>');"><img src="<?php echo base_url('images/cross.png');?>"></a>
  </td>
</tr>

<?php 
}
?>
</table>
 </div>

</div>


<?php
if(($limit-($this->config->item('number_of_rows')))>=0){ $back=$limit-($this->config->item('number_of_rows')); }else{ $back='0'; } ?>

<a href="<?php echo site_url('notification/index/'.$back);?>"  class="btn btn-primary"><?php echo $this->lang->line('back');?></a>
&nbsp;&nbsp;
<?php
 $next=$limit+($this->config->item('number_of_rows'));  ?>

<a href="<?php echo site_url('notification/index/'.$next);?>"  class="btn btn-primary"><?php echo $this->lang->line('next');?></a>





</div>
