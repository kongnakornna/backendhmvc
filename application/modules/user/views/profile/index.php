<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="https://unpkg.com/vue-cookies@1.5.12/vue-cookies.js"></script>
<script src="https://unpkg.com/vue-router/dist/vue-router.js"></script>

<script src="<?=base_url()?>assets/schoolinfo/js/main.js"></script>
<script src="<?=base_url()?>assets/asm/lib/slim-cropper/slim/slim.kickstart.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/asm/lib/slim-cropper/slim/slim.min.css">

<div class="content-wrapper widthscreen mx-auto" id="app">
    <div class="content">
        <div class="row ">
            <div class="col-md-12 pb_me-3 mx-auto">
                <div class="card h-100">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="mx-auto">
                                <div class="slim" data-size="800,800" style="width:250px;height:250px"
                                    data-will-transform="saveImage">
                                    <img <?=$url? "src='$url'" :"" ?> alt="">
                                    <input type="file" name="slim[]">
                                </div>
                            </div>
                        </div>
                        <div class="text-center txt_welcome pt-4">
                            <button type="button" @click="uploadImg()" class="btn btn_blue">Upload</button>
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
                                    <input type="text" v-model="userdata.username" class="form-control bg_card_school_grey"
                                        required="">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="txt_blue">Password </label>
                                    <div class="input-group mb-3">
                                        <input name="" v-model="userdata.password" id="password" autocomplete="off" :type="passwordChange(iconPass)"
                                            class="form-control bg_card_school_grey" required="" aria-describedby="passwordAdd">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg_card_school_grey" id="passwordAdd">
                                                <button class="" style="background:none;border:none;outline:none"
                                                    @click="iconPass = !iconPass">
                                                    <i class="fas fa-eye-slash" v-if="iconPass == true"></i>
                                                    <i class="fas fa-eye" v-if="iconPass == false"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">User ID *</label>
                                    <input name="people12" autocomplete="off" v-model="userdata.user_id" type="text"
                                        class="form-control bg_card_school_grey" required="" :disabled="userIdwrite? !'diabled' : 'disabled'">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">Company </label>
                                    <select class="form-control bg_card_school_grey" v-model="userdata.company" name=""
                                        required="">
                                        <option value="">เลือกคำนำหน้า</option>
                                        <option :value="c" v-for="c in companies">{{c}}</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">Company Group </label>
                                    <select class="form-control bg_card_school_grey" v-model="userdata.company_group"
                                        name="" required="">
                                        <option value="">เลือกคำนำหน้า</option>
                                        <option :value="cg" v-for="cg in company_groups">{{cg}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="row">
                                    <div class="col-12">
                                        <span class="txt_blue"> คำนำหน้า</span>
                                    </div>
                                    <div class="col-6" style="margin-top:9px !important">
                                        <div class="form-group">
                                            <select class="form-control bg_card_school_grey" v-model="userdata.salutation"
                                                required="">
                                                <option value="">เลือกคำนำหน้า</option>
                                                <option value="นาย" class="" selected="selected">นาย</option>
                                                <option value="นาง" class="">นาง</option>
                                                <option value="นางสาว" class="">นางสาว</option>
                                                <option value="ว่าที่ร้อยตรี" class="">ว่าที่ร้อยตรี</option>
                                                <option value="อื่นๆ" class="">อื่นๆ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 pl-0" style="margin-top:9px !important">
                                        <input v-model="prefix" type="text" class="form-control bg_card_school_grey    "
                                            :disabled="userdata.salutation != 'อื่นๆ'">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">ชื่อ</label>
                                    <input v-model="userdata.name" type="text" class="form-control bg_card_school_grey"
                                        required="">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">นามสกุล</label>
                                    <input v-model="userdata.surname" type="text" class="form-control bg_card_school_grey   "
                                        required="">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">เบอร์มือถือ</label>
                                    <input type="text" v-model="userdata.phone" class="form-control bg_card_school_grey   "
                                        required="">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">E-mail</label>
                                    <input type="text" v-model="userdata.email" class="form-control bg_card_school_grey"
                                        required="">
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">User Type</label>
                                    <select class="form-control bg_card_school_grey" @change="selectUserType()" v-model="userdata.user_type_id"
                                        name="" required="">
                                        <option value="">เลือกคำนำหน้า</option>
                                        <option v-for="type in allUserType" :value="type.user_type_id">{{type.user_type_title}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">Position</label>
                                    <select class="form-control bg_card_school_grey" v-model="userdata.position" name=""
                                        required="">
                                        <option value="">เลือกคำนำหน้า</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="txt_blue">PMO</label>
                                    <select class="form-control bg_card_school_grey" v-model="userdata.remark_PMO" name=""
                                        required="">
                                        <option value="">เลือกคำนำหน้า</option>
                                        <option :value="pmo.salutation + ' ' + pmo.name + ' ' + pmo.surname" v-for="pmo in pmoall">{{pmo.salutation
                                            + " " +
                                            pmo.name + " " + pmo.surname}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center col-12 col-md-4 offset-md-4 p-0">
                                <div class="col-12">
                                    <button type="button" @click="updateData()" class="btn btn-block btn_blue">Save</button>
                                </div>
                            </div>
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
<br>

<script>
    function api(name = "") {
        return getBaseUrl() + "api/user/" + name;
    }

    let app = new Vue({
        el: "#app",
        data: {
            iconPass: true,
            userdata: {},
            allUserType: [],
            companies: ['TRUE CORP2'],
            company_groups: ['TRUE'],
            pmoall: [],
            userIdwrite: true,
            prefix: '',
            isvalid: false,
            valid: {}
        },
        methods: {
            passwordChange: function (iconpass) {
                if (iconpass)
                    return "password";
                else
                    return "text"
            },
            selectUserType: function () {
                if (this.userdata.user_type_id)
                    fetch(api("getPMO?ustype=" + this.userdata.user_type_id)).then(res => res.json()).then(
                        (data) => {
                            this.pmoall = data;
                        });
            },
            checkValid: function () {
                let data = {
                    result: [],
                    value: true
                };

                if (!this.userdata.user_id || this.userdata.user_id == -1) {
                    data.result.push({
                        err: 'กรุณากรอก USER ID'
                    });
                    data.value = false;
                }

                if (!document.getElementById("password").value && this.id == -1) {
                    data.result.push({
                        err: 'กรุณากรอกรหัสผ่าน'
                    });
                    data.value = false;
                }

                return data;
            },
            savetodb: function () {
                temp = {
                    company: this.userdata.company,
                    company_group: this.userdata.company_group,
                    email: this.userdata.email,
                    name: this.userdata.name,
                    phone: this.userdata.phone,
                    position: this.userdata.position,
                    profile_image: this.userdata.profile_image,
                    remark_PMO: this.userdata.remark_PMO,
                    salutation: (this.userdata.salutation == "อื่นๆ") ? this.prefix : this.userdata
                        .salutation,
                    surname: this.userdata.surname,
                    type: this.userdata.type,
                    user_id: this.userdata.user_id,
                    user_status: this.userdata.user_status,
                    user_type: this.userdata.user_type,
                    user_type_id: this.userdata.user_type_id,
                    username: this.userdata.username,
                }

                if (this.userdata.password)
                    temp.password = this.userdata.password;
                    
                if (this.userdata.username)
                    temp.username = this.userdata.username;
                
                $.ajax({
                    url: api("updateData"),
                    method: 'POST',
                    data: temp,
                    dataType: 'json'
                }).then(data => {
                    if (data.type == 'create') {
                        $.myalert2('รายละเอียด', 'ทำการสร้างผู้ใช้งานสำเร็จ').then(data => {
                            window.location.href = getBaseUrl() + "user/profile?t=" +
                                this.userdata.user_id;
                        });
                    } else
                        $.myalert2('รายละเอียด', 'ทำการอัพเดทเรียบร้อย').then(data => {
                            window.location.href = getBaseUrl() + "user/profile?t=" + this.userdata
                                .user_id;
                        });
                });
            },
            updateData: function () {
                let temp = {};
                let check = this.checkValid();

                if (check.value) {
                    if (this.id == '-1') {
                        fetch(api("isexist/" + this.userdata.user_id)).then(res => res.json()).then(data => {
                            if (data) {
                                $.myalert({
                                    title: 'รายละเอียด',
                                    text: 'เนื่องจากบัญชีมีอยู่ในระบบคุณต้องการบันทึกข้อมูลใหม่เข้าไปหรือไม่',
                                    showCancelButton: true,
                                    showConfirmButton: true,
                                    confirmButtonText: "OK",
                                    cancelButtonText: "Cancel"
                                }).then(data => {
                                    if(data.value)
                                        this.savetodb();
                                });
                            }else
                                this.savetodb();
                        });
                    } else
                        this.savetodb();
                } else
                    $.myalert2('รายละเอียด', check.result[0].err);
            },
            checkPrefix: function () {
                if (this.userdata) {
                    switch (this.userdata.salutation) {
                        case '':
                            this.userdata.salutation = '';
                            break;
                        case 'นาย':
                            this.userdata.salutation = 'นาย';
                            break;
                        case 'นาง':
                            this.userdata.salutation = 'นาง';
                            break;
                        case 'นางสาว':
                            this.userdata.salutation = 'นางสาว';
                            break;
                        case 'ว่าที่ร้อยตรี':
                            this.userdata.salutation = 'ว่าที่ร้อยตรี';
                            break;
                        default:
                            this.prefix = this.userdata.salutation;
                            this.userdata.salutation = 'อื่นๆ';
                            break;
                    }
                }
            },
            uploadImg: function () {
                if (this.id && this.id != -1) {

                    let form = new FormData();
                    form.append('image', this.blob);
                    form.append('user_id', this.id);

                    $.ajax({
                        url: api('upload'),
                        type: 'POST',
                        data: form,
                        cache: false,
                        contentType: false,
                        processData: false,
                    }).then(data => {
                        $.myalert2('รายละเอียด', 'ทำการอัพเดทเรียบร้อย').then(data => {
                            window.location.href = getBaseUrl() + "user/profile?t=" + this.userdata
                                .user_id;
                        })
                    })
                } else
                    $.myalert2('รายละเอียด', 'กรุณากรอกข้อมูลผู้ใช้');
            }
        },
        beforeCreate: function () {

        },
        created: function () {
            this.userdata = {
                company: '',
                company_group: '',
                salutation: '',
                user_type_id: '',
                position: '',
                remark_PMO: ''
            }

            this.searchParams = new URLSearchParams(window.location.search);
            this.id = -1;

            findType = (data) => {
                this.allUserType = data;
            }

            getUser = (data) => {
                this.userdata = data;
                console.log(data);
                this.selectUserType();
                this.checkPrefix();

            }

            fetch(api("findAllUserType")).then(res => res.json()).then(findType);

            // if (this.$cookies.get("user_type_id") > 2) {
            if (this.$cookies.get("user_type_id")) {
                if (this.searchParams.get('t')) {
                    if (this.searchParams.get('t') != this.$cookies.get("user_id")) {
                        this.id = this.$cookies.get("user_id");
                        fetch(api("getUser/" + this.id)).then(res => res.json()).then(getUser);
                        this.userIdwrite = false;
                    }else{
                        this.id = this.searchParams.get('t');
                        fetch(api("getUser/" + this.id)).then(res => res.json()).then(getUser);
                        this.userIdwrite = false;
                    }
                }else 
                    window.location.href = getBaseUrl() + "user/profile?t=" + this.$cookies.get("user_id");
                    // this.id = this.$cookies.get("user_id");
                    // fetch(api("getUser/" + this.id)).then(res => res.json()).then(getUser);
                    // this.userIdwrite = false;
            } 
            // else {
            //     if (this.searchParams.get('t')) {
            //         this.id = this.searchParams.get('t');
            //         fetch(api("getUser/" + this.id)).then(res => res.json()).then(getUser);

            //         this.userIdwrite = false;
            //     } else {
            //         this.userIdwrite = true;
            //     }
            // }
        }
    });

    function saveImage(data, ready) {
        fetch(data.output.image.toDataURL())
            .then(res => res.blob())
            .then(blob => {
                app.blob = blob;
            });
        ready(data);
    }
</script>