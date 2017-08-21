<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
  header('Content-Type: text/html; charset=utf-8');
  
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  class Cmsblog extends Admin_Controller {
    
    function __construct(){
      parent::__construct();
      $this->load->library('pagination');
      // $this->config->item('product_path');

      $upload_dir= $this->config->item('static_path').'tppy/'.$this->get_member_folder().'/cms';
      $upload_url= $this->config->item('static_url').'tppy/'.$this->get_member_folder().'/cms';
      
      if(!is_dir($upload_dir)){
        //echo "Create DIR : ".$upload_dir;
        mkdir($upload_dir, 0777, TRUE);
        chmod($upload_dir, 0777);
      }
      
      $_SESSION['KCFINDER'] = array(
      'disabled' => false,
      'uploadURL' =>  $upload_url,//"data/product/blog/images/upload/".FLOOR($user_id/2500)."/".$user_id."/upload_shop",
      'uploadDir' =>$upload_dir,
      'dirPerms' => 0777,
      'mediaStock' =>true,
      );
      
    }
    function index($uri=''){
      $this->cms_list();
    }
    function cms_list(){
      $data['breadcrumb'] = set_crumbs(array('Content Management'));
      $q=$this->input->get("q") ? $this->input->get("q"): "";
      $s=is_numeric($this->input->get("s")) ? $this->input->get("s") : NULL;
      $c=$this->input->get("c") ? $this->input->get("c"): array();
      $non_cat=$this->input->get("non_cat") ? $this->input->get("non_cat"): array();
      $offset=$this->input->get("per_page") ? $this->input->get("per_page") : 0;
      $sort=$this->input->get("sort") ? $this->input->get("sort") :  'c.idx';
      $order=$this->input->get("order")  ? $this->input->get("order") : 'desc';
      $per_page=30;

      $sql_status="";
      if($s  != NULL){
        $sql_status .=" AND c.record_status=%s%";
        if($s == -1){
            $sql_status .=" AND c.create_user_id=".$this->secure->get_user_session()->id;
        }
      }
      $sql_like="";
      if(!empty($q)){
        $sql_like=" AND CONCAT_WS('', c.title, c.description_short, c.description_long) LIKE %q%";
      }
      $sql_in_category="";
      if($c) {
        $sql_in_category ="AND m.category_id IN (".(str_replace('-', ',', $c)).") ";
      }
      if($non_cat){
        $sql_in_category ="AND m.category_id IS NULL";
      }
      
      $sql = "SELECT c.*, u.user_id, u.user_permission, u.psn_display_name, uu.user_id u_user_id, uu.user_permission u_user_permission, uu.psn_display_name u_psn_display_name, GROUP_CONCAT(mc.category_name_th) category_name_th, GROUP_CONCAT(mc.category_id) category_id FROM cmsblog_detail c
      LEFT JOIN users_account u ON c.create_user_id=u.user_id
      LEFT JOIN users_account uu ON c.update_user_id=uu.user_id
      LEFT JOIN cmsblog_category_mapping m ON m.content_id=c.idx AND m.content_type=2
      LEFT JOIN cmsblog_category mc ON mc.category_id=m.category_id AND mc.zone_id=1
      WHERE 1=1 $sql_status $sql_like  $sql_in_category
      GROUP BY c.idx ORDER BY $sort $order";
      // $sql .= " LIMIT 1";
      
      $query=$this->db->query($sql, array('%q%'=>"%$q%", '%s%'=>$s));
      // exit($this->db->last_query());
      $config = array(
      'base_url' => site_url(ADMIN_PATH . "/cmsblog/cms-list/?q=$q&s=$s&sort=$sort&order=$order&" . (is_array($c) ? "c[]=".implode("&c=", $c) : 'c='.$c) ),
      'total_rows' => $query->num_rows(),
      'page_query_string' => TRUE,
      'display_pages'=>TRUE,
      'per_page'=>$per_page,
      );
      $this->pagination->initialize($config);
      $data['pagination'] = $this->pagination;
      $data['lists'] = $query->result_array($offset, $per_page);
      $this->template->view('list', $data);
    }
    function cms_list_children(){
      $data['breadcrumb'] = set_crumbs(array(
        '/cmsblog'=>'Content Management', 
        'Group List'
      ));
      $pid=$this->input->get("pid") ? $this->input->get("pid"): "";
      $q=$this->input->get("q") ? $this->input->get("q"): "";
      $s=is_numeric($this->input->get("s")) ? $this->input->get("s") : NULL;
      $c=$this->input->get("c") ? $this->input->get("c"): array();
      $non_cat=$this->input->get("non_cat") ? $this->input->get("non_cat"): array();
      $offset=$this->input->get("per_page") ? $this->input->get("per_page") : 0;
      $sort=$this->input->get("sort") ? $this->input->get("sort") :  'c.idx';
      $order=$this->input->get("order")  ? $this->input->get("order") : 'desc';
      $per_page=30;

      $sql_status="";
      if($s  != NULL){
        $sql_status .=" AND c.record_status=%s%";
        if($s == -1){
            $sql_status .=" AND c.create_user_id=".$this->secure->get_user_session()->id;
        }
      }
      $sql_like="";
      if(!empty($q)){
        $sql_like=" AND CONCAT_WS('', c.title, c.description_short, c.description_long) LIKE %q%";
      }
      $sql_in_category="";
      if($c) {
        $sql_in_category ="AND m.category_id IN (".(str_replace('-', ',', $c)).") ";
      }
      if($non_cat){
        $sql_in_category ="AND m.category_id IS NULL";
      }
      
      $sql = "SELECT c.*, u.user_id, u.user_permission, u.psn_display_name, uu.user_id u_user_id, uu.user_permission u_user_permission, uu.psn_display_name u_psn_display_name, GROUP_CONCAT(mc.category_name_th) category_name_th, GROUP_CONCAT(mc.category_id) category_id FROM cmsblog_detail c
      LEFT JOIN users_account u ON c.create_user_id=u.user_id
      LEFT JOIN users_account uu ON c.update_user_id=uu.user_id
      LEFT JOIN cmsblog_category_mapping m ON m.content_id=c.idx AND m.content_type=2
      LEFT JOIN cmsblog_category mc ON mc.category_id=m.category_id AND mc.zone_id=1
      WHERE 1=1 AND (parent_idx=$pid OR idx=$pid)$sql_status $sql_like  $sql_in_category
      GROUP BY c.idx ORDER BY parent_idx ASC, child_order ASC, $sort $order";
      // $sql .= " LIMIT 1";
      
      $query=$this->db->query($sql, array('%q%'=>"%$q%", '%s%'=>$s));
      // exit($this->db->last_query());
      $config = array(
      'base_url' => site_url(ADMIN_PATH . "/cmsblog/cms-list/?pid=$pid&q=$q&s=$s&sort=$sort&order=$order&" . (is_array($c) ? "c[]=".implode("&c=", $c) : 'c='.$c) ),
      'total_rows' => $query->num_rows(),
      'page_query_string' => TRUE,
      'display_pages'=>TRUE,
      'per_page'=>$per_page,
      );
      $this->pagination->initialize($config);
      $data['pagination'] = $this->pagination;
      $data['lists'] = $query->result_array($offset, $per_page);
      $this->template->view('list_children', $data);
    }
    function cms_detail() {
      $data['breadcrumb'] = set_crumbs(array('cmsblog'=>'Content Management', 'เนื้อหา'));
      
      $this->form_validation->set_rules('banner_path', 'BANNER', 'trim');
      // $this->form_validation->set_rules('image_filename_s', 'Image Small size', 'trim');
      // $this->form_validation->set_rules('image_filename_m', 'Image Medium size', 'trim');
      // $this->form_validation->set_rules('cms_category_id', 'หมวดหมู่', 'trim');
      $this->form_validation->set_rules('thumb_path', 'Image Large size', 'trim');
      $this->form_validation->set_rules('title','หัวข้อ','trim|required');
      // $this->form_validation->set_rules('description_short','รายละเอียดย่อ','trim|required');
      $this->form_validation->set_rules('description_long','รายละเอียด','trim|required');
      // $this->form_validation->set_rules('seo_keywords','Keyword','trim|required');
      // $this->form_validation->set_rules('seo_description','Keyword Descriptions','trim|required');
      $this->form_validation->set_rules('credit_by_display','Credit','');
      $this->form_validation->set_rules('credit_by_link','Credit','');
      $this->form_validation->set_rules('start_date','วันเริ่ม','trim|required');
      // $this->form_validation->set_rules('end_date','วันสิ้นสุด','trim|required');
      $this->form_validation->set_rules('event_date','Event date','trim');
      $this->form_validation->set_rules('hashtag','Hashtag','trim');
      $this->form_validation->set_rules('record_status','สถานะ:','trim|required');
      $this->form_validation->set_rules('editor_picks','สถานะ:','trim');
      $this->form_validation->set_rules('encyclopedia','สถานะ:','trim');
      
      $id=$this->input->get('id') ? $this->input->get('id') : 0;
      $dup_id=$this->input->get('dup_id') ? $this->input->get('dup_id') : 0;
      $parent_idx=$this->input->get('parent_idx') ? $this->input->get('parent_idx') : 0;

      if($this->input->post()) {
        if($this->form_validation->RUN()){

          $input['title']=$this->input->post('title');
          // $input['description_short']=$this->input->post('description_short');
          $input['description_long']=$this->input->post('description_long');
          // $input['seo_keywords']=$this->input->post('seo_keywords');
          // $input['seo_description']=$this->input->post('seo_description');
          $input['credit_by']=$this->input->post('credit_by');
          $input['start_date']=$this->input->post('start_date');
          // $input['end_date']=$this->input->post('end_date');
          $input['event_date']=(bool)strtotime($this->input->post('event_date')) ? $this->input->post('event_date') : NULL;
          $input['hashtag']=$this->input->post('hashtag');
          $input['record_status']=$this->input->post('record_status');
          $input['editor_picks']=$this->input->post('editor_picks') ? $this->input->post('editor_picks') : '0';
          $input['encyclopedia']=$this->input->post('encyclopedia') ? $this->input->post('encyclopedia') : '0';
          $input['banner_path']=$this->input->post('banner_path_url');
          $input['update_user_id']=$this->secure->get_user_session()->member_id;
          $input['parent_idx']=$parent_idx;
          
            if($input['hashtag']){
              foreach(explode(',', $input['hashtag']) as $tag){
                $sql ="INSERT INTO hashtag (tag_name) SELECT %tag% FROM dual WHERE NOT EXISTS ( SELECT tag_name FROM hashtag  WHERE tag_name =%tag% )";
                $this->db->query($sql, array('%tag%'=>$tag));
              }
            }
          
          $credit_by_display=$this->input->post('credit_by_display');
          $credit_by_link=$this->input->post('credit_by_link');
          
          $credit_by=array();
          for($i=0;$i<count($credit_by_display); $i++){
            if(!empty($credit_by_display[$i])){
              $credit_by[] = array(
                'name'=>$credit_by_display[$i],
                'link'=>$credit_by_link[$i],
              );
            }
          }
          
          $input['credit_by'] = json_encode($credit_by, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

          if($id > 0) {
            $this->db->update('cmsblog_detail', $input, array('idx'=>$id));
            // exit($this->db->last_query());
          } else {
            // $input['child_order'] = $this->db->query("SELECT IFNULL(MAX(child_order)+ 1, 0) child_order FROM cmsblog_detail WHERE parent_idx=$parent_idx")->row()->child_order; // child_order
            $input['create_user_id']=$this->secure->get_user_session()->member_id;
            $input['create_date']=date('Y-m-d H:i:s');
            $input['update_date']=date('Y-m-d H:i:s');
            $this->db->insert('cmsblog_detail', $input);
                            // exit($this->db->last_query());
            $id=$this->db->insert_id();
          }
          // $input['thumb_path']="hash_thumbnail/".($id%4000)."/$id/";
          // $input['thumb_url']=$this->config->item('product_media_url').$input['thumb_path'];
          // $upload_path=str_replace('/',DIRECTORY_SEPARATOR,$this->config->item('product_media_path'). "hash_thumbnail/".($id%4000)."/$id/");
          
          
          
          // echo $this->db->last_query();
          // die;
          // 

          // UPLOAD IMAGE : thumb_path
          if($_FILES['thumb_path_file']['name']){
            $upload_path="cmsblog/".($id%4000)."/$id/";
            $upload_config = array(
            'upload_path' =>  str_replace('/',DIRECTORY_SEPARATOR,$this->config->item('static_path'). $upload_path),
            'file_name' => 'thumb_file',
            'allowed_types' => "gif|jpg|png|jpeg",
            'overwrite' => TRUE,
            'max_size' => "1024000", 
            );
            $upload_data = do_upload('thumb_path_file', $upload_config);
            if(!empty($upload_data['error'])){
              $this->form_validation->add_error($upload_data['error']);
              }else{
              $this->db->update('cmsblog_detail', array('thumb_path'=>$upload_path.$upload_data['file_name']), array('idx'=>$id));
              $input['thumb_path'] = $upload_path.$upload_data['file_name'];
              // var_dump($upload_path.$upload_data['file_name']); die;
            }
          }

          // UPLOAD IMAGE : banner_path
            if($_FILES['banner_path_file']['name']){
              $upload_path="cmsblog/".($id%4000)."/$id/";
              $real_upload_path=str_replace('/',DIRECTORY_SEPARATOR,$this->config->item('static_path'). $upload_path);
              $upload_config = array(
              'upload_path' =>  $real_upload_path,
              'file_name' => 'banner_file',
              'allowed_types' => "gif|jpg|png|jpeg",
              'overwrite' => TRUE,
              'max_size' => "1024000", 
              );
              $upload_data = do_upload('banner_path_file', $upload_config);
              if(!empty($upload_data['error'])){
                $this->form_validation->add_error($upload_data['error']);
                }else{
                // CUTSIZE THUMBNAIL
                
                // $this->load->library('Image', $real_upload_path.$upload_data['file_name']);
                // $this->image->resizeImage(610, 343, "crop");
                // $this->image->saveImage($real_upload_path.'thumb_file.png');

                // $props['source_image'] = $real_upload_path.$upload_data['file_name'];
                // $props['new_image'] = $real_upload_path.'thumb_file.png';
                // $props['width'] = 610;
                // $props['height'] = 340;
                // $props['crop'] = TRUE;

                // $this->image_cache->initialize($props);
                // $image = $this->image_cache->image_cache();
                // $this->image_cache->clear();
                // _vd($image);
                // die;
                
                $this->db->update('cmsblog_detail', array(/*'thumb_path'=> $upload_path.'thumb_file.png',*/'banner_path'=>$upload_path.$upload_data['file_name']), array('idx'=>$id));
                $input['banner_path'] = $upload_path.$upload_data['file_name'];
                // var_dump($upload_path.$upload_data['file_name']); die;
              }
            }
            swal_redirect("บันทึกข้อมูลสำเร็จ", "ข้อมูลของท่านถูกบันทึกเรียบร้อย", "success" ,site_url(ADMIN_PATH."/cmsblog/cms-detail?id=$id"));
          } else {
            // $input['id']=$id;
            $data['files'] = $this->db->where('cmsblog_idx',$id)->get('cmsblog_file')->result_array();
            $this->template->view('detail', $data);
          }
        }elseif($id>0){

          $data=$this->db->where('idx',$id)->get('cmsblog_detail')->row_array();

          if(!$data){
            // redirect(site_url(ADMIN_PATH.'/cmsblog/cms-detail'));
            swal_redirect("ไม่พบข้อมูล", "ไม่พบข้อมูลของคอนเทนท์นี้", "error" ,site_url(ADMIN_PATH."/cmsblog/cms-detail?id=$id"));
          }else{
            $data['id']=$id;
            $data['files'] = $this->db->where('cmsblog_idx',$id)->get('cmsblog_file')->result_array();
          }
          $this->template->view('detail', $data);
        }elseif($dup_id>0){
          $data['id']=0;
          $data=$this->db->where('idx',$dup_id)->get('cmsblog_detail')->row_array();
          $this->template->view('detail', $data);
        }else{
        // NEW INSERT
        $this->template->view('detail', array());
      }
    }
    function cms_category_select(){
      $this->template->set_layout('blankpage');
      $id=$this->input->get('id');
      // _vd($this->input->post());
      if($this->input->post()){
        // echo 'das';
        $cat=$this->input->post('checklist');
        $insert_data=array();
        if($cat){
          foreach($cat as $r){
            $insert_data[] = array(
            'content_id'=>$id,
            'category_id'=>$r,
            'content_type'=>2,
            );
          }
        }
        $this->db->delete('cmsblog_category_mapping', array('content_id'=>$id, 'content_type'=>2,));
        if($cat){
          $this->db->insert_batch('cmsblog_category_mapping', $insert_data);
        }
        echo "<script>parent.location.reload();parent.$.fn.colorbox.close();</script>";
      }
      
      $sql ="SELECT GROUP_CONCAT(mm.category_id) category_id
      FROM cmsblog_category mc1
      LEFT JOIN cmsblog_category_mapping mm ON mc1.category_id=mm.category_id
      WHERE content_id = $id AND mc1.zone_id=1";
      $query=$this->db->query($sql);
      $selected=$query->row()->category_id;
      $data['selected']= explode(',', $selected);
      // _vd($data);
      $this->template->view('category_select', $data);
    }
    function cms_item(){
      $data=array();
      $id=$data['id']=$this->input->get('id');
      if($this->input->post()){
        // _vd($_FILES['add_img']);
        
        if($_FILES['add_img']){
          $this->load->library('upload');
          for($i=0;$i<count($_FILES['add_img']['tmp_name']); $i++) {
            $project_files['add_img'][$i]= array(
            'file_name'=> 'asdas'.$i.'.gif',
            'upload_path' => 'E:\UwAmp\www\adm.trueplookpanya.com\public_html\V3',
            'max_size' => 0,
            'allowed_types'=>'gif|jpg|png',
            'overwrite' => TRUE,
            'remove_spaces' => TRUE,
            );
            $this->upload->upload_files($project_files);
            if(empty($this->upload->error_msg)){
              /*all files uploaded successfully*/
              _vd($this->upload->file_name);
              }else{
              _vd($this->upload->error_msg);
            }
          }
        }
        
        
        
        }else{
        $data['item']=$this->db->query("SELECT * FROM z_item_detail WHERE id=$id")->row_array();
        $data['image']=$this->db->query("SELECT * FROM z_item_image WHERE item_id=$id")->row_array();
      }
      
      $this->template->view('item', $data);
    }
    function cmsblog_file_edit($cmsblog_idx=0, $cmsblog_file_id=0) {
    
      if($cmsblog_idx > 0 && $cmsblog_file_id > 0 && $this->input->get('method') == "DELETE"){
        $file_data=$this->db->where('cmsblog_file_id', $cmsblog_file_id)->get('cmsblog_file')->row_array();
        $file_path=$file_data['file_path'];
        $data=$this->db->delete('cmsblog_file', array('cmsblog_file_id' => $cmsblog_file_id));
      }
      $this->form_validation->set_rules('file_title', 'ชือไฟล์', 'trim|required');
      if(!isset($_FILES['upload_file']['tmp_name'])){
        $this->form_validation->set_rules('file_link', 'Link / FTP', 'trim|required');
      }

      $this->template->set_layout('blankpage');
      if($this->input->post() && $this->form_validation->run()) {
        $input['cmsblog_idx']=$cmsblog_idx;
        $input['file_title']=$this->input->post('file_title');
        $input['create_user_id']=$this->secure->get_user_session()->user_id;
        $input['record_status']=$this->input->post('record_status');
        // $input['file_type']=$this->input->post('file_type');
        // $input['file_type']=$this->input->post('file_type');
        if($_FILES['upload_file']['tmp_name']) {
          $ext = pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION);
          $input['file_type'] = $this->file_type($ext);
          
          if($this->input->get('method') == "EDIT") { // ลบไฟล์เดิมก่อน หากมีการแก้ไข
            $file_data=$this->db->where('cmsblog_file_id', $cmsblog_file_id)->get('cmsblog_file')->row_array();
            $file_path=$this->config->item("static_path").$file_data['file_path'];
            $this->deleteDir($file_path);
            $data=$this->db->delete('cmsblog_file', array('cmsblog_file_id' => $cmsblog_file_id));
          }
          $target_dir="hash_cmsblog/".date('Ym')."/$cmsblog_idx/".time()."/";
          $full_dir=$this->config->item("static_path").$target_dir;
          $target_filename= "FILE_".time().".".pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION); 
          if(!is_dir($full_dir)){
            mkdir($full_dir,0777, true);
            chmod($full_dir,0777);
          }
          copy($_FILES['upload_file']['tmp_name'],  $full_dir.$target_filename);
          $input['file_name']=$target_filename;
          $input['file_path']=$target_dir;
          $input['file_size']=$_FILES['upload_file']['size'];
        } elseif($this->input->post('file_link')) {
          $input['file_type'] = $this->file_type($this->input->post('file_link'));
          // $input['file_name']='';
          $input['file_path']=$this->input->post('file_link');
          
          if(!file_exists($this->input->post('file_link'))){
            $this->form_validation->add_error('FTP Link not found!!');
          }
          // $input['file_size']=$_FILES['upload_file']['size'];
        }else{
          $this->form_validation->add_error('Please upload file or FTP Link .');
        }
        
        if($cmsblog_file_id > 0) {
          $this->db->update('cmsblog_file', $input, array('cmsblog_file_id'=>$cmsblog_file_id));
          exit("<script>parent.location.reload();parent.$.fn.colorbox.close();</script>");
        }else{
          $this->db->insert('cmsblog_file', $input);  
          $cmsblog_file_id=$this->db->insert_id();
          exit("<script>parent.location.reload();parent.$.fn.colorbox.close();</script>");
        }
      }
      $data=array();
      if($cmsblog_file_id > 0){
        $query=$this->db->where('cmsblog_file_id', $cmsblog_file_id)->get('cmsblog_file');
        if($query->num_rows() > 0){
          $data=$query->row_array();
        }
      }

      $this->template->view('cmsblog_file_edit', $data);      
    }
    function cmsblog_category_list(){
    //http://adm.trueplookpanya.local/V3/sitemin/cmsblog?q=&s=0&c=29
      $data['breadcrumb'] = set_crumbs(array(
        '/cmsblog'=>'Content Management', 
        'Category List'
      ));
      $sql = "SELECT A.category_id, A.category_name_th, A.category_name_code, A.child_category_id_list
      , B.category_id p_category_id, B.category_name_th p_category_name_th, B.category_name_code p_category_name_code
      , COUNT(DISTINCT(D.idx)) count_content
      , IFNULL(SUM(D.view_count), 0) sum_view_count
      FROM cmsblog_category A
      LEFT OUTER JOIN cmsblog_category B ON B.category_id=A.category_parent_id
      LEFT OUTER JOIN cmsblog_category_mapping C ON C.category_id=A.category_id AND content_type=2
      LEFT OUTER JOIN cmsblog_detail D ON D.idx=C.content_id AND D.record_status = 1
      GROUP BY A.category_id
      ORDER BY A.category_parent_id, A.category_id";
      $data['lists']=$this->db->query($sql)->result_array();
      
      
      $sql="SELECT  
      COUNT(*) count_content,
      SUM((CASE WHEN record_status=1 THEN 1 ELSE 0 END)) status_open,
      SUM((CASE WHEN record_status=0 THEN 1 ELSE 0 END)) status_close,
      SUM((CASE WHEN record_status=-1 THEN 1 ELSE 0 END)) status_draft,
      SUM((CASE WHEN record_status=-2 THEN 1 ELSE 0 END)) status_delete,
      SUM((CASE WHEN record_status=-3 THEN 1 ELSE 0 END)) status_waitapprove,
      SUM((CASE WHEN B.content_id IS NULL THEN 1 ELSE 0 END)) non_category
      FROM cmsblog_detail A
      LEFT OUTER JOIN cmsblog_category_mapping B ON A.idx=B.content_id";
      
     $data['content']=$this->db->query($sql)->row_array();
     $this->template->view('category_list', $data);
    }
    
    /* COMMON FUNCTION */
    public function get_member_folder($user_id =0){
      // return 'ds';
      if(empty($user_id)){
        $user_id = $this->secure->get_user_session()->id;
      }
      $start = floor($user_id / 2500) * 2500;
      $c=join(DIRECTORY_SEPARATOR , 
      array(  'member', 
      'm_'. $start.'_'.($start+2500),
      $user_id)
      ); 
      // echo $c;
      return $c;
    }
    public function createTree($flat=null, $root = 0) {
      if(!$result = $this->tppymemcached->get('content_category_tree')){
        if($flat==null){
          $flat = $this->db->query("SELECT * FROM cmsblog_category")->result_array();
        }
        $parents = array();
        foreach ($flat as $a) {
          $parents[$a['category_parent_id']][$a['category_id']] = $a;
        }
        $result=$this->createBranch($parents, $parents[$root]);
        $this->tppymemcached->set('content_category_tree', $result);
      }
      return $result;
    }
    public function printTree($tree, $r = 0, $p = null, $pn='') {
      $result="";
      foreach ($tree as $i => $t) {
        $pnx = $t['category_parent_id'] == 0 ? '' : $pn  .' &gt; ';
        $c=$this->input->get("c") ? explode('-',$this->input->get("c")) : '';
        
        
        $result .=  '<option value="'.$t['category_id'].'" '.(in_array($t['category_id'], $c) ? 'selected' : '').'>'. $pnx . $t['category_name_th'].'</option>';
        if ($t['category_parent_id'] == $p) {
          $r = 0;
        }
        if (isset($t['children'])) {
          $result .=$this->printTree($t['children'], ++$r, $t['category_parent_id'],  $pnx.$t['category_name_th'] );
        }
      }
      return $result;
    }
    public function printTreeCheckbox($tree, $r = 0, $p = null, $pn='', $selected=array()) {
      $result="";
      foreach ($tree as $i => $t) {
        $pnx = $t['category_parent_id'] == 0 ? '' : $pn  .' &gt; ';
        
        $result .=  '<label class="select col-md-4 col-sm-6 col-xs-12"><input type="checkbox" name="checklist[]" rel="'.$pnx .$t['category_name_th'].'" value="'.$t['category_id'].'" '.(in_array($t['category_id'], $selected) ? 'checked' : '').' /> '. $pnx . $t['category_name_th'].'</label>';
        if ($t['category_parent_id'] == $p) {
          $r = 0;
        }
        if (isset($t['children'])) {
          $result .=$this->printTreeCheckbox($t['children'], ++$r, $t['category_parent_id'],  $pnx.$t['category_name_th'], $selected);
        }
      }
      return $result;
    }
    public function createBranch(&$parents, $children) {
      $tree = array();
      foreach ($children as $child) {
        if (isset($parents[$child['category_id']])) {
          $child['children'] = $this->createBranch($parents, $parents[$child['category_id']]);
        }
        $tree[$child['category_id']] = $child;
      } 
      return $tree;
    }
    public function update_child(){
      $query=$this->db->query("SELECT category_id FROM cmsblog_category");
      foreach($query->result_array() as $k=>$v){
      // echo;
        $sql = "UPDATE cmsblog_category CCC
        SET CCC.child_category_id_list = CONCAT_WS(',',". $v['category_id'].",(SELECT GROUP_CONCAT(lv SEPARATOR ',') children FROM (
        SELECT @pv:=(SELECT GROUP_CONCAT(category_id SEPARATOR ',') FROM cmsblog_category WHERE category_parent_id IN (@pv) AND status=1) AS lv FROM cmsblog_category
        JOIN
        (SELECT @pv:=". $v['category_id'].")tmp
        WHERE category_parent_id IN (@pv)) a))
        WHERE CCC.category_id=". $v['category_id'].";";
        $this->db->query( $sql);
      }
    }  
    public function deleteDir($dirPath) {
      if (! is_dir($dirPath)) {
          throw new InvalidArgumentException("$dirPath must be a directory");
      }
      if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
          $dirPath .= '/';
      }
      $files = glob($dirPath . '*', GLOB_MARK);
      foreach ($files as $file) {
          if (is_dir($file)) {
              $this->deleteDir($file);
          } else {
              unlink($file);
          }
      }
      rmdir($dirPath);
    }
    public function createCurl($url = 'nul')  {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      return $output;
    }
    public function file_type($ext){
      if(in_array($ext, array('pdf')))
        return 'Doc-pdf';
      if(in_array($ext, array('swf', 'flv')))
        return 'Flash';
      if(in_array($ext, array('mp3', 'wma', 'wav', '3gp')))
        return 'Audio';
      if(in_array($ext, array('zip')))
        return 'Doc-zip';
      if(in_array($ext, array('mp4', 'wmv', 'mpg', 'mpeg', 'm4v')))
        return 'Video';
      if (strpos($url, 'youtube') > 0) 
        return 'Youtube';
      
      return 'File';
        // 'Link',
        // 'Youtube',
        // 'Vimeo',
    }
    
    /* AJAX */
    public function cmsblog_file_reconvert($cmsblog_idx=0, $cmsblog_file_id=0){
    // _vd(gethostbyname("mediaconverter.trueplookpanya.com"));
    
      $sql = "SELECT * FROM cmsblog_file WHERE cmsblog_idx=%cmsblog_idx% AND cmsblog_file_id=%cmsblog_file_id% AND file_type='Video'";
      $query=$this->db->query($sql, array('%cmsblog_file_id%'=>$cmsblog_file_id, '%cmsblog_idx%'=>$cmsblog_idx));
      $result=$query->row();

      if($this->input->get("method")=="RECONVERT") {
        $video_file=$this->config->item('static_path').$result->file_path.$result->file_name;
        $notification_url=site_url(ADMIN_PATH."/cmsblog/notification-file-reconvert/$cmsblog_idx/$cmsblog_file_id");
        $output_file_path=$this->config->item('static_path').$result->file_path;
        $file_source=$this->config->item('static_path').$result->file_path.$result->file_name;
        $ref_id="CMSBLOG_".$cmsblog_idx."_".$cmsblog_file_id;
        
        $this->load->library("Convert_vdo_v4");
        $output=$this->convert_vdo_v4->ConvertVideoFile($file_source, $output_file_path, $output_file_path, $ref_id, $notification_url);
        if($output['response'] == '200'){
          exit('true');
        }
        exit('false');
      }
      
      if($this->input->get("method")=="GETPERCENT") {
        $output=$this->createCurl("http://mediaconverter.trueplookpanya.com/api/getPercent/CMSBLOG_".$cmsblog_idx."_".$cmsblog_file_id);
        // $output=file_get_contents("http://mediaconverter.trueplookpanya.com/api/getPercent/TV_EPISODE_VDO2618yG5PJLTeuN.mp4");
        $json_output=json_encode(simplexml_load_string($output));
        exit($json_output);
      }
      
      $this->template->set_layout('blankpage');
      $this->template->view("file_reconvert", array("data"=>$result));
    }
    public function tags(){
      $query=$this->db->select('tag_name')->get('hashtag');
      $data=array();
      foreach($query->result_array() as $v){
        $data[]=$v['tag_name'];
      }
      echo(json_encode($data));
    }
    public function swap_child_order($parent_idx, $idx_A, $order_A, $idx_B, $order_B){
      $this->db->update("cmsblog_detail", array('child_order'=>$order_B), array('idx'=>$idx_A));
      $this->db->update("cmsblog_detail", array('child_order'=>$order_A), array('idx'=>$idx_B));
      redirect(site_url(ADMIN_PATH."/cmsblog/cms-list-children?pid=$parent_idx"));
    }
  //////////////////////////////
   function dashboard(){
      // ใครโพสเท่าไร (Admin Only)
      $sql_date = 'AND create_date BETWEEN "' . date("Y-m-d", time()-60*60*24*365) . '" AND "'. date("Y-m-d") .'"';
      $sql_admin ='AND u.user_permission IS NOT NULL AND user_permission!=3';
      $sql ="SELECT u.user_username, u.psn_display_name, u.psn_display_image, cd.create_user_id, COUNT(*) count_c FROM cmsblog_detail cd INNER JOIN users_account u ON cd.create_user_id=u.user_id $sql_admin WHERE 1=1 $sql_date GROUP BY cd.create_user_id ORDER BY  count_c DESC LIMIT 15";
      
      // echo $sql;
    }
/* 
  REPORT
    - ใครโพสเท่าไร (Admin Only)
    - โพสใน Cate ไหนคนดูเยอะ
    - Top Rank 

*/
}  