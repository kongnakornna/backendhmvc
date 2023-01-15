<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';
/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Api extends REST_Controller {
function __construct(){
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
public function index_get(){
	 $this->info_get();
 }
public function info_get(){
//Load library
$this->load->library('Memcached_library');
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$module_name='info';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$list= [['id' => 1, 'Cachefile' => base_url('/api/cache/cachedb?format=json'), 'Type' => 'CacheDB', 'Cache' => 'DB Cache'],
['id' => 2, 'Cachefile' => base_url('/api/cache/cachefile?u_id=73&format=json'), 'Type' => 'Cache', 'Cache' => 'DB & file'],
['id' => 3, 'Memcache' => base_url('/api/cache/memcachetestliberery?limit=10&deletekey='),'Type' => 'Memcache', 'Cache' => 'DB & Memory'],
];
$listapi=array('examination'=>base_url('/api/examination/'),
               'cache'=>base_url('/api/cache/'),
               );
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion,'listapi'=>$listapi,'list'=>$list);
if($cacheinfo!==''){$this->response(array('header'=>array(
										'title'=>'REST_Controller::HTTP_OK',
                                                  'module'=>$module_name,
                                                  'listapi'=>$listapi,
										'message'=>' DATA OK',
										'status'=>TRUE,
										'code'=>200), 
										'data'=> $cacheinfo),200);
}elseif($cacheinfo==''){$this->response(array('header'=>array(
										'title'=>'HTTP_BAD_REQUEST',
                                                  'module'=>$module_name,
                                                  'listapi'=>$listapi,
										'message'=>'Data could not be found',
										'status'=>FALSE, 
										'code'=>204), 
										'data'=> $cacheinfo),204);
}else{$this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
}
die();
}
public function user_get(){
        // Users from a data store e.g. database
        $users = [
            ['id' => 1, 'Cachefile' => base_url('/api/cache/cachedb?format=json'), 'Type' => 'CacheDB', 'Cache' => 'DB Cache'],
			['id' => 2, 'Cachefile' => base_url('/api/cache/cachefile?u_id=73&format=json'), 'Type' => 'Cache', 'Cache' => 'DB & file'],
			
			['id' => 3, 'Memcache' => base_url('/api/cache/memcachetestliberery?limit=10&deletekey='),'Type' => 'Memcache', 'Cache' => 'DB & Memory'],
        	
        ];
 
        $id = $this->get('id');

        // If the id parameter doesn't exist return all the users

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($users)
            {
                // Set the response and exit
                $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.

        $id = (int) $id;

        // Validate the id.
        if ($id <= 0)
        {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // Get the user from the array, using the id as key for retrieval.
        // Usually a model is to be used for this.

        $user = NULL;

        if (!empty($users))
        {
            foreach ($users as $key => $value)
            {
                if (isset($value['id']) && $value['id'] === $id)
                {
                    $user = $value;
                }
            }
        }

        if (!empty($user))
        {
            $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
public function users_get(){
        // Users from a data store e.g. database
        $users = [
            ['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'],
            ['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
            ['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
        ];

        $id = $this->get('id');

        // If the id parameter doesn't exist return all the users

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($users)
            {
                // Set the response and exit
                $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.

        $id = (int) $id;

        // Validate the id.
        if ($id <= 0)
        {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // Get the user from the array, using the id as key for retrieval.
        // Usually a model is to be used for this.

        $user = NULL;

        if (!empty($users))
        {
            foreach ($users as $key => $value)
            {
                if (isset($value['id']) && $value['id'] === $id)
                {
                    $user = $value;
                }
            }
        }

        if (!empty($user))
        {
            $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
public function users_post(){
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }
public function users_delete(){
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }
}