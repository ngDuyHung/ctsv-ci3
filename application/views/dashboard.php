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
                                <span class="pull-left">Danh sách người dùng </span>
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
                                <span class="pull-left">Danh sách đề thi </span>
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
                            <div class="panel-footer">Danh sách câu hỏi </span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
 </div>
 
 
 
 

</div>
 
<div class="row"></div>









<div class="row">
                          
  <div class="col-md-4" >
    <div class="panel panel-info" >
      <div class="panel-heading"  style="background-color:#72B159;text-align:center;">                    
        <div class="font-size-34"> <strong style="color:#ffffff;"><?php echo $active_users;?></strong>
          <br>
          <small class="font-weight-light text-muted" style="font-size:18px;color:#eeeeee;">Tài khoản còn hiệu lực </small>
        </div>             
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel panel" >
      <div class="panel-heading"  style="background-color:#DB5949;text-align:center;">                  
        <div class="font-size-34" > <strong style="color:#ffffff;"><?php echo $inactive_users;?></strong>
          <br>
          <small class="font-weight-light text-muted" style="font-size:18px;color:#eeeeee;  "> Tài khoản bị khóa</small>
        </div>         
      </div>
    </div>
  </div>


  <div class="col-md-4">
    <div class="panel panel" >
      <div class="panel-heading"  style="background-color:#DB5949;text-align:center;">                  
        <div class="font-size-34" > <strong style="color:#ffffff;"><?php echo $inactive_users;?></strong>
          <br>
          <small class="font-weight-light text-muted" style="font-size:18px;color:#eeeeee;  "> <?php echo $this->lang->line('users');?> <?php echo $this->lang->line('inactive');?></small>
        </div>         
      </div>
    </div>
  </div>

</div>
        <!-- recent users -->
<div class="row">

        <div class="panel">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $this->lang->line('recently_registered');?></div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped valign-middle">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('studentid');?></th>
                  <th class="text-xs-right"><?php echo $this->lang->line('first_name');?> <?php echo $this->lang->line('last_name');?></th>
                  <th><?php echo $this->lang->line('email');?></th>
                  
                  <th class="text-xs-right"><?php echo $this->lang->line('contact_no');?></th>
                  <th class="text-xs-right"><?php echo $this->lang->line('classid');?></th>
                  <th></th>
                </tr>
              </thead>
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
?>
  <tr>
    <td>
      <a href="<?php echo site_url('user/edit_user/'.$val['uid']);?>"><?php echo $val['studentid'];?> <?php echo $val['wp_user'];?></a>
    </td>
    <td  class="text-xs-right">
      <?php echo $val['first_name'];?> <?php echo $val['last_name'];?>
    </td>
    <td  class="text-xs-right"><?php echo $val['email'];?></td>
    <td  class="text-xs-right"><?php echo $val['contact_no'];?></td>
    <td  class="text-xs-right"><?php echo $val['classid'];?></td>

                
              </tr>
             
             <?php 
             }
             ?> 
     
            </tbody></table>
          </div>
        </div>

        <!-- recent users -->

      </div>




 



</div>
