<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Kết quả điểm danh</title>

		<meta name="description" content="Static &amp; Dynamic Tables" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/template/assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/template/assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/template/assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/template/assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/template/assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/template/assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/template/assets/css/ace-rtl.min.css" />
	<style>
		.sidebar+.main-content{margin-left: 0px !important}
		/* .dataTables_wrapper .dt-buttons {
		  float:none;  
		  text-align:center;
		} */
	</style>
		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/template/assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/ace-extra.min.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/html5shiv.min.js"></script>
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="no-skin">
		
		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar                  responsive                    ace-save-state"></div>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="breadcrumbs ace-save-state" id="breadcrumbs">

						<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div><!-- /.nav-search -->
					</div>

					<div class="page-content">
						
						<div class="page-header">
							<h1>
								Kết quả điểm danh: <?php echo $from ;?> =&gt; <?php echo $to;?>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								

								

								<div class="row">
									<div class="col-xs-12">
										
										

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
									<?php
									//Hien thi chi tiet danh sach theo dong
									if ($content=='content1')
										{
											?>
										<div class="table-header">
											<form method="post" action="<?php echo base_url() ?>attendance/resultAttendance">
											Ket qua diem danh: Từ
											<input type="text" name="from">
											Đến <input type="text" name="to">
											<input type="submit" value="Ketqua">
										</form>

										</div>
										<div>
										<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>MSSV</th>
														<th>Họ và lót</th>
														<th>Tên</th>
														<th >Lớp</th>

														<th>
															<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
															Thời gian 
														</th>
														<th class="hidden-480">Người điểm danh</th>

													</tr>
												</thead>

												<tbody>

												<?php

												foreach ($data as $key => $value) 
												{
												?>
													<tr>
														<td>
															<a href="<?php echo base_url();?>attendance/user/<?php echo $value->studentid ?>"><?php echo $value->studentid ?></a>
														</td>
														
														<td class="hidden-480">
															<?php echo $value->last_name ?>
														</td>
														<td>
															<?php echo $value->first_name ?>

														</td>
														<td><?php echo $value->classid ?></td>
														<td><?php echo $value->time1 ?></td>

														<td class="hidden-480">
															<?php echo $value->info_staff ?>
														</td>

													</tr>
											<?php
										}
											?>

												</tbody>
											</table>
										
										</div>
										<?php
									}
									else 
									{//Hien thi danh sach theo cot
										?>

										<div class="table-header">

											<form method="post" action="<?php echo base_url() ?>attendance/resultAttendance2">

											
											    <table style="width: 100%">
											    	<tr>
											    		<td>Từ</td>
											    		<td>Đến</td>
											    		<td>Khoa</td>
											    		<td>-</td>
											    	</tr>
											    	<tr>
											    		<td><input type="text" class="form-control" placeholder="yyyy-mm-dd hh:mm" name=from></td>
											    		<td><input type="text" class="form-control" placeholder="yyyy-mm-dd hh:mm" name=to></td>
											    		<td><select name="facultyid">
													<option value="">---</option>
													<?php 
													foreach ($faculty as $key => $value) {
														echo "<option value='{$value->facultyid}'>{$value->facultyname}</option>";
													}
													?>
												</select></td>
											    		<td><input type="submit" value="Ketqua"></td>
											    	</tr>
											    </table>
											
											</form>

										</div>
										<div>
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr><?php
														foreach ($dataTH as $k => $v) {
														 echo "<th>". $v ."</th>";
														}
														 ?>
													</tr>
												</thead>
												<tbody>
												<?php
												foreach ($data as $key => $value) 
												{
												echo '<tr>';
														foreach ($dataTH as $k => $v) 
														{
															if ($k=='studentid')
															{?>
																<a href="<?php echo base_url();?>attendance/user/<?php echo $value[$k] ?>">
																	<td><?php echo $value[$k] ?></td>
																	<?php
															}
															else
														 	echo '<td>'.$value[$k] .'</td>';
														}
													
												echo '</tr>';
												
												}
											?>
										</tbody>
										</table>
										
										</div>
										<?php
									}
									?>
									</div>
								</div>

								

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Ace</span>
							Application &copy; 2013-2014
						</span>

						&nbsp; &nbsp;
						<span class="action-buttons">
							<a href="<?php echo base_url();?>bootstrap/template/#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="<?php echo base_url();?>bootstrap/template/#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="<?php echo base_url();?>bootstrap/template/#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
						</span>
					</div>
				</div>
			</div>

			<a href="<?php echo base_url();?>bootstrap/template/#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/jquery.dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/buttons.flash.min.js"></script>
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/buttons.html5.min.js"></script>
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/buttons.colVis.min.js"></script>
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/dataTables.select.min.js"></script>

		<!-- ace scripts -->
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/ace-elements.min.js"></script>
		<script src="<?php echo base_url();?>bootstrap/template/assets/js/ace.min.js"></script>


