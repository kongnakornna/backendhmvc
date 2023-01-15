<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CKEDITOR version with custom config - Gridmanager Demos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">  

   
     <link href="/V3/assets/jquery-gridmanager-master/demo/css/bootstrap.css" rel="stylesheet">    
    <link href="/V3/assets/jquery-gridmanager-master/dist/css/jquery.gridmanager.css" rel="stylesheet">  
    <link href="/V3/assets/jquery-gridmanager-master/demo/css/demo.css" rel="stylesheet">  
     <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
 
    <script src="/V3/assets/jquery-gridmanager-master/demo/js/jquery.js"></script> 
    <script src="/V3/assets/jquery-gridmanager-master/demo/js/jquery-ui.js"></script> 
    <script src="/V3/assets/jquery-gridmanager-master/demo/js/bootstrap.js"></script> 
    <script src="//cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script> 
    <script src="//cdn.ckeditor.com/4.4.3/standard/adapters/jquery.js"></script> 
    <script src="/V3/assets/jquery-gridmanager-master/dist/js/jquery.gridmanager.js"></script> 
 
  </head>
<body> 
  
<div class="container">  
    <div id="mycanvas">


    </div> 
</div> 
 
<!--================== JS ================--> 
<script> 
$(document).ready(function(){  
 
 
  $("#mycanvas").gridmanager({ 
    debug: 1,
    remoteURL: "/replace-with-your-url",
    rowPreAppend:false,
    colSelectEnabled: false,
    // editableRegionEnabled: false,
    autoEdit: false,
    controlButtons: [[12], [6,6], [4,4,4],],
    colButtonsAppend: [
        // {
         // title:"Add Nested Row",
         // element: "a",
         // btnClass: "pull-left gm-addRow",
         // iconClass: "fa fa-plus-square"
        // },
        // {
         // title:"Remove Column",
         // element: "a",
         // btnClass: "pull-right gm-removeCol",
         // iconClass: "fa fa-trash-o"
        // }
    ],
    colButtonsPrepend: [
      {
         title:"Move",
         element: "a",
         btnClass: "gm-moveCol pull-left",
         iconClass: "fa fa-arrows "
      },
      // {
           // title:"Column Settings",
           // element: "a",
           // btnClass: "pull-right gm-colSettings",
           // iconClass: "fa fa-cog"
        // },
       // {
         // title:"Make Column Narrower",
         // element: "a",
         // btnClass: "gm-colDecrease pull-left",
         // iconClass: "fa fa-minus"
      // },
      // {
       // title:"Make Column Wider",
       // element: "a",
       // btnClass: "gm-colIncrease pull-left",
       // iconClass: "fa fa-plus"
      // }
    ],
    rowButtonsPrepend: [
                {
                 title:"Move",
                 element: "a",
                 btnClass: "gm-moveRow pull-left",
                 iconClass: "fa fa-arrows "
              },
                // {
                   // title:"New Column",
                   // element: "a",
                   // btnClass: "gm-addColumn pull-left  ",
                   // iconClass: "fa fa-plus"
                // },
                 // {
                   // title:"Row Settings",
                   // element: "a",
                   // btnClass: "pull-right gm-rowSettings",
                   // iconClass: "fa fa-cog"
                // }

            ],
            
    // customControls: {
      // global_row: [{ callback: 'test_callback', loc: 'bottom' }],
      // global_col: [{ callback: 'test_callback', loc: 'top' }]
    // },
    ckeditor: {
      customConfig: "/jQuery-gridmanager/demo/js/example-ckeditor.js"
    },
  });

   
});
</script> 
</body>
</html>
  