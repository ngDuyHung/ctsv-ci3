 <style>
@media print {
   
   .navbar{
   display:none;
   }
   #footer{
   display:none;
   } 
   .printbtn{
	  display:none; 
   }
   #social_share{
	   display:none;
   }
   
   #page_break2{
	   
   page-break-after: always;
	}
	
.noprint{
	
	display:none; 
}
}
@media screen {
.onlyprint{
	display:none; 
	
}
}
 td{
		color:#212121;
		font-size:14px;
		padding:4px;
	}
	
	
	
	
	
	.circle_result{
	
	    width: 40px;
    height: 40px;
    border-radius: 20px;
    background: #0b8d6f;
    color: #ffffff;
    padding: 5px;
    font-size: 16px;
    text-align: center;
	margin-right:20px;
		float:left;
}


.circle_ur{
	
	    width: 40px;
    height: 40px;
    border-radius: 20px;
    background: #ffcc66;
    color: #ffffff;
    padding: 5px;
    font-size: 16px;
    text-align: center;
	margin-right:20px;
	float:left;
}


.circle_l{
	
	    width: 40px;
    height: 40px;
    border-radius: 20px;
    background: #ff3300;
    color: #ffffff;
    padding: 5px;
    font-size: 16px;
    text-align: center;
	margin-right:20px;
	float:right;
}

.td_line{
	background:url('<?php echo base_url('images/rankbar.png');?>');background-repeat: repeat-x;
}
</style>
 <div class="container">
<?php 
$logged_in=$this->session->userdata('logged_in');
?>
   
    
 
<?php 

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}

function questioninwhichcategory($key,$c_range){
	foreach($c_range as $k => $cv){
		
		if($key >= $cv[0] && $key <= $cv[1]){
			return $k;
		}
	}
	
}




function cia_cat($narray,$c_range){
	$unattempted=array();
	$correct=array();
	$incorrect=array();
	foreach($narray as $k => $val){
		
	if($val==0){
		if(isset($unattempted[questioninwhichcategory($k,$c_range)])){
		$unattempted[questioninwhichcategory($k,$c_range)]+=1;
		}else{
		$unattempted[questioninwhichcategory($k,$c_range)]=1;	
		}
	}else if($val==1){
// $correct+=1;
		if(isset($correct[questioninwhichcategory($k,$c_range)])){
		$correct[questioninwhichcategory($k,$c_range)]+=1;
		}else{
		$correct[questioninwhichcategory($k,$c_range)]=1;	
		}
	}else if($val==2){
// $incorrect+=1;
		if(isset($incorrect[questioninwhichcategory($k,$c_range)])){
		$incorrect[questioninwhichcategory($k,$c_range)]+=1;
		}else{
		$incorrect[questioninwhichcategory($k,$c_range)]=1;	
		}
	}	
	 
		 
	 
	}
	
	return array($correct,$incorrect,$unattempted);
}





function cia_tim_cate($narray,$tim,$c_range){
	$unattempted=array();
	$correct=array();
	$incorrect=array();
	foreach($narray as $k => $val){
	
	if($val==0){
		if(isset($unattempted[questioninwhichcategory($k,$c_range)])){
		$unattempted[questioninwhichcategory($k,$c_range)]+=$tim[$k];
		}else{
		$unattempted[questioninwhichcategory($k,$c_range)]=$tim[$k];	
		}
	}else if($val==1){
// $correct+=1;
		if(isset($correct[questioninwhichcategory($k,$c_range)])){
		$correct[questioninwhichcategory($k,$c_range)]+=$tim[$k];
		}else{
		$correct[questioninwhichcategory($k,$c_range)]=$tim[$k];	
		}
	}else if($val==2){
// $incorrect+=1;
		if(isset($incorrect[questioninwhichcategory($k,$c_range)])){
		$incorrect[questioninwhichcategory($k,$c_range)]+=$tim[$k];
		}else{
		$incorrect[questioninwhichcategory($k,$c_range)]=$tim[$k];	
		}
	}	
	
	}
		
	return array($correct,$incorrect,$unattempted);
}

