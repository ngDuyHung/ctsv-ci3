<div class="container">
<?php ?>
 	
  <div class="row">
		<div class="col-md-12">
			<br> 
 			<div class=" panel panel-default">
				<div class="panel-body"> 
				<?php 
					if($this->session->flashdata('message')){
						echo $this->session->flashdata('message');	
					}
				?>	
				</div>
				<div class="form-group">
					<h3 align="center"><?php echo $notification['title'];?></h3>
				</div>
				<div class="panel-body">
					<?php
						echo $notification['message'];
					?>
				</div>
				<div style="text-align:right">
					<i>Ngày post: <?php echo $notification['start_date']; ?> </i>
				</div>
				
			</div>
		
</div>
