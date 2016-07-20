<?php
if ( ! function_exists('getHeadersList'))
{
         function getHeadersList($one_record)
         {
		/**
		 * getHeadersList() function
		 *
		 * Parses data array and return list of headers
		 *
		 * @param $one_record one record from data array
		 *
		 * @return comma separated list of headers
		 */
			$out = array();
			$one_record=array_slice($one_record,1);
			foreach($one_record as $h)
			{
				$out[] = $h['header'];
			}
			
			 return join(',',$out);
         }
}
//------------------------------------------------------
if ( ! function_exists('convertHTMLTable'))
{
			/**
		 * convertHTMLTable() function
		 *
		 * Prepares and echoes HTML table for jQuery datatables
		 *
		 * @param $data Data Array
		 *
		 * @return void
		 */
     function convertHTMLTable($data,$widths='',$colList = false,$styleSheet = true)
     {
        $widthArr = array();
        $dashBoardURL = array();
        if(is_array($widths)) $widthArr = $widths ;
        ////////////////////////////////////////////////////
    	  $dataset = $data['dataset']['result_set'];
        
        
    	 $pk = explode(",",$data['dataset']['metadata']['PK']);
        //------------ Create headers -------------------
        //$headerList = getHeadersList($dataset['dataset']['metadata']);
        //$headers = explode(',',$headerList);
        $headers =array_slice($data['dataset']['metadata'],1);
		if (!$styleSheet){
			$styleSheet = "";
		}elseif($styleSheet === true ||$styleSheet ==''){
			$styleSheet = "\n".'<link rel="stylesheet" type="text/css" href="'.base_url("public/css/html_gridview.css").'" media="screen">' ;
		}else{
			$styleSheet = "\n".'<link rel="stylesheet" type="text/css" href="'.$styleSheet.'" media="screen">' ;
		}
        $tableHTML = "\n<table id='cigen_datatable' style='' class=''>\n\t~HEADERS~\n\t\n\t<tbody>~DATAROWS~\n\n\t</tbody>\n\t<tfoot><tr><td colspan='".(count($colList?$colList:$headers))."'></tr></tfoot>\n</table>";
        $headerHTML = '';
        $headerHTML .= "\n\t<colgroup>";
        foreach($headers as $key=>$h)     //Set col widths
  			{
          if($h['width'] =="" || $h['width'] < "15"){
          	$width="50";              
          }else{
          	$width=$h['width'];              	
          }
          
          if ($colList) {            
            if (in_array($key,$colList) ) { 
              if (sizeof($widthArr)>0) { //User specified column widths are active
                $width=$widthArr[$key];
              }
              $headerHTML .= "\n\t\t\t<col style='width:{$width}em;' >";
            }
          }else{
            $headerHTML .= "\n\t\t\t<col style='width:{$width}em;'>";
          }
  			}
        $headerHTML .= "\n\t</colgroup>\n\t<thead>";
        $headerHTML .= "\n\t\t<tr class=\"headerrow\" >";
  			foreach($headers as $key=>$h)
  			{
          if ($colList) {
            if (in_array($key,$colList) ) {
              //$headerHTML .= "\n\t\t\t<th class=\"headercell\" style='width:{$h['width']}; text-align: center;'>{$h['header']}</th>";
              $headerHTML .= "\n\t\t\t<th class=\"headercell\" style='text-align: center;'>{$h['header']}</th>";
            }
          }else{
            //$headerHTML .= "\n\t\t\t<th class=\"headercell\" style='width:{$h['width']}; text-align: center;'>{$h['header']}</th>";
            $headerHTML .= "\n\t\t\t<th class=\"headercell\" style='text-align: center;'>{$h['header']}</th>";
          }
  			}
        $headerHTML .= "\n\t\t</tr>\n\t</thead>";
        // ----------end of headers -----------------
  			$nrows = sizeof($dataset) ;
        $datarowHTML = '';
        for($i=1;$i<=$nrows;$i++)
        {
          $datarowHTML .= "\n\t\t<tr class=\"dararow\" >\n";
          foreach($dataset[$i] as $key=>$v)
          {   
            if(!(isset($v))) {
              $thisValue = '';
            } else {
              $thisValue = $v;
            }
            if ($colList){
              if(in_array($key,$colList) ){
                $datarowHTML .= "\n\t\t\t<td class=\"datacell\">".stripslashes($thisValue)."</td>";
              }
            }else {
              $datarowHTML .= "\n\t\t\t<td class=\"datacell\">".stripslashes($thisValue)."</td>\n";
            }
  				}
          $datarowHTML .= "\n\t\t</tr>\n";
  			 }
  			 $out = str_replace('~HEADERS~',$headerHTML,(str_replace('~DATAROWS~',$datarowHTML,$tableHTML)));
  			 return $styleSheet.$out;
      }
}

