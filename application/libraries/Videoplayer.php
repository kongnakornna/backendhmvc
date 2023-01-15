<?php

class Videoplayer {

    function player() {
        
    }

    function getVideoUrl($id) {
        
    }

    function getCSV($filename = "") {
        $filename = '../assets/csv/bio.csv';
        $videos = $this->csv_to_array($filename);
        _pr($videos);
            
    }

    function csv_to_array($filename = '', $delimiter = ',') {
        if (!file_exists($filename) || !is_readable($filename)){
//            echo 55;
            return FALSE;
        }
            
        echo 8888;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    list($link,$v480p,$v720p) = explode($row,',');
                    $data['link'] = $link;
                    $data['v480p'] = $v480p;
                    $data['v720p'] = $v720p;
                    $videos[] = (object) $data;
            }
            fclose($handle);
        }
        return $videos;
    }

}
