 <style>
    .panel {
  border: 1px solid #f4511e;
  border-radius:0;
  transition: box-shadow 0.5s;
  padding:5px
}

.panel:hover {
  box-shadow: 5px 0px 40px rgba(0,0,0, .2);
}

.panel-footer .btn:hover {
  border: 1px solid #f4511e;
  background-color: #fff !important;
  color: #f4511e;
}

.panel-heading {
  color: #fff !important;
  background-color: #f4511e !important;
  padding: 25px;
  border-bottom: 1px solid transparent;
  border-top-left-radius: 0px;
  border-top-right-radius: 0px;
  border-bottom-left-radius: 0px;
  border-bottom-right-radius: 0px;
}

.panel-footer {
  background-color: #fff !important;
}

.panel-footer h3 {
  font-size: 32px;
}

.panel-footer h4 {
  color: #aaa;
  font-size: 14px;
}

.panel-footer .btn {
  margin: 15px 0;
  background-color: #f4511e;
  color: #fff;
}
 </style>
 <div class="container">
 <?php 
 $logged_in=$this->session->userdata('logged_in');
?>  
 <h3>Thông báo </h3>
<div class="row">
    <?php 
   
    if (isset($currid)) //chi tiet 
    {

            ?>
            
            <div class="row">
            <div class="col-md-12">
            <?php
            foreach($notification_list as $k=>$val)
                {
                    if ($val['nid']==$currid) 
                    {
                        ?>
                        <div class="panel">
                            <div class="panel-header">
                               <h4> <?php echo $val['title'] ?></h4>
                            </div>
                            <div class="panel-body">
                                <?php 
                                echo $val['message']
                                ?>
                            </div>
                        </div>
                        <?php
                    }

                }
                ?>
            </div>
            </div>
            <div class="row">
                <ul>
                <?php 
                foreach($notification_list as $k=>$val)
                {
                    if ($val['nid']=='$currid') continue;
                    ?>
                    <div class="col-md-12">
                    <li> <a href='/notification/detail/<?php echo $val['nid'] ?>'><?php echo $val['title'] ?></a> &nbsp; (<?php echo substr($val['created_date'], 8, 2) ?>/
                                <?php echo substr($val['created_date'], 5, 2) ?>/
                                <?php echo substr($val['created_date'], 0, 4) ?>)
                    </li>
                    </div>
                    <?php
                }
                ?>
                </ul>
            </div>

            <?php
    }
    else 
    {
        $i=0;
    foreach($notification_list as $key => $val){
        $i++;
        if ($i>6) break;
        if (($key%3==0) && $i<Count($notification_list))
          echo '</div><div class=row>';
        
        ?>
        <div class="col-md-4">
            <div class="panel" style="">
                <div class="panel-header">
                    <p><a href='/notification/detail/<?php echo $val['nid'] ?>'><?php echo $val['title'] ?></a></p>
                </div>
                <div class="panel-body">
                    <p style="padding-right: 5px; text-align:right;font-style:italic"> 
                    Ngày tạo: <?php echo substr($val['created_date'], 8, 2) ?>/
                    <?php echo substr($val['created_date'], 5, 2) ?>/
                    <?php echo substr($val['created_date'], 0, 4) ?></p>
                </div>
                

            </div>
        </div>

        <?php 
    }
    ?>
</div>
<h4>Thông báo khác. </h4>
<div class="row">
    <ul>
    <?php 
    foreach($notification_list as $k=>$val)
    {
        if ($k< $i) continue;
        ?>
        <div class="col-md-12">
           <li> <a href='/notification/detail/<?php echo $val['nid'] ?>'><?php echo $val['title'] ?></a> &nbsp; (<?php echo substr($val['created_date'], 8, 2) ?>/
                    <?php echo substr($val['created_date'], 5, 2) ?>/
                    <?php echo substr($val['created_date'], 0, 4) ?>)
           </li>
        </div>
        <?php
    }
    ?>
    </ul>
</div>

<?php 
    }
?>

<?php
//echo "$limit ,". $this->config->item('number_of_rows');
if(($limit-($this->config->item('number_of_rows')))>=0){ $back=$limit-($this->config->item('number_of_rows')); }else{ $back='0'; } ?>

<a href="<?php echo site_url('notification/index/'.$back);?>"  class="btn btn-primary">-<?php echo $this->lang->line('back');?></a>
&nbsp;&nbsp;
<?php
 $next=$limit+($this->config->item('number_of_rows'));  ?>

<a href="<?php echo site_url('notification/index/'.$next);?>"  class="btn btn-primary">+<?php echo $this->lang->line('next');?></a>




</div>
