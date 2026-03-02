<html lang="vi">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
		<title><?php echo $title;?></title>
	<!-- bootstrap css -->
	<link href="<?php echo base_url('bootstrap/css/bootstrap.min.css');?>" rel="stylesheet">
	
	<!-- custom css -->
	<link href="<?php echo base_url('css/style.css');?>" rel="stylesheet">
	
	<script>
	
	var base_url="<?php echo base_url();?>";

	</script>
	
	<!-- jquery -->
	<script src="<?php echo base_url('js/jquery.js');?>"></script>
	
	<!-- custom javascript -->
	  	<script src="<?php echo base_url('js/basic.js');?>"></script>
		
	<!-- bootstrap js -->
    <script src="<?php echo base_url('bootstrap/js/bootstrap.min.js');?>"></script>
	
	<!-- fontawesome css -->
	<link href="<?php echo base_url('font-awesome/css/font-awesome.css');?>" rel="stylesheet">
	
	<!-- chartjs -->
	<script src="<?php echo base_url('js/Chart.bundle.min.js');?>"></script>
	
	<!-- firebase messaging menifest.json -->
	 <link rel="manifest" href="<?php echo base_url('js/manifest.json');?>">

	<!--DatePicker -->
	<script src="<?php echo base_url('bootstrap/js/moment.js');?>"></script>
	<script src="<?php echo base_url('bootstrap/js/bootstrap-datetimepicker.min.js');?>"></script>
	<link href="<?php echo base_url('bootstrap/css/bootstrap-datetimepicker.css');?>" rel="stylesheet">
<style>
	.container{margin-top:50px}
</style>
 </head>
  <body>
  <?php
		//Cat chuoi
		function sub_str($s,$n)
		{
			if(strlen($s)<=$n)
				return $s;
			$i=$n;
			while ($s[$n]!=' ' && $s[$n]!='.' && $s[$n]!=',') {
				$n--;
			}
			if($i==$n)
				return $s;
			return substr($s,0,$n)."...";
		}
		//echo sub_str("Bài thi giữa khóa (Khoa Cơ khí và khoa công nghệ thông tin",64);exit;

	if($this->session->userdata('logged_in'))
				$logged_in=$this->session->userdata('logged_in');
	else $logged_in=false;
	
	if($logged_in['su']==1)
		{
				//menu admin
		$class1=' active'; $class2=''; $class3=''; $class4=''; $class5='';$class6='';
		if ( ($this->uri->segment(1)=='etp') || ($this->uri->segment(1)=='socialwork') || ($this->uri->segment(1)=='socialwork') || ($this->uri->segment(1)=='form2')) {$class1='';  $class2=' active';}  
		
		if ( ($this->uri->segment(1)=='quiz') || ($this->uri->segment(1)=='qbank') || ($this->uri->segment(1)=='result') ) {$class1='';  $class3=' active';}  
		
		if ( ($this->uri->segment(1)=='attendance') || ($this->uri->segment(1)=='survey') ) {$class1='';  $class4=' active';}  
		
		if  ($this->uri->segment(1)=='user')  {$class1='';  $class5=' active';} 
		
	?>
  <nav class="navbar navbar-fixed-top navbar-inverse" >
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Phòng CTSV</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="<?php echo $class1;?>"><a href="/">Trang  chủ <span class="sr-only">(current)</span></a></li>
       
        <li class="dropdown <?php echo $class2;?>">
          <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" 
          aria-expanded="false">Công tác sinh viên <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="/etp">Điểm Rèn Luyện</a></li>
			<li role="separator" class="divider"></li>
            <li><a href="/socialwork">Ngày CTXH</a></li>
			<li role="separator" class="divider"></li>
            <li><a href="/socialwork/index2">Khen Thưởng-Kỷ Luật</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="/form2" target='xacnhansv'
			>Xác Nhận Sinh Viên</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="/inoutpatient">Thông Tin Ngoại Trú</a></li>
          </ul>
        </li>

		<li class="dropdown <?php echo $class3;?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bài Thu Hoạch Tuần SHCD <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo site_url('quiz');?>">Đề Thi</a></li>
			<li role="separator" class="divider"></li>
            <li><a href="/qbank">Ngân Hàng Câu Hỏi</a></li>
			<li role="separator" class="divider"></li>
            <li><a href="<?php echo site_url('result');?>">Kết Quả Thi</a></li>
          </ul>
        </li>

		<li class="dropdown <?php echo $class4; ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
		  role="button" aria-haspopup="true" aria-expanded="false">Điểm Danh <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="https://ctsv.stu.edu.vn/attendance">Điểm danh</a></li>
			<li role="separator" class="divider"></li>
            <li><a href="<?php echo base_url();?>attendance/resultAttendance3" target='diemdanh'>Kết Quả Điểm Danh</a></li>
			<li role="separator" class="divider"></li>
            <li><a href="/survey" >Kết Quả Khảo Sát</a></li>
           
          </ul>
        </li>

		<li class="dropdown  <?php echo $class5; ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
		  role="button" aria-haspopup="true" aria-expanded="false">Quản Lý  <span class="caret"></span></a>
          <ul class="dropdown-menu">
          <li><a href="<?php echo site_url('faculty');?>">Danh sách Khoa</a></li>
          <li><a href="<?php echo site_url('classroom');?>">Danh sách <?php echo $this->lang->line('class');?></a></li>
					<li><a href="<?php echo site_url('user');?>">Danh sách sinh viên</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="<?php echo site_url('notification');?>"><?php echo $this->lang->line('notification');?></a></li>
          <li><a href="<?php echo site_url('qbank/category_list');?>"><?php echo $this->lang->line('category_list');?></a></li>
					<li><a href="<?php echo site_url('quiz/time');?>">Thiết lập thời gian thi</a></li>
                  
				<!--	<li><a href="<?php echo site_url('dashboard/config');?>"><?php echo $this->lang->line('config');?></a></li>
					 
					<li><a href="<?php echo site_url('dashboard/css');?>"><?php echo $this->lang->line('custom_css');?></a></li>
				-->
					
          <li><a href="/setting/course">Thiết lập điểm danh</a></li>
        <!-- <li><a href="<?php echo site_url('user/new_user');?>"><?php echo $this->lang->line('add_new');?></a></li>
            <li role="separator" class="divider"></li> -->
          <li><a href="/setting">Thiết lập công tác sinh viên</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="/setting/deletedata">Xóa dữ liệu cũ</a></li>
          </ul>
        </li>
		<li  class='<?php echo $class6; ?>'><a href="http://stu.edu.vn/vi/383/tin-tuc-su-kien.html" target='_blank'>Tin tức </a></li>
       
      </ul>

     
      <ul class="nav navbar-nav navbar-right">
       
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
		  role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <span class="caret"></span></a>
          <ul class="dropdown-menu">
             <!-- <li><a href="#">Đổi Thông Tin</a></li> -->
              <!-- <li role="separator" class="divider"></li>  -->
                    <li><a href="#">Đổi Mật Khẩu</a></li>
                  
              <li role="separator" class="divider"></li>
            <li><a href="/user/logout">Thoát</a></li>
           
           
          </ul>
        </li>

<li>
</li>
</ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<?php
}//end menu admin


