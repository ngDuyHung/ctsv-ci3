 <div class="container">

   
 <h3>QR Code </h3>
   
 

  <div class="row">
		
		<?php

		$imagePath = 'photo/qr/'. $result['studentid'] .'.png';
		if (is_file($imagePath))
		{
		echo '<table><tr><td>';
		echo "<img width=150 src='data:image/jpeg;base64,".base64_encode(file_get_contents($imagePath))."'>";
		echo '</td><td>';
		echo "<a href='" . base_url()."attendance/downloadQr'>Download QR</a></tr>";
		
        echo '<tr><td colspan=2><a href="' . base_url().'attendance/qrcodeGenerator'.'"><b>Tạo lại QR Code</b></a></td></tr></table>';


	}
	else
	{
		echo '<a href="' . base_url().'attendance/qrcodeGenerator'.'">Xem QR Code</a>';
		//redirect( base_url().'attendance/qrcodeGenerator');
		
	}

     ?>
</div>
<div class=row>
Nếu Mã QR tạo ra không điểm danh được, có thể 
<a href='<?php echo base_url();?>huong-dan.html' target=_blank'>xem hướng dẫn sinh mã tại đây</a>
</div>



 



</div>
