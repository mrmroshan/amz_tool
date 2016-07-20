<center>
<form method="post" action="<?php echo base_url('index.php/amazon_tool/get_urls')?>">
Project Name :<input type='text' name='proj_name' id='proj_name' value='<?php echo $proj_name?>' size="50">
<br>
URL: <input type='text' name='given_url' id='given_url' value='<?php echo $given_url?>' size="100">
<br>
<input type='submit' name='submit' value='Fetch All Urls' >
</form>
<br>
</center>
<hr>
<?php echo $url_list;?>