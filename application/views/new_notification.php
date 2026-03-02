 <div class="container">

   
 <h3>Thêm thông báo</h3>
   
  <div class="row">
  <form method="post" action="<?php echo site_url('notification/insert_notification/');?>" onsubmit="return check_notification_form();">
	
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
								<label for="title" class="sr-only"><?php echo $this->lang->line('title');?></label> 
								<input type="text" id="title" name="title" class="form-control" placeholder="<?php echo $this->lang->line('title');?>"required autofocus>
						</div>
						<div class="form-group">	 
								<label for="message"><?php echo $this->lang->line('message');?></label> 
								<textarea  name="message"  class="form-control"></textarea>
						</div>
						
	
						<div class="form-group">	 
						<label for="title"><?php echo $this->lang->line('start_date');?>(yyyy-mm-dd H:i:s)</label> 	
							<div class='input-group date' id='startdate'>
								<input type='text' id="txtstartdate" name="txtstartdate" class="form-control" />
								<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar">
										</span>
								</span>
						</div>
					</div>
					<div class="form-group">	 
						<label for="title"><?php echo $this->lang->line('end_date');?>(yyyy-mm-dd H:i:s)</label> 	
						<div class='input-group date' id='enddate'>
						
							<input type='text' id="txtenddate" name="txtenddate" class="form-control" />
							<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar">
									</span>
							</span>
					</div>
				</div>
						<button class="btn btn-default" type="submit"><?php echo $this->lang->line('submit');?></button>
					</div>
				</div>
		</div>
	</form>

</div>

<script>
$(function () {
	$('#startdate').datetimepicker({
			defaultDate: moment(),
			format: 'YYYY-MM-DD H:m:s',
			minDate: moment(),
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

function check_notification_form() {
	if($("#txtenddate").val()!="" && $("#txtstartdate").val()>$("#txtenddate").val())
	{
		alert("Ngày hết hiệu lực phải sau ngày bắt đầu có hiệu lực");
		return false;
	}
	return true;
		
}
</script>
<script>
<?php
if(strpos($this->uri->segment(2),'new_notification')!==FALSE )
{
?>
 window.onload=function()
 {
     CKEDITOR.replace('message', {
         baseHref: '<?php echo $this->config->item('base_url');?>'
         //entities_additional: 'lt, gt',
         //basicEntities: true
		 });
		 
 }

 <?php
}
?>
</script>