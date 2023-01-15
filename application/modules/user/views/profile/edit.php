<script src="<?=base_url()?>assets/asm/lib/image-compressor/image-compressor.min.js"></script>

<link rel="stylesheet" href="<?=base_url()?>assets/asm/lib/slim-cropper/slim/slim.min.css">
<script src="<?=base_url()?>assets/connexted/assets/js/plugins/forms/styling/switchery.min.js"></script>


<div class="content-wrapper widthscreen mx-auto">
    <div class="content" ng-app="editProfile" ng-controller="editProfileCtrl">
        <div class="row ">
            <div class="col-md-12 pb_me-3 mx-auto">
                <div class="card h-100">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="mx-auto" ng-if="imgShow">
                                <slim ng-if="newImg" data-instant-edit="true" data-size="250,250" data-ratio="1:1"
                                    data-will-transform="slim.addImage" data-initial-image="{{newImg}}">
                                    <input type="file" name="slim[]" />
                                </slim>
                                <slim ng-if="!newImg" data-instant-edit="true" data-size="250,250" data-ratio="1:1"
                                    data-will-transform="slim.addImage">
                                    <input type="file" name="slim[]" />
                                </slim>
                            </div>
                        </div>
                        <div class="text-center txt_welcome pt-4">
                            <button type="button" class="btn btn_blue" ng-click="uploadImg()">Save Image</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="bd-callout bd-callout-blue card mt-0 mb-0 pt-2 pb-1 rounded-bottom-0 bg_grey_behaviorhead">
                    <div class="row">
                        <div class="col-12 col-lg-6 text-left my-1 txt_schoolprofilehead">
                            ข้อมูลผู้ใช้</div>
                    </div>
                </div>
            </div>
            <div class="col-12 pb_me-3">
                <div class="card h-100 rounded-top-0">
                    <div class="card-body pb-0">

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="txt_blue">Username </label>
                                    <input type="text" ng-model="response.username"
                                        class="form-control bg_card_school_grey" required="" disabled>
                                </div>
                            </div>
                            <!-- <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="txt_blue">Password </label>
                            <div class="input-group mb-3">
                                <input name="" id="password" autocomplete="off"
                                    class="form-control bg_card_school_grey" required="" aria-describedby="passwordAdd">
                                <div class="input-group-append">
                                    <span class="input-group-text bg_card_school_grey" id="passwordAdd">
                                        <button class="" style="background:none;border:none;outline:none">
                                             <i class="fas fa-eye-slash" v-if="iconPass == true"></i> 
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div> -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="txt_blue">User ID *</label>
                                    <input name="people12" ng-model="response.user_id" autocomplete="off" type="text"
                                        class="form-control bg_card_school_grey" disabled>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">Company </label>
                                    <input type="text" ng-model="response.company"
                                        class="form-control bg_card_school_grey {{valid.company}}" disabled>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">Company Group </label>
                                    <input type="text" ng-model="response.company_group"
                                        class="form-control bg_card_school_grey {{valid.company_group}}" disabled>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="row">
                                    <div class="col-12">
                                        <span class="txt_blue"> คำนำหน้า</span>
                                    </div>
                                    <div class="col-6" style="margin-top:9px !important">
                                        <div class="form-group">
                                            <select ng-model="response.salutation"
                                                class="form-control bg_card_school_grey" required="">
                                                <option value="">เลือกคำนำหน้า</option>
                                                <option value="นาย" class="">นาย</option>
                                                <option value="นาง" class="">นาง</option>
                                                <option value="นางสาว" class="">นางสาว</option>
                                                <option value="ว่าที่ร้อยตรี" class="">ว่าที่ร้อยตรี</option>
                                                <option value="อื่น ๆ" class="">อื่น ๆ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 pl-0" style="margin-top:9px !important">
                                        <input type="text" ng-model="prefixtemp"
                                            class="form-control bg_card_school_grey"
                                            ng-disabled="response.salutation != 'อื่น ๆ'">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">ชื่อ</label>
                                    <input ng-model="response.name" type="text" class="form-control bg_card_school_grey"
                                        required="">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">นามสกุล</label>
                                    <input ng-model="response.surname" type="text"
                                        class="form-control bg_card_school_grey   " required="">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">เบอร์มือถือ</label>
                                    <input ng-model="response.phone" type="text"
                                        class="form-control bg_card_school_grey   " required="">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">E-mail</label>
                                    <input ng-model="response.email" type="text"
                                        class="form-control bg_card_school_grey" required="">
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">User Type</label>
                                    <select ng-model="response.user_type_id" ng-change="selectUserType()"
                                        class="form-control bg_card_school_grey" name="" required="" disabled>
                                        <option value="">เลือกคำนำหน้า</option>
                                        <option ng-repeat="type in allUserType" ng-value="type.user_type_id">
                                            {{type.user_type_title}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">Position</label>
                                    <input type="text" name="postion" ng-model="response.position"
                                        class="form-control bg_card_school_grey {{valid.position}}" required
                                        autocomplete="new-position">
                                </div>
                            </div>

                            <!-- <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="txt_blue">PMO</label>
                            <select ng-model="response.remark_PMO" class="form-control bg_card_school_grey" name=""
                                required="">
                                <option value="">เลือกคำนำหน้า</option>
                                <option ng-repeat="pmo in pmoall" ng-value="pmo.salutation + ' ' + pmo.name + ' ' + pmo.surname">{{pmo.salutation
                                    + " " +
                                    pmo.name + " " + pmo.surname}}</option>
                            </select>
                        </div>
                    </div> -->

                            <!-- <div class="col-12 col-md-4 p-0">
                        <div class="row">

                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-block btn_blue">Save</button>
                        </div>
                    </div> -->
                        </div>


                        <div class="row col-8 mx-auto">
                            <div class="col-12 col-md-6 mx-auto mb-2">
                                <button type="button" ng-click="passwordModal()" class="btn btn-block btn_blue">Change
                                    Password</button>
                                <!-- <button type="button" onclick="alert('Coming Soon')" class="btn btn-block btn_blue">Change Password</button> -->
                            </div>
                            <div class="col-12 col-md-6 mx-auto mb-2">
                                <button type="button" class="btn btn-block btn_blue" ng-click="saveData()">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" ng-if="admin">
            <div class="col-md-12">
                <div class="bd-callout bd-callout-blue card mt-0 mb-0 pt-2 pb-1 rounded-bottom-0 bg_grey_behaviorhead">
                    <div class="row">
                        <div class="col-12 col-lg-6 text-left my-1 txt_schoolprofilehead">
                            Admin</div>
                    </div>
                </div>
            </div>
            <div class="col-12 pb_me-3">
                <div class="card h-100 rounded-top-0">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-12 col-md-4 mb-2">
                                <span>Username</span>
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <input type="text" class="form-control" value="<?=$username?>" disabled>
                            </div>
                            <div class="col-12 col-md-4 mb-2">
                                <span>Password</span>
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <input type="text" class="form-control" value="<?=$password?>" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <span>User Status</span>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input type="checkbox" ui-switchery ng-model="onoff" ng-change="onoffChange()">
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 col-md-3 mx-auto mt-5">
                                <button type="button" ng-click="passwordModal()" class="btn btn-block btn_blue">Change
                                    Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade show" id="passModal" style="display: none; padding-right: 16px;">
            <!-- <form action="profile/passwordChange" method="post" ng-submit="submitData($event)"> -->
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header bg-blue p-2 d-flex justify-content-center">
                        <h4 class="modal-title txt_addtaskhead"> Change Password</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <input type="hidden" value="">
                    <div class="modal-body px-4">
                        <div class="alert alert-info">
                            <strong>คำแนะนำในการตั้งรหัสผ่าน </strong>ควรมีความยาวอย่างน้อย 8 ตัวอักษรและประกอบด้วย
                            ตัวอักษรตัวเล็ก, ตัวใหญ่, ตัวเลข และเครื่องหมายอักขระพิเศษ (เช่น @_|-)
                        </div>
                        <div>
                            <div class="alert alert-danger" ng-repeat="e in error">
                                <strong>{{e.title}}</strong> {{e.content}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="txt_blue">Old Password</label>
                            <input name="oldpassword" ng-model="oldpassword" type="password" class="form-control"
                                placeholder="Old Password" required>
                        </div>
                        <div class="mb-2">
                            <div class="progress progressbar_radius" style="height:10px">
                                <div class="progress-bar progressbar_radius {{progressColor(floor(progress * 100))}}"
                                    style="width:{{floor(progress * 100)}}%;"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="txt_blue">New Password</label>
                            <input name="newpassword" ng-model="newpassword" ng-change="strengPass()" type="password"
                                class="form-control" placeholder="New Password" required>
                        </div>
                        <div class="form-group">
                            <label class="txt_blue">Confirm New Password</label>
                            <input name="confirmpassword" ng-model="confirmpassword" type="password"
                                class="form-control" placeholder="Confirm New Password" required>
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <div class="col-10 col-md-4">
                            <button type="button" ng-click="submitData()"
                                class="btn btn-block btn_blue myModal-Confirm">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="navbar navbar-expand-lg">
        <div class="text-center d-lg-none w-100"> © 2018 CONNEXT ED. All rights reserved. Terms of Service | Privacy
            Policy </div>
        <div class="navbar-collapse collapse" id="navbar-footer"> <span class="navbar-text mx-auto"> © 2018 CONNEXT ED.
                All rights reserved. Terms of Service | Privacy Policy </span> </div>
    </div>

</div>

<script>
let user_id = "<?php echo $user_id;?>";
let isadmin = <?=$admin?>;


function api(name = "") {
    return getBaseUrl() + "api/user/" + name;
}
</script>

<script src="<?=base_url()?>assets/user/dist/edit.js"></script>