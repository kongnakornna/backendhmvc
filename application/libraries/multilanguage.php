<?php
/**
 * MultiLanguage
 *
 *	Adding real multilanguage feature
 *
 * @author		Simon Martin
 */
 class Multilanguage {

    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->library('session');

        $this->reload();
    }
	

    function reload()
    {
    	//resolving var autosetting to 'assets' I dont know the reason
        if($this->ci->session->userdata('language') == 'assets')
            $this->ci->session->set_userdata('language', $this->ci->config->item('language'));
            
        if($this->ci->session->userdata('language') != null){
            $this->ci->lang->reload('app', $this->ci->session->userdata('language'));
        }
    }
}

/*
class Multilanguage {

    function __construct(){
        $this->ci=& get_instance();
        $this->ci->load->library('session');
        $this->reload();
    }
	

    function reload(){
    	
    	
    	//resolving var autosetting to 'assets' I dont know the reason
		$language_session=$this->ci->session->userdata('language');
		echo '<pre> language_session=>'; print_r($language_session); echo '</pre>';
		$ci=& get_instance();
		$is_loaded=$ci->lang->is_loaded;
		//echo '<pre> $is_loaded=>'; print_r($is_loaded); echo '</pre>';
        //$language2=$ci->load->helper('language');
        #echo '<pre> $language2=>'; print_r($language2); echo '</pre>';
		 
        if($this->ci->session->userdata('language') == 'assets')
            $this->ci->session->set_userdata('language', $this->ci->config->item('language'));
            $this->ci->session->set_userdata('adminlang', $this->ci->config->item('language'));
            
        if($this->ci->session->userdata('language') != null){
            //$this->ci->lang->reload('app', $this->ci->session->userdata('language'));
			 # $this->ci->lang->reload('app', $language_session);
        }
    }
}
*/