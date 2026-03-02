<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="Cache-Control" content="no-cache">
	<?php 
	header("Cache-control: no-store, no-cache, must-revalidate");
	header("Expires: Mon, 26 Jun 1997 05:00:00 GMT");
	header("Pragma: no-cache");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="mobile-web-app-capable" content="yes">		
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="jquery.min.js"></script>
	<script src="bootstrap.min.js"></script>
	<!--load jsQR plugin -->
	<script src="./jsQR.js"></script>
</head>
<body>
	<audio class="sound" src="barcode.wav" ></audio>
		<div class="row">
				<div class="col-lg-6">
					<div class="input-group">
						<input id="scanner_input" class="form-control" placeholder="Bấm nút để scan mã vạch 2D..." type="text" /> 
						<span class="input-group-btn"> 
							<button class="btn btn-default" type="button" data-toggle="modal" data-target="#livestream_scanner">
								<i class="fa fa-barcode"></i> 
							</button> 
						</span>
					</div><!-- /input-group -->
				</div><!-- /.col-lg-6 -->
			</div><!-- /.row -->
			<div class="modal" id="livestream_scanner">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title">Quét QR từ camera</h4>
						</div>
						<div class="modal-body" style="position: static">
							<div id="loadingMessage"></div>
							<div id="interactive" class="viewport">
								<canvas id="canvas" hidden></canvas>
							</div>
							<div class="error"></div>
						</div>
						<div id="output" hidden>
						    <div id="outputMessage">Chưa nhận diện được QR.</div>
						    <div hidden><b>QR đọc được:</b> <span id="outputData"></span></div>
						 </div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
<style>
	#interactive.viewport {position: relative; width: 100%; height: auto; overflow: hidden; text-align: center;}
	#interactive.viewport > canvas, #interactive.viewport > video {max-width: 100%;width: 100%;}
	canvas.drawing, canvas.drawingBuffer {position: absolute; left: 0; top: 0;}
</style>
<script type="text/javascript">
	var video = document.createElement("video");
    var canvasElement = $("#canvas")[0];
    var canvas = canvasElement.getContext("2d");
    var loadingMessage = $("#loadingMessage")[0];
    var outputContainer = $("#output")[0];
    var outputMessage = $("#outputMessage")[0];
    var outputData = $("#outputData")[0];

    function drawLine(begin, end, color) {
      canvas.beginPath();
      canvas.moveTo(begin.x, begin.y);
      canvas.lineTo(end.x, end.y);
      canvas.lineWidth = 4;
      canvas.strokeStyle = color;
      canvas.stroke();
    }

    $('#livestream_scanner').on('show.bs.modal', function(){
		 // Use facingMode: environment to attemt to get the front camera on phones
	    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream){
	      video.srcObject = stream;
	      video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
	      video.play();
	      requestAnimationFrame(tick);
	    });
	});

    $('#livestream_scanner').on('hide.bs.modal', function(){
		video.pause();
		video.src = "";
		video.srcObject.getTracks()[0].stop();
	});

	function tick() {
      loadingMessage.innerText = "Đang nạp..."
      if (video.readyState === video.HAVE_ENOUGH_DATA) {
        loadingMessage.hidden = true;
        canvasElement.hidden = false;
        outputContainer.hidden = false;

        //lay hinh va parse hinh de doc QR
        canvasElement.height = video.videoHeight;
        canvasElement.width = video.videoWidth;
        canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
        var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
        var code = jsQR(imageData.data, imageData.width, imageData.height, {
          inversionAttempts: "innerText",
        });
        //tim thay boder canvas
        if (code) {
          drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#EB0202");
          drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#EB0202");
          drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#EB0202");
          drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#EB0202");
          outputMessage.hidden = true;
          outputData.parentElement.hidden = false;
          outputData.innerText = code.data;
          //lam gi do voi code.data vd: so sanh code neu found thi stop
          if(code.data == '7b1b36a1f87459f45aafe9a168d5ef96'){
          	$('.sound').get(0).play();
          	$('#scanner_input').val(code.data);
          	$('#livestream_scanner').modal('hide');
          }
        } else {
          outputMessage.hidden = false;
          outputData.parentElement.hidden = true;
        }
      }
      requestAnimationFrame(tick);
    }
</script>
</body>
</html>