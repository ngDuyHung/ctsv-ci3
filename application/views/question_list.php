 <div class="container">

   
 <h3><?php echo $title;?> &nbsp;  &nbsp;  &nbsp; 
<a href='/qbank/new_question_1/4' class='btn btn-default'>Thêm mới</a>
</h3>
  <div class="row">
 
  <div class="col-md-6">
    <form method="post" action="<?php echo site_url('qbank/index/');?>">
			<div class="input-group">
    		<input type="text" class="form-control" name="search" placeholder="<?php echo $this->lang->line('search');?>...">
      	<span class="input-group-btn">
        	<button class="btn btn-default" type="submit"><?php echo $this->lang->line('search');?></button>
      	</span>
    	</div><!-- /input-group -->
	 	</form>
	</div><!-- /.col-md-6 -->
	<div class="col-md-6">
		<form method="post"  action="<?php echo site_url('qbank/index/');?>">
			<div class="input-group">
				<select   name="cid" class="form-control" onChange="choose_category(this.value);">
					<option value="0"><?php echo $this->lang->line('all_category');?></option>
					<?php 
					foreach($category_list as $key => $val){
						?>
						
						<option value="<?php echo $val['cid'];?>" <?php if($val['cid']==$cid){ echo 'selected';} ?> ><?php echo $val['category_name'];?></option>
						<?php 
					}
					?>
				</select></div>
		</form>
	</div><!-- /.col-md-6 -->
</div><!-- /.row -->


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
 				<th>#</th>
 				<th><?php echo $this->lang->line('question');?></th>
				<th><?php echo $this->lang->line('category_name');?></th>
				<th><?php echo $this->lang->line('percent_corrected');?></th>
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
		<td><?php echo $val['qid'];?>  <a href="javascript:show_question_stat('<?php echo $val['qid'];?>');">...</a>  </td>
		<td><?php echo sub_str(strip_tags($val['question']),60);?>
		
		
		<span style="display:none;" id="stat-<?php echo $val['qid'];?>"> 
			<table class="table table-bordered">
				<tr><td><?php echo $this->lang->line('no_times_corrected');?></td><td><?php echo $val['no_time_corrected'];?></td></tr>
				<tr><td><?php echo $this->lang->line('no_times_incorrected');?></td><td><?php echo $val['no_time_incorrected'];?></td></tr>
				<tr><td><?php echo $this->lang->line('no_times_unattempted');?></td><td><?php echo $val['no_time_unattempted'];?></td></tr>
			</table>
		</span> 
		</td>
		<td><?php echo $val['category_name'];?> </td>
		
		<td><?php if($val['no_time_served']!='0'){ $perc=($val['no_time_corrected']/$val['no_time_served'])*100; 
		?>

		<div style="background:#eeeeee;width:100%;height:10px;"><div style="background:#449d44;width:<?php echo intval($perc);?>%;height:10px;"></div>
		<span style="font-size:10px;"><?php echo intval($perc);?>%</span>

		<?php
		}else{ echo $this->lang->line('not_used');} ?></td>

		<td>
		<?php 
		$qn=1;
		if($val['question_type']==$this->lang->line('multiple_choice_single_answer')){
			$qn=1;
		}
		if($val['question_type']==$this->lang->line('multiple_choice_multiple_answer')){
			$qn=2;
		}
		if($val['question_type']==$this->lang->line('match_the_column')){
			$qn=3;
		}
		if($val['question_type']==$this->lang->line('short_answer')){
			$qn=4;
		}
		if($val['question_type']==$this->lang->line('long_answer')){
			$qn=5;
		}


		?>
		<a href="<?php echo site_url('qbank/edit_question_'.$qn.'/'.$val['qid']);?>"><img src="<?php echo base_url('images/edit.png');?>"></a>
		<a href="javascript:remove_entry('qbank/remove_question/<?php echo $val['qid'];?>');"><img src="<?php echo base_url('images/cross.png');?>"></a>

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

<a href="<?php echo site_url('qbank/index/'.$back.'/'.$cid.'/'.$lid);?>"  class="btn btn-primary"><?php echo $this->lang->line('back');?></a>
&nbsp;&nbsp;
<?php
 $next=$limit+($this->config->item('number_of_rows'));  ?>

<a href="<?php echo site_url('qbank/index/'.$next.'/'.$cid.'/'.$lid);?>"  class="btn btn-primary"><?php echo $this->lang->line('next');?></a>







<br><br><br><br>
<div class="login-panel panel panel-default">
	<div class="panel-heading">
<h4><?php echo $this->lang->line('import_question');?></h4> 
</div>
	<div class="panel-body"> 

<?php echo form_open('qbank/import',array('enctype'=>'multipart/form-data')); ?>
  
 <select name="cid"  required >
 <option value=""><?php echo $this->lang->line('select_category');?></option>
<?php 
					foreach($category_list as $key => $val){
						?>
						
						<option value="<?php echo $val['cid'];?>" <?php if($val['cid']==$cid){ echo 'selected';} ?> ><?php echo $val['category_name'];?></option>
						<?php 
					}
					?></select>
 <select name="did"  required >
 <option value=""><?php echo $this->lang->line('select_level');?></option>
<?php 
					foreach($level_list as $key => $val){
						?>
						
						<option value="<?php echo $val['lid'];?>"  <?php if($val['lid']==$lid){ echo 'selected';} ?> ><?php echo $val['level_name'];?></option>
						<?php 
					}
					?>
					</select> 

<?php echo $this->lang->line('upload_excel');?>
	<input type="hidden" name="size" value="3500000">
	<input type="file" name="xlsfile" style="width:150px;float:left;margin-left:10px;">
	<div style="clear:both;"></div>
	<input type="submit" value="Nạp" style="margin-top:5px;" class="btn btn-default">
	<a href="<?php echo base_url();?>sample/sample.xls" target="new">Click đây</a> <?php echo $this->lang->line('upload_excel_info');?> 
</form>

</div>
</div>

<script>
	
</script>