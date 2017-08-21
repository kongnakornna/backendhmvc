<div class="col-md-6">
  <?=$this->load->view('content_blog01', array('data'=>$data[0]));?>
</div>
<div class="col-md-6 ">
  <?=$this->load->view('content_blog03', array('data'=>$data[1],'disabled_cat'=>TRUE));?>
  <?=$this->load->view('content_blog03', array('data'=>$data[2],'disabled_cat'=>TRUE));?>
  <?=$this->load->view('content_blog03', array('data'=>$data[3],'disabled_cat'=>TRUE));?>
</div>