<?php
class Cachedb_model extends CI_Model{
public function __construct(){
		parent::__construct();
  $this->load->driver('cache');  
	}
    /**
     * CI Singleton
     *
     * @var object
     */
    public $CI;

    /**
     * Database object
     *
     * Allows passing of DB object so that multiple database connections
     * and returned DB objects can be supported.
     *
     * @var object
     */
    public $db;

    /**
     * Constructor
     *
     * @param   object  &$db
     * @return  void
     */
    public function __construct(&$db){
        // Assign the main CI object to $this->CI and load the file helper since we use it a lot
        $this->CI = & get_instance();
        $this->db = & $db;
        $this->CI->load->helper('file');

        $this->check_path();
    }
    /**
     * Set Cache Directory Path
     *
     * @param   string  $path   Path to the cache directory
     * @return  bool
     */
    public function check_path($path = ''){
        if ($path === '') {
            if ($this->db->cachedir === '') {
                return $this->db->cache_off();
            }

            $path = $this->db->cachedir;
        }

        // Add a trailing slash to the path if needed
        $path = realpath($path) ? rtrim(realpath($path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : rtrim($path, '/') . '/';

        if (!is_dir($path)) {
            log_message('debug', 'DB cache path error: ' . $path);

            // If the path is wrong we'll turn off caching
            return $this->db->cache_off();
        }

        if (!is_really_writable($path)) {
            log_message('debug', 'DB cache dir not writable: ' . $path);

            // If the path is not really writable we'll turn off caching
            return $this->db->cache_off();
        }

        $this->db->cachedir = $path;
        return TRUE;
    }

    /**
     * Gets table name from SQL statement
     * 
     * @param SQL statement $sql
     * @return string or null
     */
    private function get_table_name($sql){
        $pattern = "/FROM `(.*?)`/";
        preg_match($pattern, $sql, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * Returns the strtotime equivalent of the last_modified field
     * for a given table
     * 
     * @param string $table
     * @return boolean || int
     */
    private function get_last_modified($table){
        $res = $this->db->simple_query("SELECT `last_modified` FROM `master_table_modified` WHERE `table_name` = '{$table}'");
        if ($res !== true && $res->num_rows !== 1) {
            return false;
        }
        return strtotime($res->fetch_row()[0]);
    }

    /**
     * Retrieve a cached query
     * 
     * Cache sub-folder is the name of the table
     *
     * @param   string  $sql
     * @return  string
     */
    public function read($sql){
        $table = $this->get_table_name($sql);
        if (is_null($table)) {
            return false;
        }
        $filepath = $this->db->cachedir . $table . DS . md5($sql);
        $table_last_modified = $this->get_last_modified($table);
        if ($table_last_modified === FALSE) {
            return false;
        }
        if (!is_file($filepath)) {
            return false;
        }
        // check table last modified against file modified time
        if ($table_last_modified > filemtime($filepath)) {
            @unlink($filepath);
            return false;
        }
        if (FALSE === ($cachedata = file_get_contents($filepath))) {
            return false;
        }
        return unserialize($cachedata);
    }
    // --------------------------------------------------------------------

    /**
     * Write a query to a cache file
     *
     * @param   string  $sql
     * @param   object  $object
     * @return  bool
     */
    public function write($sql, $object){
        $table = $this->get_table_name($sql);
        if (is_null($table)) {
            return false;
        }
        $dir_path = $this->db->cachedir . $table . DS;
        $filename = md5($sql);
        if (!is_dir($dir_path) && !@mkdir($dir_path, 0750)) {
            return FALSE;
        }
        if (write_file($dir_path . $filename, serialize($object)) === FALSE) {
            return FALSE;
        }
        chmod($dir_path . $filename, 0640);
        return TRUE;
    }
    // --------------------------------------------------------------------

    /**
     * Delete cache files within a particular directory
     * 
     * @depreciated
     */
    public function delete($segment_one = '', $segment_two = ''){
        return;
    }
    // --------------------------------------------------------------------

    /**
     * Delete all existing cache files
     *
     * @return  void
     */
    public function delete_all(){
        delete_files($this->db->cachedir, TRUE, TRUE);
    }
}
 