function prezero($val){
	if($val <= 9){
	return '0'.$val;	
	}else{
		return $val;
	}
}
function secintomin($sec){
	if($sec >= 60){
	$splitin=explode('.',($sec/60));
	if(isset($splitin[1])){
		$secs=substr(intval((substr($splitin[1],0,2)*60)/100),0,2);
	}else{
		$secs=0;
	}
	return $splitin[0].':'.prezero($secs);
	}else{
	return '0:'.prezero($sec);	
	}
}


function per_nonzero($arr){
	
$totallen=count($arr);
$filt=array_filter($arr);
$per=(count($filt)/$totallen)*100;
return intval($per);	
}

$c_range=array();
$j=0;
$i=0;
foreach(explode(",",$result['category_range']) as $ck => $cv){
	$c_range[]=array($i,($i+($cv-1)));
	$i+=$cv;
}
$correct_incorrect_unattempted=explode(",",$result['score_individual']);
 
$cia_cat=cia_cat($correct_incorrect_unattempted,$c_range);
 
$cia_tim_cate=cia_tim_cate($correct_incorrect_unattempted,explode(",",$result['individual_time']),$c_range);



?>
<div class="row noprint" >
<div class="col-lg-12" style="background-image:url('<?php echo base_url('images/result_bg.jpg');?>');background-size:cover;font-size:18px;padding:20px;color:#ffffff;min-height:400px;">
<div class="col-lg-12" >
<center><h3><span style="color:#e39500;"> 
<?php echo 'Xin chào' ?> 
<?php echo $result['last_name'];?> <?php echo $result['first_name'];?>!</span> Đây là kết quả bài kiểm tra của bạn </h3></center>
</div>
<div class="col-lg-12" >
<center>
<h2><span style="color:#e39500;"><?php echo $result['quiz_name'];?>   </span></h2>
</center>
</div>
<div class="col-lg-12" style="margin-top:20px;">
	<!--<div class="col-lg-2" style="text-align:center;">
		<p><?php echo $this->lang->line('score_obtained');?></p>
		<p style="color:#e39500;" ><?php echo $result['score_obtained'];?></p>
	</div> -->
	<!--<div class="col-lg-2"  style="text-align:center;">
		<p><?php echo $this->lang->line('time_spent');?></p>
		<p style="color:#e39500;" ><?php echo secintomin($result['total_time']);?> Min.</p>

	</div>
	-->
	<div class="col-lg-3"  style="text-align:center;">
		<p><?php echo $this->lang->line('attempt_time');?></p>
		<p style="color:#e39500;" ><?php echo date('Y-m-d H:i:s',$result['start_time']);?></p>

	</div>
	<div class="col-lg-3"  style="text-align:center;">
		<p><?php echo $this->lang->line('percentage_obtained');?></p>
		<p style="color:#e39500;" ><?php echo $result['percentage_obtained'];?>%</p>

	</div>
	<div class="col-lg-3"  style="text-align:center;">
		<p>Điểm</p>
		<p style="color:#e39500;" ><?php echo round($result['percentage_obtained']/10	,2);?></p>

	</div>
<!--	
 <div class="col-lg-2"  style="text-align:center;">
		<p><?php echo $this->lang->line('percentile_obtained');?></p>
		<p style="color:#e39500;" ><?php echo substr(((($percentile[1]+1)/$percentile[0])*100),0,5);   ?>%</p>

	</div>
-->
<div class="col-lg-3"  style="text-align:center;">
		<p>Kết quả</p>
		<p style="color:#e39500;" ><?php echo $result['result_status'];?></p>

	</div>
 

	 
</div>
<!--
<div class="col-lg-12" style="margin-top:20px;">
<center>
<?php 
if($result['view_answer']=='1' || $logged_in['su']=='1'){
	
?>
<a href="#answers_i" class="btn btn-info" style="margin-top:10px;"><?php echo $this->lang->line('answer_sheet');?></a>
<?php 
}
?>

<a href="javascript:print();" class="btn btn-success printbtn" style="margin-top:10px;"><?php echo $this->lang->line('print');?></a>