//-----------------------------------------------------
/*
if ( ! function_exists('convertHTMLTable'))
{
			/**
		 * convertHTMLTable() function
		 *
		 * Prepares and echoes HTML table for jQuery datatables
		 *
		 * @param $data Data Array
		 *
		 * @return void
		 *
     function convertHTMLTable($data,$widths='',$colList = false)
     {
        $widthArr = array();
        $dashBoardURL = array();
        if(is_array($widths)) $widthArr = $widths ;
        ////////////////////////////////////////////////////
    	  $dataset = $data['dataset']['result_set'];
        
        
    	 $pk = explode(",",$data['dataset']['metadata']['PK']);
        //------------ Create headers -------------------
        //$headerList = getHeadersList($dataset['dataset']['metadata']);
        //$headers = explode(',',$headerList);
        $headers =array_slice($data['dataset']['metadata'],1);
        $tableHTML = "\n<table id='datatable' style='' class=''>\n\t~HEADERS~\n\t\n\t<tbody>~DATAROWS~\n\n\t</tobody>\n\t<tfoot>~HEADERS~\n\t</tfoot>\n</table>";
        $headerHTML = '';
        $headerHTML .= "\n\t<colgroup>";
        foreach($headers as $key=>$h)     //Set col widths
  			{
          if($h['width'] =="" || $h['width'] < "15"){
          	$width="50";              
          }else{
          	$width=$h['width'];              	
          }
          
          if ($colList) {            
            if (in_array($key,$colList) ) { 
              if (sizeof($widthArr)>0) { //User specified column widths are active
                $width=$widthArr[$key];
              }
              $headerHTML .= "\n\t\t\t<col style='width:{$width}em;' >";
            }
          }else{
            $headerHTML .= "\n\t\t\t<col style='width:{$width}em;'>";
          }
  			}
        $headerHTML .= "\n\t</colgroup>\n\t<thead>";
        $headerHTML .= "\n\t\t<tr>";
  			foreach($headers as $key=>$h)
  			{
          if ($colList) {
            if (in_array($key,$colList) ) {
              //$headerHTML .= "\n\t\t\t<th class=\"headercell\" style='width:{$h['width']}; text-align: center;'>{$h['header']}</th>";
              $headerHTML .= "\n\t\t\t<th class=\"headercell\" style='text-align: center;'>{$h['header']}</th>";
            }
          }else{
            //$headerHTML .= "\n\t\t\t<th class=\"headercell\" style='width:{$h['width']}; text-align: center;'>{$h['header']}</th>";
            $headerHTML .= "\n\t\t\t<th class=\"headercell\" style='text-align: center;'>{$h['header']}</th>";
          }
  			}
        $headerHTML .= "\n\t\t</tr>\n\t</thead>";
        // ----------end of headers -----------------
  			$nrows = sizeof($dataset) ;
        $datarowHTML = '';
        for($i=1;$i<=$nrows;$i++)
        {
          $datarowHTML .= "\n\t\t<tr>\n";
          foreach($dataset[$i] as $key=>$v)
          {   
            if(!(isset($v))) {
              $thisValue = '';
            } else {
              $thisValue = $v;
            }
            if ($colList){
              if(in_array($key,$colList) ){
                $datarowHTML .= "\n\t\t\t<td class=\"datarow\">".stripslashes($thisValue)."</td>";
              }
            }else {
              $datarowHTML .= "\n\t\t\t<td class=\"datarow\">".stripslashes($thisValue)."</td>\n";
            }
  				}
          $datarowHTML .= "\n\t\t</tr>\n";
  			 }
  			 $out = str_replace('~HEADERS~',$headerHTML,(str_replace('~DATAROWS~',$datarowHTML,$tableHTML)));
  			 return $out;
      }
} */

if ( ! function_exists('getArrayElements'))
{
	/**
	 * getArrayElements() function
	 *
	 * Returns wanter array element, if exists
	 *
	 * @param $array , the array
	 *
	 * @param $key , key to be checked
	 *
	 * @return String, array element value if found, otherwise an empty string
	 */
	function getArrayElements($array,$key)
	{
		if(is_array($array))
		{
			if(array_key_exists($key,$array))
			{
				return $array[$key];
			}
			else
				return '';
		}
		else
			return '';
	}
}

/**
 * input() function
 *
 * Prepares and echoes HTML Code for Input feilds
 *
 * @param $data Data Array
 *
 * @param $pkval , Value of the primary key field of data record, to identity record
 *
 * @param $field, Name of database table field
 *
 * @param $type Optional, type of input tag, Default Text
 *
 * @return void
 */
