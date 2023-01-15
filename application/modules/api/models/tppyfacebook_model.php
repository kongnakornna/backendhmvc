<?php if(!defined('BASEPATH'))exit('No direct script access allowed');
class Tppyfacebook_model extends CI_Model {
    function __construct(){
        parent::__construct();
         $this->load->helper(array('form', 'url'));
         //Load codeigniter FTP class
          $this->load->library('ftp');

    } 
    ///// Fackboook SDK 5.5 V.2017-06 Start
    
    public function facebookcallback(){
		ob_start();
		@session_start();
		// Include the required dependencies.
		 require(APPPATH.'third_party/Facebook/autoload.php');
		 

	    // Initialize the Facebook PHP SDK v5.
	    // Config facebook
	    $facebook_config=$this->config->config['facebook'];
	    $app_id=$facebook_config['api_id'];
	    $app_secret=$facebook_config['app_secret'];
	    $app_version=$facebook_config['version'];
	    $redirect_url=$facebook_config['redirect_url'];
	    $redirect_url_login=$facebook_config['redirect_url_login'];
	    $app_permissions=$facebook_config['permissions'];
	    // Automatically picks appId and secret from config
		$fb = new Facebook\Facebook([
		  'app_id'                => $app_id,
		  'app_secret'            => $app_secret,
		  'default_graph_version' => $app_version,
		]);
        //echo '<pre> $fb=>'; print_r($fb); echo '</pre>';Die();

		// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
		//   $helper = $fb->getRedirectLoginHelper();
		 $helper1 = $fb->getJavaScriptHelper();
		 //echo '<pre> $helper1=>'; print_r($helper1); echo '</pre>';
		 $helper2 = $fb->getCanvasHelper();
		 //echo '<pre> $helper2=>'; print_r($helper2); echo '</pre>';
		 $helper3 = $fb->getPageTabHelper();
		 //echo '<pre> $helper3=>'; print_r($helper3); echo '</pre>';
         $helper = $fb->getRedirectLoginHelper();  
         //echo '<pre> $helper=>'; print_r($helper); echo '</pre>';Die(); 
         $sr = Null;      
        try {  
            
            $accessToken = $helper->getAccessToken();  
            #echo '<pre> $accessToken=>'; print_r($accessToken); echo '</pre>';Die(); 
        }catch(Facebook\Exceptions\FacebookResponseException $e) {  
          // When Graph returns an error  
            $getMessage='Graph returned an error: ' . $e->getMessage();  
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {  
          // When validation fails or other local issues  
            $getMessage='Facebook SDK returned an error: ' . $e->getMessage();   
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();   
        }  
 
 
        try {
          // Get the Facebook\GraphNodes\GraphUser object for the current user.
          // If you provided a 'default_access_token', the '{access-token}' is optional.
          $response = $fb->get('/me?fields=id,name,email,first_name,last_name,birthday,location,gender,verified', $accessToken);
          $response0 = $fb->get('/me',$accessToken);
          $response1 = $fb->get('/me?fields=id,name,email,verified',$accessToken);
          
          
         // print_r($response);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
            $getMessage='ERROR: Graph ' . $e->getMessage();  
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
            $getMessage='ERROR: validation fails ' . $e->getMessage();
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        }
    
        // User Information Retrival begins................................................
        $me=$response->getGraphUser();
        $UserId=null;
        $location=$me->getProperty('location');
        $name=$me->getProperty('name');
        $first_name=$me->getProperty('first_name');
        $last_name=$me->getProperty('last_name');
        $gender=$me->getProperty('gender');
        $email=$me->getProperty('email');
        $locationname=$location['name'];
        $birthday=$me->getProperty('birthday')->format('d/m/Y')."<br>";
        $fid=$me->getProperty('id');
        $profileid = $me->getProperty('id');
        $profileid= $profileid;
        $accessToken=$accessToken; 
        $data=array('me' => $me,
		    		'location' => $location,
	            	'name' => $name,
                    'first_name' => $first_name,
                    'gender' => $gender,
                    'email' => $email,
                    'locationname' => $locationname,
                    'birthday' => $birthday,
                    'fid' => $fid,
                    'profileid' => $profileid,
                    'accessToken' =>  $accessToken,
                    'fb'=>$fb,
                    'sr'=>$sr,
                    'response1'=>$response1,
		 );
          
             $getMessage='Ready ';
             $dataretrun= array('massage' => $getMessage,
                                'status' => 200,
                                'data' => $data,
                                'me'=>$me,
                                'fb'=>$fb,
		 );
				
	     return $dataretrun;  Die(); 
       
    }
    public function facebookcallback2016(){
		ob_start();
		@session_start();
		// Include the required dependencies.
		 require(APPPATH.'third_party/Facebook/autoload.php');
		 
	    // Initialize the Facebook PHP SDK v5.
	    // Config facebook
	    $facebook_config=$this->config->config['facebook'];
	    $app_id=$facebook_config['api_idv1'];
	    $app_secret=$facebook_config['app_secretv1'];
	    $app_version=$facebook_config['versionv1'];
	    $redirect_url=$facebook_config['redirect_urlv1'];
	    $redirect_url_login=$facebook_config['redirect_url_loginv1'];
	    $app_permissions=$facebook_config['permissionsv1'];
	    // Automatically picks appId and secret from config
		$fb = new Facebook\Facebook([
		  'app_id'                => $app_id,
		  'app_secret'            => $app_secret,
		  'default_graph_version' => $app_version,
		]);
         #echo '<pre> $fb=>'; print_r($fb); echo '</pre>';Die();

		// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
		//   $helper = $fb->getRedirectLoginHelper();
		 $helper1 = $fb->getJavaScriptHelper();
		 //echo '<pre> $helper1=>'; print_r($helper1); echo '</pre>';
		 $helper2 = $fb->getCanvasHelper();
		 //echo '<pre> $helper2=>'; print_r($helper2); echo '</pre>';
		 $helper3 = $fb->getPageTabHelper();
		 //echo '<pre> $helper3=>'; print_r($helper3); echo '</pre>';
         $helper = $fb->getRedirectLoginHelper();  
         //echo '<pre> $helper=>'; print_r($helper); echo '</pre>';Die(); 
         $sr = Null;      
        try {  
            
            $accessToken = $helper->getAccessToken();  
            #echo '<pre> $accessToken=>'; print_r($accessToken); echo '</pre>';Die(); 
        }catch(Facebook\Exceptions\FacebookResponseException $e) {  
          // When Graph returns an error  
            $getMessage='Graph returned an error: ' . $e->getMessage();  
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {  
          // When validation fails or other local issues  
            $getMessage='Facebook SDK returned an error: ' . $e->getMessage();   
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();   
        }  
 
 
        try {
          // Get the Facebook\GraphNodes\GraphUser object for the current user.
          // If you provided a 'default_access_token', the '{access-token}' is optional.
          $response = $fb->get('/me?fields=id,name,email,first_name,last_name,birthday,location,gender,verified', $accessToken);
          $response0 = $fb->get('/me',$accessToken);
          $response1 = $fb->get('/me?fields=id,name,email,verified',$accessToken);
          
          
         // print_r($response);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
            $getMessage='ERROR: Graph ' . $e->getMessage();  
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
            $getMessage='ERROR: validation fails ' . $e->getMessage();
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        }
    
        // User Information Retrival begins................................................
        $me=$response->getGraphUser();
        $UserId=null;
        $location=$me->getProperty('location');
        $name=$me->getProperty('name');
        $first_name=$me->getProperty('first_name');
        $last_name=$me->getProperty('last_name');
        $gender=$me->getProperty('gender');
        $email=$me->getProperty('email');
        $locationname=$location['name'];
        $birthday=$me->getProperty('birthday')->format('d/m/Y')."<br>";
        $fid=$me->getProperty('id');
        $profileid = $me->getProperty('id');
        $profileid= $profileid;
        $accessToken=$accessToken; 
        $data=array('me' => $me,
		    		'location' => $location,
	            	'name' => $name,
                    'first_name' => $first_name,
                    'gender' => $gender,
                    'email' => $email,
                    'locationname' => $locationname,
                    'birthday' => $birthday,
                    'fid' => $fid,
                    'profileid' => $profileid,
                    'accessToken' =>  $accessToken,
                    'fb'=>$fb,
                    'sr'=>$sr,
                    'response1'=>$response1,
		 );
          
             $getMessage='Ready ';
             $dataretrun= array('massage' => $getMessage,
                                'status' => 200,
                                'data' => $data,
                                'me'=>$me,
                                'fb'=>$fb,
		 );
				
	     return $dataretrun;  Die(); 
       
    }
    public function fblogin2016(){
	   // Pass session data over.
		ob_start();
		@session_start();
		// Include the required dependencies.
		//require( __DIR__.'/third_party/Facebook/autoload.php' );
	     require(APPPATH.'third_party/Facebook/autoload.php');
		 
	    // Initialize the Facebook PHP SDK v5.
	    // Config facebook
	    $facebook_config=$this->config->config['facebook'];
	    $app_id=$facebook_config['api_idv1'];
	    $app_secret=$facebook_config['app_secretv1'];
	    $app_version=$facebook_config['versionv1'];
	    $redirect_url=$facebook_config['redirect_urlv1'];
	    $redirect_url_login=$facebook_config['redirect_url_loginv1'];
	    $app_permissions=$facebook_config['permissionsv1'];
	    // Automatically picks appId and secret from config
		$fb = new Facebook\Facebook([
		  'app_id'                => $app_id,
		  'app_secret'            => $app_secret,
		  'default_graph_version' => $app_version,
		]);
	    #echo '<pre> $facebook_config=>'; print_r($facebook_config); echo '</pre>';
	    #echo '<pre> $app_permissions=>'; print_r($app_permissions); echo '</pre>';
	    #echo '<pre> $fb=>'; print_r($fb); echo '</pre>'; //die();
	    $helper = $fb->getRedirectLoginHelper();
		$permissions = $app_permissions; // optional
		$callback    = $redirect_url_login;
		$loginUrl    = $helper->getLoginUrl($callback, $permissions);
		$linkurl=$loginUrl;
		$link='<a href="' . $loginUrl . '">Log in with Facebook!</a>';  
		$dataretrun= array('massage' => 'facebooklogin',
                           'status' => 200,
                           'callback' => $callback,
                           'loginUrl'=>$loginUrl,
                           'permissions'=>$permissions,
                           'linkurl'=>$linkurl,
                           'link'=>$link,
                           );
	     return $dataretrun;  Die();  
	} 
    public function fblogin2017(){
	   // Pass session data over.
		ob_start();
		@session_start();
		// Include the required dependencies.
		//require( __DIR__.'/third_party/Facebook/autoload.php' );
	    require(APPPATH.'third_party/Facebook/autoload.php');
	    // Initialize the Facebook PHP SDK v5.
	    $facebook_config=$this->config->config['facebook'];
	    $app_id=$facebook_config['api_id'];
	    $app_secret=$facebook_config['app_secret'];
	    $app_version=$facebook_config['version'];
	    $redirect_url=$facebook_config['redirect_url'];
	    $redirect_url_login=$facebook_config['redirect_url_login'];
	    $app_permissions=$facebook_config['permissions'];
	    
		$fb = new Facebook\Facebook([
		  'app_id'                => $app_id,
		  'app_secret'            => $app_secret,
		  'default_graph_version' => $app_version,
		]);
	    #echo '<pre> $facebook_config=>'; print_r($facebook_config); echo '</pre>';
	    #echo '<pre> $app_permissions=>'; print_r($app_permissions); echo '</pre>';
	    #echo '<pre> $fb=>'; print_r($fb); echo '</pre>'; //die();
	    $helper = $fb->getRedirectLoginHelper();
		$permissions = $app_permissions; // optional
		$callback    = $redirect_url_login;
		$loginUrl    = $helper->getLoginUrl($callback, $permissions);
		$linkurl=$loginUrl;
		$link='<a href="' . $loginUrl . '">Log in with Facebook!</a>';  
		$dataretrun= array('massage' => 'facebooklogin',
                           'status' => 200,
                           'callback' => $callback,
                           'loginUrl'=>$loginUrl,
                           'permissions'=>$permissions,
                           'linkurl'=>$linkurl,
                           'link'=>$link,
                           );
	     return $dataretrun;  Die();  
	} 
    public function facebookcallback2017a(){
		ob_start();
		@session_start();
		// Include the required dependencies.
		 require(APPPATH.'third_party/Facebook/autoload.php');
	    // Initialize the Facebook PHP SDK v5.
	    // Config facebook
	    $facebook_config=$this->config->config['facebook'];
	    $app_id=$facebook_config['api_idv1'];
	    $app_secret=$facebook_config['app_secretv1'];
	    $app_version=$facebook_config['versionv1'];
	    $redirect_url=$facebook_config['redirect_urlv1'];
	    $redirect_url_login=$facebook_config['redirect_url_loginv1'];
	    $redirect_url_login_test=$facebook_config['redirect_url_login_testv1'];
	    $app_permissions=$facebook_config['permissionsv1'];
	    // Automatically picks appId and secret from config
		$fb = new Facebook\Facebook([
		  'app_id'                => $app_id,
		  'app_secret'            => $app_secret,
		  'default_graph_version' => $app_version,
		]);
        //echo '<pre> $fb=>'; print_r($fb); echo '</pre>';Die();
		// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
		//   $helper = $fb->getRedirectLoginHelper();
		 $helper1 = $fb->getJavaScriptHelper();
		 //echo '<pre> $helper1=>'; print_r($helper1); echo '</pre>';
		 $helper2 = $fb->getCanvasHelper();
		 //echo '<pre> $helper2=>'; print_r($helper2); echo '</pre>';
		 $helper3 = $fb->getPageTabHelper();
		 //echo '<pre> $helper3=>'; print_r($helper3); echo '</pre>';
         $helper = $fb->getRedirectLoginHelper();  
         //echo '<pre> $helper=>'; print_r($helper); echo '</pre>';Die(); 
         $sr = Null;      
        try {  
            
            $accessToken = $helper->getAccessToken();  
            #echo '<pre> $accessToken=>'; print_r($accessToken); echo '</pre>';Die(); 
        }catch(Facebook\Exceptions\FacebookResponseException $e) {  
          // When Graph returns an error  
            $getMessage='Graph returned an error: ' . $e->getMessage();  
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {  
          // When validation fails or other local issues  
            $getMessage='Facebook SDK returned an error: ' . $e->getMessage();   
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  #Die();   
        }  
 
 
        try {
          // Get the Facebook\GraphNodes\GraphUser object for the current user.
          // If you provided a 'default_access_token', the '{access-token}' is optional.
          $response = $fb->get('/me?fields=id,name,email,first_name,last_name,birthday,location,gender,verified', $accessToken);
          $response0 = $fb->get('/me',$accessToken);
          $response1 = $fb->get('/me?fields=id,name,email,verified',$accessToken);
          
          
         // print_r($response);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
            $getMessage='ERROR: Graph ' . $e->getMessage();  
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  #Die();  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
            $getMessage='ERROR: validation fails ' . $e->getMessage();
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  #Die();  
        }
    
        // User Information Retrival begins................................................
        $me=$response->getGraphUser();
        $UserId=null;
        $location=$me->getProperty('location');
        $name=$me->getProperty('name');
        $first_name=$me->getProperty('first_name');
        $last_name=$me->getProperty('last_name');
        $gender=$me->getProperty('gender');
        $email=$me->getProperty('email');
        $locationname=$location['name'];
        $birthday=$me->getProperty('birthday')->format('d/m/Y')."<br>";
        $fid=$me->getProperty('id');
        $profileid = $me->getProperty('id');
        $profileid= $profileid;
        $accessToken=$accessToken; 
        $data=array('me' => $me,
		    		'location' => $location,
	            	'name' => $name,
                    'first_name' => $first_name,
                    'gender' => $gender,
                    'email' => $email,
                    'locationname' => $locationname,
                    'birthday' => $birthday,
                    'fid' => $fid,
                    'profileid' => $profileid,
                    'accessToken' =>  $accessToken,
                    'fb'=>$fb,
                    'sr'=>$sr,
                    'response1'=>$response1,
		 );
          
             $getMessage='Ready ';
             $dataretrun= array('massage' => $getMessage,
                                'status' => 200,
                                'data' => $data,
                                'me'=>$me,
                                'fb'=>$fb,
		 );
				
	     return $dataretrun;  #Die(); 
       
    } 
    public function fblogin2017a(){
	   // Pass session data over.
		ob_start();
		@session_start();
		// Include the required dependencies.
		//require( __DIR__.'/third_party/Facebook/autoload.php' );
	    require(APPPATH.'third_party/Facebook/autoload.php');
	    // Initialize the Facebook PHP SDK v5.
	    $facebook_config=$this->config->config['facebook'];
	    $app_id=$facebook_config['api_idv1'];
	    $app_secret=$facebook_config['app_secretv1'];
	    $app_version=$facebook_config['versionv1'];
	    $redirect_url=$facebook_config['redirect_urlv1'];
	    $redirect_url_login=$facebook_config['redirect_url_loginv1'];
	    $redirect_url_login_test=$facebook_config['redirect_url_login_testv1'];
	    $app_permissions=$facebook_config['permissionsv1'];
	    
		$fb = new Facebook\Facebook([
		  'app_id'                => $app_id,
		  'app_secret'            => $app_secret,
		  'default_graph_version' => $app_version,
		]);
	  #echo '<pre> $facebook_config=>'; print_r($facebook_config); echo '</pre>';
	  #echo '<pre> $app_permissions=>'; print_r($app_permissions); echo '</pre>';
	  #echo '<pre> $fb=>'; print_r($fb); echo '</pre>'; //die();
	    $helper = $fb->getRedirectLoginHelper();
		$permissions = $app_permissions; // optional
		$callback    = $redirect_url_login_test;
		$loginUrl    = $helper->getLoginUrl($callback, $permissions);
		$linkurl=$loginUrl;
		$link='<a href="' . $loginUrl . '">Log in with Facebook!</a>';  
		$dataretrun= array('massage' => 'facebooklogin',
                           'status' => 200,
                           'callback' => $callback,
                           'loginUrl'=>$loginUrl,
                           'permissions'=>$permissions,
                           'linkurl'=>$linkurl,
                           'link'=>$link,
                           );
	     return $dataretrun;  Die();  
	} 
// App TrueplookpanyaQuiz
	public function facebookcallbackquiz(){
		ob_start();
		@session_start();
		// Include the required dependencies.
		 require(APPPATH.'third_party/Facebook/autoload.php');
		 

	    // Initialize the Facebook PHP SDK v5.
	    // Config facebook
	    $facebook_config=$this->config->config['facebook'];
	    $app_id=$facebook_config['api_id_quiz'];
	    $app_secret=$facebook_config['app_secret_quiz'];
	    $app_version=$facebook_config['version_quiz'];
	    $redirect_url=$facebook_config['redirect_url_quiz'];
	    $redirect_url_login=$facebook_config['redirect_url_login_quiz'];
	    $app_permissions=$facebook_config['permissions_quiz'];
	    // Automatically picks appId and secret from config
		$fb = new Facebook\Facebook([
		  'app_id'                => $app_id,
		  'app_secret'            => $app_secret,
		  'default_graph_version' => $app_version,
		]);
        //echo '<pre> $fb=>'; print_r($fb); echo '</pre>';Die();

		// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
		//   $helper = $fb->getRedirectLoginHelper();
		 $helper1 = $fb->getJavaScriptHelper();
		 //echo '<pre> $helper1=>'; print_r($helper1); echo '</pre>';
		 $helper2 = $fb->getCanvasHelper();
		 //echo '<pre> $helper2=>'; print_r($helper2); echo '</pre>';
		 $helper3 = $fb->getPageTabHelper();
		 //echo '<pre> $helper3=>'; print_r($helper3); echo '</pre>';
         $helper = $fb->getRedirectLoginHelper();  
         //echo '<pre> $helper=>'; print_r($helper); echo '</pre>';Die(); 
         $sr = Null;      
        try {  
            
            $accessToken = $helper->getAccessToken();  
            #echo '<pre> $accessToken=>'; print_r($accessToken); echo '</pre>';Die(); 
        }catch(Facebook\Exceptions\FacebookResponseException $e) {  
          // When Graph returns an error  
            $getMessage='Graph returned an error: ' . $e->getMessage();  
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {  
          // When validation fails or other local issues  
            $getMessage='Facebook SDK returned an error: ' . $e->getMessage();   
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();   
        }  
 
 
        try {
          // Get the Facebook\GraphNodes\GraphUser object for the current user.
          // If you provided a 'default_access_token', the '{access-token}' is optional.
          $response = $fb->get('/me?fields=id,name,email,first_name,last_name,birthday,location,gender,verified', $accessToken);
          $response0 = $fb->get('/me',$accessToken);
          $response1 = $fb->get('/me?fields=id,name,email,verified',$accessToken);
          
          
         // print_r($response);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
            $getMessage='ERROR: Graph ' . $e->getMessage();  
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
            $getMessage='ERROR: validation fails ' . $e->getMessage();
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        }
    
        // User Information Retrival begins................................................
        $me=$response->getGraphUser();
        $UserId=null;
        $location=$me->getProperty('location');
        $name=$me->getProperty('name');
        $first_name=$me->getProperty('first_name');
        $last_name=$me->getProperty('last_name');
        $gender=$me->getProperty('gender');
        $email=$me->getProperty('email');
        $locationname=$location['name'];
        $birthday=$me->getProperty('birthday')->format('d/m/Y')."<br>";
        $fid=$me->getProperty('id');
        $profileid = $me->getProperty('id');
        $profileid= $profileid;
        $accessToken=$accessToken; 
        $data=array('me' => $me,
		    		'location' => $location,
	            	'name' => $name,
                    'first_name' => $first_name,
                    'gender' => $gender,
                    'email' => $email,
                    'locationname' => $locationname,
                    'birthday' => $birthday,
                    'fid' => $fid,
                    'profileid' => $profileid,
                    'accessToken' =>  $accessToken,
                    'fb'=>$fb,
                    'sr'=>$sr,
                    'response1'=>$response1,
		 );
          
             $getMessage='Ready ';
             $dataretrun= array('massage' => $getMessage,
                                'status' => 200,
                                'data' => $data,
                                'me'=>$me,
                                'fb'=>$fb,
		 );
				
	     return $dataretrun;  Die(); 
       
    }
    public function fbloginquiz(){
	   // Pass session data over.
		ob_start();
		@session_start();
		// Include the required dependencies.
		//require( __DIR__.'/third_party/Facebook/autoload.php' );
	    require(APPPATH.'third_party/Facebook/autoload.php');
	    // Initialize the Facebook PHP SDK v5.
	    $facebook_config=$this->config->config['facebook'];
	    $app_id=$facebook_config['api_id_quiz'];
	    $app_secret=$facebook_config['app_secret_quiz'];
	    $app_version=$facebook_config['version_quiz'];
	    $redirect_url=$facebook_config['redirect_url_quiz'];
	    $redirect_url_login=$facebook_config['redirect_url_login_quiz'];
	    $app_permissions=$facebook_config['permissions_quiz'];
	    
		$fb = new Facebook\Facebook([
		  'app_id'                => $app_id,
		  'app_secret'            => $app_secret,
		  'default_graph_version' => $app_version,
		]);
	    #echo '<pre> $facebook_config=>'; print_r($facebook_config); echo '</pre>';
	    #echo '<pre> $app_permissions=>'; print_r($app_permissions); echo '</pre>';
	    #echo '<pre> $fb=>'; print_r($fb); echo '</pre>'; //die();
	    $helper = $fb->getRedirectLoginHelper();
		$permissions = $app_permissions; // optional
		$callback    = $redirect_url_login;
		$loginUrl    = $helper->getLoginUrl($callback, $permissions);
		$linkurl=$loginUrl;
		$link='<a href="' . $loginUrl . '">Log in with Facebook!</a>';  
		$dataretrun= array('massage' => 'facebooklogin',
                           'status' => 200,
                           'callback' => $callback,
                           'loginUrl'=>$loginUrl,
                           'permissions'=>$permissions,
                           'linkurl'=>$linkurl,
                           'link'=>$link,
                           );
	     return $dataretrun;  Die();  
	} 
// App Plookplookquiz///////////////
	public function facebookcallbackplookquiz(){
		ob_start();
		@session_start();
		// Include the required dependencies.
		 require(APPPATH.'third_party/Facebook/autoload.php');
		 

	    // Initialize the Facebook PHP SDK v5.
	    // Config facebook
	    $facebook_config=$this->config->config['facebook'];
	    $app_id=$facebook_config['api_id_plookquiz'];
	    $app_secret=$facebook_config['app_secret_plookquiz'];
	    $app_version=$facebook_config['version_plookquiz'];
	    $redirect_url=$facebook_config['redirect_url_plookquiz'];
	    $redirect_url_login=$facebook_config['redirect_url_login_plookquiz'];
	    $app_permissions=$facebook_config['permissions_plookquiz'];
	    // Automatically picks appId and secret from config
		$fb = new Facebook\Facebook([
		  'app_id'                => $app_id,
		  'app_secret'            => $app_secret,
		  'default_graph_version' => $app_version,
		]);
        //echo '<pre> $fb=>'; print_r($fb); echo '</pre>';Die();

		// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
		//   $helper = $fb->getRedirectLoginHelper();
		 $helper1 = $fb->getJavaScriptHelper();
		 //echo '<pre> $helper1=>'; print_r($helper1); echo '</pre>';
		 $helper2 = $fb->getCanvasHelper();
		 //echo '<pre> $helper2=>'; print_r($helper2); echo '</pre>';
		 $helper3 = $fb->getPageTabHelper();
		 //echo '<pre> $helper3=>'; print_r($helper3); echo '</pre>';
         $helper = $fb->getRedirectLoginHelper();  
         //echo '<pre> $helper=>'; print_r($helper); echo '</pre>';Die(); 
         $sr = Null;      
        try {  
            
            $accessToken = $helper->getAccessToken();  
            #echo '<pre> $accessToken=>'; print_r($accessToken); echo '</pre>';Die(); 
        }catch(Facebook\Exceptions\FacebookResponseException $e) {  
          // When Graph returns an error  
            $getMessage='Graph returned an error: ' . $e->getMessage();  
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {  
          // When validation fails or other local issues  
            $getMessage='Facebook SDK returned an error: ' . $e->getMessage();   
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();   
        }  
 
 
        try {
          // Get the Facebook\GraphNodes\GraphUser object for the current user.
          // If you provided a 'default_access_token', the '{access-token}' is optional.
          $response = $fb->get('/me?fields=id,name,email,first_name,last_name,birthday,location,gender,verified', $accessToken);
          $response0 = $fb->get('/me',$accessToken);
          $response1 = $fb->get('/me?fields=id,name,email,verified',$accessToken);
          
          
         // print_r($response);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
            $getMessage='ERROR: Graph ' . $e->getMessage();  
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
            $getMessage='ERROR: validation fails ' . $e->getMessage();
            $dataretrun= array('massage' => $getMessage,
			    'status' => 404,
			    'data' => $fb,
		 );
				
	     return $dataretrun;  Die();  
        }
    
        // User Information Retrival begins................................................
        $me=$response->getGraphUser();
        $UserId=null;
        $location=$me->getProperty('location');
        $name=$me->getProperty('name');
        $first_name=$me->getProperty('first_name');
        $last_name=$me->getProperty('last_name');
        $gender=$me->getProperty('gender');
        $email=$me->getProperty('email');
        $locationname=$location['name'];
        $birthday=$me->getProperty('birthday')->format('d/m/Y')."<br>";
        $fid=$me->getProperty('id');
        $profileid = $me->getProperty('id');
        $profileid= $profileid;
        $accessToken=$accessToken; 
        $data=array('me' => $me,
		    		'location' => $location,
	            	'name' => $name,
                    'first_name' => $first_name,
                    'gender' => $gender,
                    'email' => $email,
                    'locationname' => $locationname,
                    'birthday' => $birthday,
                    'fid' => $fid,
                    'profileid' => $profileid,
                    'accessToken' =>  $accessToken,
                    'fb'=>$fb,
                    'sr'=>$sr,
                    'response1'=>$response1,
		 );
          
             $getMessage='Ready ';
             $dataretrun= array('massage' => $getMessage,
                                'status' => 200,
                                'data' => $data,
                                'me'=>$me,
                                'fb'=>$fb,
		 );
				
	     return $dataretrun;  Die(); 
       
    }
    public function fbloginplookquiz(){
	   // Pass session data over.
		ob_start();
		@session_start();
		// Include the required dependencies.
		//require( __DIR__.'/third_party/Facebook/autoload.php' );
	    require(APPPATH.'third_party/Facebook/autoload.php');
	    // Initialize the Facebook PHP SDK v5.
	    $facebook_config=$this->config->config['facebook'];
	    $app_id=$facebook_config['api_id_plookquiz'];
	    $app_secret=$facebook_config['app_secret_plookquiz'];
	    $app_version=$facebook_config['version_plookquiz'];
	    $redirect_url=$facebook_config['redirect_url_plookquiz'];
	    $redirect_url_login=$facebook_config['redirect_url_login_plookquiz'];
	    $app_permissions=$facebook_config['permissions_plookquiz'];
	    
		$fb = new Facebook\Facebook([
		  'app_id'                => $app_id,
		  'app_secret'            => $app_secret,
		  'default_graph_version' => $app_version,
		]);
	    #echo '<pre> $facebook_config=>'; print_r($facebook_config); echo '</pre>';
	    #echo '<pre> $app_permissions=>'; print_r($app_permissions); echo '</pre>';
	    #echo '<pre> $fb=>'; print_r($fb); echo '</pre>'; //die();
	    $helper = $fb->getRedirectLoginHelper();
		$permissions = $app_permissions; // optional
		$callback    = $redirect_url_login;
		$loginUrl    = $helper->getLoginUrl($callback, $permissions);
		$linkurl=$loginUrl;
		$link='<a href="' . $loginUrl . '">Log in with Facebook!</a>';  
		$dataretrun= array('massage' => 'facebooklogin',
                           'status' => 200,
                           'callback' => $callback,
                           'loginUrl'=>$loginUrl,
                           'permissions'=>$permissions,
                           'linkurl'=>$linkurl,
                           'link'=>$link,
                           );
	     return $dataretrun;  Die();  
	} 
	/////////////////////////////
	
}