<?php
if($result['gen_certificate']=='1'){
?>
<a href="<?php echo site_url('result/generate_certificate/'.$result['rid']);?>" class="btn btn-warning printbtn" style="margin-top:10px;"><?php echo $this->lang->line('download_certificate');?></a>
	
<?php
}
?>



</center>
</div>

<div class="col-lg-12" style="margin-top:50px;color:#dddddd;font-size:14px;">
 
	
		 
		 <center>
		 <?php echo $this->lang->line('result_id');?> <?php echo $result['rid'];?> &nbsp;&nbsp;&nbsp;
		<?php echo $this->lang->line('user_id');?> <?php echo $result['uid'];?>&nbsp;&nbsp;&nbsp;
		<?php echo $this->lang->line('email');?>: <?php echo $result['email'];?>
		 </center>
		
		 

	 
  
 

	 
</div>
-->

</div>



</div>




 


  <div class="row">
     
<div class="col-md-12">
<br> 
 <div class="login-panel panel panel-default onlyprint">
		<div class="panel-body"> 
	
	
	
		 
<table class="table table-bordered">
<?php 
if($result['camera_req']=='1'){
	?>
<tr><td colspan='2'> <?php if($result['photo']!=''){ ?> <img src ="<?php echo base_url('photo/'.$result['photo']);?>" id="photograph" ><?php } ?></td></tr>
	
	<?php 
}
?>
<tr><td><?php echo $this->lang->line('first_name');?></td><td><?php echo $result['first_name'];?></td></tr>
<tr><td><?php echo $this->lang->line('last_name');?></td><td><?php echo $result['last_name'];?></td></tr>
<tr><td><?php echo $this->lang->line('email');?></td><td><?php echo $result['email'];?></td></tr>
<tr><td><?php echo $this->lang->line('quiz_name');?></td><td><?php echo $result['quiz_name'];?></td></tr>
<tr><td><?php echo $this->lang->line('attempt_time');?></td><td><?php echo date('Y-m-d H:i:s',$result['start_time']);?></td></tr>
<tr><td><?php echo $this->lang->line('time_spent');?></td><td><?php echo secintomin($result['total_time']);?></td></tr>
<tr><td><?php echo $this->lang->line('percentage_obtained');?></td><td><?php echo $result['percentage_obtained'];?>%</td></tr>
<tr><td><?php echo $this->lang->line('percentile_obtained');?></td><td><?php echo substr(((($percentile[1]+1)/$percentile[0])*100),0,5);   ?>%</td></tr>
<tr><td><?php echo $this->lang->line('score_obtained');?></td><td><?php echo $result['score_obtained'];?></td></tr>
<tr><td><?php echo $this->lang->line('status');?></td><td><?php echo $result['result_status'];?></td></tr>

</table>
  
 
		</div>
</div>
<br>


<?php
$ind_score=explode(',',$result['score_individual']); 
// view answer if($result['view_answer']=='1' || $logged_in['su']=='1'){

