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
								Kết quả thi
							</h3>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								

								

								<div class="row">
									<div class="col-xs-12">
										
										

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
									
										<div >
										<form method="post" action="<?php echo site_url('result/filter/');?>">
   <div class="row">
	 
	
		<div class="col-md-2">
			<div class="form-group">
				<select name="quid" class="form-control">
					<option value="0"><?php echo $this->lang->line('select_quiz');?></option>
					<?php 
					foreach($quiz_list as $qk => $quiz){
						?>
						<option value="<?php echo $quiz['quid'];?>"><?php echo $quiz['quiz_name'];?></option>
						<?php 
					}
					?>
				</select>
			</div>
		</div>
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
			<div class="form-group">
				<select name="status" class="form-control">
					<option value="0"><?php echo $this->lang->line('all');?></option>
					<option value="<?php echo $this->lang->line('pass'); ?>" <?php if($status==$this->lang->line('pass')){ echo 'selected'; } ?> ><?php echo $this->lang->line('pass');?></option>
					<option value="<?php echo $this->lang->line('fail'); ?>" <?php if($status==$this->lang->line('fail')){ echo 'selected'; } ?> ><?php echo $this->lang->line('fail');?></option>
					<option value="<?php echo $this->lang->line('pending'); ?>" <?php if($status==$this->lang->line('pending')){ echo 'selected'; } ?> ><?php echo $this->lang->line('pending');?></option>
					<option value="NULL">Không làm bài</option>
				</select>
			</div>
		</div>
		<div class="col-md-2" style="text-align:center">
 			<button class="btn btn-info" name="filter" value="filter" type="submit">Lọc</button>
			
		
		</div>
	</div>
</form>

										</div>
										<div>
										<table id="ketqua_thi" class="table table-bordered">
												<thead>
													<tr>
													<th>STT</th>
														<th>MSSV</th>
														<th>Họ và lót</th>
														<th>Tên</th>
														<th >Lớp</th>
														<th>Điểm</th>
														<th>Kết quả</th>
														<th >Số điểm danh</th>
														<th >Ghi chú</th>

														<th>Hành động</th>
													</tr>
												</thead>

												<tbody>

							<?php
							if(count($result)==0)
								echo '<tr><td colspan="9">Không có kết quả nào</td></tr>';
							else
							{
								$i=1;
							foreach ($result as $row) {
							
							
							?>
								<tr>
									<td><?php echo $i; ?></td>
									<td>
										<!--<a href="<?php echo base_url().'result/'.$row['rid']?>"><?php echo $row['studentid'] ?></a> -->
										<?php echo $row['studentid'] ?>
									</td>
									
									<td >
									<?php echo $row['last_name'] ?>
									</td>
									<td>
									<?php echo $row['first_name'] ?>

									</td>
									<td><?php echo $row['classid'] ?></td>
									<td><?php echo round($row['percentage_obtained']/10	,2);?></td>

									<td><?php 
									if($row['result_status']=='')
											echo 'Không đạt';
									else
											echo $row['result_status'] ?></td>
									<td>
									<?php
										echo $row['num_attendance']
									?>
									</td>
									<td>
									<?php
										if($row['result_status']=='')
											echo "Không làm bài"
									?>
									</td>
									
									<td >
									<?php
										if($row['rid']!="")
										{
									?>
									<a href="<?php echo site_url('result/view_result/'.$row['rid']);?>" class="btn btn-success" ><?php echo $this->lang->line('view');?> </a>
									<a href="javascript:remove_entry('result/remove_result/<?php echo $row['rid'];?>');"><img src="<?php echo base_url('images/cross.png');?>"></a>
									<?php } ?>
									</td>

								</tr>
						<?php
						$i++;
					}}
											?>

												</tbody>
											</table>
										
										</div>
										
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
	var myTable = $('#ketqua_thi').DataTable({
			dom:'Blfrtip',
			  buttons: [
					{
           extend: 'excel',
           footer: true,
					 exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8]
        		}
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