function input($data,$pkval,$field,$type="text")
{

	$dataset = $data['dataset']['result_set'];
    $pk = explode(",",$data['dataset']['metadata']['PK']);
	
	$nrows = sizeof($dataset) - 1;
	for($ix=1;$ix<=$nrows;$ix++)
	{
		if($ix > 0)
		{
			unset($pk_g);
			foreach($pk as $col){
				$pk_g[] = $dataset[$i][$col];
				 
			}
			$pk_grid=join("_",$pk_g);
			unset($pk_g);
			if($pk_grid == $pkval)
			{
				echo '<input type="'.$type.'" id="'.$field.$pkval.'" name="'.$field.$pkval.'" value="'.$dataset[$ix][$field] .'">';
			}
		}
		$pk_grid="";
		$ix++;
	}
}




////////////////////////////////////////////////////////////////////////////////////////////
// old drop down function for dinishika's refference . Pls delete this section after reffering them.

/*
 * 
 * 
function dropdown($data,$selectByText=null,$selectByVal,$label, $fieldName, $display,$format='html',$keyfield=true,$multiple=false,$skipfield=false,$optionsOnly=false)
{
	
	$dataset = $data['dataset']['result_set'];
	$pk = $data['dataset']['metadata']['PK'];

  if ($selectByText && $selectByVal) $selectByText = null; // Choos ByVal if both provided //WCD
	//if its calling for multiple select box with or without default selected ids
	// IF $selected is a sring of comaseperated values we do not need all below code
  
   
	
    switch ($format) {
        
    case 'html':
	  
    	   	
	    if (!is_array($pk)) $pk = explode(',', $pk);
    	$nrows = sizeof($dataset) - 1;
        $mul = ($multiple)?'[]':'';
	    if(!$optionsOnly) {     
        echo '<select size="1"  id="'.$fieldName.'" name="'.$fieldName.$mul.'" label="'.$label.'" ';
      
	    if($multiple) echo ' MULTIPLE ';
        echo '>',"\n";          
      }
        if(empty($selectByText) && empty($selectByVal)){
        	echo "\t",'<option value="" selected="selected" >Select.... </option>',"\n";
	    }else{
			echo "\t",'<option value="" >Select.... </option>',"\n";
		}

 		
          
           for($ix=1;$ix<=$nrows;$ix++) {
              if($ix > 0) {
              	$val='';
                //for composite keys construct the composit value with '_'
                //suffix
                foreach($pk as $key){
                  $val .= $dataset[$ix][$key].'_';
                }
                $val=trim($val,'_');
                $selectedAttrib = null;
                $foundText= false; $foundVal=false;
    		
		
		  if(!empty($selectByText) || !empty($selectByVal) ){
                 
 			//Error suppression used to suppres the empty string error. WCD
			if(!empty($selectByText) && !empty($dataset[$ix][$fieldName])){
                     	$foundText = strpos($selectByText,(string)$dataset[$ix][$fieldName]);
			}else{ $foundText = false;}
			
			if(!empty($selectByVal) && !empty($val)){
				$foundVal = strpos($selectByVal,$val) ;
			}else{ $foundVal = false;}
			
			// for some reason following code returns 1 or 0 insted of booleon true of false.therefor commented
			//$foundText = (strcasecmp((string)$selectByText, (string)$dataset[$ix][$fieldName]) == 0)? true: false ;
			//$foundVal = (strcasecmp((string)$selectByVal,(string) $val) == 0)?  true : false ;
                }
			
		
			
				
		if($foundText !== false || $foundVal !== false ) {
			$selectedAttrib = 'selected="selected"';									
		}

		echo "\t",'<option value="'.$val.'" '.$selectedAttrib.' >'.formatString($dataset[$ix][$display]).'</option>',"\n";
              }//end if(ix)   
            }//end for     
            
            echo '</select>',"\n";
                      			
		break;
				
        case 'xml':
        	
        	if (!is_array($pk)) $pk = explode(',', $pk);
        	
        	$nrows = sizeof($dataset);
        	
        	if($multiple) { $type='multiselect'; } else { $type='select';}
        	 
        	$name=$fieldName;
        	
        	if($skipfield==true) {
        		$name=$fieldName.'_skip';
        	}
        	
                if(!$optionsOnly) {
        	echo '<item type="'.$type.'" name="'.$name.'" id="'.$name.'" label="'.$label.'">';        	
        	}

        	if(empty($selectByVal)&& empty($selectByText)){
        		echo "\n\t",'<option value=""  selected="true"  label="Select...."  >Select....</option>';
        	}else{
        		echo "\n\t",'<option value="" label="Select...."  >Select....</option>';
        	}
        	
        	
        	for($ix=1;$ix<=$nrows;$ix++){
        		
          	if($ix > 0)
          		{
          			$val='';
          			foreach($pk as $key){
          				$val .= $dataset[$ix][$key].'_';
          			}
          	
          			$val=trim($val,'_');        
                  $foundText= false; $foundVal=false;				
                  if(!empty($selectByVal)|| !empty($selectByText)){
                    //Error suppression used to suppres the empty string error. WCD
                    $foundText = @strpos($selectByText,(string)$dataset[$ix][$fieldName]);			
			//(strcasecmp($selectByText, (string)$dataset[$ix][$fieldName]) == 0)? $foundText = true :$foundText = false ;
			$foundVal = strpos($selectByVal,$val) ;
			//(strcasecmp($selectByVal, $val) == 0)? $foundVal = true :$foundVal = false ;

                  }
        	        if($foundText !== false || $foundVal !== false ) {
         						$selectedAttrib = 'selected="true"';
        					}else{
        						$selectedAttrib = '';
        					}        				
          				echo "\n\t",'<option value="'.$val.'" '.$selectedAttrib.' label="'.formatString($dataset[$ix][$display]).'" >'.formatString($dataset[$ix][$display]).'</option>';
          	 
          	}//end if(ix)
        		
        	}//end for
        	
               if(!$optionsOnly) {
        	echo "\n\t",'</item>';
               }	
         
         break;      

         
        case 'list':
            //to do
            break;
        
        case 'json':
            //todo
            break;          
            
   }//end switch case
   
}//end of dropdown();

 * 
 * */

