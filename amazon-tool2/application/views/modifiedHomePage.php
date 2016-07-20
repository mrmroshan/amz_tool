
<?php
//ob_start();
echo $homePage;
//ob_end_flush();


?>

<script>
$(window).bind("load", function() {
	var iframes = document.getElementsByTagName('iframe');
	
	
	for(var i=0;i<=iframes.length;i++){
		//alert(iframes[i].id);
		if(typeof iframes[i] !== 'undefined'){			
			iframes[i].parentNode.removeChild(iframes[i]);
		}
	}


	var as = document.getElementsByTagName('a');
	for(var n=0;n<=as.length;n++){
		var newsUrl = as[n].href;
		//as[n].href = 'http://localhost/news/index.php/lankacnews/loadcnews/?u='+ newsUrl;
	}
	
	//window.frames['myIFrame'].document.getElementById('myIFrameElemId')
});
</script>
