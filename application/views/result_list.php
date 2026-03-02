 <div class="container">
<?php 
$logged_in=$this->session->userdata('logged_in');
?>



<h3>Kết quả các bài thi</h3>
 

  <div class="row">
 
<div class="col-md-12">
<br> 
			<?php 
		if($this->session->flashdata('message')){
			echo $this->session->flashdata('message');	
		}
		?>	
		
<table class="table table-bordered">
<tr>
 
<th>Họ và tên</th>
 <th><?php echo $this->lang->line('quiz_name');?></th>
 <th>Điểm</th>
 <th>Kết quả </th>
 <!--<th><?php echo $this->lang->line('percentage_obtained');?></th> -->
<th><?php echo $this->lang->line('action');?> </th>
</tr>
<?php 
if(count($result)==0){
	?>
<tr>
 <td colspan="5"><?php echo $this->lang->line('no_record_found');?></td>
</tr>	
	
	
	<?php
}

foreach($result as $key => $val){
	
?>
<tr>
 
<td><?php echo $val['first_name'];?> <?php echo $val['last_name'];?></td>
 <td><?php echo $val['quiz_name'];?></td>
 <td><?php echo round($val['percentage_obtained']/10,2);?></td>
 <td><?php 
 if($val['sid']=='')
 {?>
	<a href="<?php echo site_url('survey/new_survey/'.$val['rid']);?>" class="btn btn-warning" >Khảo sát </a>
 <?php 
 }else
 	echo $val['result_status'];?></td>
 <!--<td><?php echo $val['percentage_obtained'];?>%</td>-->
<td>
<?php
if($val['sid']!='')
{
?>
<a href="<?php echo site_url('result/view_result/'.$val['rid']);?>" class="btn btn-success" ><?php echo $this->lang->line('view');?> </a>
<?php } ?>
</td>
</tr>

<?php 
}
?>
</table>
</div>

</div>



</div>