///////////////////////////////////////////////////////////////////////////////////////////


/**
 * dropdown() function
 *
 * Prepares and echoes HTML/DHTMLX Code for Dropdown list
 *
 * @param $data - required - Data Array
 * 
 * @param $name - required - The name and the id of the dropdown controller.
 * 
 * @param $fieldName - required - The field name of the record set which is to be listed in options.
 *
 * @param $selectByText - optional - The option text(s) to be selected on load of the dropdown.  
 *
 * @param $selectByVal - optional -The option value(s) to be selected on load of the dropdown 
 *
 * @param $format - optional - The dropdown output format as html or xml
 * 
 * @param $multiple - optional - to specify either multiple select or single select option
 * 
 * @param $optionsOnly - optional - To specify to output only options list without select controller
 * 
 * @return void
 * 
 * Updates:
 * 
 * 1. Rearranged parameter list from required to optional  from left to right 
 * 2. Removed unwanted parameters - $keyfield,$skipfield,$label
 * 			
 * Updated on 11/4/2013
 * 
 * By: MRoshan
 * 
 * 
 *   $dataArray = array('data' => $data,
 *   					'fieldName' => $fieldName,  (html name attrib)
 *   					'label' => $label,
 *   					'selectByText' => null,
 *   					'selectByVal' => null,
 *   					'format' => 'html',
 *   					'multiple' => false,
 *   					'optionsOnly' => false
 *    );
 * 
 * //function dropdown($data, $name, $fieldName,$label, $selectByText=null, $selectByVal=null, $format='html', $multiple=false, $optionsOnly=false)

 * */


