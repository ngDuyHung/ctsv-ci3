 <div class="container">

   
 <h3>Code QR </h3>
   
 

  <div class="row">
		
		<?php

		$imagePath = 'photo/qr/'. $result['studentid'] .'.png';
		if (is_file($imagePath))
		{
		echo "<img src='data:image/jpeg;base64,".base64_encode(file_get_contents($imagePath))."'><hr>";
		echo "<a href='" . base_url()."attendance/downloadQr'>Download QR</a>";

	}
	else
	{
		echo '<a href="' . base_url().'attendance/qrcodeGenerator'.'">Xem QR Code</a>';
		//redirect( base_url().'attendance/qrcodeGenerator');
		
	}

     ?>
</div>



 



</div>
