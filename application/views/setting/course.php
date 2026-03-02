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
<h3>Thiết lập điểm danh</h3>
<div class="row">
    <div class="col-md-12">

      <div class="alert alert-info  "  style='height:60px'>
        <div class='col-md-6 text-bold'> Môn - chuyên đề điểm danh  </h4> </div>
        <div class='col-md-6 text-right'> <a href="#" class='hocky btn btn-info '>Thêm mới</a></div>
      </div>
    </div>
    <div class="col-md-12">
      <div id="result_etp" >
              <table class="cell-border table   table-bordered table-striped" style="width:100%" id="user_list">
              <thead>
                <tr>
                
                  <th>stt </th>
                  <th>Id</th>
                  <th>Tên môn -chuyên đề </th>
                  <th>Mô tả</th>
                  <th>Thời gian bắt đầu </th>
                  <th>Thời gian kết thúc </th>
                  <th>Sửa </th>
                  <th>Xóa</th>
                </tr>
              </thead>
              <tbody>
                    <?php 
                    foreach($course  as $k=>$item)
                    {
                        ?>
                        <tr>
                            <td><?php echo $k+1 ?></td>
                            <td><?php echo $item['id'] ?></td>
                            <td><?php echo $item['name'] ?></td>
                            <td><?php echo $item['description'] ?></td>
                            <td><?php echo $item['datefrom'] ?></td>
                            <td><?php echo $item['dateto'] ?></td>
                            <td>
                                <a href="#" data-id='<?php echo $item['id'] ?>' class='edit'>Sửa</a>
                            </td>
                            <td>
                                <a href="#" data-id='<?php echo $item['id'] ?>' class='delete'>Xóa</a>
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

</div>
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="modalInsert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Quản lý môn học - chuyên đề điểm danh</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
            <form action="#">
                <input type="hidden" id='ACTION' name='ACTION' value='INSERT'>
                <input type="hidden" name=id id=id>
                <div class="form-group">
                    <label for="email">Tên chuyên đề:</label>
                    <input type="text" class="form-control" id="name" name=name 
                    placeholder="Nhập tên môn hoặc chuyên đề cho điểm danh" >
                </div>
                <div class="form-group">
                    <label for="pwd">Mô tả:</label>
                    <input type="text" class="form-control" id="description" name='description' 
                    placeholder='Mô tả cho chuyên đề này'>
                </div>
                <div class="form-group">
                    <label for="email">Ngày bắt đầu có thể điểm danh:</label>
                    <input type="date" class="form-control" id="datefrom" name=datefrom 
                    placeholder="Nhập ngày bắt đầu" >
                </div>
                <div class="form-group">
                    <label for="pwd">Kết thúc điểm danh:</label>
                    <input type="date" class="form-control" id="dateto" name='dateto' 
                    placeholder='Nhập ngày kết thúc'>
                </div>
                <!-- <div class="form-group">
                    <label for="email">Học kỳ áp dụng cộng điểm:</label>
                    <input type="text" class="form-control" id="name" name=name 
                    placeholder="Nhập tên chuyên đề" >
                </div> -->
                <div class="form-group">
                    <label for="pwd">Điểm cộng cho mỗi lần điểm danh:</label>
                    <input type="text" class="form-control" id="point" name='point' placeholder='0'>
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



<script>
    $(document).ready(
        function(){
            $('a.hocky').click(function(){
                   $('#modalInsert').modal('show');
                });
            
                $('a.edit').click(function(){
                    $('#modalInsert #ACTION').val('UPDATE');
                   $('#modalInsert').modal('show');
                   $.ajax({
                    url: '/setting/editCource',
                    type:'POST',
                    data:{id: $(this).data('id') },
                    success:function(r)
                    {
                        console.log(r);
                        r = JSON.parse(r);//alert(r.dateto.substr(0, 10));
                        $('#modalInsert form #id').val(r.id);
                        $('#modalInsert form #name').val(r.name);
                        $('#modalInsert form #description').val(r.description);
                        $('#modalInsert form #datefrom').val(r.datefrom.substr(0, 10));
                        $('#modalInsert form #dateto').val(r.dateto.substr(0, 10));
                        //    "id":"42","name":"9","description":"999","datefrom":null,"dateto":"2022-12-20 00:00:00","foretp":"0","semester":null,
                          //  "groupid":null,"point":null,"ordnumber":"0","sid":"0","deleted":"0"}
                    }
                   });

                });
            
               
            //-------------
            $('.saveHocky').click(function(){
                $.ajax({
                    url:'/setting/storeCourse',
                    type:'POST',
                    data:$('#modalInsert form').serializeArray(),
                    success:function(result){
                        console.log(result);
                       location.reload();
                    }
                })
            });

            $('a.delete').click(function(){
                if (confirm('Chắc chắn muốn xóa?'))
                {
                    $.ajax({
                    url:'/setting/deletecourse',
                    type:'POST',
                    data:{id: $(this).data('id')},
                    success:function(result){
                        console.log(result);
                        location.reload();
                    }
                })
                }
            });
            
            
      });

      
</script>