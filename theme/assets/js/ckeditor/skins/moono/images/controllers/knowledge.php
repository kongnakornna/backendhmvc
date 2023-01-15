<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); //header('Content-type: text/html; charset=utf-8');

class Knowledge extends TPPY_Controller {

    function __construct() {
        parent::__construct();
        /* REDIRECT ไปหน้าเก่า */
        if($this->router->fetch_method() == 'index'){
          if(!$this->uri->rsegment(3)){
            redirect('/education/learning/'.($this->input->server('QUERY_STRING') ? '?'.$this->input->server('QUERY_STRING') : ''), 'location');
          }
        }
        if($this->router->fetch_method() == 'search'){
          redirect('/education/learning/search?'.$this->input->server('QUERY_STRING'), 'location');
        }
        if($this->router->fetch_method() == 'detail'){
          $content_id=$this->uri->segment(3);
          $source_id=$this->uri->segment(4);
          $sec=$this->uri->segment(5);
          redirect("/education/learning/detail". ($content_id ? "/$content_id": "" ) . ($source_id ? "/$source_id": "" ) . ($sec ? "/$sec": "" ) .($this->input->server('QUERY_STRING') ? '?'.$this->input->server('QUERY_STRING') : ''), 'location');
        }
        /* REDIRECT ไปหน้าเก่า */
        
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('TPPY_Utils');
        $this->load->library('tppymemcached');
        $this->load->model('cmsblogmodel');
        $this->template->set_theme('trueplookpanya', 'default', 'themes');
    }

    public function blank() {
        $this->output->enable_profiler(TRUE);
        $this->template->view('blank');
    }

    public function index($category_name_code_lv1 = null, $category_name_code_lv2 = null, $category_name_code_lv3 = null, $category_name_code_lv4 = null) {
        $data = array();
        /* PARSE FROM LINK */
//        if ($category_name_code_lv1) {
//            $category_name_code = $category_name_code_lv1;
//            if ($category_name_code_lv2) {
//                $category_name_code.="/$category_name_code_lv2";
//                if ($category_name_code_lv3) {
//                    $category_name_code.="/$category_name_code_lv3";
//                    if ($category_name_code_lv4) {
//                        $category_name_code.="/$category_name_code_lv4";
//                    }
//                }
//            }
//        }
        $category_name_code=  implode('/', func_get_args());       
        if ($category_detail = $this->cmsblogmodel->menu_detail_by_category_name_code($category_name_code)) {
            $category_id = intval($category_detail->category_id, 0);
            $data['category_data'] = $this->cmsblogmodel->category_detail($category_detail->category_id);
            $data['category_child'] = $this->cmsblogmodel->find_children($category_detail->category_id);
            $data['category_id'] = $category_detail->category_id;
            // $deep_level = ($data['category_data']['deep_level'] > 2 ? 3 :$data['category_data']['deep_level']);

            $data['editorpicks'] = $this->cmsblogmodel->cmsblog_get_editorpicks_list($category_detail->child_category_id_list);

            foreach ($data['category_child'] as $k => $v) {
                $data['child'][$k]['detail'] = $this->cmsblogmodel->category_detail($v['category_id']);
                $data['child'][$k]['data'] = $this->cmsblogmodel->cmsblog_get_list($v['child_category_id_list'], $k > 0 ? 4 : 12);
            }

            if ($data['category_data']['deep_level'] == 1) {
                $this->template->view('category_d1', $data);
            } elseif ($data['category_data']['deep_level'] == 2 && count($data['category_child']) > 0) {
                $this->template->view('category_d2', $data);
            } else {
                if (count($data['category_child']) == 0) {
                    $child = $this->cmsblogmodel->find_children($category_detail->category_parent_id);
                    $data['category_child'] = $this->cmsblogmodel->find_children($category_detail->category_parent_id);
                    $data['menu_parent'] = $this->cmsblogmodel->category_detail($category_detail->category_parent_id);
                }

                $data['list'] = $this->cmsblogmodel->cmsblog_get_list($category_detail->child_category_id_list);
                $this->template->view('category_d3', $data);
            }
        } else {
            //  MENU LV 0
            $this->template->view('category_d0', $data);
        }
    }

