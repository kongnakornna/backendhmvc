<?php
class Location_model extends CI_Model {
public function __construct(){
		parent::__construct();
        //$this->load->database();
        //	$language = $this->lang->language['lang'];
    }
##############Location##########
public function sd_na_geography($arraydata=array()){
    $geo_id=@$arraydata['geo_id'];
    $geo_name=@$arraydata['geo_name'];
    ##########################################
    $language=@$arraydata['lang'];
    if($language==null){ $language=$this->lang->language['lang'];
         if($language==null){  $language='th'; }   
         }
    ##########################################
    $deletekey=@$arraydata['deletekey'];
    if($deletekey==null){$deletekey=0;}else{$deletekey=1;}
        $this->db->cache_off();
        $this->db->select('geo.*');
        $this->db->from('sd_na_geography as geo'); 
        if($geo_id==''){}else{
            $geo_id=(int)$geo_id;
            $this->db->where('geo.geo_id_map',$geo_id);
            }
        if($geo_name==''){}else{                         
                $this->db->like('geo.geo_name', $geo_name);
                } 
        $this->db->where('geo.lang',$language);
        $query_get=$this->db->get();
        $last_query=$this->db->last_query();
        $num=$query_get->num_rows();
        if($num==0){ 
                 $data=null;  
            }else{ 
                $data=$query_get->result(); 
                 }
        #################cvs_course_answer###############
        # echo '<pre>arraydata=>'; print_r($arraydata); echo '</pre>';
        # echo '<pre>last_query=>'; print_r($last_query); echo '</pre>'; 
        # echo '<pre>data=>'; print_r($data); echo '</pre>'; die;
        return $data; 
    }
public function sd_na_province($arraydata=array()){
        $geo_id=@$arraydata['geo_id'];
        $province_id=@$arraydata['province_id'];
        $province_name=@$arraydata['province_name'];
        $language=@$arraydata['lang'];
         if($language==null){ $language=$this->lang->language['lang'];
         if($language==null){  $language='th'; }   
         }
        $deletekey=@$arraydata['deletekey'];
        if($deletekey==null){$deletekey=0;}else{$deletekey=1;}
            $this->db->cache_off();
            $this->db->select('province.*,geo.geo_name');
            $this->db->from('sd_na_province as province'); 
            $this->db->join('sd_na_geography as geo', 'geo.geo_id=province.geo_id');
            if($geo_id==''){}else{
                $geo_id=(int)$geo_id;
                $this->db->where('province.geo_id',$geo_id);
                }
            if($province_id==''){}else{
                    $province_id=(int)$province_id;
                    $this->db->where('province.province_id_map',$province_id);
                    }
            if($province_name==''){}else{                         
                        $this->db->like('province.province_name', $province_name);
                        } 
            $this->db->where('province.lang',$language);
            $query_get=$this->db->get();
            $last_query=$this->db->last_query();
            $num=$query_get->num_rows();
            if($num==0){ 
                     $data=null;  
                }else{ 
                    $data=$query_get->result(); 
                     }
            #################cvs_course_answer###############
            #echo '<pre>arraydata=>'; print_r($arraydata); echo '</pre>';
            #echo '<pre>last_query=>'; print_r($last_query); echo '</pre>'; 
            #echo '<pre>data=>'; print_r($data); echo '</pre>'; die;
            return $data; 
    }
public function sd_na_amphur($arraydata=array()){
        $geo_id=@$arraydata['geo_id'];
        $province_id=@$arraydata['province_id'];
        $amphur_id=@$arraydata['amphur_id'];
        $amphur_code=@$arraydata['amphur_code'];
        $amphur_name=@$arraydata['amphur_name'];
        $zipcode=@$arraydata['zipcode'];
        $language=@$arraydata['lang'];
        if($language==null){ $language=$this->lang->language['lang'];
         if($language==null){  $language='th'; }   
         }
        $deletekey=@$arraydata['deletekey'];
        if($deletekey==null){$deletekey=0;}else{$deletekey=1;}
            $this->db->cache_off();
            $this->db->select('amphur.*,province.province_name,geo.geo_name');
            $this->db->select('code.zipcode');
            $this->db->from('sd_na_amphur as amphur'); 
            $this->db->join('sd_na_province as province', 'amphur.province_id=province.province_id_map');
            $this->db->join('sd_na_geography as geo', 'geo.geo_id_map=province.geo_id');
            $this->db->join('sd_na_zipcode as code', 'code.amphur_id=amphur.amphur_id_map');
            if($geo_id==''){}else{
                $geo_id=(int)$geo_id;
                $this->db->where('amphur.geo_id',$geo_id);
                }
            if($province_id==''){}else{
                    $province_id=(int)$province_id;
                    $this->db->where('amphur.province_id',$province_id);
                    }
            if($amphur_id==''){}else{
                    $amphur_id=(int)$amphur_id;
                    $this->db->where('amphur.amphur_id',$amphur_id);
                    }
            if($amphur_code==''){}else{
                        $amphur_code=(int)$amphur_code;
                        $this->db->where('amphur.amphur_code',$amphur_code);
                        }
            if($amphur_name==''){}else{
                        $this->db->like('amphur.amphur_name', $amphur_name);
                        }  
            $this->db->where('amphur.lang',$language);                  
            $query_get=$this->db->get();
            $last_query=$this->db->last_query();
            $num=$query_get->num_rows();
            if($num==0){ 
                     $data=null;  
                }else{ 
                    $data=$query_get->result(); 
                     }
            #################cvs_course_answer###############
            # echo '<pre>arraydata=>'; print_r($arraydata); echo '</pre>';
             #echo '<pre>last_query=>'; print_r($last_query); echo '</pre>';   echo '<pre>data=>'; print_r($data); echo '</pre>'; die;
            return $data; 
    }
public function sd_na_district($arraydata=array()){
        $geo_id=@$arraydata['geo_id'];
        $province_id=@$arraydata['province_id'];
        $amphur_id=@$arraydata['amphur_id'];
        $district_id_map=@$arraydata['district_id'];
        $district_code=@$arraydata['district_code'];
        $district_name=@$arraydata['district_name'];
        $zipcode=@$arraydata['zipcode'];
        $language=@$arraydata['lang'];
        if($language==null){ $language=$this->lang->language['lang'];
         if($language==null){  $language='th'; }   
         }
        $deletekey=@$arraydata['deletekey'];
        if($deletekey==null){$deletekey=0;}else{$deletekey=1;}
            $this->db->cache_off();
            $this->db->select('district.*,amphur.*,province.province_name,geo.geo_name');
            $this->db->select('zipcode.zipcode as postcode');
            $this->db->from('sd_na_district  as district'); 
            $this->db->join('sd_na_amphur as amphur', 'amphur.amphur_id=district.amphur_id');
            $this->db->join('sd_na_province as province', 'amphur.province_id=province.province_id');
            $this->db->join('sd_na_geography as geo', 'geo.geo_id=province.geo_id');
            $this->db->join('sd_na_zipcode as zipcode', 'zipcode.district_id=district.district_id_map');
            if($geo_id==''){}else{
                $geo_id=(int)$geo_id;
                $this->db->where('district.geo_id',$geo_id);
                }
            if($province_id==''){}else{
                    $province_id=(int)$province_id;
                    $this->db->where('district.province_id',$province_id);
                    }
            if($amphur_id==''){}else{
                    $amphur_id=(int)$amphur_id;
                    $this->db->where('district.amphur_id',$amphur_id);
                    }
            if($district_id==''){}else{
                    $district_id=(int)$district_id;
                    $this->db->where('district.district_id_map',$district_id);
                    }
            if($district_code==''){}else{
                        $amphur_code=(int)$district_code;
                        $this->db->where('district.district_code',$district_code);
                        }
            if($district_name==''){}else{                         
                        $this->db->like('district.district_name', $district_name);
                        }       
            if($zipcode==''){}else{                       
                         
                        $this->db->where('zipcode.zipcode', $zipcode);
                        }         
            $this->db->where('district.lang',$language);         
            $query_get=$this->db->get();
            $last_query=$this->db->last_query();
            $num=$query_get->num_rows();
            if($num==0){ 
                     $data=null;  
                }else{ 
                    $data=$query_get->result(); 
                     }
            #################cvs_course_answer###############
            # echo '<pre>arraydata=>'; print_r($arraydata); echo '</pre>';
            # echo '<pre>last_query=>'; print_r($last_query); echo '</pre>'; 
            # echo '<pre>data=>'; print_r($data); echo '</pre>'; die;
            return $data; 
    }
##############Location##########
function delete_na_countries_by_admin($countries_id){
		$this->db->where('countries_id', $countries_id);
		$this->db->delete('_na_countries'); 
	}
 
}
?>	
