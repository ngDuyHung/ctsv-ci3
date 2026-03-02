<link href="<?php echo base_url('css/jquery.dataTables.min.css');?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
function choose_class(id) {
	window.location.href=base_url+"user/lstclass/"+id;;
}
function choose_group(id) {
	window.location.href=base_url+"user/group/"+id;;
}
 </script>
<div class="container">

  <h3><?php echo $title;?> &nbsp; &nbsp;&nbsp;<a href='/user/new_user' class='btn btn-default '>Thêm mới</a></h3>
  <div class="row">
  <!--  <div class="col-md-6">
      <form method="post" action="<?php echo site_url('user/index/');?>">
        <div class="input-group">
          <input type="text" class="form-control" name="search" placeholder="<?php echo $this->lang->line('search');?>...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><?php echo $this->lang->line('search');?></button>
          </span>
        </div>
	    </form>
    </div>
    -->
    <div class="col-md-3">
     
				<select   name="faculty" class="form-control" onChange="select_faculty(this.value);">
					<option value="0">Tất cả các khoa</option>
					<?php 
					foreach($faculty_list as  $val){
						?>
						
						<option value="<?php echo $val['facultyid'];?>"  ><?php echo $val['facultyname'];?></option>
						<?php 
					}
					?>
        </select>
    
    </div>
    <div class="col-md-3">
     
			
				<select  id="class_slt"  name="classid" class="form-control" onChange="choose_class(this.value);">
				<option value="-">Chọn lớp</option>
					<?php 	
					$class_lst = '<option value="-">Chọn lớp</option>';				
					foreach($class_list as $val){
						$class_lst .='<option value="'.$val['classid'].'" faculty="'.$val['facultyid'].'">'.$val['classid'].'</option>';
						?>
						
						<option value="<?php echo $val['classid'];?>" faculty="<?php echo $val['facultyid'];?>" <?php if(isset($classid) && $classid==$val['classid']) echo "selected"; ?>><?php echo $val['classid'];?></option>
						<?php 
					}

					?>
          </select>
     
    </div>
    <div class="col-md-3">      
     
        <select   name="group" class="form-control" onChange="choose_group(this.value);">
					<option value="-">Chọn nhóm</option>
          <option value="1" <?php if($groupid=="1") echo "selected"; ?>>Quản trị</option>
          <option value="-1" <?php if($groupid=="-1") echo "selected"; ?>>Nhóm hỗ trợ</option>
          <option value="0" <?php if($groupid=="0") echo "selected"; ?>>Sinh viên</option>
        </select>
		
    
       
    </div>
    
  </div><!-- /.row -->
  <div class="row">
    <div class="col-md-12">
      <br> 
      <?php 
      if($this->session->flashdata('message')){
        echo $this->session->flashdata('message');	
      }
      ?>	
      <table  class="cell-border" style="width:100%" id="user_list">
      <thead>
        <tr>
          <th>#</th>
          <th><?php echo $this->lang->line('studentid');?></th>
          <th><?php echo 'Họ và tên';?></th>
         
          <th><?php echo $this->lang->line('birthdate');?> </th>
          <th><?php echo $this->lang->line('classid');?> </th>
          <th><?php echo $this->lang->line('faculty_name');?> </th>
          <th><?php echo $this->lang->line('type');?> </th>
          <th><?php echo $this->lang->line('academic_year');?> </th>
          <th><?php echo $this->lang->line('group');?> </th>
          <th><?php echo $this->lang->line('action');?> </th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if(count($result)==0){
          ?>
        <tr>
          <td colspan="10"><?php echo $this->lang->line('no_record_found');?></td>
        </tr>	
        <?php
        }
        foreach($result as $key => $val){
        ?>
        <tr>
          <td><?php echo $val['uid'];?></td>
          <td><?php echo $val['studentid'];?></td>
          <td><?php echo $val['last_name'],' ',$val['first_name'];?></td>
          
          <td><?php 
          if($val['birthdate']!="")
            echo date("d/m/Y", strtotime($val['birthdate']));
          
            ?></td>
          <td><?php echo $val['classid'];?></td>
          <td><?php echo $val['facultyname'];?></td>
          <td><?php echo $val['type'];?></td>
          <td><?php echo $val['academic_year'];?></td>
          <td><?php echo $val['group_name'];?></td>
          <td>
          
          
          <a href="<?php echo site_url('user/edit_user/'.$val['uid']);?>"><img src="<?php echo base_url('images/edit.png');?>"></a>
          <?php
          if($this->session->userdata('logged_in')['uid']!=$val['uid'] )
          {
          ?>
          <a href="javascript:remove_entry('user/remove_user/<?php echo $val['uid'];?>');"><img src="<?php echo base_url('images/cross.png');?>"></a>
          <?php

            if($val['user_status']=='Active')
            {
          ?>
          <a href="javascript:lock_entry('user/lock_user/<?php echo $val['uid'];?>');">
            <span class="glyphicon glyphicon-lock"></span>
          </a>
          
            <?php 
            }
            else
            { ?>
              <a href="javascript:unlock_entry('user/unlock_user/<?php echo $val['uid'];?>');">
              <i style="font-size:24px" class="fa">&#xf13e;</i>
              </a>
          <?php  
            }
          } ?>
          </td>
        </tr>
        <?php 
        }
      ?>
      </tbody>
  </table>
</div>

</div>




<br><br><br><br>
<div class="login-panel panel panel-default">
	<div class="panel-heading">
    <h4><?php echo $this->lang->line('import_user');?></h4> 
  </div>
  <div class="panel-body"> 
  <form action='<?php echo site_url('user/import/');?>' method="post" enctype='multipart/form-data'>
  
    <?php echo $this->lang->line('upload_excel');?>
    <input type="hidden" name="size" value="3500000">
    <input type="file" name="xlsfile" style="width:150px;float:left;margin-left:10px;">
    <div style="clear:both;"></div>
    <input type="submit" value="Nạp" style="margin-top:5px;" class="btn btn-default">
    <a href="<?php echo base_url();?>sample/user.xlsx" target="new">Click đây</a> <?php echo $this->lang->line('upload_excel_info');?> 
    
    </form>
  </div>
</div>
<script src="<?php echo base_url();?>js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>js/buttons.flash.min.js"></script>
<script src="<?php echo base_url();?>js/jszip.min.js"></script>
<script src="<?php echo base_url();?>js/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>js/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>js/buttons.print.min.js"></script>
<script>
					
  $(document).ready(function() {
	var myTable = $("#user_list").DataTable({
			dom:'Blfrtip',
			  buttons: [
					{
           extend: 'excel',
           footer: true,
					 exportOptions: {
                columns: [1,2,3,4,5,6]
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

class_lst='<?php echo $class_lst;?>';
</script>