    function preview($cms_id = 0) {
        // $this->tppy_utils->ViewNumberSet($cms_id, "cmsblog_detail");
        $content = $this->cmsblogmodel->content_detail(intval($cms_id, 0));

        $this->template->meta_title = 'TRUEPLOOKPANYA :: ' . $content['cms_subject'];
        $this->template->meta_keywords = $content['seo_keywords'];
        $this->template->meta_description = $content['cms_detail_short'];
        $this->template->meta_og_type = 'article';
        $this->template->meta_og_title = $content['cms_subject'];
        $this->template->meta_og_description = $content['cms_detail_short'];
        $this->template->meta_og_image = $content['thumb_url'] . '/' . $content['image_filename_thumb'];

        $data['content'] = $content;
        $this->load->model('api/getRelate_model');
        $data['relate'] = $this->getRelate_model->getRelate_cms($cms_id, 4);
        $this->template->view('detail', $data);
    }

    function view($cms_id = 0) {
        $content = $this->cmsblogmodel->content_detail(intval($cms_id, 10));

        $this->template->meta_title = 'TRUEPLOOKPANYA :: ' . $content['cms_subject'];
        $this->template->meta_keywords = $content['seo_keywords'];
        $this->template->meta_description = $content['cms_detail_short'];
        $this->template->meta_og_type = 'article';
        $this->template->meta_og_title = $content['cms_subject'];
        $this->template->meta_og_description = $content['cms_detail_short'];
        $this->template->meta_og_image = $content['thumb_url'] . '/' . $content['image_filename_thumb'];

        $data['content'] = $content;
        $this->load->model('api/getRelate_model');
        $data['relate'] = $this->getRelate_model->getRelate_cms($cms_id, 4);
        $this->template->view('detail', $data);
    }

    function detail_edit($cms_id = 0) {
        $data = array();
        $data['menu_list'] = $this->printTree($this->createTree());
        $data['reply_list'] = $this->cmsblogmodel->cmsblog_message_get($cms_id);
        $data['content'] = $this->cmsblogmodel->content_detail(intval($cms_id, 0));
        $this->template->view('detail_edit', $data);
    }

    private function createBranch(&$parents, $children) {
        $tree = array();
        foreach ($children as $child) {
            if (isset($parents[$child['category_id']])) {
                $child['children'] = $this->createBranch($parents, $parents[$child['category_id']]);
            }
            $tree[$child['category_id']] = $child;
        }
        return $tree;
    }

    private function createTree($flat = null, $root = 0) {
        if (!$result = $this->tppymemcached->get('content_menu_tree')) {
            if ($flat == null) {
                $flat = $this->content_detail->menu_detail(); //$this->db->query("SELECT * FROM cmsblog_category")->result_array();
            }
            $parents = array();
            foreach ($flat as $a) {
                $parents[$a['category_parent_id']][$a['category_id']] = $a;
            }
            $result = $this->createBranch($parents, $parents[$root]);
            $this->tppymemcached->set('content_menu_tree', $result);
        }
        return $result;
    }

    private function printTree($tree, $r = 0, $p = null, $pn = '') {
        $result = "";
        foreach ($tree as $i => $t) {
            $pnx = $t['category_parent_id'] == 0 ? '' : $pn . ' &gt; ';
            $result .= '<option value="' . $t['category_id'] . '">' . $pnx . $t['category_name_th'] . '</option>';
            if ($t['category_parent_id'] == $p) {
                $r = 0;
            }
            if (isset($t['children'])) {
                $result .=$this->printTree($t['children'], ++$r, $t['category_parent_id'], $pnx . $t['category_name_th']);
            }
        }
        return $result;
    }

    function createPath($id, $except = null) {
        $row = $this->cmsblogmodel->category_detail($id);
        if ($row) {
            $name = $row['category_name_th'];
            $link = "/knowledge/{$row['category_name_code']}";

            if ($row['category_parent_id'] == 0) {
                $this->tppymemcached->delete("knowledge_breadcrumbs_$id");
                if (!$result = $this->tppymemcached->get("knowledge_breadcrumbs_$id")) {
                    $result = "";
                    // $result.="<a href='/home'>Home</a> &gt; ";
                    $result.="<a href='/knowledge'>Plook Knowledge</a> &gt; <a href='$link'>" . $name . "</a> &gt;";
                    $this->tppymemcached->set("knowledge_breadcrumbs_$id", $result);
                }
                return $result;
            } else {
                if (!empty($except) && $except == $name) {
                    return $this->createPath($row['category_parent_id'], $except) . " " . $name;
                }
            }
            return $this->createPath($row['category_parent_id'], $except) . " $name";
        } else {
            return null;
        }
    }
    
}
