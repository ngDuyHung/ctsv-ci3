<?php 
if ($su==0)
{
?>
<table class="table">
	<tr>
		<td>stt</td>
		<td>Tên module</td>
		
		<td>Ngày</td>
		<td></td>
		<td>Kết quả điểm danh</td>
	</tr>
	<?php 
	foreach ($course as $key => $value) 
	{
		?>
	<tr>
		<td></td>
		<td>
			<?php echo $value->name ?>
		</td>
		<td>
			
		
		
		</td>
		<td><?php echo $value->description ?></td>
		<td></td>
		<td>
			<a href="#" onclick="fketQua('<?php echo $value->id ?>'); return false;">Xem</a>
		</td>
	</tr>
	<?php 
	}
	?>
</table>
<?php 
}

if ($su==1)
{
?>

<table class="table" id='tb1'>
	<tr><td colspan="5" align="center"><a href="#" onclick="fCourseNew()" class="btn btn-info">Thêm mới </a></td></tr>
	<tr><td colspan="5" align="center"><a href="#" onclick="fCourseNew()" class="btn btn-info">Học kỳ :[<?php  echo $this->session->userdata('semester')['id'] ?> ]</a></td></tr>
	<tr>
		<td>stt</td>
		<td>Tên module</td>

		<td>Mô tả</td>
		<td>Thứ tự hiển thị</td>
		<td>Chỉnh sửa</td>
	</tr>
	<?php 
	foreach ($course as $key => $value) 
	{
		?>
	<tr id='id_<?php echo $value->id ?>'>
		<td>
			<?php echo $key++; ?>
		</td>
		<td>
			<?php echo $value->name ?>
		</td>
		<td><?php echo $value->description ?></td>
		<td><?php echo $value->ordnumber ?></td>
		<td>
			<a href="#" onclick="fCourse('<?php echo $value->id ?>'); return false;">Sửa</a>
		</td>
		<td>
			<a href="#" onclick="fCourseDelete('<?php echo $value->id ?>'); return false;">Xóa</a>
		</td>
	</tr>
	<?php 
	}
	?>
</table>
<?php 
}