<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">

<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>


		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				//initiate dataTables plugin
				var myTable = 
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
				//	 dom: 'Bfrtip',
				dom:'Blfrtip',
			        buttons: [
			             'excel', 'pdf', 'print'
			        ],

					"aaSorting": [],
					
		
					//"bProcessing": true,
			        //"bServerSide": true,
			        //"sAjaxSource": "http://127.0.0.1/table.php"	,
			
					//,
					//"sScrollY": "200px",
					//"bPaginate": false,
			
					//"sScrollX": "100%",
					//"sScrollXInner": "120%",
					//"bScrollCollapse": true,
					//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
					//you may want to wrap the table inside a "div.dataTables_borderWrap" element
			
					"iDisplayLength": 50,
			
			
					select: {
						style: 'multi'
					}
			    } );
			
				
				
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
				
				//style the message box
				var defaultCopyAction = myTable.button(1).action();
				myTable.button(1).action(function (e, dt, button, config) {
					defaultCopyAction(e, dt, button, config);
					$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
				});
				
				
				var defaultColvisAction = myTable.button(0).action();
				myTable.button(0).action(function (e, dt, button, config) {
					
					defaultColvisAction(e, dt, button, config);
					
					
					if($('.dt-button-collection > .dropdown-menu').length == 0) {
						$('.dt-button-collection')
						.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
						.find('a').attr('href', '#').wrap("<li />")
					}
					$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
				});
			
				////
			
				setTimeout(function() {
					$($('.tableTools-container')).find('a.dt-button').each(function() {
						var div = $(this).find(' > div').first();
						if(div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
						else $(this).tooltip({container: 'body', title: $(this).text()});
					});
				}, 500);
				
				
				
				
				
				myTable.on( 'select', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
					}
				} );
				myTable.on( 'deselect', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
					}
				} );
			
			
			
			
				/////////////////////////////////
				//table checkboxes
				$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
				
				//select/deselect all rows according to table header checkbox
				$('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$('#dynamic-table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) myTable.row(row).select();
						else  myTable.row(row).deselect();
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
					var row = $(this).closest('tr').get(0);
					if(this.checked) myTable.row(row).deselect();
					else myTable.row(row).select();
				});
			
			
			
				$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
					e.stopImmediatePropagation();
					e.stopPropagation();
					e.preventDefault();
				});
				
				
				
				//And for the first simple table, which doesn't have TableTools or dataTables
				//select/deselect all rows according to table header checkbox
				var active_class = 'active';
				$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$(this).closest('table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
						else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
					var $row = $(this).closest('tr');
					if($row.is('.detail-row ')) return;
					if(this.checked) $row.addClass(active_class);
					else $row.removeClass(active_class);
				});
			
				
			
				/********************************/
				//add tooltip for small view action buttons in dropdown menu
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				
				//tooltip placement on right or left
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
				
				
				
				
				/***************/
				$('.show-details-btn').on('click', function(e) {
					e.preventDefault();
					$(this).closest('tr').next().toggleClass('open');
					$(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
				});
				/***************/
				
				
				
				
				
				/**
				//add horizontal scrollbars to a simple table
				$('#simple-table').css({'width':'2000px', 'max-width': 'none'}).wrap('<div style="width: 1000px;" />').parent().ace_scroll(
				  {
					horizontal: true,
					styleClass: 'scroll-top scroll-dark scroll-visible',//show the scrollbars on top(default is bottom)
					size: 2000,
					mouseWheelLock: true
				  }
				).css('padding-top', '12px');
				*/
			
			
			})
		</script>
	</body>
</html>
