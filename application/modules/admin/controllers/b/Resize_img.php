<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Resize_img extends CI_Controller{

    public function __construct (){
        parent::__construct();
        //$this->load->helper('general');
    }

    /**
     * Resize original image into multiple images and display them
     * @return void
     * @author Joost van Veen
     */
	function multi(){
			
			$this->load->library('images');

			$inputdata = $this->input->get();
			$filename = $inputdata['filename'];
			$folder = $inputdata['folder'];
			$type = $inputdata['type'];

			$obj = array();

			//$data['filename'] = 'uploads'.DS.$type.DS.$filename;
			$data['filename'] = $filename;

			$data['newFileName'] = $filename;
			$data['enlargeOnResize'] = FALSE;
			
			//$sourcefile = realpath(FCPATH . 'uploads') . DS . $data['filename'];
			$sourcefile = 'uploads'.DS.$type.DS.$folder.DS.$data['filename'];

			if(!file_exists($sourcefile)){ die('no source file.'); }
			$images = $this->_get_images_config();

			foreach ($images as $image) {
				
					//$destinationfile = FCPATH . 'uploads'. DS . $image['folder'] .DS. $data['newFileName'];
					//$destinationfile = 'uploads'. DS . $image['folder'] .DS. $data['newFileName'];
					$destinationfile = 'uploads'. DS . $image['folder'] .DS. $folder . DS. $data['newFileName'];
					
					echo "<hr>";
					Debug($sourcefile);
					Debug($destinationfile);

					$obj[$image['folder']] = $destinationfile;

					if (isset($image['crop']) && $image['crop'] == TRUE) {

						if($this->images->squareThumb($sourcefile, $destinationfile, $image['width'], $data['enlargeOnResize']))
							echo "Resize complete.<br>";
						else
							echo "Can not resize.";

					}else{
						if($this->images->resize($sourcefile, $destinationfile, $image['width'], $image['height'], $data['enlargeOnResize']))
							echo "Resize complete.<br>";
						else
							echo "Can not resize.";
					}
			}
			
			$data['images'] = $images;
			//$this->load->view('example_resized', $data);	

			echo '<div style="border: 1px solid #ccc; padding: 20px; margin: 10px; width: 720px;">';
			echo '<h1>'.$sourcefile.'</h1>';
			echo '<img src="'. $sourcefile .'" alt="" />';
			echo '</div>';

			foreach ($obj as $image) {

				//echo "$image<br>";
				echo '<div style="border: 1px solid #ccc; padding: 20px; margin: 10px; width: 720px;">';
				//echo '<h1>uploads'.DS.$image['folder'] .DS. $folder . DS.$data['newFileName'].'</h1>';
				//echo '<img src="uploads'.DS.$image['folder'] .DS. $folder . DS.$data['newFileName'].'" alt="" />';
				echo '<h1>'.$image.'</h1>';
				echo '<img src="'.$image.'" alt="" />';
				echo '</div>';
			}
	}

    /**
     * Resize original image into a smaller version, with the same aspect ratio, and display it.
     * @return void
     * @author Joost van Veen
     */
    public function resize_single ()
    {
        $data['filename'] = 'rooney.jpg';
        $data['newFileName'] = 'resized_single.jpg';
        $data['newWidth'] = 180;
        $data['newHeight'] = 180;
        $data['enlargeOnResize'] = FALSE;
        $sourcefile = realpath(FCPATH . 'gfx') . DIRECTORY_SEPARATOR . $data['filename'];
        $destinationfile = FCPATH . 'gfx/' . $data['newFileName'];
        
        $this->load->library('images');
        $this->images->resize($sourcefile, $destinationfile, $data['newWidth'], $data['newHeight'], $data['enlargeOnResize']);
        
        $this->load->view('example_resize_single', $data);
    }

    /**
     * Resize original image into a smaller, square version, and display it.
     * @return void
     * @author Joost van Veen
     */
    public function resize_single_square ()
    {
        $data['filename'] = 'rooney.jpg';
        $data['newFileName'] = 'resized_single_square.jpg';
        $data['newWidth'] = 200;
        $data['newHeight'] = 200;
        $data['enlargeOnResize'] = FALSE;
        $sourcefile = realpath(FCPATH . 'gfx') . DIRECTORY_SEPARATOR . $data['filename'];
        $destinationfile = FCPATH . 'gfx/' . $data['newFileName'];
        
        $this->load->library('images');
        $this->images->squareThumb($sourcefile, $destinationfile, $data['newWidth'], $data['enlargeOnResize']);
        
        $this->load->view('example_resize_single', $data);
    }

    /**
     * Return a config array for nmultiple image sizes and cropping settings
     * @return array - The config array
     * @author Joost van Veen
     */
	private function _get_images_config () {
        // Resize & preserve aspect ratio
        $i = 0;
        $images[$i]['width'] = 300;
        $images[$i]['height'] = 169;
        $images[$i]['crop'] = FALSE;
        $images[$i]['prefix'] = 'thumbs_';
        $images[$i]['folder'] = 'thumbs';
        
        // Resize & crop; square from the center
        $i ++;
        $images[$i]['width'] = 120;
        $images[$i]['height'] = 120;
        $images[$i]['crop'] = TRUE;
        $images[$i]['prefix'] = 'size120_';
        $images[$i]['folder'] = 'size120';
        
        // Resize & crop; square from the center
        $i ++;
        $images[$i]['width'] = 305;
        $images[$i]['height'] = 305;
        $images[$i]['crop'] = TRUE;
        $images[$i]['prefix'] = 'size305_';
        $images[$i]['folder'] = 'size305';
        
        // Resize as thumb & preserve aspect ratio
        $i ++;
        $images[$i]['width'] = 80;
        $images[$i]['height'] = 80;
        $images[$i]['crop'] = TRUE;
        $images[$i]['prefix'] = 'thumbs3_';
        $images[$i]['folder'] = 'thumbs3';

        $i ++;
        $images[$i]['width'] = 89;
        $images[$i]['height'] = 51;
        $images[$i]['crop'] = FALSE;
        $images[$i]['prefix'] = 'thumbs4_';
        $images[$i]['folder'] = 'thumbs4';
        
        return $images;
    }

}