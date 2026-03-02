<link rel="stylesheet" type="text/css" media="screen" href="">
<div class="container">
<link href="<?php echo base_url('css/jquery.dataTables.min.css');?>" rel="stylesheet">
<?php 
$logged_in=$this->session->userdata('logged_in');
?>
<script>
	class_lst="";
	function select_faculty(facultyid) {
		$("#class_slt").html(class_lst);
		if(facultyid!="-")
		{
			options=$("#class_slt option");
			for(i=1;i<options.length;i++)
				if(options[i].getAttribute('faculty')!=facultyid)
					options[i].remove();
		}
		//$("#class_slt option[faculty!="+facultyid+"]").css('display','none');
	}
</script>   
  
<!--	 <div class="main-container ace-save-state" id="main-container">  -->

			<div id="sidebar" class="sidebar                  responsive                    ace-save-state"></div>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						
							<h3>
								Kết quả khảo sát
							</h3>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
									
										<div >
										<form method="post" action="<?php echo site_url('survey/filter');?>">
   			<div class="row">
				 <div class="col-md-2">
					<div class="form-group">
						<select name="gid" class="form-control">
							<option value="-"><?php echo $this->lang->line('select_group');?></option>
							<?php 
							foreach($group_list as $gk => $group){
								?>
								<option value="<?php echo $group['gid'];?>"><?php echo $group['group_name'];?></option>
								<?php 
							}
							?>
						</select>
					</div>
					</div>		
					<div class="col-md-2">
					<div class="form-group">
					<select class="form-control" name="faculty" id="faculty" onchange="select_faculty(this.value)">
					<option value="-">Chọn khoa</option>
					<?php 
					foreach($faculty_list as $val){
						?>
						<option value="<?php echo $val['facultyid'];?>"><?php echo $val['facultyname'];?></option>
						<?php 
					}
					?>
					</select>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
			<select class="form-control" name="class_lst" id="class_slt" >
					<option value="-">Chọn lớp</option>
					<?php 	
					$class_lst = '<option value="-">Chọn lớp</option>';				
					foreach($class_list as $val){
						$class_lst .='<option value="'.$val['classid'].'" faculty="'.$val['facultyid'].'">'.$val['classid'].'</option>';
						?>
						
						<option value="<?php echo $val['classid'];?>" faculty="<?php echo $val['facultyid'];?>"><?php echo $val['classid'];?></option>
						<?php 
					}
					?>
					</select>
					
			</div>
		</div>
		<div class="col-md-2">
			<input type="date" name="start_date" id="start_date" class="form-control">
		</div>
		<div class="col-md-2">
			<input type="date" name="end_date" id="end_date" class="form-control">
		</div>
		<div class="col-md-2" style="text-align:center">
 			<button class="btn btn-info" name="filter" value="filter" type="submit">Lọc</button>
			
		
		</div>
	</div>
