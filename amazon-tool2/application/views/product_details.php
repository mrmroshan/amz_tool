<?php include('link-loader.php')?>
<center>
<form method="post" action="<?php echo base_url('index.php/amazon_tool/show_product_details')?>">
Enter ANSI Code: <input type='text' name='txtAsinCode' id='txtAsinCode' value='<?php echo $txtAsinCode?>'>
<input type='submit' name='submit' value='Fetch Q&A' >

</form>
<hr>
</center>

<?php
$debug = false;
if($debug){
	echo '<pre>';
	print_r($result->Items);//->Item);
	echo '</pre>';
}
if(!empty($result)){

echo '<div id="a_tabbar" class="dhtmlxTabBar" imgpath="../codebase/imgs/" style="width:100%; height:95%;"  skinColors="" >';

echo '<div id="a1" name="Product Name">';
echo '<div style="  width: 100%;height: 90%;overflow: scroll;">';
//item attributes
$itemTitle = $result->Items->Item->ItemAttributes->Title;
$itemAttributes = $result->Items->Item->ItemAttributes;
$offerSummary = $result->Items->Item->OfferSummary;
$editorialReviews = $result->Items->Item->EditorialReviews;
$customerReviews = $result->Items->Item->CustomerReviews;

echo "<h2>".$itemAttributes->Title.'</h2>';
echo "<h3>".$itemAttributes->Brand.'</h3>';
echo "Features:";
echo '<ul>';
foreach($itemAttributes->Feature as $feature){
	echo '<li>'.$feature.'</li>';
}
echo '</ul>';
echo 'List Price:'.$itemAttributes->ListPrice->FormattedPrice.'<br>';
echo '<b>Offer Price:'.$offerSummary->LowestNewPrice->FormattedPrice.'</b><br>';
echo 'Product Overview:<br>';
echo $editorialReviews->EditorialReview->Content.'<br>';
echo '</div>';
echo '</div>';


echo '<div id="a2" name="Images" >';
echo '<div style="  width: 100%;height: 90%;overflow: scroll;">';
$mainImage = $result->Items->Item->LargeImage;
$imageSet  = $result->Items->Item->ImageSets->ImageSet;

//echo '<img src="'.$mainImage->URL.'" height="'.$mainImage->Height.'" width="'.$mainImage->Width.'" />';
//echo '<hr>';
echo '<center>';
foreach($imageSet as $image){

echo '<img src="'.$image->LargeImage->URL.'" height="'.$image->LargeImage->Height.'" width="'.$image->LargeImage->Width.'" />';
echo '<br>';
}
echo '</center>';
echo '</div>';
echo '</div>';

echo '<div id="a4" name="Customer Reviews">';
echo '<div style="  width: 100%;height: 90%;overflow: scroll;">';
echo '<iframe src="'.$customerReviews->IFrameURL.'" width="100%"; height="100%" style="borders:0;"></iframe>';
echo 'iframe';
echo '</div>';
echo '</div>';

echo '<div id="a3" name="debug">';
echo '<div style="  width: 100%;height: 90%;overflow: scroll;">';
echo '<pre>';
print_r($result->Items);
echo '</pre>';
echo '</div>';
echo '</div>';

echo '</div>';

}//end if
?>