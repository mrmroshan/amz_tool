<?php 
include('application/views/link-loader.php');
?>			
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>


<style type='text/css'>
.container {width:100%;	border:1px solid #d3d3d3;}
.container div { width:100%;}
.container .header {   background-color:white;   padding: 2px;   cursor: pointer;   font-weight: bold;}
.container .content {   display: none;   padding : 5px;}
.header{ font-weight: bold; }
</style>


<script>

$(window).load(function(){
	$(".header").click(function () {

		$header = $(this);
		//getting the next element
		$content = $header.next();
		//open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
		$content.slideToggle(500, function () {
			//execute this after slideToggle is done
			//change text of header based on visibility of content div
			$header.text(function () {
				//change text based on condition
				//return $content.is(":visible") ? "Click here to collapse" : "Click here to expand";
			});
		});

	});
});

	$(function() {
		$( "#tabs" ).tabs();
	});
</script>


</head>
<body>



<h2>Prepare Content </h2>

<center>

<form method="post" action ="<?php echo site_url('projects/prepare_product_contents'.'/'.$asin_code.'/'.$project_id)?>">

<table>			

<tr>
<td class="tdLable">Project Id:</td>
<td class="tdItem">
<input type ='text' name="project_id" id="project_id" value="<?php echo $project_id?>">
</td>
</tr>


<tr>
<td class="tdLable">ASIN Code:</td>
<td class="tdItem">
<input type ='text' name="asin_code" id="asin_code" value="<?php echo $asin_code?>">
</td>
</tr>

<tr>
<td class="tdLable"></td>
<td class="tdItem"><input type ='submit' name="btnSubmit" id="btnSubmit" value="Create Contents Now"></td>
</tr>
</table>

<?php 

	$debug = false;
	$output = null;

		$mainImage = $result->Items->Item->LargeImage;
		$imageSet  = $result->Items->Item->ImageSets->ImageSet;
		//var_dump($imageSet);
		//item attributes
		$itemTitle = $result->Items->Item->ItemAttributes->Title;
		$itemAttributes = $result->Items->Item->ItemAttributes;
		$offerSummary = $result->Items->Item->OfferSummary;
		$editorialReviews = $result->Items->Item->EditorialReviews;
		$productPageUrl = $result->Items->Item->ItemLinks->ItemLink[0]->URL;
		$productReviewPageUrl = $result->Items->Item->ItemLinks->ItemLink[5]->URL;
		$customerReviewsUrl = $result->Items->Item->CustomerReviews->IFrameURL;
		$buyButton = '<br><a href="'.$productPageUrl.'" target="_blank" title="Check Price On Amazon" rel="nofollow">'.AMAZON_BUTTON.'</a></br>';
		$aOpen = '<a href="'.$productPageUrl.'" target="_blank" title="'.$itemTitle.'">';

		$output .= "<h2>".$itemAttributes->Title.' - Questions & Answers</h2>';
		
		if(isset($imageSet[0]->LargeImage->URL)){
			$output .= '<table align="center"><tr>';
			$output .= '<td valign="top">';
			$output .= $aOpen.'<img src="'.$imageSet[0]->LargeImage->URL.'" height="'.$imageSet[0]->LargeImage->Height.'" width="'.$imageSet[0]->LargeImage->Width.'" alt="'.$itemAttributes->Title.'" /></a>';
			$output .= '</td>';

			//$output .= '</tr><tr><td>';
			$output .= '<td valign="top">';
			//$output .= $custRevSumDiv;


			$output .= '<br>';
			$output .= '<div style="padding-left:5px;">';
			$siteA = '<a href="'.SITE_URL.'" target="_blank" title="'.$itemTitle.'" >';
			$output .= $siteA.'<b>'.$itemTitle."</a> features</b>";
			$output .= '<ul>';
			foreach($itemAttributes->Feature as $feature){
				$output .= '<li>'.$feature.'</li>';
			}
			$output .= '</ul>';
			$output .= '</div>';

			$output .= '</td>';
			$output .= '</tr>';
			$output .= '</table>';
			$output .= '<br>';
		}


		$output .= '<!--more-->';

		if(isset($imageSet[1]->LargeImage->URL)){
			$output .= '<center>';
			$output .= '<br>';
			$output .= $aOpen.'<img src="'.$imageSet[1]->LargeImage->URL.'" height="'.$imageSet[1]->LargeImage->Height.'" width="'.$imageSet[1]->LargeImage->Width.'" alt="'.$itemAttributes->Title.'" /></a>';
			$output .= '</center>';
			$output .= '<br>';
			$output .= $buyButton;
		}

		foreach($editorialReviews->EditorialReview as $ereview){
			//$output .= $ereview->Content.'<br>';
		}

		if(isset($imageSet[2]->LargeImage->URL)){
			$output .= '<center>';
			$output .= '<br>';
			$output .= $aOpen.'<img src="'.$imageSet[2]->LargeImage->URL.'" height="'.$imageSet[2]->LargeImage->Height.'" width="'.$imageSet[2]->LargeImage->Width.'" alt="'.$itemAttributes->Title.'" /></a>';
			$output .= '</center>';
			$output .= '<br>';
			$output .= $buyButton;
		}


		$output .= '<div id="tabs"><ul><li><a href="#tabs-1">Reviews</a></li><li><a href="#tabs-2">Questions and answers</a></li>';
		$output .= '</ul><div id="tabs-1"><p><iframe src="'.$customerReviewsUrl.'" width="100%"; height="700px" frameborder="0" scrolling="yes"></iframe></p>'.
		$customerReviewsUrl.'</div>';
		$output .= '<div id="tabs-2">';
		$output .= '<p><strong>Please click on a question to see its answers.</strong></p>';

		$output .= (!empty($contents))?str_replace('@@BUTTON@@',$buyButton,$contents):null;

		if(isset($imageSet[3]->LargeImage->URL)){
			$output .= '<center>';
			$output .= '<br>';
			$output .= $aOpen.'<img src="'.$imageSet[3]->LargeImage->URL.'" height="'.$imageSet[3]->LargeImage->Height.'" width="'.$imageSet[3]->LargeImage->Width.'" alt="'.$itemAttributes->Title.'" /></a>';
			$output .= '</center>';
			$output .= '<br>';
		}
		$output .= $buyButton;

		$output .= '</div><!-- end of tabs-2 -->';
		$output .= '</div><!-- end of tabs -->';

		echo '<center><textarea onClick="this.select();" rows="6" cols="100" >';
		echo $output;
		echo '</textarea></center>';
		echo '<hr>';
		echo $output;
		echo '<hr>';

?>

</form>
</center>