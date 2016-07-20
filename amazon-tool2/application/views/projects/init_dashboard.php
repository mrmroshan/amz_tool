<?php include './application/views/link-loader.php';?>

<center>
<h2>Project Initiation Progress Dashboard</h2>


<table>
<tr>
<td>Process 1: Collect best selling page Urls</td>
<td>
<div id="divProcess1">
<img alt="loading" src="<?php echo base_url('public/imgs/ajax-loader.gif')?>">
</div>
</td>
</tr>

<tr>
<td>Process 2:Collecting product ids from each best selling page Urls</td>
<td><div id="divProcess2"></div>
</td>
</tr>

<tr>
<td>Process 3:Collecting product data from Amazon</td>
<td><div id="divProcess3"></div>
</td>
</tr>

</table>
<div id="divcompletestatus"></div>
</center>

<script>

var url = "<?php echo site_url("projects/collect_best_selling_page_urls/".$project_id)?>";
var divProcess1 = document.getElementById('divProcess1');

//process 1
dhtmlxAjax.get(url,function(loader){
    divProcess1.innerHTML = loader.xmlDoc.responseText;

    var url2 = "<?php echo site_url("projects/collect_product_url_from_each_page/".$project_id)?>";
    var divProcess2 = document.getElementById('divProcess2');
    divProcess2.innerHTML = '<img alt="loading" src="<?php echo base_url('public/imgs/ajax-loader.gif')?>">';

    //process 2
    dhtmlxAjax.get(url2,function(loader){
    	divProcess2.innerHTML = loader.xmlDoc.responseText;

        var url3 = "<?php echo site_url("projects/get_product_details/".$project_id)?>";
        var divProcess3 = document.getElementById('divProcess3');
        divProcess3.innerHTML = '<img alt="loading" src="<?php echo base_url('public/imgs/ajax-loader.gif')?>">';

        //process 3
        dhtmlxAjax.get(url3,function(loader){
        	divProcess3.innerHTML = loader.xmlDoc.responseText;

        	var divcomplete = document.getElementById("divcompletestatus");
        	divcomplete.innerHTML = "<h2>Project created and related product data are extracted successfully.</h2>"+
        		"<br>Please wait till you are redirected to project page";       
    				
         
        });    	
    });    
});
</script>