function dropdown($dataArray)
{
	
	/* 
	 * following set of codes are common for both xml and html code segments. 
	 * 
	 * */
	
    	$dataset = $dataArray['data']['dataset']['result_set'];
    	$pk = $dataArray['data']['dataset']['metadata']['PK'];
	   $fieldName = isset($dataArray['fieldName'])? $dataArray['fieldName']: null;
	   $selectByText = isset($dataArray['selectByText'])? $dataArray['selectByText']:null;
	   $selectByVal = isset($dataArray['selectByVal'])? $dataArray['selectByVal']:null;
	   $format = isset($dataArray['format'])? $dataArray['format']:null;
	  $multiple = isset($dataArray['multiple'])? $dataArray['multiple']:false;
	  $optionsOnly = isset($dataArray['optionsOnly'])? $dataArray['optionsOnly']:false;
	  $label = isset($dataArray['label'])? $dataArray['label']:null;
         $display = isset($dataArray['display'])? $dataArray['display']:null;
        $required = isset($dataArray['required'])? $dataArray['required']:null;
         
    if ($selectByText && $selectByVal) $selectByText = null; // Choos ByVal if both provided //WCD
 
	
    switch ($format) {
        
    case 'html':
	  
    	   	
	    if (!is_array($pk)) $pk = explode(',', $pk);
    	$nrows = sizeof($dataset) ;
        $mul = ($multiple)?'[]':'';
        
	    if(!$optionsOnly) {     
        	echo '<select size="1"  id="'.$fieldName.'" name="'.$fieldName.$mul.'" label="'.$label.'" ';      
	    	if($multiple) echo ' MULTIPLE ';
        	echo '>',"\n";          
      	}//end if(options)
      		
        if(empty($selectByText) && empty($selectByVal)){
        	echo "\t",'<option value="" selected="selected" >Select.... </option>',"\n";
	    }else{
			echo "\t",'<option value="" >Select.... </option>',"\n";
		}		

		$sbt = null;
        for($ix=1;$ix<=$nrows;$ix++) {
        	
        	if($ix > 0) {

        		$val='';
                //for composite keys construct the composit value with '_'
                //suffix
                
                foreach($pk as $key){
                  $val .= $dataset[$ix][$key].'_';
                }
                
                $val=trim($val,'_');
                $selectedAttrib = null;
                $foundText= false; $foundVal=false;
    		
		
		  		if(!empty($selectByText) || !empty($selectByVal) ){
		  			
		  			if(!empty($selectByText) && !empty($dataset[$ix][$display])){
		  				
                     	//$foundText = strpos($selectByText,(string)$dataset[$ix][$display]);
                     	$sbt = explode(',',$selectByText);
                     	$foundText = in_array($dataset[$ix][$display],$sbt);
                     	
					}else{ 
						$foundText = false;
					}
			
					if(!empty($selectByVal) && !empty($val)){
						$foundVal = strpos($selectByVal,$val) ;
					}else{ 
						$foundVal = false;
					}			
		
           }//end if(!empty($selectByText)
           
           if($foundText !== false || $foundVal !== false ) {
           	
		   		$selectedAttrib = 'selected="selected"';									
		   }
		   echo "\t",'<option value="'.$val.'" '.$selectedAttrib.' >'.formatString($dataset[$ix][$display]).'</option>',"\n";
			
      	}//end if(ix)
      		   
      }//end for     

      if(!$optionsOnly) {
      		echo '</select>',"\n";
      }
                      			
	break;
				
    case 'xml':
        	
        	
        	if (!is_array($pk)) $pk = explode(',', $pk);
        	
        	$nrows = sizeof($dataset);
        	
        	if($multiple) { $type='multiselect'; } else { $type='select';}
                if($required) { $validate='NotEmpty'; } 
        	 
        	               	
            if(!$optionsOnly) {
                        if($required) {
                        echo '<item type="'.$type.'" name="'.$fieldName.'" id="'.$fieldName.'" label="'.$label.'" validate="NotEmpty" required="true">';  
                        }else {    
        		echo '<item type="'.$type.'" name="'.$fieldName.'" id="'.$fieldName.'" label="'.$label.'">';     
                        }   	
        	}

        	if(empty($selectByVal)&& empty($selectByText)){
        		echo "\n\t",'<option value=""  selected="true"  label="Select...."  >Select....</option>';
        	}else{
        		echo "\n\t",'<option value="" label="Select...."  >Select....</option>';
        	}
        	
        	$sbt = null;
        	for($ix=1;$ix<=$nrows;$ix++){ 
        		
          	if($ix > 0)
          		{
          			$val='';
          			foreach($pk as $key){
          				$val .= $dataset[$ix][$key].'_';
          			}
          	
          			$val=trim($val,'_');        
          			
                  	$foundText= false; $foundVal=false;				
                  
                  	if(!empty($selectByVal)|| !empty($selectByText)){

                  		if(!empty($selectByText) && !empty($dataset[$ix][$display])){
                  		
                  			//$foundText = strpos($selectByText,(string)$dataset[$ix][$display]);
                  			$sbt = explode(',',$selectByText);
                     		$foundText = in_array($dataset[$ix][$display],$sbt);
                  		}else{
                  			$foundText = false;
                  		}
                  			
                  		if(!empty($selectByVal) && !empty($val)){
                  			$foundVal = strpos($selectByVal,$val) ;
                  		}else{
                  			$foundVal = false;
                  		}	
             
                  }//endif
                  
        	      if($foundText !== false || $foundVal !== false ) {
         		  		$selectedAttrib = 'selected="true"';
        		  }else{
        				$selectedAttrib = '';
        		  }        				
          		
        		  echo "\n\t",'<option value="'.$val.'" '.$selectedAttrib.' label="'.formatString($dataset[$ix][$display]).'" >'.formatString($dataset[$ix][$display]).'</option>';
          	 
          		}//end if(ix)
        		
        	}//end for
        	
            if(!$optionsOnly) {
        		echo "\n\t",'</item>';
            }
           	
         break;            
        case 'list':
            //to do
            break;
        
        case 'json':
            //todo
            break;          
            
   }//end switch case
   
}//end of dropdown();





/*###################################### Added by Dinishika ####################*/
function formatString($string) {
            
           $string = str_replace ( '&', '&amp;', $string );

           return $string;
}
/*###########################################################################*/


