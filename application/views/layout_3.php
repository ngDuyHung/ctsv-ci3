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
  <link rel="stylesheet" href="<?php echo $this->config->item('base_urls');?>bootstrap/template/qr/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <style>
    div.attendance:first-child { 
          background-color: yellow;
        }
  </style>
<script>
var arrDaDiemDanh =new Array();
var arrChuaDiemDanh = new Array();
function updateDiemDanh(mssv)
{
  return 1;
  /*
  $.ajax({
    url:'ajaxUpdate.php', 
    data:{mssv:mssv},
    type:"POST",
    //dataType:'json',
    success:function(datareturn)
    {
      if (datareturn==1)
      {
        $("#dssvchuadiemdanh #"+mssv).appendTo($("#dssvchuadiemdanh));
        //$("#source").appendTo("#destination");
      }
    }
  });*/
}

//console.log(arrChuaDiemDanh);
function FFF(v)
{
  $("#test").html(v);
}
</script>
  <script src="<?php echo $this->config->item('base_urls');?>bootstrap/template/qr/jquery.min.js"></script>
  <script src="<?php echo $this->config->item('base_urls');?>bootstrap/template/qr/bootstrap.min.js"></script>
  <!--load jsQR plugin -->
  <script src="<?php echo $this->config->item('base_urls');?>bootstrap/template/qr/jsQR.js"></script>
</head>
<body>
<input id="txtTest" type=hidden><span id="divTest"></span>
  <audio class="sound" src="<?php echo $this->config->item('base_urls');?>bootstrap/template/qr/barcode.wav" ></audio>
    <div class="row">
        <div class="col-lg-6">
          <div class="input-group">
            <input id="scanner_input" class="form-control" placeholder="Bấm chọn button để quét mã vạch..." type="text"  /> 
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
              <h4 class="modal-title" id="cameretitle">Scan QR from Camera</h4>
            </div>
            <div class="modal-body" style="position: static">
              <div id="loadingMessage"></div>
              <div id="interactive" class="viewport">
                <canvas id="canvas" hidden></canvas>
              </div>
              <div class="error"></div>
            </div>
            <div id="output" hidden>
                <div id="outputMessage">No code QR.</div>
                <div hidden><b>QR ...:</b> <span id="outputData"></span></div>
             </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Scan</button>
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
      loadingMessage.innerText = "Scanning..."
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
         // outputData.innerText = code.data;

          //lam gi do voi code.data vd: so sanh code neu found thi stop
          if(code.data != '' && code.data != $("#txtTest").val())
          {
            $('.sound').get(0).play();
            $('#scanner_input').val(code.data);
         //   $('#livestream_scanner').modal('hide');
          //  alert("test:"+ code.data);
            $("#txtTest").val(code.data);
         ////  $('#exampleModalLong').modal('show');
            Ktra();
          // $('#exampleModalLongTitle').html(code.data );
           
          }
        } else {
          outputMessage.hidden = false;
          outputData.parentElement.hidden = true;
        }
      }
      requestAnimationFrame(tick);
    }
  
  
  $('#txtTest').on('change textInput input', function () {
     //  $("#divTest").html($('#txtTest').val());
  // diemDanh($('#txtTest').val());
});

  //);

</script>


<div id="attendance">
  
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Đang kiểm tra SV....
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      
      </div>
    </div>
  </div>
</div>

<script>
 /*
  $('#exampleModalLong').on('show.bs.modal', function (e) 
  {
    //checkAttendance(); 
   Ktra();
});
*/
  function Ktra()
  {
    $("#cameretitle").html("Checking..."+$("#txtTest").val());
      $.ajax({
          url: href="<?php echo $this->config->item('base_urls');?>attendance/check",
          data:{qr: $('#txtTest').val()},
        //  type:"POST",
         // dataType:'json',
          success:function(datareturn)
          {
          //  alert(datareturn +' , len='+ datareturn.length);
           
           datareturn = datareturn.trim();
            console.log(datareturn);
            if (datareturn=='1')
            {
              s ="<div class='attendance'>"+ $('#scanner_input').val()+"</div>";
              $("#attendance").html(s + $("#attendance").html());
              $("#cameretitle").html("Đã ghi nhận:"+$("#txtTest").val());
              alert("Thank!");

             // $('#exampleModalLong').modal('hide');
            }
             if (datareturn=='2')
            {

              alert("SV đã điểm danh rồi - (2)");

            //  $('#exampleModalLong').modal('hide');
            }
            if (datareturn=='0')
            {
              $("#cameretitle").html("Not found:"+$("#txtTest").val());
              alert("Not found!");

            //  $('#exampleModalLong').modal('hide');
            }

          },
          error: function (xhr, ajaxOptions, thrownError) 
          {
              console.log(xhr.status);
              alert(thrownError);
              $("#cameretitle").html("Scan QR from Camera");
          }
      });

}
</script>
</body>
</html>