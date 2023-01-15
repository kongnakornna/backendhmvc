  <!-- Main content -->
  <div class="content-wrapper widthscreen mx-auto"> 
    <!-- Content area -->
    <div class="content">
      <div class="row">
        <div class="col-md-12 pb_me-3">
          <div class="card h-100">
            <div class="card-body pb-0 mb-0"> <img src="<?php echo base_url(); ?>assets/connexted/assets/images/main/logo_schoolpartner.png" class="img-fluid"> </div>
          </div>
        </div>
      </div>
      <form method="GET" action="">
        <div class="row">
          <div class="col-md-4 pb-2">
            <select id="user_type" name="user_type" class="form-control form_card_front border_front_grey">
                  <option value=""> เลือกตำแหน่ง </option>
                  <?php if(isset($list_user_type)) { foreach ($list_user_type as $user_type){ ?>
                      <option value="<?php echo $user_type->user_type_id; ?>" <?php echo $this->input->get('user_type') == $user_type->user_type_id ? "selected" : ""; ?>><?php echo $user_type->user_type_title; ?></option>
                  <?php } } ?>
            </select>
          </div>
          <div class="col-md-4 pb-2">
            <?php if(isset($user_status)){ ?>
                    <select id="user_status" name="user_status" class="form-control form_card select2-multiple-search province">
                          <option value="">-- All --</option>
                          <?php foreach ($user_status as $key => $val): ?>
                              <option value="<?php echo $key; ?>" <?php echo $this->input->get('user_status') == $key ? "selected" : ""; ?>><?php echo $val; ?></option>
                          <?php endforeach; ?>
                    </select>
            <?php } ?>
          </div>
          <div class="col-md-4 pb-2">
            <div class="input-group form_card">
              <input type="text" class="form-control form_card" placeholder="ค้นหาจากคำ" name="q" value="<?php echo $this->input->get('q'); ?>">
              <div class="input-group-append">
                <button class="btn btn_blue btn-block rounded-right" type="submit">ค้นหา</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <!-- Content 02 -->
      <div class="row">
          <div class="col-6"> ผลการค้นหา </div>
          <div class="col-6 "> <a class="col-2 btn btn_green btn-block float-right" href="<?php echo base_url().'user/profile#!/create/';?>">ADD</a> </div>
        <div class="col-md-12 pt-2"> 
          <hr class="mt-0 mb-2">
        </div>
        <div class="col-md-12 pb_me-3">
          <div class="card h-100 mb-0">
            <div class="table-responsive table_convert rounded-bottom">
              <table class="table table_reportcard table-striped table-light text-center table-hover border-gray rounded-top">
                <thead class="thead-ictteam rounded-top">
                  <tr class="bg_table_blue rounded-top">
                    <th class="text-center">IDX.</th>
                    <!-- <th class="text-center">Image</th> -->
                    <th class="text-center ">User ID</th>
                    <th class="text-center m_width_icttalent">Name</th>
                    <th class="text-center ">Username</th>
                    <th class="text-center ">User Type</th>
                    <th class="text-center ">Last Login</th>
                    <th class="text-center m_width_160p">Actions</th>
                  </tr>
                </thead>
                <tbody>
                    <?php if(empty($lists)): ?>
                    <tr>
                        <td class="txt_red text-left text-md-center" colspan="6">ไม่มีข้อมูล</td>
                    </tr>
                    <?php endif; ?>
                    <?php 
                    $font_color = "";
                    // $no = 1;
                    foreach($lists as $row):
                      $font_color = "";
                      if(!$row->user_status){
                          $font_color = "text-danger";
                      }
                    ?>
                    <tr class="<?php echo $font_color; ?>">
                        <td class=""><?php echo $row->user_idx; ?></td>
                        <td class=""><?php echo $row->user_id; ?></td>
                        <td class="text-left">
                          <a href="<?php echo base_url().'user/profile#!/edit/'.$row->user_id;?>">
                            <img src="<?php echo $row->profile_image; ?>" class="rounded-circle align-middle img-fluid" width="50" alt=""> <?php echo $row->fullname;?>
                          </a>
                        </td>
                        <td class="text-left"><?php echo $row->username;?></td>
                        <td class="text-left"><?php echo $row->user_type ? $row->user_type : '-'; ?></td>
                        <td class="text-left"><?php echo $row->lastlogin > '0000-00-00 00:00:00' ? date('d/m/Y H:i', strtotime($row->lastlogin)).'น.' : '-'; ?></td>
                        <td class="text-left">
                          <!-- <a onclick="return confirm('ต้องการ Reset Password เป็น Cned#<?php echo $row->user_idx;?> หรือไม่')" href="<?php echo base_url().'user/management/resetPass/'.$row->user_idx.'?url_redirect='.base_url('user/management/lists?'.$_SERVER['QUERY_STRING']); ?>"> Reset Password </a> -->
                          <a onclick="return alert('Coming Soon')" href="#"> Reset Password </a>
                          |<br>
                          <a href="<?php echo base_url().'user/login/summonsingin/'.$row->user_id;?>"> Login (Summon) </a>
                          |<br>
                          <a href="<?php echo base_url().'user/profile#!/edit/'.$row->user_id;?>"> Edit </a>
                          
                        </td>
                    </tr>
                    <?php
                        $i++;
                    endforeach;
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            <ul class="pagination justify-content-end">
                                <?php echo $pagination;?>
                            </ul>
                        </td>
                    </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- /.Content 02 --> 
      <!-- Content 03 --> 
      
      <!-- /.Content 03 --> 
      <!-- Content 04 --> 
      
      <!-- /.Content 04 --> 
      
      <!-- Content 05 --> 
      
      <!-- /.Content 05 --> 
      
    </div>
    <!-- /content area --> 
    
    <!-- Footer -->
    <div class="navbar navbar-expand-lg">
      <div class="text-center d-lg-none w-100"> &copy; 2018 CONNEXT ED. All rights reserved. Terms of Service | Privacy Policy </div>
      <div class="navbar-collapse collapse" id="navbar-footer"> <span class="navbar-text mx-auto"> &copy; 2018 CONNEXT ED. All rights reserved. Terms of Service | Privacy Policy </span> </div>
    </div>
    <!-- /footer --> 
    
    <!-- /main content --> 
  </div>

  <!-- /page content -->
  


  <div class="modal" id="modal-reset" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Reset Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>ต้องการ Reset Password หรือไม่</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="modal-btn-si">ยืนยัน</button>
          <button type="button" class="btn btn-default" id="modal-btn-no">ยกเลิก</button>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    var modalConfirm = function(callback){
      
      $(".btn-confirm").on("click", function(){
        $("#modal-reset").modal('show');
      });

      $("#modal-btn-si").on("click", function(){
        callback(true);
        $("#modal-reset").modal('hide');
      });
      
      $("#modal-btn-no").on("click", function(){
        callback(false);
        $("#modal-reset").modal('hide');
      });
    };

    modalConfirm(function(confirm){
      if(confirm){
        //Acciones si el usuario confirma
        reset_pass();
      }
    });

    // function reset_pass(user_id = false){
    // event.preventDefault();
    // var dataString = 'user_id='+ user_id;
    //     jQuery.ajax({
    //         url: "<?php echo base_url().'user/management/resetPass/';?>",
    //         data: dataString,
    //         type: "POST",
    //         success: function(data){
    //           if(data == 0){
    //             $('#modal-login').modal('show');
    //           }
    //         }
    //     });
    // }
  </script>