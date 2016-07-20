<?php 
include('application/views/link-loader.php');
?>			
<h2>Add Project </h2>
<table> 
  <tr>
     <td><a href="<?php echo site_url('projects/dashboard')?>">Back</a></td>
  </tr>
</table>

<center>

<form method="post" action ="<?php echo site_url('projects/add_new')?>">
<?php echo $gen_message['text'];?>
<?php echo validation_errors(); ?>
<table>			

<tr>
<td class="tdLable">Project Name:</td>
<td class="tdItem">
<input type ='text' name="proj_name" id="proj_name" value="<?php echo $post['proj_name']?>">
</td>
</tr>


<tr>
<td class="tdLable">AmazonTracking Code:</td>
<td class="tdItem">
<input type ='text' name="amazon_tracking_code" id="amazon_tracking_code" value="<?php echo $post['amazon_tracking_code']?>">
</td>
</tr>



<tr>
<td class="tdLable">Amazon best selling url:</td>
<td class="tdItem">
<input type ='text' name="amazon_best_selling_url" id="amazon_best_selling_url" value="<?php echo $post['amazon_best_selling_url']?>">
</td>
</tr>


<tr>
<td class="tdLable">Read more element HTML:</td>
<td class="tdItem">
<input type ='text' name="read_more_tag" id="read_more_tag" value="<?php echo $post['read_more_tag']?>">
</td>
</tr>

<tr>
<td class="tdLable">Site URL:</td>
<td class="tdItem">
<input type ='text' name="site_url" id="site_url" value="<?php echo $post['site_url']?>">
</td>
</tr>

<tr>
<td class="tdLable">Amazon button html:</td>
<td class="tdItem">
<input type ='text' name="amazon_button_url" id="amazon_button_url" value="<?php echo $post['amazon_button_url']?>">
</td>
</tr>

<tr>
<td class="tdLable">Content template:</td>
<td class="tdItem">
<input type ='text' name="template_id" id="template_id" value="<?php echo $post['template_id']?>">
</td>
</tr>


<tr>
<td class="tdLable"></td>
<td class="tdItem"><input type ='submit' name="btnSubmit" id="btnSubmit" value="Create Contents Now"></td>
</tr>
</table>
</form>
</center>