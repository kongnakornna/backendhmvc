<script src="<?=base_url()?>assets/asm/lib/image-compressor/image-compressor.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jsrsasign/8.0.12/jsrsasign-all-min.js"></script> -->

<!-- js/plugins/forms/styling/switch.min.js -->
<script src="<?=base_url()?>assets/connexted/assets/js/plugins/forms/styling/switchery.min.js"></script>

<link rel="stylesheet" href="<?=base_url()?>assets/asm/lib/slim-cropper/slim/slim.min.css">

<div ng-app="userProfile" class="content-wrapper widthscreen mx-auto">
    <ng-view class="content"></ng-view>

    <div class="navbar navbar-expand-lg">
        <div class="text-center d-lg-none w-100"> © 2018 CONNEXT ED. All rights reserved. Terms of Service | Privacy
            Policy </div>
        <div class="navbar-collapse collapse" id="navbar-footer"> <span class="navbar-text mx-auto"> © 2018 CONNEXT ED.
                All rights reserved. Terms of Service | Privacy Policy </span> </div>
    </div>

</div>

<script>

function api(name = "") {
    return getBaseUrl() + "api/user/" + name;
}
</script>

<script src="<?=base_url()?>assets/user/dist/index.js"></script>