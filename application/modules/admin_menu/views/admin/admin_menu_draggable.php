
<?php 
		$language = $this->lang->language; 
		$i=0;
		$maxcat = count($web_menu);
?>
<style type="text/css">
.col-sm-6 {width: 100%;text-align: center;	}
#saveorder{cursor:pointer;}
.input-small{text-align: center;}
</style>
						<!-- <div class="page-header">
							<h1>
							<?php //echo $HeadTitle?>
							<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								<?php //echo $HeadTitle?>
							</small>
							</h1>
						</div> -->

						<hr />	
<?php ################################***Draggable****################################?>
	<!-- start: MAIN CONTAINER -->
 
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-info">
								<?php echo $language['draggable'];?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<!-- start: DRAGGABLE HANDLES 3 PANEL -->
							<div class="panel panel-default">
								<div class="panel-heading">
									 
									 <?php #echo $language['draggable'];?>
									 Draggable
								</div>
<?php ###################?>
<!--<textarea id="nestable-output"></textarea>-->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-sm-6">
										<div class="dd" id="nestable">									
<?php ####Main###?>
<?php
	 # debug($web_menu);#Die();
			$count=count($web_menu);
			$i=1;
			if($count>=1){
			foreach ($web_menu  as $key=>$menu){
				$admin_menu_id=$menu['admin_menu_id'];
				$admin_menu_id2=$menu['admin_menu_id2'];
				$title=$menu['title'];
				$url=$menu['url'];
				$parent=$menu['parent'];
				$admin_menu_alt=$menu['admin_menu_alt'];
				$option=$menu['option'];
				$create_date=$menu['create_date'];
				$create_by=$menu['create_by'];
				$lastupdate_date=$menu['lastupdate_date'];
				$lastupdate_by=$menu['lastupdate_by'];
				$order_by=$menu['order_by'];
				$weight=$order_by;
				$icon=$menu['icon'];
				$params=$menu['params'];
				$status=$menu['status'];
				$lang=$menu['lang'];
				$count_sub=$menu['count_sub'];
?>
<ol class="dd-list">
	<li class="dd-item" data-id="<?php echo $admin_menu_id2; ?>" value="<?php echo $weight; ?>" data="<?php echo $weight; ?>">
		<div class="dd-handle" align="left">
		<i class="fa <?php echo $icon;?>"></i> <?php echo $title;?>
		</div>
<?php ####SUB###?>
<?php 			
			$this->load->model('Admin_menu_model');
			$status_sub_menu=1;
			$web_menu_sub = $this->Admin_menu_model->getMenu_sub($admin_menu_id2,$status_sub_menu);
			$count_menu_sub=count($web_menu_sub);
			$j=1;
			if($count_menu_sub>=1){
			foreach ($web_menu_sub  as $key=>$menu_sub){
				$admin_menu_id_sub=$menu_sub['admin_menu_id'];
				$admin_menu_id2_sub=$menu_sub['admin_menu_id2'];
				$title_sub=$menu_sub['title'];
				$url_sub=$menu_sub['url'];
				$parent_sub=$menu_sub['parent'];
				$admin_menu_alt_sub=$menu_sub['admin_menu_alt'];
				$option_sub=$menu['option'];
				$create_date_sub=$menu_sub['create_date'];
				$create_by_sub=$menu_sub['create_by'];
				$lastupdate_date_sub=$menu_sub['lastupdate_date'];
				$lastupdate_by_sub=$menu_sub['lastupdate_by'];
				$order_by_sub=$menu_sub['order_by'];
				$weight_sub=$order_by;
				$icon_sub=$menu_sub['icon'];
				$params_sub=$menu_sub['params'];
				$status_sub=$menu_sub['status'];
				$lang_sub=$menu_sub['lang'];
				$count_sub_sub=$menu_sub['count_sub'];

?>
													<ol class="dd-list">
														<li class="dd-item" data-id="<?php echo $admin_menu_id2_sub; ?>" value="<?php echo $weight_sub; ?>" data="<?php echo $weight_sub; ?>">
															<div class="dd-handle" align="left">
																<i class="fa <?php echo $icon_sub;?>"></i> <?php echo $title_sub;?>
															</div>
														</li>
													</ol>
<?php
			   $j++;
				}
			}										
?>
			</li>
		</ol>
<?php
	$i++;
	}
}										
?>
										</div>
									</div>
								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div>