if ( ! function_exists('converDHTMLXGrid')) {
  /**
  * converDHTMLXGrid() function
  *
  * Prepares and echoes XML for DHTMLX Data Grid
  *
  * @param $data Data Array
  *
  * @param $sr Optional, Enable/Disable Smart Rendering, Boolean TRUE/FALSE,
  *  Default false
  *
  * @param $start Optional, Start Position of data included in XML, Default 0
  *
  * @param $nnrows Optional, Number of data rows to included in XML, Default 20
  *
  * @param $includeHeaders   boolean, optional, use true to have headers included
  *  in XML, otherwise keep it false
  *
  * @param $widths , array of column widths, optional (e.g. array('col1'=>100,'col2'=>150,'col3'=>200); ) 
  *
  * @param $cellStyles , array of CSS styles, optiona (e.g. array('color:red;font-
  * weight:bold;','color:#00ff00'); )
  *
  * @param $colList , array of columns to  display, optional (e.g. array('col1','col2','col3'); ) table column names  
  * @return String, Grid XML
  */
  function converDHTMLXGrid($data,$sr = false, $start=0,$nnrows=20,$includeHeaders=false,$widths='',$cellStyles='',$colList = false) {
    
  	//error_reporting(0);
  	
    $widthStr = '';
    $widthArr = array();
    $rowStyleStr = '';
    $rowStylesArr = array();
    $sidx = 0;
    
  	// For debuging WCD
    log_message('debug',"DATA ARRAY :- ".serialize($data));	 
    if(is_array($widths))
    {	
      /*foreach($widths as $w)
      {
      	$widthArr[] = $w;
      }	*/
      $widthArr = $widths ;		 
    }
    if(is_array($cellStyles))
    {	
    foreach($cellStyles as $rsty)
      {
      	$rowStylesArr[] = $rsty;
      }			 
    }
    $dataset = $data['dataset']['result_set'];
    $pk = explode(",",$data['dataset']['metadata']['PK']);
    //$headerList = getHeadersList($data['dataset']['metadata']);
    //$headers = explode(',',$headerList);
    $headers =array_slice($data['dataset']['metadata'],1);
    $nrows = sizeof($dataset);
    $nnrows = ($nrows < $nnrows)?$nrows:$nnrows;     
         
    //set header for XML Using DOM to do a clean job
    $doc = new DOMDocument('1.0', 'UTF-8');
    /*
    $doc->formatOutput = true;     //To produce output in a nice way
    
    header("Access-Control-Allow-Headers: X-Requested-With");
    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
        header("Content-type: application/xhtml+xml"); 
    } else {
        header("Content-type: text/xml");
    }  */
    //Set Row data for XML
    $myrows = $doc->createElement("rows");
    //Following for smart rendering. Does not work with paging--
    if ($sr)   {
      $myrows->setAttribute('pos', $start);
      $myrows->setAttribute('rec_count', $nrows);
    }
    //Smart rendering End ---------------------------------------
    
    $doc->appendChild($myrows);
    //Following needed for paging ------------------
    // Put Paging code here (Not needed for DHTMLX Enterprice version)
    //Paging End -----------------------------------
    
    //-----For column Headers---------
    $listCols = is_array($colList) ?  $colList :  $headers;
    if($includeHeaders)
    {  
        $myhead = $doc->createElement("head");
        $myrows->appendChild($myhead);
        $hix = 0;
  		foreach($headers as $key=>$h)
  		{
        if ($colList) {
          if (in_array($key,$colList)){
            $mycolumn = $doc->createElement("column");
            $mycolumn->setAttribute('type', 'ro');
            $mycolumn->setAttribute('align', 'center');
            $mycolumn->setAttribute('sort', 'str');
            //$mycolumn->setAttribute('width', getArrayElements($widthArr,$hix));
            //TODO The following code should be modified to get the header width
            // as the minimum width if a width is not specified by user. WCD
            if (sizeof($widthArr)>0) { //User specified column widths are active
              $width=$widthArr[$key];
            }else{ //User not specified column widths so use header stuff
              if($h['width'] =="" || $h['width'] < "5"){
              	$width="10";
              
              }else{
              	$width=$h['width'];
              	
              }
            }
            //The column character width is converted to corrosponding pixel width
            $mycolumn->setAttribute('width', ($width * 6.27 + 12));
   		      $mycolumn->appendChild($doc->createTextNode('<div style="text-align:center;">'.$h['header'].'</div>')); 
            $myhead->appendChild($mycolumn);
            unset ($mycolumn);
            $hix++;
          }
        } else {
          $mycolumn = $doc->createElement("column");
          $mycolumn->setAttribute('type', 'ro');
          $mycolumn->setAttribute('align', 'center');
          $mycolumn->setAttribute('sort', 'str');
          //$mycolumn->setAttribute('width', getArrayElements($widthArr,$hix));
          //TODO The following code should be modified to get the header width
          // as the minimum width if a width is not specified by user. WCD
          if (sizeof($widthArr)>0) { //User specified column widths are active
            $width=$widthArr[$key];
          }else{ //User not specified column widths so use header stuff
            if($h['width'] =="" || $h['width'] < "5"){
            	$width="10";          
            }else{
            	$width=$h['width'];
            }
          } 
          //The column character width is converted to corrosponding pixel width
          $mycolumn->setAttribute('width', ($width * 6.27 + 12));
  	      $mycolumn->appendChild($doc->createTextNode('<div style="text-align:center;">'.$h['header'].'</div>')); 
          $myhead->appendChild($mycolumn);
          unset ($mycolumn);
          $hix++;
        }
  		}
    } // End of Header construction
    
    //$pk_grid=join("_",$pk); 
    $datarowHTML = '';
    for($i=($start+1);$i<=($start+$nnrows);$i++)
    {
      $pk_grid="";
      unset($pk_g);
      foreach($pk as $col){
      	$pk_g[] = $dataset[$i][$col];
      
      }
      
      $pk_grid=join("_",$pk_g);
      unset($pk_g);
      //var_dump($dataset[$i]);
      $sidx = 0; 
      $myrow = $doc->createElement("row");
      //$myrow->setAttribute('id',$dataset[$i][$pk]);
      $myrow->setAttribute('id',$pk_grid);
      $myrows->appendChild($myrow);
      
      foreach($dataset[$i] as $key=>$v)
      {
      	if(!(isset($v))){
      	 	$thisValue = '-';
      	}else {
      		$thisValue = $v;
        }
        if (is_array($colList)) {
          if (in_array($key,$colList)) {
            $mycell = $doc->createElement("cell");
            $mycell->setAttribute('style',getArrayElements($rowStylesArr,$sidx));
            $mycell->setAttribute('fieldName',$key);
            $mycell->appendChild($doc->createTextNode(stripslashes($thisValue))); 
            $myrow->appendChild($mycell);
          }
        }else {
          $mycell = $doc->createElement("cell");
          $mycell->setAttribute('style',getArrayElements($rowStylesArr,$sidx));
          $mycell->setAttribute('fieldName',$key);
          $mycell->appendChild($doc->createTextNode(stripslashes($thisValue))); 
          $myrow->appendChild($mycell);
        } 
        unset ($mycell,$thisValue);
        unset($pk_g);
        $pk_grid="";
      }
    	$sidx++;
      unset ($myrow);
      
    }
    $myXML=$doc->saveXML();
    //var_dump($myXML);
    return $myXML;
  }
} //-------------------End of  converDHTMLXGrid() -------------------

