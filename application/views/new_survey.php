 <div class="container">

   
 
   
  <div class="row">
  	<form method="post" action="<?php echo site_url('survey/insert_survey/');?>" onsubmit="return check_notification_form();">
		<input type="hidden" name="rid" value="<?php echo $rid;?>">
		<div class="col-md-12">
		<h3 style="text-align:center">PHIẾU LẤY Ý KIẾN SINH VIÊN VỀ TUẦN SINH HOẠT CÔNG DÂN GIỮA KHÓA, <br>
		CUỐI KHÓA NĂM HỌC 
		<?php
		$year=date('Y');
		$month=date('m');
		if($month>=9)
			echo $year, ' - ',$year+1;
		else
			echo $year-1, ' - ',$year;
		?>
		</h3> 
		<?php 
		if($this->session->flashdata('message')){
			echo $this->session->flashdata('message');	
		}
		if($done==1)
			echo '<h4 align="center">Bạn đã làm khảo sát rồi</h4>';
		else if(isset($rid) && $rid!='')
		{

		?>
		<p style="font-weight:bold">Phòng Công tác Sinh viên chúng tôi lắng nghe và ghi nhận ý kiến đóng góp của sinh viên</p>
			 <div class="login-panel panel panel-default">
					<div class="panel-body"> 

							<p style="font-weight:bold">Đánh giá về chương trình :</p>
							<div class="form-group"> <!--start table-->	 
							<div class="row" style="margin: 10px 0px !important">
								<div class="col-md-4">Hình thức tổ chức chương trình</div>
									
								<div class="col-md-4">
										<select name="formality" id="formality" class="form-control" required>
											<option value="">Hãy chọn mức độ hài lòng</option>
											<option value="1">Không hài lòng</option>
											<option value="2">Bình thường</option>
											<option value="3">Hài lòng</option>
											<option value="4">Hoàn toàn hài lòng</option>
										</select>
								</div>
							</div>
							<div class="row" style="margin: 10px 0px !important">
								<div class="col-md-4">Thời gian tổ chức chương trình</div>
								<div class="col-md-4">
										<select name="time" id="time" class="form-control" required>
											<option value="">Hãy chọn mức độ hài lòng</option>
											<option value="1">Không hài lòng</option>
											<option value="2">Bình thường</option>
											<option value="3">Hài lòng</option>
											<option value="4">Hoàn toàn hài lòng</option>
										</select>
								</div>
						</div>
						<div class="row" style="margin: 10px 0px !important">
								<div class="col-md-4">Nội dung chuyên đề</div>
								<div class="col-md-4">
										<select name="content" id="content" class="form-control" required>
											<option value="">Hãy chọn mức độ hài lòng</option>
											<option value="1">Không hài lòng</option>
											<option value="2">Bình thường</option>
											<option value="3">Hài lòng</option>
											<option value="4">Hoàn toàn hài lòng</option>
										</select>
								</div>
							</div>
							<div class="row" style="margin: 10px 0px !important">
								<div class="col-md-4">Báo cáo viên</div>
								<div class="col-md-4">
										<select name="presenter" id="presenter" class="form-control" required>
											<option value="">Hãy chọn mức độ hài lòng</option>
											<option value="1">Không hài lòng</option>
											<option value="2">Bình thường</option>
											<option value="3">Hài lòng</option>
											<option value="4">Hoàn toàn hài lòng</option>
										</select>
								</div>
							</div>
							</div> <!--end_table-->
					</div>
					<div class="form-group" style="margin:20px !important">	 
								<label for="suggest">Đề nghị cải tiến (nếu có):</label> 
								<textarea name="suggest" id="suggest" class="form-control"  ></textarea>
						</div>
						<div class="form-group" style="text-align:center !important">
					<button class="btn btn-primary" type="submit"><?php echo $this->lang->line('submit');?></button>
					</div>
				</div>
			</div>
			<?php
				}else
					echo "Không xác định";
			?>
		</div>
	</form>

</div>
</div>