</form>

										</div>
									
										<div>
										<table id="ketqua_khaosat" class="table table-bordered">
												<thead>
													<tr>
														<th>STT</th>
														<th>Khoa</th>
														<th>Lớp</th>
														<th>Khóa học</th>
														<th>Hình thức</th>
														<th>Thời gian </th>
														<th >Nội dung</th>
														<th>Báo cáo viên</th>
														<th>Đề nghị cải tiến</th>
													</tr>
												</thead>

												<tbody>

												<?php
												if(count($survey_list)==0)
													echo '<tr><td colspan="9">Không có kết quả nào</td></tr>';
												else
												{
													$rate=array(1=>'Không hài lòng',2=>'Bình thường',3=>'Hài lòng',4=>'Hoàn toàn hài lòng');
													$formality=array(1=>0,0,0,0);
													$time=array(1=>0,0,0,0);;
													$content=array(1=>0,0,0,0);;
													$presenter=array(1=>0,0,0,0);;
													$i=1;
												foreach ($survey_list as $row) {
													//var_dump($row);exit;
													$formality[$row['formality']]++;
													$time[$row['time']]++;
													$content[$row['content']]++;
													$presenter[$row['presenter']]++;
												
												?>
													<tr>
														<td align='center'><?php echo $i; ?></td>
														<td><?php echo $row['facultyname'] ?></td>
														<td><?php echo $row['classid'] ?></td>
														<td><?php echo $row['academic_year'] ?></td>
														<td><?php echo $rate[$row['formality']] ?></td>
														<td><?php echo $rate[$row['time']] ?></td>
														<td><?php echo $rate[$row['content']] ?></td>
														<td><?php echo $rate[$row['presenter']] ?></td>
														<td><?php echo $row['suggest'] ?></td>
													</tr>
											<?php
											$i++;
										}
											?>
												</tbody>
											
									<?php
									}
									$i--;
									?>
											</table>
										

										
										</div>
										<?php if($i>0)
									{
										?>
										<div>
											<br>
											<h3 align="center">TỔNG HỢP PHIẾU LẤY Ý KIẾN SINH VIÊN</h3>
										<table id="summary" class="table table-bordered">
										<thead>
											<tr>
												<th rowspan="2" align='center'>Ý kiến <br>Nội dung</th>
												<th colspan="2" align='center'>Không hài lòng</th>
												<th colspan="2" align='center'>Bình thường</th>
												<th colspan="2" align='center'>Hài lòng</th>
												<th colspan="2" align='center'>Hoàn toàn hài lòng</th>
												<th align='center'>Tổng số phiếu</th>
											</tr>
											<tr>
												<th>Số lượng</th>
												<th>Tỉ lệ</th>
												<th>Số lượng</th>
												<th>Tỉ lệ</th>
												<th>Số lượng</th>
												<th>Tỉ lệ</th>
												<th>Số lượng</th>
												<th>Tỉ lệ</th>
												<th>Số lượng</th>
											</tr>
											</thead>
											<tbody>
											<tr>
												<td>Hình thức tổ chức chương trình</td>
												<td><?php echo $formality[1] ?></td>
												<td><?php echo round($formality[1]/$i*100,2)?> %</td>
												<td><?php echo $formality[2] ?></td>
												<td><?php echo round($formality[2]/$i*100,2)?> %</td>
												<td><?php echo $formality[3] ?></td>
												<td><?php echo round($formality[3]/$i*100,2)?> %</td>
												<td><?php echo $formality[4] ?></td>
												<td><?php echo round($formality[4]/$i*100,2)?> %</td>
												<td align='center'><?php echo $i;?></td>
											</tr>
											<tr>
												<td>Thời gian tổ chức chương trình</td>
												<td><?php echo $time[1] ?></td>
												<td><?php echo round($time[1]/$i*100,2)?> %</td>
												<td><?php echo $time[2] ?></td>
												<td><?php echo round($time[2]/$i*100,2)?> %</td>
												<td><?php echo $time[3] ?></td>
												<td><?php echo round($time[3]/$i*100,2)?> %</td>
												<td><?php echo $time[4] ?></td>
												<td><?php echo round($time[4]/$i*100,2)?> %</td>
												<td align='center'><?php echo $i;?></td>
											</tr>
											<tr>
												<td>Nội dung chuyên đề</td>
												<td><?php echo $content[1] ?></td>
												<td><?php echo round($content[1]/$i*100,2)?> %</td>
												<td><?php echo $content[2] ?></td>
												<td><?php echo round($content[2]/$i*100,2)?> %</td>
												<td><?php echo $content[3] ?></td>
												<td><?php echo round($content[3]/$i*100,2)?> %</td>
												<td><?php echo $content[4] ?></td>
												<td><?php echo round($content[4]/$i*100,2)?> %</td>
												<td align='center'><?php echo $i;?></td>
											</tr>
											<tr>
												<td>Báo cáo viên</td>
												<td><?php echo $presenter[1] ?></td>
												<td><?php echo round($presenter[1]/$i*100,2)?> %</td>
												<td><?php echo $presenter[2] ?></td>
												<td><?php echo round($presenter[2]/$i*100,2)?> %</td>
												<td><?php echo $presenter[3] ?></td>
												<td><?php echo round($presenter[3]/$i*100,2)?> %</td>
												<td><?php echo $presenter[4] ?></td>
												<td><?php echo round($presenter[4]/$i*100,2)?> %</td>
												<td align='center'><?php echo $i;?></td>
											</tr>
											</tbody>
										</table>
										</div>
									<?php } ?>
									</div>
								</div>

								

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			

		
	<!--	</div> --><!-- /.main-container -->

		<!-- basic scripts -->

		<script src="<?php echo base_url();?>js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>js/buttons.flash.min.js"></script>
		<script src="<?php echo base_url();?>js/jszip.min.js"></script>
		<script src="<?php echo base_url();?>js/pdfmake.min.js"></script>
		<script src="<?php echo base_url();?>js/vfs_fonts.js"></script>
		<script src="<?php echo base_url();?>js/buttons.html5.min.js"></script>
		<script src="<?php echo base_url();?>js/buttons.print.min.js"></script>
		<script>
						
		class_lst='<?php echo $class_lst;?>';
					
$(document).ready(function() {
	var myTable = $('#ketqua_khaosat').DataTable({
			dom:'Blfrtip',
			  buttons: [
					{
           extend: 'excel',
           footer: true
       		},
					{
           extend: 'pdf',
           footer: true,
           
       	},
       	{
           extend: 'print',
           footer: true  
       }
       
		],
			"language": {
    	"search": "Tìm:",
			"paginate": {
        "first":      "|<",
        "last":       ">|",
        "next":       ">>",
        "previous":   "<<"
    	},
			"info":           "Đang hiển thị từ _START_ đến _END_ của _TOTAL_",
			"lengthMenu":     "Hiển thị _MENU_ dòng",
			"infoEmpty":      "0 dòng",
			"infoFiltered":   "(được lọc từ _MAX_ dòng)",
			"zeroRecords":    "Tìm không có"
  	}
		});

		$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
		new $.fn.dataTable.Buttons( myTable, {
					buttons: [
					  {
						"extend": "colvis",
						"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
						"className": "btn btn-white btn-primary btn-bold",
						columns: ':not(:first):not(:last)'
					  },
					  {
						"extend": "copy",
						"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "csv",
						"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "excel",
						"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "pdf",
						"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "print",
						"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
						"className": "btn btn-white btn-primary btn-bold",
						autoPrint: false,
						message: 'This print was produced using the Print button for DataTables'
					  }		  
					]
				} );
				myTable.buttons().container().appendTo( $('.tableTools-container') );
		
} );


</script>


</div>