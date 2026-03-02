<style>
    .card {
  /* Add shadows to create the "card" effect */
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
}

/* On mouse-over, add a deeper shadow */
.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}
.alert{ margin-top: 20px;}
</style>
<div class="container" style='margin-top:40px'>
<h3>Thiết lập</h3>
<div class="row">
    <div class="col-md-12">

      <div class="alert alert-info  " data-toggle="collapse" data-target="#result_etp" style='height:60px'>
        <div class='col-md-6 text-bold'> Học kỳ </h4> </div>
        <div class='col-md-6 text-right'> <a href="#" class='hocky btn btn-info '>Thêm mới</a></div>
      </div>
    </div>
    <div class="col-md-12">
      <div id="result_etp" class="collapse ">
              <table class="cell-border table   table-bordered table-striped" style="width:100%" id="user_list">
              <thead>
                <tr>
                
                  <th>stt </th>
                  <th>Id</th>
                  <th>Tên học kỳ </th>
                  <th>Active </th>
                  <!-- <th>Sửa </th> -->
                  <th>Xóa</th>
                </tr>
              </thead>
              <tbody>
                    <?php 
                    foreach($semester  as $k=>$item)
                    {
                        ?>
                        <tr>
                            <td><?php echo $k+1 ?></td>
                            <td><?php echo $item['id'] ?></td>
                            <td><?php echo $item['name'] ?></td>
                            <td><?php echo $item['active'] ?></td>
                            <!-- <td>
                                <a href="#" data-id='<?php echo $item['id'] ?>' class='editsemeter'>Sửa</a>
                            </td> -->
                            <td>
                                <a href="#" data-id='<?php echo $item['id'] ?>' class='deleteHocky'>Xóa</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
              </tbody>
          </table>
    </div>  
</div>
</div>

     <!-- end etp -->
     
 <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info  " data-toggle="collapse" data-target="#result_socialwork"  style='height:60px'>
                        <div class='col-md-6 text-bold'> Thông tin ngày công tác xã hội </h4> </div>
                        <div class='col-md-6 text-right'> <a href="#" class='themmoiCongtacxahoi btn btn-info '>Thêm mới</a></div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="result_socialwork" class="collapse">
                <table class="cell-border table table-bordered " style="width:100%" id="user_list">
                <thead>
                    <tr> 
                        <th>STT</th>
                    <th>Năm Học</th>
                    <th width="60%">Tiêu đề</th>
                    <th>Sửa</th>
                    <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                                foreach($socialwork  as $k=>$item)
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $k+1 ?></td>
                                        <td><?php echo $item['schoolyear'] ?></td>
                                        <td><?php echo $item['title'] ?></td>
                                    
                                        <td>
                                            <a href="#" data-id='<?php echo $item['id'] ?>' class='editCTXH_KTKL'>Sửa</a>
                                        </td>
                                        <td>
                                            <a href="#" data-id='<?php echo $item['id'] ?>' class='deleteCTXH_KTKL'>Xóa</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                </tbody>
                </table>
            </div>  
            </div>
     </div>
       <!-- end socialwork -->
   
    
      <div class="row">
      <div class="col-md-12">
        <div class="alert alert-info  " data-toggle="collapse" data-target="#result_commendation" style='height:60px'>
            <div class='col-md-6 text-bold'> Khen thưởng kỷ luật </h4> </div>
            <div class='col-md-6 text-right'> <a href="#" class='themmoiKhenthuongkyluat btn btn-info '>Thêm mới</a></div>
        </div>
      </div>
      <div class="col-md-12">
            <div id="result_commendation" class="collapse">
            <table class="cell-border table" style="width:100%" id="user_list">
            <thead>
                <tr>
                    <th>STT</th>
                <th>Năm học</th>
                
                <th width="60%">Tiêu đề </th>
                <th>Sửa</th>
                <th >Xóa</th>
               
                
                
                </tr>
            </thead>
            <tbody>
            <?php 
                                foreach($commendation  as $k=>$item)
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $k+1 ?></td>
                                        <td><?php echo $item['schoolyear'] ?></td>
                                        <td><?php echo $item['title'] ?></td>
                                         <td>
                                            <a href="#" data-id='<?php echo $item['id'] ?>' class='editCTXH_KTKL'>Sửa</a>
                                        </td>
                                       <td>
                                            <a href="#" data-id='<?php echo $item['id'] ?>' class='deleteCTXH_KTKL'>Xóa</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>            
            </tbody>
        </table>         
    </div>  
      </div>
</div>
    <!-- recommendation -->
    
<div class="row">
      <div class="col-md-12">
        <div class="alert alert-info  " data-toggle="collapse" data-target="#result_khaibaonoingoaitru" style='height:60px'>
            <div class='col-md-6 text-bold'> Khai báo nội trú - ngoại trú</h4> </div>
            <div class='col-md-6 text-right'> <a href="#" class='themmoiNoiNgoaiTru btn btn-info '>Thêm mới</a></div>
        </div>
      </div>
      <div class="col-md-12">
            <div id="result_khaibaonoingoaitru" class="collapse">
            <table class="cell-border table table-bordered "  id="khaibaonoingoaitru_list">
            <thead>
                <tr>
                <th>Stt</th>
                <th>Năm học</th>
                <th>Tiêu đề</th>
                <th >Ngày bắt đầu</th>
                <th >Ngày kết thúc</th>
                <th>Trạng thái</th>
                <th colspan=2>#</th>
             </tr>
            </thead>
            <tbody>
            <?php 
                                foreach($inoutpatient  as $k=>$item)
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $k+1 ?></td>
                                        <td><?php echo $item['schoolyear'] ?></td>
                                        <td><?php echo $item['title'] ?></td>
                                        <td><?php echo $item['date1'] ?></td>
                                        <td><?php echo $item['date2'] ?></td>
                                        <td><?php if ($item['active']=='1') echo 'Hiện thông báo'; else echo 'Tắt thông báo'; ?></td>
                                        <td>
                                            <a href="#" data-id='<?php echo $item['id'] ?>' class='deleteNoiNgoaiTru'>Xóa</a>
                                        </td>
                                        <td>
                                            <a href="#" data-id='<?php echo $item['id'] ?>' class='editNoiNgoaiTru'>Sửa</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>            
            </tbody>
        </table>         
    </div>  
      </div>
</div> 

    <!-- Khai báo nội ngoại trú -->
 
</div>
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="modelHocky" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Quản lý học kỳ - Năm Học</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
            <form action="#">
                <div class="form-group">
                    <label for="email">Mã học kỳ:</label>
                    <input type="text" class="form-control" id="idsemester" name=id placeholder="Format dang: Nam hoc - Hoc ky. Vi du:2022-2023-1" >
                </div>
                <div class="form-group">
                    <label for="pwd">Tên học kỳ:</label>
                    <input type="text" class="form-control" id="namesemester" name='name' placeholder='Format dang: Nam hoc - Hoc ky. Vi du:2022-2023-HK1'>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name='active' id='activememester' value='1'> Active</label>
                </div>
               
        </form>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
    <button type="button" class="btn btn-Close saveHocky">Lưu</button>
    </div>
    </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelCTXH_KTKL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Modal CTXH-KTKL</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
    <form action="#"><input type="hidden" id=type1 name='type1' value='INSERT'>
    <input type="hidden" name="id" id="id">
                                <input type="hidden" id='typeCTXH_KTKT' name='type'>
                <div class="form-group">
                    <label for="email">Năm học:</label>
                    <input type="text" class="form-control" id="schoolyear" name='schoolyear' placeholder="Format dang: Nam hoc. Vi du:2022-2023" >
                </div>
                <div class="form-group">
                    <label for="pwd">Tiêu đề:</label>
                    <input type="text" class="form-control" id="title" name='title' placeholder='Tiêu đề của tin'>
                </div>
              
               
        </form>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
    <button type="button" class="btn btn-Close store">Lưu</button>
    </div>
    </div>
    </div>
</div>


<!-- Khai bao noi ngoai tru -->

<!-- Modal -->
<div class="modal fade" id="modelNoiNgoaiTru" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Khai báo nội trú -  ngoại trú</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
            <form action="#">
                <input type="hidden" name='type' id='type' value='INSERT'>
                <input type="hidden" id="id" name='id'>
                <div class="form-group">
                    <label for="pwd">Năm học:</label>
                    <input type="text"  class="form-control" id="schoolyear" name='schoolyear' placeholder='Format dang: Nam hoc - Hoc ky. Vi du:2022-2023'>
                </div>
                <div class="form-group">
                    <label for="pwd">Ngày bắt đầu:</label>
                    <input type="date" class="form-control" id="date1" name='date1' placeholder='Ngày bắt đầu'>
                </div>
                <div class="form-group">
                    <label for="pwd">Ngày kết thúc:</label>
                    <input type="date" class="form-control" id="date2" name='date2' placeholder='Ngày kết thúc'>
                </div>
                <div class="form-group">
                    <label for="pwd">Tiêu đề:</label>
                    <input type="text" class="form-control" id="title" name='title' placeholder='Tiêu đề thông báo'>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name='active' id='active' value='1' checked> Active</label>
                </div>
               
        </form>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
    <button type="button" class="btn btn-Close saveNoiNgoaiTru">Lưu</button>
    </div>
    </div>
    </div>
</div>

<div class="modal fade" id="modelCTXH" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Khai báo nội trú -  ngoại trú</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
            <form action="#">
                <input type="hidden" name='type' id='type' value='INSERT'>
                <input type="hidden" id="id" name='id'>
                <div class="form-group">
                    <label for="pwd">Năm học:</label>
                    <input type="text"  class="form-control" id="schoolyear" name='schoolyear' placeholder='Format dang: Nam hoc - Hoc ky. Vi du:2022-2023'>
                </div>
                <div class="form-group">
                    <label for="pwd">Ngày bắt đầu:</label>
                    <input type="date" class="form-control" id="date1" name='date1' placeholder='Ngày bắt đầu'>
                </div>
                <div class="form-group">
                    <label for="pwd">Ngày kết thúc:</label>
                    <input type="date" class="form-control" id="date2" name='date2' placeholder='Ngày kết thúc'>
                </div>
                <div class="form-group">
                    <label for="pwd">Tiêu đề:</label>
                    <input type="text" class="form-control" id="title" name='title' placeholder='Tiêu đề thông báo'>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name='active' id='active' value='1' checked> Active</label>
                </div>
               
        </form>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
    <button type="button" class="btn btn-Close saveNoiNgoaiTru">Lưu</button>
    </div>
    </div>
    </div>
</div>


<script>
    $(document).ready(
        function(){
            $('a.hocky').click(function(){
                   $('#modelHocky').modal('show');
                });
            
                $('a.themmoiKhenthuongkyluat').click(function(){
                   $('#modelCTXH_KTKL').modal('show');
                   $('#modelCTXH_KTKL #typeCTXH_KTKT').val('KTKL');
                   $('#modelCTXH_KTKL .modal-title').html('Tin Khen Thưởng kỷ Luật');
                   
                });
                
                $('a.themmoiCongtacxahoi').click(function(){
                   $('#modelCTXH_KTKL').modal('show');
                   $('#modelCTXH_KTKL #typeCTXH_KTKT').val('CTXH');
                   $('#modelCTXH_KTKL .modal-title').html('Tin Ngày Công Tác Xã Hội');
                });
                
                $('a.themmoiNoiNgoaiTru').click(function(){
                   $('#modelNoiNgoaiTru').modal('show');
                   $('#modelNoiNgoaiTru #type').val('INSERT');
                  
                });
                
            //-------------
            $('.saveNoiNgoaiTru').click(function(){
                $.ajax({
                    url:'/setting/store_inoutpatient',
                    type:'POST',
                    data:$('#modelNoiNgoaiTru form').serializeArray(),
                    success:function(result){
                        console.log(result);
                       location.reload();
                    }
                })
            });

            $('a.deleteNoiNgoaiTru').click(function(){
                if (confirm('Chắc chắn muốn xóa?'))
                {
                    $.ajax({
                    url:'/setting/delete_inoutpatient',
                    type:'POST',
                    data:{id: $(this).data('id')},
                    success:function(result){
                        console.log(result);
                        location.reload();
                    }
                })
                }
            });
            
            $('a.editNoiNgoaiTru').click(function(){


                $.ajax({
                    url:'/setting/edit_inoutpatient',
                    type:'POST',
                    dataType:'json',
                    data:{id: $(this).data('id')},
                    success:function(result){
                        console.log(result);
                        data:$('#modelNoiNgoaiTru').modal('show');
                        $('#modelNoiNgoaiTru #type').val('EDIT');
                        $('#modelNoiNgoaiTru #id').val(result.id);
                        $('#modelNoiNgoaiTru #schoolyear').val(result.schoolyear);
                        $('#modelNoiNgoaiTru #date1').val(result.date1);
                        $('#modelNoiNgoaiTru #date2').val(result.date2);
                        $('#modelNoiNgoaiTru #title').val(result.title);
                        $('#modelNoiNgoaiTru #active').prop('checked', result.active=='1');
                       
                     }
                })
            });
            //-------------
            $('.saveHocky').click(function(){
                $.ajax({
                    url:'/setting/store_semester',
                    type:'POST',
                    data:$('#modelHocky form').serializeArray(),
                    success:function(result){
                        console.log(result);
                        location.reload();
                    }
                })
            });

            $('a.deleteHocky').click(function(){
                if (confirm('Chắc chắn muốn xóa?'))
                {
                    $.ajax({
                    url:'/setting/delete_semester',
                    type:'POST',
                    data:{id: $(this).data('id')},
                    success:function(result){
                        console.log(result);
                        location.reload();
                    }
                })
                }
            });

            //them tin cong tac xa hoi, khen thuong ky luat
            $('#modelCTXH_KTKL button.store').click(function(){
              //  alert('save');
                $.ajax({
                    url:'/setting/store_congtacxh_khenthuongkl',
                    type:'POST',
                    data:$('#modelCTXH_KTKL form').serializeArray(),
                    success:function(result){
                        console.log(result);
                       location.reload();
                    }
                })
            });

            $('a.deleteCTXH_KTKL').click( function(){
                if (confirm('Chắc chắn muốn xóa?'))
                {
                    $.ajax({
                    url:'/setting/delete_congtacxh_khenthuongkl',
                    type:'POST',
                    data:{id: $(this).data('id')},
                    success:function(result){
                        console.log(result);
                        location.reload();
                    }
                })
                }
            });

            //edit ngay ctxh
             $('a.editCTXH_KTKL').click( function(){
               
                $.ajax({
                    url:'/setting/edit_congtacxh_khenthuongkl',
                    type:'POST',
                    dataType:'json',
                    data:{id: $(this).data('id')},
                    success:function(result){
                        console.log(result);
                        data:$('#modelCTXH_KTKL').modal('show');
                        $('#modelCTXH_KTKL #type1').val('EDIT');
                        $('#modelCTXH_KTKL #id').val(result.id);
                        $('#modelCTXH_KTKL #schoolyear').val(result.schoolyear);
                        $('#modelCTXH_KTKL #title').val(result.title);
                        
                       
                       
                     }
                })
            });
      });

      
</script>