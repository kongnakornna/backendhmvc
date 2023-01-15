<form method="post" enctype="multipart/form-data">
<input type="text"  name="name" value="<?=set_value('name', isset($item['name']) ? $item['name']  : '')?>"/>


<img src="http://dummyimage.com/240x126/b3/ff.png?text=0x2B" style="cursor:pointer" id="add_new"/>
  <script>
  $(function(){
    $('#add_new').click(function(){
        var id= 'add_img_' + Math.round(Math.random() * new Date().getTime());
        var txt='<input type="file" name="add_img[]" id="'+id+'" style="display:none;" onchange="check_image(this, \''+id+'\')">';
        $(txt).insertBefore(this);
        $('#'+id).click();
        
    });
  });
  function check_image(input, file_id) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.readAsDataURL(input.files[0]);
      reader.onload = function (e) {
        var image = new Image();
        image.src = e.target.result;
        image.onload = function () {
          var height = this.height;
          var width = this.width;
          if(height == 630 && width==1200){
            var txt='<img id="images_'+file_id+'" style="width:240px; height:126px">';
            $(txt).insertBefore(input);
            $('#images_'+file_id).attr('src', this.src);
            return true;
          } else {
            $(input).remove();
            alert("Wrong image size plase upload file 1200x600 ");
          }
        };
      };
    }else{
      $(input).remove();
    }
  }
</script>
<button>SUBMIT</button>
</form>