 <?php
#echo'<hr><pre> arraypage=>';print_r($arraypage);echo'<pre>'; 
// echo'<hr><pre> log=>';print_r($log);echo'<pre><hr>'; Die();
$rs=$log['rs'];
//echo'<hr><pre>  rs=>';print_r($rs);echo'<pre><hr>'; Die();
$prevlink=$arraypage['prevlink'];
$nextlink=$arraypage['nextlink'];
$page=$arraypage['page'];
$pages=$arraypage['pages'];
$total=$arraypage['total'];
$end=$arraypage['end'];
$start=$arraypage['start'];
$url_link=$arraypage['url_link'];
/*
$arr=array();
if(is_array($rs)){
foreach($rs as $key=>$val){
     $arr1=array();
     $arr1['b']['user_id']=$val->user_id;
     $arr1['b']['username']=$val->username;
     $arr1['b']['user_type']=$val->user_type;
     $arr1['b']['modules']=$val->modules;
     $arr1['b']['process']=$val->process;
     $arr1['b']['message']=$val->message;
     $arr1['b']['user_idx']=$val->user_idx;
     $arr1['b']['company']=$val->company;
     $arr1['b']['company']=$val->company;
     $arr1['b']['company_group']=$val->company_group;
     $arr1['b']['email']=$val->email;
     $arr1['b']['salutation']=$val->salutation;
     $arr1['b']['name']=$val->name;
     $arr1['b']['surname']=$val->surname;
     $arr1['b']['position']=$val->position;
  $arr[]=$arr1['b'];  
 }              
}
echo'<hr><pre> arr=>';print_r($arr);echo'<pre><hr>';   
echo '<div id="paging"><p>', $prevlink, ' หน้า ', $page, ' จาก ', $pages, ' หน้า, แสดง ', $start, '-', $end, ' จาก ', $total, ' รายการ ', $nextlink, ' </p></div>';
Die();
*/

$input=$this->input->get();
if($input==null){
$input=$this->input->post();  
}

//echo'<hr><pre>  input=>';print_r($input);echo'<pre><hr>';


$user_id=@$input['user_id'];
//$user_id=$_COOKIE['useridx'];
if($user_id==null){$user_id=null;}
$ip_addess=@$input['ip_addess'];
if($ip_addess==null){$ip_addess=null;}
$page=@$input['page'];

$date_start=@$input['date_start'];
if($date_start==null){$date_start=null;}
$date_end=@$input['date_end'];
if($date_end==null){$date_end=null;}
$date=@$input['date'];
if($date==null){$date=null;}
$month=@$input['month'];
if($month==null){$month=null;}
$year=@$input['year'];
if($year==null){$year=null;}
$status=@$input['status'];
if($status==null){$status=2;}
$perpage=@$input['perpage'];
if($perpage==null){$perpage=20;}
$limit=$perpage;
$orderby=@$input['orderby'];
if($orderby==null){$orderby=null;}
$search='user_id='.$user_id.'&date_start='.$date_start.'&date_end='.$date_end.'&date='.$date.'&month='.$month.'&year='.$year.'&status='.$status.'&orderby='.$orderby;
$userlogpage_post=$url_link;  

?>

<form action="<?php echo $userlogpage_post?>" method="post" name="form1"  id="form1"> 
  <label><fieldset><legend>Search log</legend>
  
<label> ip_addess
    <input name="ip_addess" type="text" value="<?php echo $ip_addess;?>" size="20" />
</label>

  <input type="submit" name="Search" value="Search" />
  </fieldset></label>
</form>

<hr> 


                 
<table class="table table-striped table-hover table-bordered">
                <caption>Data List  </caption>
                <thead>
                    <tr>
     				<th>user_id</th>
     				<th>user_type</th>
                         <th>date time</th>
                         <th>ip address</th>
                         <th>modules</th>
                         <th>process</th>
                         <th>message</th>
                         <th>code</th>
                    </tr>
                </thead>
                <tbody>
<?php                    
$arr=array();
if(is_array($rs)){
?>
                    
                    
                    
                  
                     
                   
<?php foreach($rs as $key=>$val){
     $arr1=array();
     $user_id=$val->user_idx;
     $username=$val->username;
     $user_type=$val->user_type;
     $modules=$val->modules;
     $process=$val->process;
     $message=$val->message;
     $user_idx=$val->user_idx;
     $company=$val->company;
     $company_group=$val->company_group;
     $email=$val->email;
     $salutation=$val->salutation;
     $name=$val->name;
     $surname=$val->surname;
     $position=$val->position;
     $ip_addess=$val->ip_addess;
     $date_time=$val->date_time;
     $code=$val->code;
     ?>
                            <tr>
						<td><?php echo $user_id; ?></td>
						<td><?php echo $user_type; ?></td>
                              <td><?php echo $date_time; ?></td>
                              <td><?php echo $ip_addess; ?></td> 
                              <td><?php echo $modules; ?></td>
                              <td><?php echo $process; ?></td>
                              <td><?php echo $message; ?></td> 
                              <td><?php echo  $code; ?></td> 
                            </tr>
               <?php }  ?>
          <?php  }else{ ?>
             <tr><td colspan="4">There are currently No Addresses</td></tr>
          <?php }?>
                </tbody>
            </table>
<?php 
echo '<div id="paging"><p>', $prevlink, ' หน้า ', $page, ' จาก ', $pages, ' หน้า, แสดง ', $start, '-', $end, ' จาก ', $total, ' รายการ ', $nextlink, ' </p></div>';
?>

<hr>
            <footer>
                <p>&copy;Contact</p>
            </footer>
</div>