<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Amazon_Tool extends CI_Controller {
	
	var $contents = null;
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('Amazon_Tool_Model');//
		$this->load->model('Products_model');//
	}

	
	public function test(){
		
		?>
		<!doctype html>
		<html lang="en">
		<head>
		<meta charset="utf-8">
		<title>tabs demo</title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		
		
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
		            return $content.is(":visible") ? "Click here to collapse" : "Click here to expand";
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
	<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Reviews</a></li>
    <li><a href="#tabs-2">Questions and answers</a></li>
    <li><a href="#tabs-3">Aenean lacinia</a></li>
  </ul>
  <div id="tabs-1">
    <p>Reviews</p>
  </div>
  <div id="tabs-2">
    <p>
	
	<div  class="tabcontent"><!-- toggle div -->
	<div class ="header" ><span>Click here to expand</span></div>
	<div class="content">
	some text here
	</div><!-- content div -->
	</div><!-- container div -->
	
	
	<div  class="tabcontent"><!-- toggle div -->
	<div class ="header"  ><span>Click here to expand</span></div>
	<div class="content">
	some text here
	</div><!-- content div -->
	</div><!-- container div -->
	
	
	</p>
  </div>
  <div id="tabs-3">
    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
  </div>
</div>
 
	
	
	
	
	
	
	</body>
	</html>
	
	<?php
	
	}//end of function
		
	public function get_product_details($asin_code = null){
	
		$debug = false;
		$obj = new AmazonProductAPI();
		$result = null;
		if(!empty($asin_code)){
			try
			{  $result = $obj->getItemByAsin($asin_code);	}
			catch(Exception $e)
			{	echo $e->getMessage();exit;	}				
			if($debug) log_message('debug',"<b>appregistrar/issueToken(),token data saved:</b>".print_r($result,true));
			return $result;
		}else{
			return 0;
		}		
		
	}//end of function
	
	
	public function get_best_selling_products($browse_node_id,$pageNo = 1){
	
		$debug = false;
		$obj = new AmazonProductAPI();
		$result = null;
		if(!empty($browse_node_id)){
			try
			{  $result = $obj->getBestSellingProducts($browse_node_id,$pageNo);	}
			catch(Exception $e)
			{	echo $e->getMessage();exit;	}				
			if($debug) log_message('debug',"<b>appregistrar/issueToken(),token data saved:</b>".print_r($result,true));
			//return $result;
		}else{
			//return 0;
		}		
		echo '<pre>';
		print_r($result);
		echo '</pre>';
		
	}//end of function
	
	
	public function show_product_details(){
	
		$asin = (isset($_REQUEST['txtAsinCode'] ))?$_REQUEST['txtAsinCode']:null;
		$data['txtAsinCode'] = $asin;
		$data['result'] = $this->get_product_details($asin);	
		$this->load->view('product_details',$data);
	
	}//end of functions
	
	
	public function get_urls(){
	
		$given_url = (isset($_REQUEST['given_url']))?$_REQUEST['given_url']:null;
		$page_url = null;		
		$data['proj_name'] = null;
		$data['given_url'] = null;
		$data['url_list'] = null;
		
		if(isset($_REQUEST['proj_name'])){
		try{
			
			$html = file_get_html($given_url);		
			$zg_page = $html->find('li[class="zg_page"]');
			
			$data['proj_name'] = (isset($_REQUEST['proj_name']))?$_REQUEST['proj_name']:null;
			$data['given_url'] = $given_url;
			
			if(!empty($zg_page)){
				foreach($zg_page as $li){			
					$page_url =$li->children(0)->attr['href'];							
					$data['url'] = $page_url;
					$data['url_list'] .= '<br>'.$page_url;
					$this->Amazon_Tool_Model->save_urls($data);								
				
				}//end foreach			
			}//end if	
			
		} catch (Exception $e) {
 			 echo 'Error Caught:'.$e;
		}
		}else{
		
		
		}
		
				
		$this->load->view('urls',$data);
	
	}//end of function
	
	
	public function extract_reviews_summery_by_url($url=null){
		//$url = 'http://www.amazon.com/reviews/iframe?akid=AKIAIJFTG2VIJHSE72ZA&alinkCode=xm2&asin=B00DRA4WAY&atag=traffic-ex-20&exp=2015-02-08T05%3A56%3A54Z&v=2&sig=He20PeryWo87%2F2urZGMq4aC4Kda108InpUPqMwnu3%2Bs%3D';
		$reviewsDiv = null;
		$reviewsDiv = '<iframe src="'.$url.'" width="500px"; height="170px" frameborder="0" scrolling="no"></iframe>';
		return $reviewsDiv;
		try{
			$html = file_get_html($url);
			//$webpage = $this->get_web_page($url);				
			//$html = str_get_html($webpage['content']);
			
			$criFrameDiv = $html->find('div[class="crIFrame"]');//[class="crIFrame"]
			$style ="<style>
			.crIFrameHeaderHistogram {
			float: left;
			}
			.crIFrameHeaderTitle {
			font-size: 24px;
			letter-spacing: -1px;
			}
			.crIFrameHeaderLeftColumn {
			float: left;
			margin-right: 20px;
			}
			</style>";			
			
			foreach($criFrameDiv as $div){
				$reviewsDiv = $style.$div->children(1);				
			}	
			
		} catch (Exception $e) {
 			 echo 'Error Caught:'.$e;
		}
		return $reviewsDiv;
	
	}//end of function
	
	

 	public function load_QnA($asin = null){
		
		ini_set('max_execution_time', 600);//10 mints
		
		$debug = true;
		$ddata = null;
		
		////'B001OC5UMQ';
		
		$data['contents'] = '';
		$data['txtAsidCode'] = '';		
		$data['urlList'] = '';		
		$data['result'] = null;
		$urlList = null;
		
		if(isset($_GET['submit'])){
		
		$asin = (isset($_GET['txtAsidCode']))?$_GET['txtAsidCode']:null;
		
		//pass it on to amazon to get product details
		$data['result'] = $this->get_product_details($asin);	
		$custRevPageUrl = $data['result']->Items->Item->CustomerReviews->IFrameURL;
		$data['custRevPageUrl'] = $custRevPageUrl;
		$data['custRevSumDiv'] = $this->extract_reviews_summery_by_url($custRevPageUrl);

		 ///http://www.amazon.com/ask/questions/asin/B00RYWO8BY/ref=ask_ql_qlh_hza  
		
		 ///new url
		 $url = (isset($_GET['url']))?$_GET['url']:"http://www.amazon.com/ask/questions/asin/$asin/ref=ask_ql_qlh_hza";
		
		 $css_data = 'div [class="a-section askTeaserQuestions"] div[class="a-fixed-left-grid a-spacing-base"]';
		
		 $this->contents .= $this->QnA_extractor($url,$css_data);
		
		 $webpage = $this->get_web_page($url);
		 $html = str_get_html($webpage['content']);
		 
		 $pagination = $html->find('ul[class="a-pagination"]');
		 //if($debug) $ddata .= '<br>pagination -<pre>'.print_r($pagination,true).'</pre>';

		 if(isset($pagination[0])){
		 	$page = $pagination[0];
		 	if($debug) $ddata .= '<br>page -'.$page;
		 	
		 	$noOfAs = count($page->find('a'));
		 	if($debug) $ddata .= '<br>noOfAs -'.$noOfAs;
		 		
		 	$secondA = $page->find('a')[1];
		 	if($debug) $ddata .= '<br>secondA -'.$secondA;
		 		
		 	$secondAhref = 'http://www.amazon.com'.$secondA->href;
		 	if($debug) $ddata .= '<br>secondAhref -'.$secondAhref;
		 		
		 	$secondAString = $page->find('a')[1]->plaintext;
		 	if($debug) $ddata .= '<br>secondAString -'.$secondAString;
		 	
		 	$lastA = $page->find('a')[$noOfAs - 2];
		 	if($debug) $ddata .= '<br>lastA -'.$lastA;
		 		
		 	$lastAString = $page->find('a')[$noOfAs - 2]->plaintext;
		 	if($debug) $ddata .= '<br>lastAString -'.$lastAString;
		 	
		 	$lastAhref = $lastA->href;
		 	if($debug) $ddata .= '<br>lastAhref -'.$lastAhref;
		 		
		 	//url = http://www.amazon.com/ask/questions/asin/B00RYWO8BY/2/ref=ask_ql_psf_ql_hza
		 		
		 	$firstUrlPart = strstr($secondAhref,"/ref",true);
		 	//http://www.amazon.com/ask/questions/asin/B00RYWO8BY/2
		 	if($debug) $ddata .= '<br>firstUrlPart -'.$firstUrlPart;
		 		
		 	$lastUrlPart = strstr($secondAhref,"/ref",false);
		 	// /ref=ask_ql_psf_ql_hza
		 	if($debug) $ddata .= '<br>lastUrlPart -'.$lastUrlPart;
		 		
		 	$urlParts = explode('/',$secondAhref);
		 	if($debug) $ddata .= '<br>lastUrlPart -<pre>'.print_r($urlParts,true)."</pre>";
		 	
		 	$upto = ((int)$lastAString >=8)? 8: (int)$lastAString; //controller
		 		
		 	for($i=2;$i<=$upto;$i++){
		 			
		 		$urlParts[7] = $i; //start from 2nd page
		 		$urlParts[8] = 'ref=ask_ql_psf_ql_hza';
		 		$newUrl = null;
		 		foreach($urlParts as $urlPart){
		 			$newUrl .= $urlPart.'/';
		 			if($debug) $ddata .= '<br>newUrl - '. $newUrl;
		 		}
		 		$urlList .=  $newUrl."<br>";
		 	
		 		if($debug) $ddata .= '<br>urlList -<pre>'.print_r($urlList,true)."</pre>";
		 		$css_data = 'div [class="a-section askTeaserQuestions"] div[class="a-fixed-left-grid a-spacing-base"]';
		 		$this->contents .= $this->QnA_extractor($newUrl,$css_data);
		 	}//end for url
		 		
		 	$data['contents'] = str_replace("exp=2015", "exp=2035", $this->contents);
		 	$data['txtAsidCode'] = $asin;
		 	$data['urlList'] = $urlList;
		 	 
		 	//get google search related topics
		 	$itemTitle = $data['result']->Items->Item->ItemAttributes->Title;
		 	//$grelatedsearch = $this->get_google_related_searches($itemTitle);
		 	
		 	$this->load->view('amazonQnA',$data);
		 	
		 	}else{
		 		$this->load->view('amazonQnA',$data);
		 	}//end if post
		 	
		 }else{
		 	$this->load->view('amazonQnA',$data);
		 }
 	}//end of function

 	
 	function get_google_related_searches($search_string){
 		
 		$search_string = str_replace(' ', '+', $search_string);
 		$search_string = str_replace('%20', '+', $search_string);
 		$url = "https://www.google.lk/#q=".$search_string;
 		echo "<br>$url<br>"; 		
 		$webpage = $this->get_web_page($url);
 		$html = str_get_html($webpage['content']); 		
 		$divrelatedsearches = $html->find('div[class="brs_col"]');
 		var_dump($divrelatedsearches);exit;	
 		
 	}//end of function
 	
 	
 	function QnA_extractor($url,$css_data){

 		$text = null;
 		
 		try{
 			//$html = file_get_html($url);
 			$webpage = $this->get_web_page($url);
 			$html = str_get_html($webpage['content']);
 			$divQnA = $html->find($css_data); 			
 				
 			if(!empty($divQnA)){ 		
 				 				
 				$n = 0;
 				foreach($divQnA as $item){ 		
 					
 					if($n % 2 == 0){
 						
 						$text .= '<p>';
 						$text .= '<div  class="tabcontent"><!-- toggle div -->';
 						$text .= '<div class ="header" ><span><b><i>Q:';
 						$text .=trim($item->children(0)//div class = 'a-fixed-left-grid-inner'
 						->children(1)//div class = 'a-fixed-left-grid-col a-col-right'
 						->children(0)//div class = 'a-fixed-left-grid a-spacing-small'
 						->children(0)//div class = 'a-fixed-left-grid-inner'
 						->children(1)//div class = 'a-fixed-left-grid-col a-col-right'
 						->children(0)//a class = 'a-link-normal'
 						->plaintext);
 							
 						$text .= '</i></b></span></div>';
 						$text .= '<div class="content">';
 						$text .= '<b>A:</b>';
 						
 						$text .=trim($item->children(0)//div class = 'a-fixed-left-grid-inner'
 						->children(1)//div class = 'a-fixed-left-grid-col a-col-right'
 						->children(1)//div class = 'a-fixed-left-grid a-spacing-base'
 						->children(0)//div class = 'a-fixed-left-grid-inner'
 						->children(1)//div class = 'a-fixed-left-grid-col a-col-right'
 						->children(0)//span
 						->plaintext);
 							
 						$text .= '</div><!-- content div -->';
 						$text .= '</div><!-- container div -->';
 						$text .= '</p>'; 						 						
 					 }//end if 					 
 					$n++;
 					if($n % 20 == 0) $text .= '@@BUTTON@@';
 				}//end foreach
 				
 			}else{
				$text = "No content found!";
			}//end if
 		} catch (Exception $e) {
 			 echo 'Error Caught:'.$e;
		}		
		return $text;		
 		
 	}//end of function
 	
 	
 	public function prepare_post($asin_code = null){
 		
 		$url = site_url().'/amazon_tool/load_QnA?txtAsidCode=' . $asin_code . '&submit=Fetch+Q%26A';
 		$webpage = $this->get_web_page($url);
 		$html = str_get_html($webpage['content']);
 		$txtPost = $html->find('textarea');
 		$content=$txtPost[0]->innertext ;
 		$data=array('content'=>$content);
 		$where=array('ASIN_id'=>$asin_code);
 		$this->Products_model->update_record($data,$where);
 		echo 'done<br><textarea id="txtPost" onClick="this.select();" rows="6" cols="100" >'.$content.'</textarea>';		
 		
 	}
 	
 	/**
 	 * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
 	 * array containing the HTTP server response header fields and content.
 	 */
 	function get_web_page( $url )
 	{
 		$options = array(
 				CURLOPT_RETURNTRANSFER => true,     // return web page
 				CURLOPT_HEADER         => false,    // don't return headers
 				CURLOPT_FOLLOWLOCATION => true,     // follow redirects
 				CURLOPT_ENCODING       => "",       // handle all encodings
 				//CURLOPT_USERAGENT      => "spider", // who am i
				CURLOPT_USERAGENT => $this->random_user_agent(),
 				CURLOPT_AUTOREFERER    => true,     // set referer on redirect
 				CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
 				CURLOPT_TIMEOUT        => 120,      // timeout on response
 				CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
 		);
 	
 		$ch      = curl_init( $url );
 		curl_setopt_array( $ch, $options );
 		$content = curl_exec( $ch );
 		$err     = curl_errno( $ch );
 		$errmsg  = curl_error( $ch );
 		$header  = curl_getinfo( $ch );
 		curl_close( $ch );
 	
 		$header['errno']   = $err;
 		$header['errmsg']  = $errmsg;
 		$header['content'] = $content;
 		
 		return $header;
 	}
 	
 	
 	public function loadcnews(){
 		
 		$newsUrl = null;
 		if(!empty($_GET['u'])) $newsUrl = $_GET['u']; 
 		if(empty($newsUrl))  $newsUrl = 'http://www.lankacnews.com'; 

 		//$newsUrl = "http://www.lankacnews.com";
 			
 		try{

 			$webpage = $this->get_web_page( $newsUrl );
 			
 			//$html = file_get_html($newsUrl);
 			$html = str_get_html($webpage['content']);

 			//$meta = $html->find('meta[http-equiv=refresh]',0)->content='';
 			
 			
 			$html->find('div[class=top_ads]',0)->innertext='';
 			$html->find('div[class=top_ad]',0)->innertext='';
 			//$html->find('div[id=google_image_div]',0)->innertext='';
 			
 			foreach($html->find('div[style=width:336px;margin:20px auto 0;height:280px;]') as $ad){
 				$ad->innertext='';
 				$ad->style='height:0';
 			}
 			
 			foreach($html->find('div[id=google_ads_frame1]') as $ad){
 				$ad->innertext='';
 			}
 			
 			foreach($html->find('ifrme[id=google_ads_frame2]') as $ad){
 				$ad->innertext='';
 			}
 			
 			foreach($html->find('div[class=right_col] div[class=top]') as $ad){
 				$ad->innertext='';
 			}
 			
 			foreach($html->find('div[class=ad_right]') as $ad){
 				$ad->innertext='';
 			}
 			
 			foreach($html->find('script') as $ad){
 				$ad->innertext='';
 			}
 			
 			foreach($html->find('div[class=google_ad]') as $ad){
 				$ad->innertext='';
 			}
 			
 			foreach($html->find('div[id=ad_unit]') as $ad){
 				$ad->innertext='';
 			}
 			
 			foreach($html->find('div[id="ads"]') as $ad){
 				$ad->innertext='';
 			}
 			
 			
 			foreach($html->find('img') as $img){
 				//$img->outertext = '';
 			}
 			
 			foreach($html->find('a') as $a){
 				$href = $a->href;
 				if(strstr($href,'http://') != false ) {
 					$a->href = base_url()."index.php/lankacnews/loadcnews/?u=$href";
 				}else{
 					$a->href = base_url()."index.php/lankacnews/loadcnews/";
 				}
 			}
 			
 			$data['homePage'] = $html;
 			$this->load->view('modifiedHomePage',$data);
 		

 			
 		} catch (Exception $e) {
 		
 			echo 'Error Caught:'.$e;
 		
 		}
 			
 		
 		
 	}//end of function
 	
 	
 	public function hot_news_article($article_id){
 		$article_id = 117460;
 		
 		$newsUrl = "http://lankacnews.com/sinhala/news/$article_id";
 		
 		try{
 			$html = file_get_html($newsUrl); 				
 			$data['hotNewsArticle'] = $html->find('div[id="page_content"] div[class="left_col"] div[class="block_outline"] div[class="news"]'); 				
 			$this->load->view('newsarticlelist',$data);
 			
 		} catch (Exception $e) {
 			
 			echo 'Error Caught:'.$e;
 			
 		}
 		
 	}//end of function
 	
 	
 	
 	public function fetch_hot_news(){
 		
 		$newsUrl = 'http://lankacnews.com/sinhala/news/';
 		$data = array();
 		
 		try{
 			$html = file_get_html($newsUrl);
 			
 			$hotNews = $html->find('div[id="page_content"] div[class="left_col"] div[class="block_outline category"]');
 				
 			foreach($hotNews as $c){
 			
 				$href = $c->children(0)->children(0)->children(0)->children(0)->attr['href'];
				$title = $c->children(0)->children(0)->children(0)->children(0)->plaintext;
				$dateSeg = $c->children(0)->children(0)->children(1)->plaintext;
				$href = explode('/', $href);
					 			
 				$data['aricle_id'] = $href[5];
 				$data['title'] = $title;
 				$data['dateSeg'] = $dateSeg;
 				//var_dump($data);exit;
 				
 				$this->lankacnews_model->save_hot_news($data);
 			}
 			
 		} catch (Exception $e) {
 			echo 'Error Caught:'.$e;
 		}
 		
 	}//end of function
	
	
	
 	public function format_contents()
 	{ 		
 		
 		//https://drive.google.com/uc?export=view&id=1uL131_l5AHYg-NxjRbbp1iddLUQizqj9Dg
 		
 			ini_set('max_execution_time', 600);//10 mints
 		
 			$debug = true; 			
 			$data['formated_str'] = null;
 			////'B001OC5UMQ'; 			
 		
 			if(isset($_POST['submit'])){
 		
 				 				
 				$html = str_get_html($_POST['txtPost']);
 				
 				//get all img tags
 				$imgs = $html->find('img');
 				
 				 				
 				//get first a link tag
 				$link = $html->find('a',0);
 				
 				//for each img tags
 				foreach($imgs as $img){
 					
 					//get img src
 					$file_name = $img->src;
 					//check if its a check price on amazon img
 					$pos = strpos($file_name, 'check-price-on-amazon.png');
 					$pos2 = strpos($file_name, 'check-price-at-amazon.png');
 					//if it so
 					if($pos !== false  || $pos2 !== false){
 						//echo strtolower ($file_name)."<br>";
 						//echo $pos."<br>";
 						//var_dump($img->attr);
 						
 						// change img file path to google img
 						$img->src = 'https://drive.google.com/uc?export=view&id=1uL131_l5AHYg-NxjRbbp1iddLUQizqj9Dg';
 						
 						//if ther is a parent tag it shoud not be having any a tags.. we want only unlnked ones.
 						//if($img->parent->tag !== 'a'){
 							
 							//now cote the image with a tag with amazon link
 							$img->parent->outertext = '<a href="'.$link->href.'" title="'.$link->title.'" target="_blank">'.$img.'</a>';
 							 							
 							//var_dump($img->parent->outertext); 							
 						//}///end if
 					}//end if
 					//var_dump( $img->parent);
 				}//end foreach
 				$data['formated_str'] = $html->save();					
 			}
 			$this->load->view('format',$data);
 			
 				
 	}//end of function
 	
 	