/**
   * This function getts the data from a URL using CURL facility
   * 
   * @param string $url - the URL fro which the data to be feched
   * @param string $user - User ID if the URL needs authentication
   * @param string $pass - User password if the URL needs authentication
   * @param int $transfer - should CURL transfer the data direct. This same
   *                        effect is achieved with ob_start by the function
   *                        however the php way is to handle it through CURL
   *                        param. Both does not work.
   * 
   * RETURNS - $client_xml session var which eeds to be converted to 
   *            simpleXml
   *            
   * Author : - Channa Dewamitta
   * Creaded on :- 15/08/2013
   * Last Modified by :-
   * Last modified on :-
   * Last Modification :-
   * 
   **/ 
function get_content($url,$user=null,$pass=null,$transfer=0)
  {
      $ch = curl_init();
      
      curl_setopt ($ch, CURLOPT_URL, $url);
      curl_setopt ($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, $transfer);
      if($user){
        curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");  
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      }
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);   
      curl_setopt($ch, CURLOPT_COOKIESESSION, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Set to false here because ekwa.com certificate is invalid.WCD
  
      ob_start();
  
      curl_exec ($ch);
      curl_close ($ch);
      $string = ob_get_contents();
  
      ob_end_clean();
      
      return $string;     
  }//End of get content 
  
  /**
   * This function fetches the client data from the xmlfeed and creates a 
   * session var with it. If the feched data is existing it just returns it
   * if not it will fech the data from the server. This way it will only 
   * pull the data once per application
   * 
   * TODO Seee if performace can be improved by passing var by referance
   * 
   *
  	 http://www.bizydads.com/MasterOrg/client_master/index.php/client_master/index/true/0/0/data_feed_template/XML/
  	 cname:
  	 wufoo:
  	 site_id:
  	 sales_notes/   	
  	$args = 'cid:cfname:cbizname:cweb:clientstatus:cstatus:title:cfirstname:clastname:cmanager:clientservicesofficer';
  	
   * 
   * RETURNS - $client_xml session var which eeds to be converted to 
   *            simpleXml
   *            
   * Author : - Channa Dewamitta
   * Creaded on :- 16/08/2013
   * Last Modified by :-
   * Last modified on :-
   * Last Modification :-
   * 
   **/                                              
  function clients_object($args = null){
	@session_start();
  	//var_dump($_SESSION);exit;
    //Get the Client data and meke it availabe as a simpleXMLObject
    
  	$url = CLIENT_FEED_URL.'client_master/index/true/0/0/data_feed_template/XML/'.$args;
  	//var_dump($url);exit;
  	
  	if (! isset($_SESSION['clients_xml']) || ! $_SESSION['clients_xml']) {
      try{
      
        $_SESSION['clients_xml'] = get_contents_with_session($url);  //_Change_ client_master_gridview
      
      } catch (Exception $e){
      	
        show_error('Error 1:Sorry Could not connect to Server for client data!' );
        
      }
    }
    try{
       return new SimpleXMLElement($_SESSION['clients_xml']);        
    } catch (Exception $e){
    
        show_error('Error 2:Sorry Could not create SimpleXMLElement object!' );
      
     }
      
  } // End of Client_object ----------------
  
  
  
  /**
   * This function is used to fetch clients only whom are just joind ekwa but have no live status.
   *
   * @return SimpleXMLElement
   */
  
  function not_live_clients_object($args = 0){
  
  	$url = CLIENT_FEED_URL.'client_master/index/true/0/0/data_feed_template/XML/'.$args.'/pending-live/';
  	//var_dump($url);exit;
  	
  	//Get the Client data and meke it availabe as a simpleXMLObject
  	if (! isset($_SESSION['not_live_clients_xml']) || ! $_SESSION['not_live_clients_xml']) {
  		try{
  			$_SESSION['not_live_clients_xml'] = get_contents_with_session($url);
  				
  		} catch (Exception $e){
  			show_error('Error 3:Sorry Could not connect to Server for not live client data!' );
  		}
  	}
  	try{
  		return new SimpleXMLElement($_SESSION['not_live_clients_xml']);
  	} catch (Exception $e){
  		show_error('Error 4:Sorry Could not SimpleXMLElement for pending live client data!' );
  	}
  } // End of function
  
  
  
  /**
   * This function fetches the staff data from the xmlfeed and creates a 
   * session var with it. If the feched data is existing it just returns it
   * if not it will fetch the data from the server. This way it will only 
   * pull the data once per application
   * 
   * TODO Seee if performace can be improved by passing var by referance
   * 
   * RETURNS - $staff_xml session var which eeds to be converted to 
   *            simpleXml
   *            
   * Author : - Channa Dewamitta
   * Creaded on :- 16/08/2013
   * Last Modified by :-
   * Last modified on :-
   * Last Modification :-
   * 
   **/   
  function staff_object(){
  
    //Load staff data
    if (! isset($_SESSION['staff_xml']) || ! $_SESSION['staff_xml']) {
      try{
        $_SESSION['staff_xml'] = get_content(STAFF_FEED_URL.'staff/index/true/0/0/staff_xml_feed/XML/staff_id:full_name:office_email?staff/status_equal=1'); //_Change_        
      } catch (Exception $e){
        show_error('Sorry Could not connect to Server for Staff data!' );
      }
    }
    try{
        return new SimpleXMLElement($_SESSION['staff_xml']);        
      } catch (Exception $e){
        show_error('Sorry Could not connect to Server for Staff data!' );
      }
  }// End of staff_object ----------------
  
  /**
   * This function gets the contents of a file or stream while establishing the
   * same session. Unlike "get_content()" function hich opens a ne session hen 
   * the file is accessed this preserves the surrent session. Especialy used to 
   * get contents from within CI in dropdowns and other pre-built componants.
   * 
   * 
   * RETURNS - the content of the file as a string.
   *     
   *            
   * Author : - Channa Dewamitta
   * Creaded on :- 13/12/2013
   * Last Modified by :-
   * Last modified on :-
   * Last Modification :-
   * 
   **/
   function get_contents_with_session($url){
   
    $opts = array( 'http'=>array( 'method'=>"GET",
                'header'=>"Accept-language: en\r\n" .
                 "Cookie: ".session_name()."=".session_id()."\r\n" ) );  
    $context = stream_context_create($opts);
    session_write_close();   // this is the key
    try {
      $contents = file_get_contents( $url, false, $context);
      return  $contents;
    }catch (Exception $e)  {
      show_error("Sorry! An error occoured while fetching data from server");
      return false;
    }
   } // End of get_contents_with_session
  

?>