?>
<script>
	var base_url ="<?php echo base_url(); ?>";
	function fketQua(cid)
	{
		
		$('#modal-id').modal('show');
		$('table#tb1 tbody').html('');
		$.ajax({
			url:'course/attendance',
			type:'POST',
			data:'cid='+cid,
			success:function(s)
			{
				//alert(s);
				s = JSON.parse(s);
				console.log(s);
				$.each(s, function(k,v){
					console.log(v);
					row ='<tr><td>' + (k+1)+'</td><td>'+ v.studentid+'</td><td>'+ v.name+'</td><td>'+ v.time1+'</td></tr>';
					$('table#tb1 tbody').append(row);
				});
			}

		});
	}

	function fCourse(id)
	{
		
		$('#modal-id2').modal('show');
		$.ajax({
			url: base_url+'course/detail',
			type:'POST',
			data:'id='+id,
			success:function(s)
			{
				console.log(s);
				s = JSON.parse(s);
				s0= s[0];
				$('#modal-id2 form input[name=id]').val(id);
				$('#modal-id2 form #name').val(s0.name);
				$('#modal-id2 form #description').val(s0.description);
				$('#modal-id2 form #ordnumber').val(s0.ordnumber);

			}

		});
	}

	function fCourseDelete(id)
	{
		if (confirm("Ban chac chan muon xoa?"))
		{
			$.ajax({
			url: base_url+'course/delete',
			type:'POST',
			data:'id='+id,
			success:function(s)
			{ //alert(s+ ' :'+ s.length);
				if (s=='2')
					alert('Da co sinh vien diem danh - khong the xoa');
				if (s=='1')
				{
					alert('Da xoa');
					$('#table#tb1 #id_'+id).hide();
				}
				if (s=='0')
					alert('Lỗi gì đó ');
				location.reload();
			}

		});
		}

	}
	function fCourseNew()
	{
		
		$('#modal-id3').modal('show');
		
	}

	function fCourseSave()
	{
		
		frm = $('#modal-id3 form').serializeArray();
		$.ajax({
			url: base_url+'course/save',
			type:'POST',
			data:frm,
			success:function(s)
			{
				
				alert(s);
				console.log(s);
				t ='<tr id=id_'+s+'><td>.</td><td>'+ $('#modal-id3 form #name').val() +'</td>';
				t +='<td>'+ $('#modal-id3 form #description').val() +'</td>';
				t +='<td>'+ $('#modal-id3 form #ordnumber').val() +'</td>';
				//t +='<td>'+ $('#modal-id3 form #description').val() +'</td>';
			//	t+='<td><a href="#" onclick="fCourse(\''+ s+'\'); return false;">Sửa</a></td></tr>';
			t +="<td>Reload để sửa - xóa</td></tr>";
				$("#tb1").append(t);
			}

		});
	}

	function fCourseUpdate()
	{
		frm = $('#modal-id2 form').serializeArray();
		$.ajax({
			url: base_url+'course/update',
			type:'POST',
			data:frm,
			success:function(s)
			{
				
				$('#modal-id2').fadeOut(2000,function() 
				{
				  $(this).modal('hide');
				  $('#tb1 tr#id_'+ $('#modal-id2 form input[name=id]').val() +' td:nth-child(2)').html($('#modal-id2 form #name').val());
				  $('#tb1 tr#id_'+ $('#modal-id2 form input[name=id]').val() +' td:nth-child(3)').html($('#modal-id2 form #description').val());
				  $('#tb1 tr#id_'+ $('#modal-id2 form input[name=id]').val() +' td:nth-child(4)').html($('#modal-id2 form #ordnumber').val());
				
				});
			}

		});
	}
</script>

<div class="modal fade" id="modal-id">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Ket qua diem danh</h4>
			</div>
			<div class="modal-body">
				<table id='tb1' class="table table-bordered">
					<thead>
						<tr> <th>STT</th>
							<th>MSSV</th>
							<th>Môn học</th>
							<th>Tgian điểm danh</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" >Save changes</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-id2">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Chinh sua module hoc</h4>
			</div>
			<div class="modal-body">
				
				<form class="form-horizontal" action="/action_page.php">
					<input type="hidden" name="id">
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">Tên môn học:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="name" name="name" placeholder="Ten mon hoc">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="pwd">Mô tả</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="description" name="description" placeholder="Ca học - phòng học - sĩ số- ID nhóm">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="pwd">Thứ tự hiển thị</label>
						<div class="col-sm-10">
							<input type="number" class="form-control" id="ordnumber" name="ordnumber" min="1" >
						</div>
					</div>

					
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="fCourseUpdate()">Save changes</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-id3">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Thêm module môn học</h4>
			</div>
			<div class="modal-body">
				
				<form class="form-horizontal" action="/action_page.php">
					<input type="hidden" name="id">
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">Tên module:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="name" name="name" placeholder="Ten mon hoc">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">Môn học:</label>
						<div class="col-sm-10">
							<select name="sid" class="form-control">
								<option value="">Tên môn học</option>
								<?php 
									foreach ($dataSubject as $key => $value) {
										?>
											<option value="<?php echo $value->sid ?>"><?php echo $value->sname ?></option>
										<?php
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="pwd">Mô tả</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="description" name="description" placeholder="Ca học - phòng học - sĩ số- ID nhóm">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="pwd">Thứ tự hiển thị</label>
						<div class="col-sm-10">
							<input type="number" class="form-control" id="ordnumber" name="ordnumber" min="1" >
						</div>
					</div>

					
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="fCourseSave()">Save changes</button>
			</div>
		</div>
	</div>
</div>