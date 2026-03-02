<?php
class Base{
public function str_before($subject, $needle)
{
    $p = strpos($subject, $needle);
    return substr($subject, 0, $p);
}

public function pagelink($curr,$count,$baseurl='',$size=5){
	if(empty($baseurl)){
		$baseURL = strstr($_SERVER['PHP_SELF'],'index.php',1);
		parse_str($_SERVER['QUERY_STRING'],$arr);
		$str="";
		if(isset($arr["page"])) unset($arr["page"]);
		foreach($arr as $k=>$v) $str.="&$k=$v";
		$str=substr($str,1);
		$baseURL.= '?'.$str.'&page=';
	}else
		$baseURL = $baseurl;
	$next=$curr + 1;
	$prev=$curr - 1;
	
	$begin = $curr - floor($size/2); $end = $curr + floor($size/2);
	if($begin < 1) $begin = 1;
	if($end > $count) $end = $count;
	if($count>1){
	echo '<ul class="paging">';
	if($curr!=1){ ?>
    	<li><a href="<?php echo $baseURL;?>1" title="Trang Đầu"><<</a></li>
		<li><a href="<?php echo $baseURL.$prev; ?>" title="Trang Trước"><</a></li>
<?php	}
	for($i=$begin;$i<=$end;$i++){
		if($curr==$i){ ?>
        	<li><a title="Trang <?php echo $i;?>" class="active"><?php echo $i;?></a></li>
<?php	}
		else
			echo "<li><a href=\"$baseURL$i\" title=\"Trang $i\">$i</a></li>";
	} 
	if($curr!=$count){?>
		<li><a href="<?php echo $baseURL.$next; ?>" title="Trang Sau">></a></li>
        <li><a href="<?php echo $baseURL.$count; ?>" title="Trang Cuối">>></a></li>
<?php	}
	echo '</ul>';
	}
}

public function pagelink2($baseURL="",$curr,$count,$size=5){
	$url = explode('?',$baseURL);
	$starturl = current($url);
	$endurl = (count($url)>1)?'?'.end($url):'';
	$next=$curr + 1;
	$prev=$curr - 2;
	$begin = $curr - floor($size/2); $end = $curr + floor($size/2);
	if($begin < 1) $begin = 1;
	if($end > $count) $end = $count;
	if($count>1){
	echo '<ul class="paging">';
	if($curr!=1){ ?>
    	<li><a href="<?php echo $starturl;?>/1<?php echo $endurl;?>" title="Trang Đầu"><<</a></li>
		<li><a href="<?php echo $starturl."/".$prev.$endurl; ?>" title="Trang Trước"><</a></li>
<?php	}
	for($i=$begin;$i<=$end;$i++){
		if($curr==$i){?>
        	<li><a title="Trang <?php echo $i;?>" class="active"><?php echo $i;?></a></li>
<?php	}
		else
			echo "<li><a href=\"$starturl/$i$endurl\" title=\"Trang $i\">$i</a></li>";
	} 
	if($curr!=$count){?>
		<li><a href="<?php echo $starturl."/".$next.$endurl; ?>" title="Trang Sau">></a></li>
        <li><a href="<?php echo $starturl."/".$count.$endurl; ?>" title="Trang Cuối">>></a></li>
<?php	}
	echo '</ul>';
	}
}

public function pagelink3($url="",$curr,$count,$size=5){
	$next=$curr + 1;
	$prev=$curr - 1;
	$begin = $curr - floor($size/2); $end = $curr + floor($size/2);
	if($begin < 1) $begin = 1;
	if($end > $count) $end = $count;
	if($count>1){
	echo '<ul class="paging">';
	if($curr!=1){ ?>
    	<li><a href="" onclick="javascript:ajax_page('<?php echo $url;?>',1); return false;" alt="1" title="Trang Đầu">Đầu</a></li>
		<li><a href="" onclick="javascript:ajax_page('<?php echo $url;?>',<?php echo $prev;?>); return false;" alt="<?php echo $prev;?>" title="Trang Trước">Trước</a></li>
<?php	}
	for($i=$begin;$i<=$end;$i++){
		if($curr==$i){?>
        	<li><a title="Trang <?php echo $i;?>" class="active"><?php echo $i;?></a></li>
<?php	}
		else
			echo "<li><a alt=\"$prev\" href=\"\" onclick=\"javascript:ajax_page('$url',$i); return false;\" title=\"Trang $i\">$i</a></li>";
	} 
	if($curr!=$count){?>
		<li><a href="" onclick="javascript:ajax_page('<?php echo $url;?>',<?php echo $next;?>); return false;" alt="<?php echo $next;?>" title="Trang Sau">Sau</a></li>
        <li><a href="" onclick="javascript:ajax_page('<?php echo $url;?>',<?php echo $count;?>); return false;" alt="<?php echo $count;?>" title="Trang Cuối">Cuối</a></li>
<?php	}
	echo '</ul>';
	}
}

public function khongdau($str) { //hàm lọc bỏ dấu tiếng việt cho 1 chuỗi
    $search = array (
        '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
        '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
        '#(ì|í|ị|ỉ|ĩ)#',
        '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
        '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
        '#(ỳ|ý|ỵ|ỷ|ỹ)#',
        '#(đ)#',
        '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
        '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
        '#(Ì|Í|Ị|Ỉ|Ĩ)#',
        '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
        '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
        '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
        '#(Đ)#',
		'#(A-Z)#',
        "/[^a-zA-Z0-9.\-\_]/",
    );
    $replace = array ('a','e','i','o','u','y','d','A','E','I','O','U','Y','D','a-z','',);
    $str = preg_replace($search, $replace, $str); 
    $str = preg_replace('/(-)+/', '', $str);
    return $str;
}

public function countcart($arr){
	$dem = 0;
	foreach($arr as $k=>$v){
		$dem += count($v);
	}
	return $dem;
}

public function locdau($string){
	$string=mb_strtolower($string,'UTF-8');
	$str=explode(" ",$string); 
	$str=join(" ",$this->khongdau($str));
	$str=addslashes($str); 
	return $str;
}

public function stripuptitle($str){

	$str=mb_convert_case($this->ecode($this->locdau($str)),MB_CASE_TITLE,'utf-8'); 
	return $str;
}

public function striptitle($str){
	$str = str_replace('-',' ', $str);
	$str = str_replace('  ','', $str);
	$str=strtolower($this->ecode($this->locdau($str))); 
	return $str;
}

public function firststring($str){
	$string='';
	foreach(explode('-',striptitle($str)) as $row){
		$string.=substr($row,0,1);
	}
	return $string;
}

public function catchuoi($str,$text){ 
	$arr1=locdau($str); 
	$text=locdau($text);
	$k=strpos($arr1,$text);
	$t=$k+strlen($text)-1; 
	$str1='';
    for($i=0;$i<mb_strlen($str,'utf-8');$i++){
		if($i>=$k && $i<=$t)
			$str1.="<b>".mb_substr($str,$i,1,'utf-8')."</b>"; 
		else
			$str1.=mb_substr($str,$i,1,'utf-8');
	}
	return $str1;
} 

public function EncodeSpecialChar($content) { 
	$content = trim($content);
	if ( get_magic_quotes_gpc())
	$content = addslashes($content);
	return $content;
}

public function select_menu($col,$table){
	$chk=mysql_query("select $col from $table") or die ("lỗi truy vấn");
	while($row=mysql_fetch_array($chk)){?>
    	<option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
<?php }
}

public function selected_menu($col,$table,$key){
	$chk=mysql_query("select $col from $table") or die ("lỗi truy vấn");
	while($row=mysql_fetch_array($chk)){
		
		$s=($row[0]==$key)?"selected":"";?>
    	<option value="<?php echo $row[0];?>" <?php echo $s;?>><?php echo $row[1];?></option>
<?php }
}

public function selected_menu2($key){
	$chk0=mysql_query("select * from nhomsp");
	while($row0 =mysql_fetch_array($chk0))
	{
		$idNhomSp = $row0["idNhomSp"];
		$tenNhomSp = $row0["tenNhomSp"];
	$chk=mysql_query("select * from loaisp where idNhomSp='$idNhomSp'") or die ("lỗi truy vấn");
	echo "<optgroup label=\"$tenNhomSp\"> ";
	while($row=mysql_fetch_array($chk)){
		
		$s=($row[0]==$key)?"selected":"";?>
    	<option value="<?php echo $row[0];?>" <?php echo $s;?>><?php echo $row[1];?></option>
<?php }
	}

}

public function ecode($str){
	$str=explode(" ", $str);
	$str=join("-",$str);
	return $str;
}

public function unencode($str){
	$str=explode("-", $str);
	$str=join(" ",$str);
	return $str;
}

public function check_file($file_name,$extent){
	$pat="#.+\.($extent)$#i";
	if(preg_match($pat,$file_name)==1)
		return true;
	else
		return false;
}

public function chuoingaunhien($n){
	$text=md5(rand(0,9999));
	$text=substr($text,0,$n);
	return $text;
}

public function substring($chuoi,$gioihan){ 
    // nếu độ dài chuỗi nhỏ hơn hay bằng vị trí cắt 
    // thì không thay đổi chuỗi ban đầu 
    if(strlen($chuoi)<=$gioihan) 
    { 
        return $chuoi; 
    } 
    else{ 
        /*  
        so sánh vị trí cắt  
        với kí tự khoảng trắng đầu tiên trong chuỗi ban đầu tính từ vị trí cắt 
        nếu vị trí khoảng trắng lớn hơn 
        thì cắt chuỗi tại vị trí khoảng trắng đó 
        */ 
        if(strpos($chuoi," ",$gioihan) > $gioihan){ 
            $new_gioihan=strpos($chuoi," ",$gioihan); 
            $new_chuoi = substr($chuoi,0,$new_gioihan)."..."; 
            return $new_chuoi; 
        } 
        // trường hợp còn lại không ảnh hưởng tới kết quả 
        $new_chuoi = substr($chuoi,0,$gioihan)."..."; 
        return $new_chuoi; 
    }
}
public function get_imagesize($path){
	$path=file_exists($path)?$path:'';
	list($info_width,$info_height) = getimagesize($path);
	return array($info_width,$info_height);
} 

	
	}