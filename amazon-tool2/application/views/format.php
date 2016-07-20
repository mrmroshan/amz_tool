<html lang="en">
		<head>
		<meta charset="utf-8">
		<title>Amazon Tool</title>
<!-- start of amazon tool code -->
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		
		
		<style type='text/css'>
		.tabcontent {width:100%;	border:1px solid #d3d3d3;}
		.tabcontent div { width:100%;}
		.tabcontent .header {   background-color:white;   padding: 2px;   cursor: pointer;   font-weight: bold;}
		.tabcontent .content {   display: none;   padding : 5px;}
		.header{ font-weight: bold; }
		</style>
	
<!-- end of amazon tool code -->
	
	</head>
	<body>



<center>
<form method="post" action="<?php ?>">
<center>
<textarea name="txtPost" onClick="" rows="20" cols="100" ></textarea>
</center>
<input type="submit" value="submit" name="submit">
</form>
<hr>
<h1>Formatted Strings</h1>
<textarea rows="20" cols="100" onclick="this.select()"><?php echo $formated_str?></textarea>

</center>

</body>