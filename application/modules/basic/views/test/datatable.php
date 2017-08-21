        <link href="<?php echo base_url()?>assets/jqwidgets/styles/jqx.base.css" type="text/css" rel="stylesheet"/>
        <script src="<?php echo base_url()?>assets/scripts/jquery-1.10.2.min.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxcore.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxbuttons.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxscrollbar.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxmenu.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxgrid.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxgrid.selection.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxgrid.filter.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxgrid.sort.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxdata.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxlistbox.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxgrid.pager.js"></script>
        <script src="<?php echo base_url()?>assets/jqwidgets/jqxdropdownlist.js"></script>
        
 
        <div class="container">
            <div class="row">
                <div>
                    <div id="jqxgrid"></div>
                </div>
                </div>
            </div>
        
        
    </body>
    
    <script>
    $(document).ready(function () {
		// prepare the data
		
      
		var source =
		{
			datatype: "json",
			datafields: [
			{ name: 'sensor_log_id', type: 'string'},
			{ name: 'sensor_hwname', type: 'string'},
			{ name: 'sensor_value', type: 'string'},
			{ name: 'datetime_log', type: 'string' }
			


		],
		cache: false,
		url: '<?php echo base_url()?>ajaxpagination/jqxdatatableAjax',
		filter: function()
		{
			// update the grid and send a request to the server.
			$("#jqxgrid").jqxGrid('updatebounddata', 'filter');
		},
		sort: function()
		{
			// update the grid and send a request to the server.
			$("#jqxgrid").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
		beforeprocessing: function(data)
		{		
			if (data != null)
			{
				source.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var dataadapter = new $.jqx.dataAdapter(source, {
			loadError: function(xhr, status, error)
			{
				alert(error);
			}
		}
		);
	
		// initialize jqxGrid
		$("#jqxgrid").jqxGrid(
		{		
			source: dataadapter,
			
			filterable: true,
			sortable: true,
			autoheight: true,
			pageable: true,
			virtualmode: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'id', datafield: 'sensor_log_id', width: 50 },
				{ text: 'name', datafield: 'sensor_hwname', width: 300 },
				{ text: 'value', datafield: 'sensor_value', width: 80 },
				{ text: 'date', datafield: 'datetime_log', width: 200 }
		
			]
		});
	});
    </script>
 