if($logged_in['su']==0)
		{
				//menu sv
		$class1=' active'; $class2=''; $class3=''; $class4=''; $class5='';$class6='';
		
		if ( ($this->uri->segment(1)=='attendance')  ) {$class1='';  $class2=' active';}  
		if ( ($this->uri->segment(1)=='quiz') ) {$class1='';  $class3=' active';}  
		if  ($this->uri->segment(1)=='result')  {$class1='';  $class4=' active';} 
		if ( ($this->uri->segment(1)=='form2') ) {$class1='';  $class5=' active';}  
		if  ($this->uri->segment(1)=='etp')  {$class1='';  $class6=' active';} 
    if  ($this->uri->segment(1)=='inoutpatient')  {$class1='';  $class7=' active';} 
		
		
	?>
  <nav class="navbar navbar-default navbar-fixed-top navbar-dark bg-dark"  >
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="https://stu.edu.vn/vi/379/gioi-thieu-phong.html">Phòng CTSV</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="<?php echo $class1;?>"><a href="/">Trang  chủ <span class="sr-only">(current)</span></a></li>
       </li>
	  
	   <li class="<?php echo $class3;?>"><a href="/quiz">Đề thi <span class="sr-only">(current)</span></a></li>
       </li>

	   <li class="<?php echo $class4;?>"><a href="/result">Kết quả thi <span class="sr-only">(current)</span></a></li>
       </li>

	   

     <li><a href="<?php echo site_url('user/edit_user/'.$logged_in['uid']);?>"><?php echo $this->lang->line('myaccount');?></a></li>
			 
   </ul>

    <ul class="nav navbar-nav navbar-right">
       
       <li class="dropdown">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
     role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <span class="caret"></span></a>
         <ul class="dropdown-menu">
                   <li><a href="/user/logout">Thoát</a></li>
          </ul>
       </li>

		</ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<?php
}//end menu user
