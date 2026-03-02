<html lang="en">
  <head>
  <title><?php echo $title;?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
	<title> Điểm danh online</title>
	<!-- bootstrap css -->
	
	<link href="<?php echo $this->config->item('base_urls').'bootstrap/css/bootstrap.min.css';?>" rel="stylesheet">
	
	<!-- custom css -->
	<link href="<?php echo $this->config->item('base_urls').'css/style.css';?>" rel="stylesheet">
	
	<script>
	
	var base_url="<?php echo $this->config->item('base_urls');?>";

	</script>
	
	<!-- jquery -->
	<script src="<?php echo $this->config->item('base_urls').'js/jquery.js';?>"></script>
	
	<!-- custom javascript -->
	  	<script src="<?php echo $this->config->item('base_urls').'js/basic.js';?>"></script>
		
	<!-- bootstrap js -->
    <script src="<?php echo $this->config->item('base_urls').'bootstrap/js/bootstrap.min.js';?>"></script>
	
	<!-- fontawesome css -->
	<link href="<?php echo $this->config->item('base_urls').'font-awesome/css/font-awesome.css';?>" rel="stylesheet">
	
	
	
 </head>
  <body  class='login'  >
  	
	<?php 
			if($this->session->userdata('logged_in')){
				if(($this->uri->segment(1).'/'.$this->uri->segment(2))!='quiz/attempt'){
				$logged_in=$this->session->userdata('logged_in');
	?>
	    <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://savsoftquiz.com"><?php echo $this->lang->line('savsoft_quiz');?></a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <?php  
				if($logged_in['su']==1){
			?>
			  
			  <li <?php if($this->uri->segment(1)=='dashboard'){ echo "class='active'"; } ?> ><a href="<?php echo site_url('dashboard');?>"><?php echo $this->lang->line('dashboard');?></a></li>
            
			 
			  <li class="dropdown" <?php if($this->uri->segment(1)=='user'){ echo "class='active'"; } ?> >
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this->lang->line('users');?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo site_url('user/new_user');?>"><?php echo $this->lang->line('add_new');?></a></li>
                  <li><a href="<?php echo site_url('user');?>"><?php echo $this->lang->line('users');?> <?php echo $this->lang->line('list');?></a></li>
                  
                </ul>
              </li>
			 
			 
			 
			  <li class="dropdown" <?php if($this->uri->segment(1)=='qbank'){ echo "class='active'"; } ?> >
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this->lang->line('qbank');?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo site_url('qbank/pre_new_question');?>"><?php echo $this->lang->line('add_new');?></a></li>
                  <li><a href="<?php echo site_url('qbank');?>"><?php echo $this->lang->line('question');?> <?php echo $this->lang->line('list');?></a></li>
                  
                </ul>
              </li>
			 
			 
			 
		    <?php 
				}else{
			?>
			 <li><a href="<?php echo site_url('user/edit_user/'.$logged_in['uid']);?>"><?php echo $this->lang->line('myaccount');?></a></li>
			<?php 
				}
			?>
     		  <li class="dropdown" <?php if($this->uri->segment(1)=='qbank'){ echo "class='active'"; } ?> >
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this->lang->line('quiz');?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                 <?php  
				if($logged_in['su']==1){
			?>     <li><a href="<?php echo site_url('quiz/add_new');?>"><?php echo $this->lang->line('add_new');?></a></li>
              <?php 
				}
?>				 <li><a href="<?php echo site_url('quiz');?>"><?php echo $this->lang->line('quiz');?> <?php echo $this->lang->line('list');?></a></li>
               
                </ul>
              </li>
	

	           <li><a href="<?php echo site_url('result');?>"><?php echo $this->lang->line('result');?></a></li>
			 
			 <li><a href="<?php echo site_url('liveclass');?>"><?php echo $this->lang->line('live_classroom');?></a></li>
			 
			  <?php  
				if($logged_in['su']==1){
			?>
			 <li><a href="<?php echo site_url('payment_gateway');?>"><?php echo $this->lang->line('payment_history');?></a></li>
			 
			  <li class="dropdown" <?php if($this->uri->segment(1)=='user_group'){ echo "class='active'"; } ?> >
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this->lang->line('setting');?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                 
                  <li><a href="<?php echo site_url('user/group_list');?>"><?php echo $this->lang->line('group_list');?></a></li>
                  <li><a href="<?php echo site_url('qbank/category_list');?>"><?php echo $this->lang->line('category_list');?></a></li>
                  <li><a href="<?php echo site_url('qbank/level_list');?>"><?php echo $this->lang->line('level_list');?></a></li>
                  
					<li><a href="<?php echo site_url('dashboard/config');?>"><?php echo $this->lang->line('config');?></a></li>
					 
					<li><a href="<?php echo site_url('dashboard/css');?>"><?php echo $this->lang->line('custom_css');?></a></li>
						  
                  
                </ul>
              </li>
			
			<?php 
				}
				?>
             <li><a href="<?php echo site_url('user/logout');?>"><?php echo $this->lang->line('logout');?></a></li>
              <!--
			  <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                 
                </ul>
              </li>
			  -->
			  
            </ul>
             
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>

	<?php 
			}
			}
	?>
	