<?php  ################################***Draggable****################################?>			
<script type="text/javascript">
$( document ).ready(function() {
var list = e.length ? e : $(e.target), output = list.data('output');
 $.ajax({
<?php $urlUpdateOrder = base_url('admin_menu/updateblock'); ?>
                    method: "POST",
                    url: "<?php echo $urlUpdateOrder; ?>",
                    data: {
                        list: list.nestable('serialize')
                    }

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert("Unable to save new list order: " + errorThrown);
                });
/*
                swal({
                    title: "จัดเรียงข้อมูลเรียบร้อย!",
                    text: "<?php echo 'บันทึกข้อมูลสำเร็จ กด F5 หรือ Refash 1ครั้ง เพื่อดูการเปลี่ยนแปลง!!'; ?>",
                    timer: 1500,
                    showConfirmButton: false
                });
*/
                alert("จัดเรียงข้อมูลเรียบร้อย..!");  
                window.location.reload();

            };
            $('#nestable').nestable({
                group: 1,
                maxDepth: 7,
            }).on('change', updateOutput);
            ///////////////////////////////
		$('#saveorder').on('click', function() {
				document.getElementById("jform").submit();
		});		
});

</script>
		<!-- basic scripts -->
		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url('theme/')?>/assets/js/jquery.min.js'>"+"<"+"/script>");
		</script>
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url('theme/')?>/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo base_url('theme/')?>/assets/js/bootstrap.min.js"></script>
		<!-- page specific plugin scripts -->
		<script src="<?php echo base_url('theme/')?>/assets/js/jquery.nestable.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo base_url('theme/')?>/assets/js/ace-elements.min.js"></script>
		<script src="<?php echo base_url('theme/')?>/assets/js/ace.min.js"></script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($){
			
				$('.dd').nestable();
			
				$('.dd-handle a').on('mousedown', function(e){
					e.stopPropagation();
				});
				
				$('[data-rel="tooltip"]').tooltip();
			
			});
		</script>
		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>/assets/css/ace.onpage-help.css" />
		<script src="<?php echo base_url('theme/')?>/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?php echo base_url('theme/')?>/assets/js/ace/ace.onpage-help.js"></script>
  
  
  
