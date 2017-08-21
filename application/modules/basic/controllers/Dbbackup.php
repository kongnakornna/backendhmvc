<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Dbbackup extends CI_Controller {

    public function __construct()    {
        parent::__construct();

        if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }        
    }
		 public function index(){
					$this->db_backup();
			}
		 function db_backup(){
			  $this->load->dbutil();   
			   $backup =& $this->dbutil->backup();  
			   $this->load->helper('file');
			   write_file('<?php echo base_url();?>/backup', $backup);
			   $this->load->helper('download');
		force_download('mysqlbackup.gz', $backup);
			 }

		function backup()
			 {
			 $this->load->helper('download');
			 $this->load->library('zip'); 
			  $time = time(); 
		$this->zip->read_dir('/backup/');

		$this->zip->download('sql_backup.'.$time.'.zip');
		}
}