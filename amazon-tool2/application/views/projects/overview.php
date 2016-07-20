<?php include './application/views/link-loader.php';?>

<h1>Project overview</h1>


<div id="mygrid_container" style="width:100%;height:600px;"></div>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script>                                  
                                   
    grd = new dhtmlXGridObject('mygrid_container');
    grd.setImagePath("<?php echo FRAMEWORK_URL?>/dhtmlx/dhtmlxGrid/codebase/imgs/");
    grd.setIconsPath("<?php echo FRAMEWORK_URL?>/dhtmlx/dhtmlxGrid/codebase/imgs/");    
    //grd.enableAutoWidth(true);
    grd.setHeader("Record Id,Image,ASIN Code,Product Title,Q&A Data,Content Data");
    grd.attachHeader(",,#text_filter,#text_filter,,");   
    grd.setInitWidths("100,300,200,200,200,200");    
    grd.setColAlign("center,center,center,left,center,center");
    grd.setColTypes("ro,ro,ro,ro,ro,ro");
    grd.setColSorting('int,str,str,str,str,str');
      
    grd.enableSmartRendering(false); 
    grd.init();    
    grd.loadXML("<?php echo site_url('products/produce_grid_feed/null/null/'.$project_id)?>");



    function fetch_QnA(div_id,asin_code,project_id){
        
        var url = '<?php echo site_url("projects/get_product_QA")?>/' + asin_code  + "/" + project_id;
        var divqnastatus = document.getElementById(div_id);
                
        divqnastatus.innerHTML = '<img alt="loading" src="<?php echo base_url('public/imgs/ajax-loader.gif')?>">';
        
        dhtmlxAjax.get(url,function(loader){
        	divqnastatus.innerHTML = loader.xmlDoc.responseText;
        	divqnastatus.setAttribute("class", "complete");
        	var divcontentstatus = document.getElementById('divPrepCont' + asin_code);        	
        	divcontentstatus.innerHTML = '<a href="<?php echo site_url('projects/prepare_product_contents')?>/'+ asin_code + '/' + project_id + '">Prepare Contents</a>';
        });
        
     }//end of function

     
	function prepare_contents(div_id,asin_code,project_id){

		var url = '<?php echo site_url("projects/prepare_product_contents")?>/' + asin_code  + "/" + project_id;
        var divcontentstatus = document.getElementById(div_id);
        divcontentstatus.innerHTML = '<img alt="loading" src="<?php echo base_url('public/imgs/ajax-loader.gif')?>">';
        
        dhtmlxAjax.get(url,function(loader){
        	divcontentstatus.innerHTML = loader.xmlDoc.responseText;         
        });
	}//end of function

    //as soon as grid is loaded do fullowing
	 grd.attachEvent("onXLE",function() {	
		 	/* 	
		$(".incomplete").each(function( i ) {
			var id = this.id;
			var id_parts = id.split('_');
			var asin_code = id_parts[1];

			var url='<?php echo site_url()?>/amazon_tool/prepare_post/'+asin_code;
			console.log(url);
			var divcontentstatus = document.getElementById(id);
        	divcontentstatus.innerHTML = '<img alt="loading" src="<?php echo base_url('public/imgs/ajax-loader.gif')?>">';
        				
			setTimeout(function()
				{
					var loader = dhtmlxAjax.getSync(url);//,function(loader){
		        	divcontentstatus.innerHTML = loader.xmlDoc.responseText;
		        	divcontentstatus.setAttribute("class", "complete");
			    	//});	 	 		
	 	 		},
	 	 		3000
	 	 	);
			
			
	        
			
		});	*/    		
	});

	function updateStatus(sel,proj_id){
		var selvalue = sel.value;
		var id= sel.id;
		
		var url = '<?php echo site_url()?>/products/updateStatus/'+proj_id+'/'+id+'/'+selvalue;
		console.log(url);	 
		var divcontentstatus = document.getElementById('div'+id);
        divcontentstatus.innerHTML = '<img alt="loading" src="<?php echo base_url('public/imgs/ajax-loader.gif')?>">';

        dhtmlxAjax.get(url,function(loader){
        	divcontentstatus.innerHTML = loader.xmlDoc.responseText;       	
        	   	         
        });
        

	}	
	
                                  
</script>