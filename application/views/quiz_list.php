 <div class="container">
<?php 
$cc=0;
$colorcode=array(
'success',
'warning',
'info',
'danger',
'primary'
);
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
  return implode(" ",$re)." ...";

}

$logged_in=$this->session->userdata('logged_in');
			 
			
			?>
   
 
    <?php 
	if($logged_in['su']=='1'){
		?>
    <h3> Danh sách đề thi &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="/quiz/add_new" class='btn btn-default'> Thêm mới</a>
  </h3>
		<div class="row">
 

  
    <p style="float:right;text-align:right">
      <?php 
      if($list_view=='grid'){
        ?>
        <a href="<?php echo site_url('quiz/index/'.$limit.'/table');?>"><?php echo $this->lang->line('table_view');?></a>
        <?php 
      }else{
        ?>
        <a href="<?php echo site_url('quiz/index/'.$limit.'/grid');?>"><?php echo $this->lang->line('grid_view');?></a>
        <?php 
      }
      ?>
    </p>

</div>

<?php 
	}
?>

  <div class="row">
    <div class="col-md-4"  >
      <h3 class="title"><?php echo 'Các thông báo'?></h3>  
      <?php
      if(count($notifications)==0)
        echo '<h4 align="center">Chưa có thông báo</h4>';
      foreach ($notifications as $notification) {
        
      ?>
      <div class="col-md-12 text-center">
          <div class="panel panel-<?php echo $colorcode[$cc];?> panel-pricing">
            <div class="panel-heading notification-title">
                <?php //echo substr(strip_tags($notification['title']),0,50);
		echo strip_tags($notification['title']);
		?>
            </div>
            <div class="panel-body text-center">
                <p class="notification-message"><?php //echo substr(strip_tags($notification['message']),0,200);
			//echo catChuoi($notification['message'],300);
		?></p>
                <a href="<?php echo site_url('notification/detail/'.$notification['nid']);?>">Xem chi tiết</a>
            </div>
            
          </div>
      </div>
      <?php
      if($cc >= 4){
        $cc=0;
        }else{
        $cc+=1;
        }
      }
      ?>
    </div>
    <div class="col-md-8" >
    <h3 class="title">Các bài thi</h3>
      
			<?php 
        if($this->session->flashdata('message')){
          echo $this->session->flashdata('message');	
        }
        ?>	
        <?php 
      if($list_view=='table'){
        $hide=0;
        ?>
        <table class="table table-bordered">
        <tr>
        <th>#</th>
        <th><?php echo $this->lang->line('quiz_name');?></th>
        <th><?php echo $this->lang->line('noq');?></th>
        <th><?php echo $this->lang->line('action');?> </th>
        </tr>
        <?php 
        if(count($result)==0){
          ?>
        <tr>
        <td colspan="3" align="center"><?php echo $this->lang->line('no_record_found');?></td>
        </tr>	
        <?php
        }
        foreach($result as $key => $val){
          if($val['demo']==-1)
          {  
            $hide++;
            continue;
          }
        ?>
        <tr>
        <td><?php echo $val['quid'];?></td>
        <td>
        <abbr title="<?php echo $val['quiz_name'];?>"><?php echo sub_str(strip_tags($val['quiz_name']),64);?></abbr></td>
        <td><?php echo $val['noq'];?></td>
        <td>
        <a href="<?php echo site_url('quiz/quiz_detail/'.$val['quid']);?>" class="btn btn-success"  ><?php echo $this->lang->line('attempt');?> </a>

        <?php 
        if($logged_in['su']=='1'){
          ?>
        
        <a href="<?php echo site_url('quiz/hide_quiz/'.$val['quid']);?>" class="btn btn-warning"  > Ẩn </a>
        <a href="<?php echo site_url('quiz/edit_quiz/'.$val['quid']);?>"><img src="<?php echo base_url('images/edit.png');?>"></a>
        <a href="javascript:remove_entry('quiz/remove_quiz/<?php echo $val['quid'];?>');"><img src="<?php echo base_url('images/cross.png');?>"></a>
        
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
        if($hide>0)
        { ?>
          <h3 class="title">Các bài thi đã ẩn</h3>
          <table class="table table-bordered">
        <tr>
        <th>#</th>
        <th><?php echo $this->lang->line('quiz_name');?></th>
        <th><?php echo $this->lang->line('noq');?></th>
        <th><?php echo $this->lang->line('action');?> </th>
        </tr>
        <?php 
        if(count($result)==0){
          ?>
        <tr>
        <td colspan="3" align="center"><?php echo $this->lang->line('no_record_found');?></td>
        </tr>	
        <?php
        }
        foreach($result as $key => $val){
          if($val['demo']!=-1) 
            continue;
        ?> 
        <tr>
        <td><?php echo $val['quid'];?></td>
        <td>
        <abbr title="<?php echo $val['quiz_name'];?>"><?php echo sub_str(strip_tags($val['quiz_name']),64);?></abbr></td>
        <td><?php echo $val['noq'];?></td>
        <td>
        <a href="<?php echo site_url('quiz/quiz_detail/'.$val['quid']);?>" class="btn btn-success"  ><?php echo $this->lang->line('attempt');?> </a>

        <?php 
        if($logged_in['su']=='1'){
          ?>
        <a href="<?php echo site_url('quiz/show_quiz/'.$val['quid']);?>" class="btn btn-info"  > Hiện </a>
        <a href="<?php echo site_url('quiz/demo_quiz/'.$val['quid']);?>" class="btn btn-primary"  > Demo </a>      
        <a href="<?php echo site_url('quiz/edit_quiz/'.$val['quid']);?>"><img src="<?php echo base_url('images/edit.png');?>"></a>
        <a href="javascript:remove_entry('quiz/remove_quiz/<?php echo $val['quid'];?>');"><img src="<?php echo base_url('images/cross.png');?>"></a>
        
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
        }
        }else{ //end view table
            ?>
            <?php 
        if(count($result)==0){
          echo "<h4 align='center'>",$this->lang->line('no_record_found'),'</h4>';
        }
        $hide2=0;
        foreach($result as $key => $val){
          if($val['demo']==-1)
          {
            $hide2++;
            continue;
          }
        ?>
	                <!-- item -->
          <div class="col-md-6 text-center">
              <div class="panel panel-<?php echo $colorcode[$cc];?> panel-pricing">
                  <div class="panel-heading quiz-name">
                      <h3><abbr title="<?php echo $val['quiz_name'];?>"><?php echo sub_str(strip_tags($val['quiz_name']),64);?></abbr></h3>
                  </div>
                  <div class="panel-body text-center">
                      <strong><?php echo $this->lang->line('duration');?> <?php echo $val['duration'];?></strong>
                  </div>
                  <ul class="list-group text-center">
                      <li class="list-group-item"><i class="fa fa-check"></i> <?php echo $this->lang->line('noq');?>:  <?php echo $val['noq'];?></li>
                      <li class="list-group-item"><i class="fa fa-check"></i> <?php echo $this->lang->line('maximum_attempts');?>: <?php echo $val['maximum_attempts'];?></li>
                  </ul>
                  <div class="panel-footer">	 
                    <a href="<?php echo site_url('quiz/quiz_detail/'.$val['quid']);?>" class="btn btn-success"  ><?php echo $this->lang->line('attempt');?> </a>

                    <?php 
                    if($logged_in['su']=='1'){
                      ?>
                          
                          <a href="<?php echo site_url('quiz/hide_quiz/'.$val['quid']);?>" class="btn btn-warning"  > Ẩn </a>
                          <a href="<?php echo site_url('quiz/edit_quiz/'.$val['quid']);?>"><img src="<?php echo base_url('images/edit.png');?>"></a>
                    <a href="javascript:remove_entry('quiz/remove_quiz/<?php echo $val['quid'];?>');"><img src="<?php echo base_url('images/cross.png');?>"></a>
                    
                    <?php 
                    }
                    ?>
                  </div>
                </div>
              </div>
                <!-- /item -->
            <?php 
            if($cc >= 4){
            $cc=0;
            }else{
            $cc+=1;
            }
        }
        if($hide2>0 && $logged_in['su']=='1')
        {
          echo '<div style="clear:both"></div>';
          echo '<h3 class="title">Các bài thi đã ẩn</h3>';
          foreach($result as $key => $val){
            if($val['demo']!=-1)
            {
              continue;
            }
          ?>
                    <!-- item -->
            <div class="col-md-6 text-center">
                <div class="panel panel-<?php echo $colorcode[$cc];?> panel-pricing">
                    <div class="panel-heading quiz-name">
                        <h3><abbr title="<?php echo $val['quiz_name'];?>"><?php echo sub_str(strip_tags($val['quiz_name']),64);?></abbr></h3>
                    </div>
                    <div class="panel-body text-center">
                        <strong><?php echo $this->lang->line('duration');?> <?php echo $val['duration'];?></strong>
                    </div>
                    <ul class="list-group text-center">
                        <li class="list-group-item"><i class="fa fa-check"></i> <?php echo $this->lang->line('noq');?>:  <?php echo $val['noq'];?></li>
                        <li class="list-group-item"><i class="fa fa-check"></i> <?php echo $this->lang->line('maximum_attempts');?>: <?php echo $val['maximum_attempts'];?></li>
                    </ul>
                    <div class="panel-footer">	 
                      <a href="<?php echo site_url('quiz/quiz_detail/'.$val['quid']);?>" class="btn btn-success"  ><?php echo $this->lang->line('attempt');?> </a>
  
                      <?php 
                      if($logged_in['su']=='1'){
                        ?>
                            
                            <a href="<?php echo site_url('quiz/show_quiz/'.$val['quid']);?>" class="btn btn-info"  > Hiện </a>
                            <a href="<?php echo site_url('quiz/demo_quiz/'.$val['quid']);?>" class="btn btn-primary"  > Demo </a>
                            <a href="<?php echo site_url('quiz/edit_quiz/'.$val['quid']);?>"><img src="<?php echo base_url('images/edit.png');?>"></a>
                      <a href="javascript:remove_entry('quiz/remove_quiz/<?php echo $val['quid'];?>');"><img src="<?php echo base_url('images/cross.png');?>"></a>
                      
                      <?php 
                      }
                      ?>
                    </div>
                  </div>
                </div>
                  <!-- /item -->
              <?php 
              if($cc >= 4){
              $cc=0;
              }else{
              $cc+=1;
              }
          }
        }
      }
      ?>
    </div>
  </div>
<br><br>
</div>