if($logged_in['su']=='1'){
	
?>

<div class="login-panel panel panel-default">
		<div class="panel-body"> 
		<a name="answers_i"></a>
<h3><?php echo $this->lang->line('answer_sheet');?></h3>

<?php 
$abc=array(
'0'=>'A',
'1'=>'B',
'2'=>'C',
'3'=>'D',
'4'=>'E',
'6'=>'F',
'7'=>'G',
'8'=>'H',
'9'=>'I',
'10'=>'J',
'11'=>'K'
);
foreach($questions as $qk => $question){
?>
 <hr>


<div class="col-md-12 " id="q<?php echo $qk;?>" class="" style="margin:10px;padding:10px;<?php if($ind_score[$qk]=='1'){ ?>background-color:#71ba5d; color:#ffffff;<?php }else if($ind_score[$qk]=='2'){ ?>background-color:#ff5e5e; color:#ffffff;<?php }else if($ind_score[$qk]=='3'){ ?>background-color:#fdfbcf;<?php }else{ ?>background-color:#ffffff;<?php } ?>">
	<div class="col-md-2 col-sm-2">
		<div style="height:45px; width:45px; background-color:#ffffff;border-radius:50%;color:#4b7d42;
		margin-top:6px;padding:14px;"><b><?php echo $qk+1;?></b></div>
	</div>
	<div class="col-md-8 col-sm-8">
		<?php echo $question['question'];?><br>
		
		 <?php

		 // multiple single choice
		 if($question['question_type']==$this->lang->line('multiple_choice_single_answer')){
			 
			 			 			 $save_ans=array();
			 foreach($saved_answers as $svk => $saved_answer){
				 if($question['qid']==$saved_answer['qid']){
					$save_ans[]=$saved_answer['q_option'];
				 }
			 }
			 
			 
			 ?>
			 <input type="hidden"  name="question_type[]"  id="q_type<?php echo $qk;?>" value="1">
			 <?php
			$i=0;
			$correct_options=array();
			foreach($options as $ok => $option){
				if($option['qid']==$question['qid']){
					if($option['score'] >= 0.1){
						$correct_options[]=$option['q_option'];
					}
			?>
			  <?php if(in_array($option['oid'],$save_ans)){   echo'<b>'.$this->lang->line('your_answer').'</b>:'.$option['q_option']; } ?>
			 
			 
			 <?php 
			 $i+=1;
				}else{
				$i=0;	
					
				}
			}echo "<br>";
			echo "<b>".$this->lang->line('correct_options').'</b>: '.implode(', ',array_map('trim',($correct_options)));
		 }
			
// multiple_choice_multiple_answer	

		 if($question['question_type']==$this->lang->line('multiple_choice_multiple_answer')){
			 			 $save_ans=array();
			 foreach($saved_answers as $svk => $saved_answer){
				 if($question['qid']==$saved_answer['qid']){
					$save_ans[]=$saved_answer['q_option'];
				 }
			 }
			 
			 ?>
			 <input type="hidden"  name="question_type[]"  id="q_type<?php echo $qk;?>" value="2">
			 <?php echo '<b>'.$this->lang->line('your_answer').'</b>:';
			$i=0;
			$correct_options=array();
			foreach($options as $ok => $option){
				if($option['qid']==$question['qid']){
						if($option['score'] >= 0.1){
						$correct_options[]=$option['q_option'];
					}
			?>
			 
		 <?php 

if(in_array($option['oid'],$save_ans)){   echo  trim($option['q_option']).', '; }?> 

			 
		 	 	 
			 
			 <?php 
			 $i+=1;
				}else{
				$i=0;	
					
				}
			}echo "<br>";
			echo "<b>".$this->lang->line('correct_options').'</b>: '.implode(', ',$correct_options);
		 }
			 
	// short answer	

		 if($question['question_type']==$this->lang->line('short_answer')){
			 			 $save_ans="";
			 foreach($saved_answers as $svk => $saved_answer){
				 if($question['qid']==$saved_answer['qid']){
					$save_ans=$saved_answer['q_option'];
				 }
			 }
			 ?>
			 <input type="hidden"  name="question_type[]"  id="q_type<?php echo $qk;?>" value="3"   >
			 
			 <?php

			 
			
			 ?>
			 
		<div class="op"> 
		<?php echo '<b>'.$this->lang->line('your_answer').'</b>:';?> 
		   <?php echo $save_ans;?>   
		</div>
			 
			 
			 <?php 
			 			 foreach($options as $ok => $option){
				if($option['qid']==$question['qid']){
					 echo "<b>".$this->lang->line('correct_answer').'</b>: '.$option['q_option'];
			 }
			 }
			 
		 }
		 
		 
		 	// long answer	

		 if($question['question_type']==$this->lang->line('long_answer')){
			 $save_ans="";
			 foreach($saved_answers as $svk => $saved_answer){
				 if($question['qid']==$saved_answer['qid']){
					$save_ans=$saved_answer['q_option'];
				 }
			 }
			 ?>
			 <input type="hidden"  name="question_type[]" id="q_type<?php echo $qk;?>" value="4">
			 <?php
			 ?>
			 
		<div class="op"> 
		<?php echo $this->lang->line('answer');?> <br>
		<?php echo $this->lang->line('word_counts');?>  <?php echo str_word_count($save_ans);?>
		<textarea name="answer[<?php echo $qk;?>][]" id="answer_value<?php echo $qk;?>" style="width:100%;height:100%;" onKeyup="count_char(this.value,'char_count<?php echo $qk;?>');"><?php echo $save_ans;?></textarea>
		</div>
		<?php 
		if($logged_in['su']=='1'){
			if($ind_score[$qk]=='3'){
			
		?>
		<div id="assign_score<?php echo $qk;?>">
		<?php echo $this->lang->line('evaluate');?>	
		<a href="javascript:assign_score('<?php echo $result['rid'];?>','<?php echo $qk;?>','1');"  class="btn btn-success" ><?php echo $this->lang->line('correct');?></a>	
		<a href="javascript:assign_score('<?php echo $result['rid'];?>','<?php echo $qk;?>','2');"  class="btn btn-danger" ><?php echo $this->lang->line('incorrect');?></a>	
		</div>
		<?php 
			}
		}
		?>		
			 
			 <?php 
			 
			 
		 }
			 
		
		
		
		
		
		
		// matching	

		 if($question['question_type']==$this->lang->line('match_the_column')){
			 			 			 $save_ans=array();
			 foreach($saved_answers as $svk => $saved_answer){
				 if($question['qid']==$saved_answer['qid']){
					// $exp_match=explode('__',$saved_answer['q_option_match']);
					$save_ans[]=$saved_answer['q_option'];
				 }
			 }
			 
			 
			 ?>
			 <input type="hidden" name="question_type[]" id="q_type<?php echo $qk;?>" value="5">
			 <?php
			$i=0;
			$match_1=array();
			$match_2=array();
			foreach($options as $ok => $option){
				if($option['qid']==$question['qid']){
					$match_1[]=$option['q_option'];
					$match_2[]=$option['q_option_match'];
			?>
			 
			 
			 
			 <?php 
			 $i+=1;
				}else{
				$i=0;	
					
				}
			}
			?>
			<div class="op">
						<table>
						<tr><td></td><td><?php echo "<b>".$this->lang->line('your_answer').'</b> ';?></td><td><?php echo "<b>".$this->lang->line('correct_answer').'</b> ';?></td></tr>
						<?php 
			 
			foreach($match_1 as $mk1 =>$mval){
						?>
						<tr><td>
						<?php echo $abc[$mk1];?>)  <?php echo $mval;?> 
						</td>
						<td>
						
							 
							 <?php 
							foreach($match_2 as $mk2 =>$mval2){
								?>
<?php $m1=$mval.'___'.$mval2; if(in_array($m1,$save_ans)){ echo $mval2; } ?>  
								<?php 
							}
							?>
							 

						</td>
						
						<td>
						<?php 
							echo $match_2[$mk1];
							?>
						</td>
						
						</tr>
				
						
						<?php 
			}
			
			
			?>
			</table>
			 </div>
			<?php
			
		 }
			
		 ?>
	<p><?php 
 if($question['description']!='') {
				echo '<b>'.$this->lang->line('description').'</b>:';
				echo $question['description'];
			 }
			
?></p>
	
</div>
<div class="col-md-2 col-sm-2" id="q<?php echo $qk;?>"  style="font-size:30px;">

<?php if($ind_score[$qk]=='1'){ ?><i class="glyphicon glyphicon-ok"></i>  <?php }else if($ind_score[$qk]=='2'){ ?><i class="glyphicon glyphicon-remove"></i> <?php }  ?>


</div>
</div>


 <div id="page_break"></div>
 
 
 <?php
}
?>
</div>
</div>
<?php 
}
// view answer ends
?>





 
 
 
 
</div>
    
</div>

 



</div>

<input type="hidden" id="evaluate_warning" value="<?php echo $this->lang->line('evaluate_warning');?>">
 
 <script>
 $('.s_title').tooltip('show');
 </script>
