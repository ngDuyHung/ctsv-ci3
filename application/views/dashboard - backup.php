 <div class="container">

   
 
<div id="update_notice"></div>  



<div class="row">

<div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $num_users;?></div>
                                    <div><?php echo $this->lang->line('no_registered_user');?> </div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo site_url('user');?>">
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo $this->lang->line('list');?> <?php echo $this->lang->line('users');?> </span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
</div>


<div class="col-md-4">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-book fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $num_quiz;?></div>
                                    <div><?php echo $this->lang->line('no_registered_quiz');?> </div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo site_url('quiz');?>">
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo $this->lang->line('list');?> <?php echo $this->lang->line('quiz');?> </span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
</div>

<div class="col-md-4">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $num_qbank;?></div>
                                    <div><?php echo $this->lang->line('no_questions_qbank');?></div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo site_url('qbank');?>">
                            <div class="panel-footer"><?php echo $this->lang->line('question');?> <?php echo $this->lang->line('list');?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
 </div>
 
 
 
 

</div>
 
<div class="row"></div>






<div class="row">
      <div class="col-lg-7">


<div class="row">
                          
 <div class="col-lg-6 " >
 <div class="panel panel" >
                        <div class="panel-heading"  style="background-color:#72B159;text-align:center;">
                        
    <div class="font-size-34"> <strong style="color:#ffffff;"><?php echo $active_users;?></strong>
    <br>
    <small class="font-weight-light text-muted" style="font-size:18px;color:#eeeeee;"><?php echo $this->lang->line('users');?> <?php echo $this->lang->line('active');?> </small>

</div>

                    
                        </div>
 </div>
</div>
 <div class="col-lg-6">
 <div class="panel panel" >
                        <div class="panel-heading"  style="background-color:#DB5949;text-align:center;">
                        
    <div class="font-size-34" > <strong style="color:#ffffff;"><?php echo $inactive_users;?></strong>
    <br>
    <small class="font-weight-light text-muted" style="font-size:18px;color:#eeeeee;"> <?php echo $this->lang->line('users');?> <?php echo $this->lang->line('inactive');?></small>

</div>

                    
                        </div>
                        </div>
</div>
  

</div>


        <!-- recent users -->

        <div class="panel">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $this->lang->line('recently_registered');?></div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped valign-middle">
              <thead>
                <tr><th><?php echo $this->lang->line('email');?></th>
                <th class="text-xs-right"><?php echo $this->lang->line('first_name');?> <?php echo $this->lang->line('last_name');?></th>
                <th class="text-xs-right"><?php echo $this->lang->line('group_name');?></th>
                <th class="text-xs-right"><?php echo $this->lang->line('contact_no');?></th>
                <th></th>
              </tr></thead>
              <tbody> 
              <?php 
if(count($result)==0){
	?>
<tr>
 <td colspan="3"><?php echo $this->lang->line('no_record_found');?></td>
</tr>	
	
	
	<?php
}
foreach($result as $key => $val){
?><tr>
<td>
<a href="<?php echo site_url('user/edit_user/'.$val['uid']);?>"><?php echo $val['email'];?> <?php echo $val['wp_user'];?></a></td>
<td  class="text-xs-right"><?php echo $val['first_name'];?> <?php echo $val['last_name'];?></td>
 <td  class="text-xs-right"><?php echo $val['group_name'];?></td>
<td  class="text-xs-right"><?php echo $val['contact_no'];?></td>

                
              </tr>
             
             <?php 
             }
             ?> 
     
            </tbody></table>
          </div>
        </div>

        <!-- recent users -->

      </div>
      <div class="col-lg-5">


 
<?php 
 
$months=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
?>
<div class="revenuew">

<canvas id="myChart" width="340" height="340"></canvas>
</div>










        <!-- References -->

        <div class="panel">
          <div class="panel-heading">
            <div class="panel-title">Tiêu đề</div>
          </div>


  <?php 
echo "gì đó";
$i=0;
$colorcode=array(
'success',
'warning',
'info',
'danger'
);
foreach($payments as $key => $val){
?>
<div class="alert alert-<?php echo $colorcode[$i];?>" style="margin:5px;">
          
           <a href="<?php echo site_url('user/edit_user/'.$val['uid']);?>">   <?php echo $val['first_name'].' '.$val['last_name'];?></a>
                <?php echo $this->lang->line('subscribed');?> 
                 <?php echo $val['group_name'];?>
                  <button class="btn btn-<?php echo $colorcode[$i];?>">
  <?php echo $this->config->item(strtolower($val['payment_gateway']).'_currency_prefix');?> <?php echo $val['amount'];?> <?php echo $this->config->item(strtolower($val['payment_gateway']).'_currency_sufix');?>  
          </button>    
     </div>     

         

<?php 
 if($i >= 4){
	  $i=0;
	  }else{
	  $i+=1;
	  }
}
?>

        <!-- / payments -->

      </div>
    </div>













 



</div>
<script>
//update_check('4.0');
</script>