<?php
/*

#######CI
public function savelist() {

    if ($this->input->post('list')) {
        $this->do_update($this->input->post('list'));
    }
}

public function do_update($list, $parent_id = 0, &$m_order = 0) {
    foreach ($list as $item) {
        $m_order++;
        $data = array(
            'parent_id' => $parent_id,
            'm_order' => $m_order,
        );
        if ($parent_id != $item['id']) {
            $where = array('id' => $item['id']);
            var_dump($data . ':' . $where);
            $this->db->where($where);
            $this->db->update('nav', $data);
        }
        if (array_key_exists("children", $item)) {
            $this->do_update($item["children"], $item["id"], $m_order);
        }
    }
}
#######CI




<script>
        $(document).ready(function () {

            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target), output = list.data('output');
                $.ajax({
                    method: "POST",
                    url: "savelist",
                    data: {
                        list: list.nestable('serialize')
                    }, success: function (data) { //, textStatus, jqXHR
                        console.log(list.nestable('serialize'));
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert(" Unable to save new list order: " + errorThrown);
                });
            };
            $('#nestable').nestable({
                group: 1,
                maxDepth: 7,
            }).on('change', updateOutput);
        });
    </script>


REATE TABLE IF NOT EXISTS `nav` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `text` varchar(500) NOT NULL,
  `link` text NOT NULL,
  `show_condition` int(5) NOT NULL,
  `parent_id` int(5) NOT NULL,
  `m_order` int(9) NOT NULL,
  `class` varchar(50) NOT NULL,
  `data` varchar(50) NOT NULL,
  `des` text NOT NULL,
  `lang` varchar(50) NOT NULL,
  `accord` int(3) NOT NULL,
  `footer` int(3) NOT NULL,
  `f_sta` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;



Here is my Controller:
public function select_menu_priority() {
        $data['product'] = $this->menu_model->select_menu_priority();

        $data['li'] = $this->generate_li($data['product']);

        $this->load->view("set_menu_priority_table", $data);
    }

function generate_li($product,$parent = NULL){

        $li = "";

        $p1 = array_filter($product, function($a)use($parent){ return $a['parent_menu_id'] == $parent; });

        foreach ($p1 as $p){

            $inner_li = "";

            $p2 = array_filter($product,function($a)use($p){ return $a['parent_menu_id'] == $p['id']; });

            if($p2){
                $inner_li = $this->generate_li($product,$p['id']);
            }

            $li .= "<li class='dd-item' data-id='".$p['id']."'><div class='dd-handle'>".$p['title']."</div>".$inner_li."</li>";

        }

        $ol = "<ol class='dd-list'>".$li."</ol>";

        return $ol;
    }


View set_menu_priority_table.php:

<?php
if (isset($product)) {    
    $entity = $this->input->post("entity");
    $entity = $entity['id'];
    if (count($product) > 0) {
        ?>
        <div class="row-fluid" style="margin-bottom: 10px;">
            <button class="btn btn-success btn-sm" tabindex="4" id="save">
                <i class="fa fa-check"></i> Save
            </button>
            <p class="pull-right" style="margin-bottom: 10px;"><?php if ($entity == "product") { ?><a href="javascript:void(0)" id="show_category" class="text-success" style="margin-right:10px;font-weight: bold;text-decoration: underline">Go to Category Priority</a><?php } ?><span class="label label-info ">Drag Menu to set Priority.</span></p>            
            <div class="clear"></div>
        </div>
        <div class="dd" id="product_list">
            <input type="hidden" id="entity_type" name="entity" value="<?php echo $entity ?>" />    
            <?php echo $li; ?>
        </div>
    <?php } else { ?>        
        <p><span class="label label-warning">No <?php echo ($entity == "product") ? "product" : "category"; ?> found.</span><?php if ($entity == "product") { ?><a href="javascript:void(0)" id="show_category" class="text-success" style="margin-left:15px;font-weight: bold;text-decoration: underline">Go to Category Priority</a><?php } ?></p>            
        <?php
    }
} else {
    ?>
    <p class="label label-info">Please select Category to Set Priority within the Category.</p>
<?php } ?>

<script type="text/javascript">
$("#save").off("click").on("click", function() {
            var product_data = $("#product_list").nestable("serialize");
            var data = {product_data: product_data, entity: $("#entity_type").val()};
            if ($.bbq.getState("product_category") !== undefined) {
                data['product_category'] = $.bbq.getState("product_category");
            }
            ajax_call({
                url: '<?php echo site_url("admin/menu/update_menu_priority");?>',
                type: "post",
                dataType: "json",
                data: data,
                beforeSend: function() { },
                success: function(result) {
                    if (result['status'] == "success") {
                        alert("Priority Updated!");
                    } 
            });
        });
</script>


For Update That Priority Add function update_menu_priority in Controller:

public function update_menu_priority() {
            $data = $this->input->post("product_data");
            if (count($data)) {
                $update = $this->menu_model->update_priority_data($data);
                if ($update) {
                    $result['status'] = "success";
                } else {
                    $result['status'] = "error";
                }
            } else {
                $result['status'] = "error";
            }
            echo json_encode($result);

    }

And at last ad model function for that update_priority_data:
function update_priority_data($data, $parent = NULL) {
        $i = 1;
        foreach ($data as $d) {
            if (array_key_exists("children", $d)) {
                $this->update_priority_data($d['children'], $d['id']);
            }
            $update_array = array("priority" => $i, "parent_menu_id" => $parent);
            $update = $this->db->where("id", $d['id'])->update("menu", $update_array);
            $i++;
        }
        return $update;
    }


foreach($featured as $key => $value){
  echo $value['name'];
}

แก้ไข
ข้อแรก
foreach(array_keys($car) as $brand) echo "<br>".$brand;

หรือ
foreach($car as $brand => $model) echo "<br>".$brand;

ข้อสอง
foreach($car as $brand => $models){
echo "<br>".$brand;
foreach($models as $model) echo "<br>-".$model;
}



*/
?> 