// Updated 9/12/2013

function random_user_agent() {
    $browser_freq = array (
        "Internet Explorer" => 11.8,
        "Firefox" => 28.2,
        "Chrome" => 52.9,
        "Safari" => 3.9,
        "Opera"=>1.8
    );

    $browser_strings = array (
        "Internet Explorer" => array (
            "Mozilla/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED/1.0",
            "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)",
            "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)",
            "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/5.0)",
            "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/4.0; InfoPath.2; SV1; .NET CLR 2.0.50727; WOW64)",
            "Mozilla/5.0 (compatible; MSIE 10.0; Macintosh; Intel Mac OS X 10_7_3; Trident/6.0)",
            "Mozilla/4.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/5.0)",
            "Mozilla/1.22 (compatible; MSIE 10.0; Windows 3.1)",
            "Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US))",
            "Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 7.1; Trident/5.0)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; Media Center PC 6.0; InfoPath.3; MS-RTC LM 8; Zune 4.7)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; Media Center PC 6.0; InfoPath.3; MS-RTC LM 8; Zune 4.7",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Zune 4.0; InfoPath.3; MS-RTC LM 8; .NET4.0C; .NET4.0E)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; chromeframe/12.0.742.112)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 2.0.50727; Media Center PC 6.0)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 2.0.50727; Media Center PC 6.0)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 2.0.50727; SLCC2; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Zune 4.0; Tablet PC 2.0; InfoPath.3; .NET4.0C; .NET4.0E)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; yie8)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET CLR 1.1.4322; .NET4.0C; Tablet PC 2.0)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; FunWebProducts)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; chromeframe/13.0.782.215)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; chromeframe/11.0.696.57)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0) chromeframe/10.0.648.205",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/4.0; GTB7.4; InfoPath.1; SV1; .NET CLR 2.8.52393; WOW64; en-US)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0; chromeframe/11.0.696.57)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/4.0; GTB7.4; InfoPath.3; SV1; .NET CLR 3.1.76908; WOW64; en-US)",
            "Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US))",
            "Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 7.1; Trident/5.0)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; Media Center PC 6.0; InfoPath.3; MS-RTC LM 8; Zune 4.7)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; Media Center PC 6.0; InfoPath.3; MS-RTC LM 8; Zune 4.7",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Zune 4.0; InfoPath.3; MS-RTC LM 8; .NET4.0C; .NET4.0E)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; chromeframe/12.0.742.112)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 2.0.50727; Media Center PC 6.0)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 2.0.50727; Media Center PC 6.0)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 2.0.50727; SLCC2; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Zune 4.0; Tablet PC 2.0; InfoPath.3; .NET4.0C; .NET4.0E)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; yie8)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET CLR 1.1.4322; .NET4.0C; Tablet PC 2.0)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; FunWebProducts)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; chromeframe/13.0.782.215)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; chromeframe/11.0.696.57)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0) chromeframe/10.0.648.205",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/4.0; GTB7.4; InfoPath.1; SV1; .NET CLR 2.8.52393; WOW64; en-US)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0; chromeframe/11.0.696.57)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/4.0; GTB7.4; InfoPath.3; SV1; .NET CLR 3.1.76908; WOW64; en-US)",
            "Mozilla/5.0 ( ; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)",
            "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/4.0; GTB7.4; InfoPath.2; SV1; .NET CLR 4.4.58799; WOW64; en-US)",
            "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/4.0; FDM; MSIECrawler; Media Center PC 5.0)",
            "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/4.0; GTB7.4; InfoPath.3; SV1; .NET CLR 3.4.53360; WOW64; en-US)",
            "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 5.1; Trident/5.0)",
            "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 1.1.4322; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; OfficeLiveConnector.1.4; OfficeLivePatch.1.3; .NET4.0C; .NE",
            "Mozilla/4.0 (compatible; MSIE 9.0; Windows 98; .NET CLR 3.0.04506.30)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 7.1; Trident/5.0; .NET CLR 2.0.50727; SLCC2; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; AskTB5.5)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; InfoPath.2; .NET4.0C; .NET4.0E)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET4.0C; .NET4.0E; InfoPath.3)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 2.0.50727; SLCC2; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET4.0C)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; FDM; .NET CLR 1.1.4322; .NET4.0C; .NET4.0E; Tablet PC 2.0)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; Tablet PC 2.0; InfoPath.3; .NET4.0E)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Trident/5.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; FDM; .NET4.0C; .NET4.0E; chromeframe/11.0.696.57)",
            "Mozilla/4.0 (compatible; U; MSIE 9.0; WIndows NT 9.0; en-US)",
            "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; FunWebProducts)"        
        ),
        "Firefox"=>array (
            "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/25.0",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:25.0) Gecko/20100101 Firefox/25.0",
            "Mozilla/5.0 (Windows NT 6.0; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:24.0) Gecko/20100101 Firefox/24.0",
            "Mozilla/5.0 (Windows NT 6.2; rv:22.0) Gecko/20130405 Firefox/23.0",
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20130406 Firefox/23.0",
            "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:23.0) Gecko/20131011 Firefox/23.0",
            "Mozilla/5.0 (Windows NT 6.2; rv:22.0) Gecko/20130405 Firefox/22.0",
            "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:22.0) Gecko/20130328 Firefox/22.0",
            "Mozilla/5.0 (Windows NT 6.1; rv:22.0) Gecko/20130405 Firefox/22.0",
            "Mozilla/5.0 (Windows NT 6.2; Win64; x64; rv:16.0.1) Gecko/20121011 Firefox/21.0.1",
            "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:16.0.1) Gecko/20121011 Firefox/21.0.1",
            "Mozilla/5.0 (Windows NT 6.2; Win64; x64; rv:21.0.0) Gecko/20121011 Firefox/21.0.0",
            "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:21.0) Gecko/20130331 Firefox/21.0",
            "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0",
            "Mozilla/5.0 (X11; Linux i686; rv:21.0) Gecko/20100101 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 6.2; rv:21.0) Gecko/20130326 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20130401 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20130331 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20130330 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 6.1; rv:21.0) Gecko/20130401 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 6.1; rv:21.0) Gecko/20130328 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 6.1; rv:21.0) Gecko/20100101 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 5.1; rv:21.0) Gecko/20130401 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 5.1; rv:21.0) Gecko/20130331 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 5.1; rv:21.0) Gecko/20100101 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 5.0; rv:21.0) Gecko/20100101 Firefox/21.0",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0",
            "Mozilla/5.0 (Windows NT 6.2; Win64; x64;) Gecko/20100101 Firefox/20.0",
            "Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20100101 Firefox/19.0",
            "Mozilla/5.0 (Windows NT 6.1; rv:14.0) Gecko/20100101 Firefox/18.0.1",
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:18.0) Gecko/20100101 Firefox/18.0",
            "Mozilla/5.0 (X11; Ubuntu; Linux armv7l; rv:17.0) Gecko/20100101 Firefox/17.0",
            "Mozilla/6.0 (Windows NT 6.2; WOW64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1",
            "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1",
            "Mozilla/5.0 (Windows NT 6.2; Win64; x64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1",
            "Mozilla/5.0 (Windows NT 6.1; rv:15.0) Gecko/20120716 Firefox/15.0a2",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.16) Gecko/20120427 Firefox/15.0a1",
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20120427 Firefox/15.0a1",
            "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:15.0) Gecko/20120910144328 Firefox/15.0.2",
            "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:15.0) Gecko/20100101 Firefox/15.0.1",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:15.0) Gecko/20121011 Firefox/15.0.1"
        ),
        "Chrome"=>array (
            "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.17 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36",
            "Mozilla/5.0 (X11; CrOS i686 4319.74.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.2 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1468.0 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1467.0 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1464.0 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36",
            "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36",
            "Mozilla/5.0 (X11; CrOS i686 3912.101.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.60 Safari/537.17",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1309.0 Safari/537.17",
            "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.15 (KHTML, like Gecko) Chrome/24.0.1295.0 Safari/537.15",
            "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.14 (KHTML, like Gecko) Chrome/24.0.1292.0 Safari/537.14",
            "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.13 (KHTML, like Gecko) Chrome/24.0.1290.1 Safari/537.13",
            "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.13 (KHTML, like Gecko) Chrome/24.0.1290.1 Safari/537.13",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.13 (KHTML, like Gecko) Chrome/24.0.1290.1 Safari/537.13",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_4) AppleWebKit/537.13 (KHTML, like Gecko) Chrome/24.0.1290.1 Safari/537.13",
            "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.13 (KHTML, like Gecko) Chrome/24.0.1284.0 Safari/537.13",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.6 Safari/537.11",
            "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.26 Safari/537.11",
            "Mozilla/5.0 (Windows NT 6.0) yi; AppleWebKit/345667.12221 (KHTML, like Gecko) Chrome/23.0.1271.26 Safari/453667.1221",
            "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.17 Safari/537.11",
            "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_0) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.79 Safari/537.4",
            "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/22.0.1207.1 Safari/537.1",
            "Mozilla/5.0 (X11; CrOS i686 2268.111.0) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1092.0 Safari/536.6",
            "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1090.0 Safari/536.6",
            "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/19.77.34.5 Safari/537.1",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.9 Safari/536.5",
            "Mozilla/5.0 (Windows NT 6.0) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.36 Safari/536.5",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1063.0 Safari/536.3",
            "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1063.0 Safari/536.3",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_0) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1063.0 Safari/536.3",
            "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1062.0 Safari/536.3",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1062.0 Safari/536.3",
            "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1061.1 Safari/536.3",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1061.1 Safari/536.3",
            "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1061.1 Safari/536.3",
            "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1061.0 Safari/536.3",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.24 (KHTML, like Gecko) Chrome/19.0.1055.1 Safari/535.24",
            "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/535.24 (KHTML, like Gecko) Chrome/19.0.1055.1 Safari/535.24",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.24 (KHTML, like Gecko) Chrome/19.0.1055.1 Safari/535.24",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.22 (KHTML, like Gecko) Chrome/19.0.1047.0 Safari/535.22",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.21 (KHTML, like Gecko) Chrome/19.0.1042.0 Safari/535.21",
            "Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.21 (KHTML, like Gecko) Chrome/19.0.1041.0 Safari/535.21",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.20 (KHTML, like Gecko) Chrome/19.0.1036.7 Safari/535.20",
            "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/18.6.872.0 Safari/535.2 UNTRUSTED/1.0 3gpp-gba UNTRUSTED/1.0",
            "Mozilla/5.0 (Macintosh; AMD Mac OS X 10_8_2) AppleWebKit/535.22 (KHTML, like Gecko) Chrome/18.6.872",
            "Mozilla/5.0 (X11; CrOS i686 1660.57.0) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.46 Safari/535.19",
            "Mozilla/5.0 (Windows NT 6.0; WOW64) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.45 Safari/535.19",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.45 Safari/535.19",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.45 Safari/535.19"
        ),
        "Safari"=>array(
            "Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/534.55.3 (KHTML, like Gecko) Version/5.1.3 Safari/534.53.10",
            "Mozilla/5.0 (iPad; CPU OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko ) Version/5.1 Mobile/9B176 Safari/7534.48.3",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; de-at) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; tr-TR) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; ko-KR) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; fr-FR) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; cs-CZ) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; ja-JP) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_8; zh-cn) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_8; ja-jp) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; ja-jp) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; zh-cn) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; sv-se) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; ko-kr) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; ja-jp) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; it-it) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; fr-fr) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; es-es) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; en-us) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; en-gb) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; de-de) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; sv-SE) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; ja-JP) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; de-DE) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; hu-HU) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; de-DE) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; ja-JP) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; it-IT) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; en-us) AppleWebKit/534.16+ (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; fr-ch) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_5; de-de) AppleWebKit/534.15+ (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_5; ar) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Android 2.2; Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; zh-HK) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5",
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; tr-TR) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5",
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; nb-NO) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5",
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; fr-FR) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-TW) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_8; zh-cn) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5",
            "Mozilla/5.0 (iPod; U; CPU iPhone OS 4_3_3 like Mac OS X; ja-jp) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",
            "Mozilla/5.0 (iPod; U; CPU iPhone OS 4_3_1 like Mac OS X; zh-cn) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8G4 Safari/6533.18.5",
            "Mozilla/5.0 (iPod; U; CPU iPhone OS 4_2_1 like Mac OS X; he-il) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148 Safari/6533.18.5",
            "Mozilla/5.0 (iPhone; U; ru; CPU iPhone OS 4_2_1 like Mac OS X; ru) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148a Safari/6533.18.5",
            "Mozilla/5.0 (iPhone; U; ru; CPU iPhone OS 4_2_1 like Mac OS X; fr) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148a Safari/6533.18.5",
            "Mozilla/5.0 (iPhone; U; fr; CPU iPhone OS 4_2_1 like Mac OS X; fr) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148a Safari/6533.18.5",
            "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_1 like Mac OS X; zh-tw) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8G4 Safari/6533.18.5",
            "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3 like Mac OS X; pl-pl) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F190 Safari/6533.18.5",
            "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3 like Mac OS X; fr-fr) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F190 Safari/6533.18.5",
            "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3 like Mac OS X; en-gb) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8F190 Safari/6533.18.5",
            "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_2_1 like Mac OS X; ru-ru) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148 Safari/6533.18.5",
            "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_2_1 like Mac OS X; nb-no) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148a Safari/6533.18.5",
            "Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/533.17.8 (KHTML, like Gecko) Version/5.0.1 Safari/533.17.8",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; th-th) AppleWebKit/533.17.8 (KHTML, like Gecko) Version/5.0.1 Safari/533.17.8",
            "Mozilla/5.0 (X11; U; Linux x86_64; en-us) AppleWebKit/531.2+ (KHTML, like Gecko) Version/5.0 Safari/531.2+",
            "Mozilla/5.0 (X11; U; Linux x86_64; en-ca) AppleWebKit/531.2+ (KHTML, like Gecko) Version/5.0 Safari/531.2+",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; ja-JP) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; es-ES) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; ja-JP) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_8; ja-jp) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_4_11; fr) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; zh-cn) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; ru-ru) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; ko-kr) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; it-it) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; HTC-P715a; en-ca) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; en-us) AppleWebKit/534.1+ (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; en-au) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; el-gr) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; ca-es) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_8; zh-tw) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_8; ja-jp) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_8; it-it) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16"        
        ),
        "Opera"=>array(
            "Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14",
            "Mozilla/5.0 (Windows NT 6.0; rv:2.0) Gecko/20100101 Firefox/4.0 Opera 12.14",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0) Opera 12.14",
            "Opera/12.80 (Windows NT 5.1; U; en) Presto/2.10.289 Version/12.02",
            "Opera/9.80 (Windows NT 6.1; U; es-ES) Presto/2.9.181 Version/12.00",
            "Opera/9.80 (Windows NT 5.1; U; zh-sg) Presto/2.9.181 Version/12.00",
            "Opera/12.0(Windows NT 5.2;U;en)Presto/22.9.168 Version/12.00",
            "Opera/12.0(Windows NT 5.1;U;en)Presto/22.9.168 Version/12.00",
            "Mozilla/5.0 (Windows NT 5.1) Gecko/20100101 Firefox/14.0 Opera/12.0",
            "Opera/9.80 (Windows NT 6.1; WOW64; U; pt) Presto/2.10.229 Version/11.62",
            "Opera/9.80 (Windows NT 6.0; U; pl) Presto/2.10.229 Version/11.62",
            "Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; fr) Presto/2.9.168 Version/11.52",
            "Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; de) Presto/2.9.168 Version/11.52",
            "Opera/9.80 (Windows NT 5.1; U; en) Presto/2.9.168 Version/11.51",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; de) Opera 11.51",
            "Opera/9.80 (X11; Linux x86_64; U; fr) Presto/2.9.168 Version/11.50",
            "Opera/9.80 (X11; Linux i686; U; hu) Presto/2.9.168 Version/11.50",
            "Opera/9.80 (X11; Linux i686; U; ru) Presto/2.8.131 Version/11.11",
            "Opera/9.80 (X11; Linux i686; U; es-ES) Presto/2.8.131 Version/11.11",
            "Mozilla/5.0 (Windows NT 5.1; U; en; rv:1.8.1) Gecko/20061208 Firefox/5.0 Opera 11.11",
            "Opera/9.80 (X11; Linux x86_64; U; bg) Presto/2.8.131 Version/11.10",
            "Opera/9.80 (Windows NT 6.0; U; en) Presto/2.8.99 Version/11.10",
            "Opera/9.80 (Windows NT 5.1; U; zh-tw) Presto/2.8.131 Version/11.10",
            "Opera/9.80 (Windows NT 6.1; Opera Tablet/15165; U; en) Presto/2.8.149 Version/11.1",
            "Opera/9.80 (X11; Linux x86_64; U; Ubuntu/10.10 (maverick); pl) Presto/2.7.62 Version/11.01",
            "Opera/9.80 (X11; Linux i686; U; ja) Presto/2.7.62 Version/11.01",
            "Opera/9.80 (X11; Linux i686; U; fr) Presto/2.7.62 Version/11.01",
            "Opera/9.80 (Windows NT 6.1; U; zh-tw) Presto/2.7.62 Version/11.01",
            "Opera/9.80 (Windows NT 6.1; U; zh-cn) Presto/2.7.62 Version/11.01",
            "Opera/9.80 (Windows NT 6.1; U; sv) Presto/2.7.62 Version/11.01",
            "Opera/9.80 (Windows NT 6.1; U; en-US) Presto/2.7.62 Version/11.01",
            "Opera/9.80 (Windows NT 6.1; U; cs) Presto/2.7.62 Version/11.01",
            "Opera/9.80 (Windows NT 6.0; U; pl) Presto/2.7.62 Version/11.01",
            "Opera/9.80 (Windows NT 5.2; U; ru) Presto/2.7.62 Version/11.01",
            "Opera/9.80 (Windows NT 5.1; U;) Presto/2.7.62 Version/11.01",
            "Opera/9.80 (Windows NT 5.1; U; cs) Presto/2.7.62 Version/11.01",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.13) Gecko/20101213 Opera/9.80 (Windows NT 6.1; U; zh-tw) Presto/2.7.62 Version/11.01",
            "Mozilla/5.0 (Windows NT 6.1; U; nl; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 11.01",
            "Mozilla/5.0 (Windows NT 6.1; U; de; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 11.01",
            "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; de) Opera 11.01",
            "Opera/9.80 (X11; Linux x86_64; U; pl) Presto/2.7.62 Version/11.00",
            "Opera/9.80 (X11; Linux i686; U; it) Presto/2.7.62 Version/11.00",
            "Opera/9.80 (Windows NT 6.1; U; zh-cn) Presto/2.6.37 Version/11.00",
            "Opera/9.80 (Windows NT 6.1; U; pl) Presto/2.7.62 Version/11.00",
            "Opera/9.80 (Windows NT 6.1; U; ko) Presto/2.7.62 Version/11.00",
            "Opera/9.80 (Windows NT 6.1; U; fi) Presto/2.7.62 Version/11.00",
            "Opera/9.80 (Windows NT 6.1; U; en-GB) Presto/2.7.62 Version/11.00",
            "Opera/9.80 (Windows NT 6.1 x64; U; en) Presto/2.7.62 Version/11.00",
            "Opera/9.80 (Windows NT 6.0; U; en) Presto/2.7.39 Version/11.00",
            "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.7.39 Version/11.00",
            "Opera/9.80 (Windows NT 5.1; U; MRA 5.5 (build 02842); ru) Presto/2.7.62 Version/11.00",
            "Opera/9.80 (Windows NT 5.1; U; it) Presto/2.7.62 Version/11.00",
            "Mozilla/5.0 (Windows NT 6.0; U; ja; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 11.00",
            "Mozilla/5.0 (Windows NT 5.1; U; pl; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 11.00",
            "Mozilla/5.0 (Windows NT 5.1; U; de; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 11.00",
            "Mozilla/4.0 (compatible; MSIE 8.0; X11; Linux x86_64; pl) Opera 11.00",
            "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; fr) Opera 11.00",
            "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; ja) Opera 11.00",
            "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; en) Opera 11.00",
            "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; pl) Opera 11.00",
            "Opera/9.80 (Windows NT 6.1; U; pl) Presto/2.6.31 Version/10.70",
            "Mozilla/5.0 (Windows NT 5.2; U; ru; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 10.70",
            "Mozilla/5.0 (Windows NT 5.1; U; zh-cn; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 10.70",
            "Opera/9.80 (Windows NT 5.2; U; zh-cn) Presto/2.6.30 Version/10.63",
            "Opera/9.80 (Windows NT 5.2; U; en) Presto/2.6.30 Version/10.63",
            "Opera/9.80 (Windows NT 5.1; U; MRA 5.6 (build 03278); ru) Presto/2.6.30 Version/10.63",
            "Opera/9.80 (Windows NT 5.1; U; pl) Presto/2.6.30 Version/10.62",
            "Mozilla/5.0 (X11; Linux x86_64; U; de; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 10.62",
            "Mozilla/4.0 (compatible; MSIE 8.0; X11; Linux x86_64; de) Opera 10.62",
            "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; en) Opera 10.62",
            "Opera/9.80 (X11; Linux i686; U; pl) Presto/2.6.30 Version/10.61",
            "Opera/9.80 (X11; Linux i686; U; es-ES) Presto/2.6.30 Version/10.61",
            "Opera/9.80 (Windows NT 6.1; U; zh-cn) Presto/2.6.30 Version/10.61",
            "Opera/9.80 (Windows NT 6.1; U; en) Presto/2.6.30 Version/10.61",
            "Opera/9.80 (Windows NT 6.0; U; it) Presto/2.6.30 Version/10.61",
            "Opera/9.80 (Windows NT 5.2; U; ru) Presto/2.6.30 Version/10.61",
            "Opera/9.80 (Windows 98; U; de) Presto/2.6.30 Version/10.61",
            "Opera/9.80 (Macintosh; Intel Mac OS X; U; nl) Presto/2.6.30 Version/10.61",
            "Opera/9.80 (X11; Linux i686; U; en) Presto/2.5.27 Version/10.60",
            "Opera/9.80 (Windows NT 6.0; U; nl) Presto/2.6.30 Version/10.60",
            "Opera/10.60 (Windows NT 5.1; U; zh-cn) Presto/2.6.30 Version/10.60",
            "Opera/10.60 (Windows NT 5.1; U; en-US) Presto/2.6.30 Version/10.60",
            "Opera/9.80 (X11; Linux i686; U; it) Presto/2.5.24 Version/10.54",
            "Opera/9.80 (X11; Linux i686; U; en-GB) Presto/2.5.24 Version/10.53",
            "Mozilla/5.0 (Windows NT 5.1; U; zh-cn; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 10.53",
            "Mozilla/5.0 (Windows NT 5.1; U; Firefox/5.0; en; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 10.53",
            "Mozilla/5.0 (Windows NT 5.1; U; Firefox/4.5; en; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 10.53",
            "Mozilla/5.0 (Windows NT 5.1; U; Firefox/3.5; en; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 10.53",
            "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; ko) Opera 10.53",
            "Opera/9.80 (Windows NT 6.1; U; fr) Presto/2.5.24 Version/10.52",
            "Opera/9.80 (Windows NT 6.1; U; en) Presto/2.5.22 Version/10.51",
            "Opera/9.80 (Windows NT 6.0; U; cs) Presto/2.5.22 Version/10.51",
            "Opera/9.80 (Windows NT 5.2; U; ru) Presto/2.5.22 Version/10.51",
            "Opera/9.80 (Linux i686; U; en) Presto/2.5.22 Version/10.51",
            "Mozilla/5.0 (Windows NT 6.1; U; en-GB; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 10.51",
            "Mozilla/5.0 (Linux i686; U; en; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 Opera 10.51",
            "Mozilla/4.0 (compatible; MSIE 8.0; Linux i686; en) Opera 10.51",
            "Opera/9.80 (Windows NT 6.1; U; zh-tw) Presto/2.5.22 Version/10.50",
            "Opera/9.80 (Windows NT 6.1; U; zh-cn) Presto/2.5.22 Version/10.50",
            "Opera/9.80 (Windows NT 6.1; U; sk) Presto/2.6.22 Version/10.50",
            "Opera/9.80 (Windows NT 6.1; U; ja) Presto/2.5.22 Version/10.50",
            "Opera/9.80 (Windows NT 6.0; U; zh-cn) Presto/2.5.22 Version/10.50",
            "Opera/9.80 (Windows NT 5.1; U; sk) Presto/2.5.22 Version/10.50",
            "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.5.22 Version/10.50",
            "Opera/10.50 (Windows NT 6.1; U; en-GB) Presto/2.2.2",
            "Opera/9.80 (S60; SymbOS; Opera Tablet/9174; U; en) Presto/2.7.81 Version/10.5",
            "Opera/9.80 (X11; U; Linux i686; en-US; rv:1.9.2.3) Presto/2.2.15 Version/10.10",
            "Opera/9.80 (X11; Linux x86_64; U; it) Presto/2.2.15 Version/10.10",
            "Opera/9.80 (Windows NT 6.1; U; de) Presto/2.2.15 Version/10.10",
            "Opera/9.80 (Windows NT 6.0; U; Gecko/20100115; pl) Presto/2.2.15 Version/10.10",
            "Opera/9.80 (Windows NT 6.0; U; en) Presto/2.2.15 Version/10.10",
            "Opera/9.80 (Windows NT 5.1; U; de) Presto/2.2.15 Version/10.10",
            "Opera/9.80 (Windows NT 5.1; U; cs) Presto/2.2.15 Version/10.10",
            "Mozilla/5.0 (Windows NT 6.0; U; tr; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 10.10",
            "Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux i686; de) Opera 10.10",
            "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 6.0; tr) Opera 10.10",
            "Opera/9.80 (X11; Linux x86_64; U; en-GB) Presto/2.2.15 Version/10.01",
            "Opera/9.80 (X11; Linux x86_64; U; en) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (X11; Linux x86_64; U; de) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (X11; Linux i686; U; ru) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (X11; Linux i686; U; pt-BR) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (X11; Linux i686; U; pl) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (X11; Linux i686; U; nb) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (X11; Linux i686; U; en-GB) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (X11; Linux i686; U; en) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (X11; Linux i686; U; Debian; pl) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (X11; Linux i686; U; de) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (Windows NT 6.1; U; zh-cn) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (Windows NT 6.1; U; fi) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (Windows NT 6.1; U; en) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (Windows NT 6.1; U; de) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (Windows NT 6.1; U; cs) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (Windows NT 6.0; U; en) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (Windows NT 6.0; U; de) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (Windows NT 5.2; U; en) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (Windows NT 5.1; U; zh-cn) Presto/2.2.15 Version/10.00",
            "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.2.15 Version/10.00"        
        )
    );

    $max = 0;
    $rcount = 0;
    $browser_type = '';

    foreach($browser_freq as $k=>$v) $max += $v;
    $roll = rand(0,$max);
    foreach($browser_freq as $k=>$v) if (($roll <= ($rcount += $v)) and (!$browser_type)) $browser_type = $k;
    $user_agent_array = $browser_strings[$browser_type];
    shuffle($user_agent_array);
    $user_agent = $user_agent_array[0];
    return $user_agent;
}


	
			
}//end of class	
?>