<!-- body -main --> 
<div class="row" style="margin-top:20px;">
<div class="container" >   
 
 
 



<div class="col-md-8">
 
</div>
<div class="col-md-4">

	<div class="login-panel panel panel-default">
		<div class="panel-body"> 
		<center>
		<a href="<?php echo $this->config->item('base_urls');?>"><img src="<?php echo $this->config->item('base_urls').'images/logo.png' ;?>"></a><br>
Đăng nhập - điểm danh
		</center>

	<form class="form-signin" method="post" action="<?php echo $this->config->item('base_urls'). 'logins/verifylogin';?>">
					<h4 class="form-signin-heading"><?php echo $this->lang->line('login');?></h4>
		<?php 
		if($this->session->flashdata('message')){
			?>
			<div class="alert alert-danger">
			<?php echo str_replace('{resend_url}',site_url('login/resend'),$this->session->flashdata('message'));?>
			</div>
		<?php	
		}
		?>	
		
		<label for="inputEmail" class="sr-only"><?php echo $this->lang->line('email_address');?></label> 
		<fieldset class="page-signin-form-group form-group form-group-lg">
                  <div class="page-signin-icon text-muted"><i class="fa fa-user"></i></div>
                  <input class="page-signin-form-control form-control" name="email"  placeholder="Email hoặc MSSV" type="text" required autofocus>
                </fieldset>
                
                <label for="inputPassword" class="sr-only"><?php echo $this->lang->line('password');?></label>
  		<fieldset class="page-signin-form-group form-group form-group-lg">
                  <div class="page-signin-icon text-muted"><i class="fa fa-star"></i></div>
                  <input class="page-signin-form-control form-control" name="password"  id="inputPassword" placeholder="<?php echo $this->lang->line('password');?>" type="password" required  >
                </fieldset>
                			  
			 
			<div class="form-group">	  
					 
					<button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $this->lang->line('login');?></button>
			</div>
<?php 
if($this->config->item('user_registration')){
	?>
	<a href="<?php echo site_url('login/pre_registration');?>"><?php echo $this->lang->line('register_new_account');?></a>
	&nbsp;&nbsp;&nbsp;&nbsp;
<?php
}
?>
	<a href="<?php echo site_url('login/forgot');?>"><?php echo $this->lang->line('forgot_password');?></a>

			</form>
			
<?php 
if($this->config->item('open_quiz')){
	?>			<p>
			<a href="<?php echo site_url('quiz/open_quiz/0');?>"  ><?php echo $this->lang->line('open_quizzes');?></a>
			</p>
			<?php 
			}
			?>
			
		</div>
	</div>

</div>
 

</div>

</div>

<!-- footer -->
<?php 
if($this->config->item('tinymce')){
					if($this->uri->segment(2)!='attempt'){
					if($this->uri->segment(2)!='view_result'){

					if($this->uri->segment(2)!='config'){
					if($this->uri->segment(2)!='css'){

	
	?>
	<script type="text/javascript" src="<?php echo $this->config->item('base_urls');?>editor/tiny_mce.js"></script>
	<script type="text/javascript" src="<?php echo $this->config->item('base_urls');?>editor/ckeditor/ckeditor.js"></script>
	<script type="text/javascript">
 <?php 
 if($this->uri->segment(2)=='edit_quiz' || $this->uri->segment(2)=='add_new'  || $this->uri->segment(2)=='edit_notification' || $this->uri->segment(2)=='edit_notification' ){
?>
			tinyMCE.init({
	
    mode : "textareas",
	editor_selector : "tinymce_textarea",
	theme : "advanced",
		relative_urls:"false",
	 plugins: "jbimages",
	  
	
  // ===========================================
  // PUT PLUGIN'S BUTTON on the toolbar
  // ===========================================
	
 
	
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "jbimages,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
		
		
	});

<?php 
 }

         /*

			tinyMCE.init({
	
    mode : "textareas",
		theme : "advanced",
		relative_urls:"false",
	 plugins: "jbimages",
	  
	
  // ===========================================
  // PUT PLUGIN'S BUTTON on the toolbar
  // ===========================================
	
 
	
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "jbimages,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
		
		
	});
	
<?php 
*/
 //}
 ?>
 
</script>

	
	<?php 
						}
					}
			}
		}
	}
?>
</body>
</html>

