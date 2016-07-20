<?php
/********************************************************************
 *	BizyCorp / Ekwa Internal development Team
*
* 	Form Object class
*
*  	Usage :- Construct as $var = new form_object('formanme')
*  	Settable porperties :- formWidth - width of form specified as px or %. If 
*  	  it is a percentage only the valuis to be given. No '%' sign
*  	  [headerText] - A header for the form given as array of 2 params 
*  	      array('text' => 'This is form header',
*  	      'delimiter' => '<h3>,</h3>') A comma seperated pare of HTML tags 
*  	                  which will surround the text
*  	  [generalMsg] - a message to be displyed on the form given as an array of
*  	     array('text' = 'Message to be shown on form (may be error)', as a string
*  	     'delimiter' => '<div class="className">,</div>',  A comma seperated 
*  	                  pare of HTML tags which will surround the text
*  	  [formOpenTag] - The opening HTML tag of form, the content is given as an 
*  	                  array
*  	    array('method' => 'post|get', - method of data submission
*  	    'action' => 'url', the url of the form data procssor
*  	    'attributes' => array('attrib01'=>'val01','attrib02'=>'val02,...) - Any 
*  	                  other HTML attributes that can go in a FORM tag
*  	    )
*  	  [fields] - form fields specified as an array of Field_object
*  	  [fieldSets] - form fields specified in HTML fieldset tags
*  	                        
*
*  	Author :- Mohamed Roshan (roshan@ekwa.com)
*  	Creation date  :- Jun 12, 2013
*  	Last Modified on :- Jun 12, 2013
*  	Last Modification :-
*
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_object{
 
	var $debug = true;
	var $headerText = null;	
	var $formName = '';
	var $formWidth = 90;
	
	var $fieldSets = null;
	var $generalMsg = null;
	var $formOpenTag = null;
	var $noOfFormColumns = 2;
	var $fields = array();
	
	
	function __construct($name = null){		
		$this->formName = $name;		
	}//end of construct	
	
	
	/**
	 * initiat the form contents in a table and present it as a form
	 * 
	 * Echo out the form with supplied html control array.
	 *
	 * @access public
	 *
	 */	
	
	function init(){
		
		// add form submit JS code
		echo '<script>function submitForm(){document.forms["'.$this->formName.'"].submit();}</script>';
		
		// header text
		if(!empty($this->headerText)){
			if(is_array($this->headerText)){				
				$attrib = explode(',',$this->headerText['delimiter']);
				echo $attrib[0].$this->headerText['text'].$attrib[1];						
			}
		}		
		
		//general info area
		if(!empty($this->generalMsg)){
			if(is_array($this->generalMsg)){
				$attrib = explode(',',$this->generalMsg['delimiter']);
				echo $attrib[0].$this->generalMsg['text'].$attrib[1];
			}
		}		
		
		//set form tag

		// form open tag
		echo '<form ';
		echo ($this->formOpenTag['method'] == 'post')? ' method = "post" ' :' method = "get" ';
		echo (empty($this->formOpenTag['action']))? ' action = "'.$_SERVER['PHP_SELF'].'" ':' action = "'.$this->formOpenTag['action'].'" ';
		if (!empty($this->formOpenTag['attributes'])) {
      foreach($this->formOpenTag['attributes'] as $attribute => $value){
  			echo $attribute . ' = "' . $value. '" ';
  		}
    }
		echo ' name = "'.$this->formName.'"';
		echo ' id = "'.$this->formName.'"';
		echo '>';
		
		
		
		//form fieldset
		if(isset($this->fieldSets) && is_array($this->fieldSets)){
			
			foreach($this->fieldSets as $fieldSetKey=>$val){
				
				//start fieldset tag				
				$this->prepareFieldset($val);
				
				//table begin
				echo '<table width="'.$this->formWidth.'%" id="'.$fieldSetKey.'">';
				
				if(!empty($val['fields'])){
					
					foreach($val['fields'] as $field){
							
						if(!empty($field)){
							//create rows with html elements
							$this->createTableRows($field);
						}else{
							echo 'No input field is defined or defined input is empty!';
							exit;
						}	
							
					}//end forecah
					echo '</table>';
					
					//end fieldset tab
					$this->endFieldset();
					
				}else{
					echo 'No input field is defined or defined input is empty!';
					exit;
				}
				
			}//endforeach
		//}else{
    }
    
    if ($this->fields && is_array($this->fields) ){
				
			//If no fieldset is defined display form items without field set
			//table begin
			echo '<table width="'.$this->formWidth.'%">';
				
			foreach($this->fields as $field){
					
				if(!empty($field)){
					//create rows with html elements
						$this->createTableRows($field);
					}else{
						echo 'No input field is defined or defined input is empty!';
						exit;
					}	
					
			}//end forecah		
			echo '</table>';
			
		}//end if fieldset check	

		//end form tab
		echo '</form>';
	
	}//end of init()
	
	
	/**
	 * TODO: documenting
	 *
	 * @access private
	 *
	 */
		
	private function prepareFieldset($fieldSet){
		
		$legend = (isset($fieldSet['legend']))? $fieldSet['legend']:'';
		$attributes = (isset($fieldSet['attributes']))? $fieldSet['attributes']:null;
		$sub_fieldsets = (isset($fieldSet['sub_fieldsets']))? $fieldSet['sub_fieldsets']:null;
		
		echo '<fieldset ';
		if(!empty($attributes) && is_array($attributes)){
			foreach($attributes as $attribute => $value){
				echo $attribute .' = "'.$value.'" ';
			}
		}//end if
		echo ' >';
		echo (!empty($legend))? "<legend>$legend</legend>":'';
		
		
	}//end of function
	
	
	
	
	/**
	 * TODO: documenting
	 *
	 * @access private
	 *
	 */
	
	private function endFieldset(){		
		echo '</fieldset>';		
	}//end of fucntion
	
	

	/**
	 * TODO: documenting
	 * 
	 * @access private
	 *
	 */
		
	private function createTableRows($field){		
		
			if($field->type != 'hidden'){
		
				echo '<tr>';
				echo '<td class="form_field_label_class">';
				if(!empty($field->settings['label'])){
					
					echo $field->settings['label'];
					
					echo ($field->settings['required_mark'])? '*':'';
				}
					
				echo '</td>';
				echo '<td  class="form_field_class">';
				echo $field->createField();
				echo '</td>';
				echo '</tr>';
		
			}else{
				echo $field->createField();
			}//end if
				
				
		
	}//end of function
	
	
	
	
	private function addMsgSpan($name,$msg){
		
		echo '<div	id="'.$name.'_MsgDiv" class="field_msg_div_class" >'.$msg.'</div>';
	}
	
		
}//end of class form object

class Field{
	
	var $name = null;
	var $type = null;
	var $settings = null;
	var $options = array();
	var $selectedOptions = array();
	
	/**
	 * Constuctor of Field class 
 	 * 
   * @param string $name - Name of the input 
	 * @param string $type - Type of the input (text/select/textarea/hidden)
	 * @param array $settings  - this is an associative array using this array you can set the status of the input. array keys are as follows,
   *			'value'=>'' - To set value for the input
	 *			'label'=>'' - To set field label on left side of the input
	 *			'before_field' =>'' - To print/add html elements (ex:<br>) or texts befor the element,
	 *			'after_field' => '' - To print/add html elements (ex:<br>) or texts after the element,
	 *			'required_mark'=>true - To show '*' mark after the field label to indicate required field. accepts only boolion true/false
	 *			'field_msg'=>'' - To set error messages or any messages below the input field
	 *			'style' => 'class = "template_name_class"' - To set CSS styling string ,
	 *			'disabled' => false - To set the field disabled. By default all fields are enabled 
	 *
	 * @param array $options - To pass <option> listing as an array in <select> element. Accepts array('value' => 'option text') format array
	 * @param array $selectedOptions - To pass selected <option> listing in multi select element. Accept array('val1','val2','val3') type of array
	 * 
	 *  ex:-
	 *	for hidden field 
	 *	$tp_id = new Field('tp_id','hidden',array('value'=>$tp_id,'label'=>'','before_field' =>'','after_field' => '','required_mark'=>null,'field_msg'=>''));
	 *    
	 *	for textbox
	 *	$tp_name = new Field('tp_name','text',	array('value'=>$tp_name,'label'=>'Template Name','before_field' =>'','after_field' => '','required_mark'=>true,'field_msg'=>'','style' => 'class = "template_name_class"','disabled' => true));
	 *
	 *	for select
	 *	$options = array('client' => 'Client','project' => 'Project','staff'=>'Staff');
	 *	$tp_type = new Field('tp_type','select',array('label'=>'Template Type','before_field' =>'','after_field' => '','required_mark'=>true,'field_msg'=>'','style' => 'class = "template_type_class"','disabled' => true),$options,$selectedValue );
	 *
	 *	for textarea
     *	$template = new Field('template','textarea',array('value'=>$template,'label'=>'Template','before_field' =>'','after_field' => '','required_mark'=>true,'field_msg'=>'','style' => 'class = "template_type_class"','disabled' => true,'cols' => 30,'rows' => 5));
	 *
	 * 
	 */
	
	function __construct($name,$type,$settings,$options=null,$selectedOptions=null){
		
		$this->name = $name;
		$this->type = $type;
		$this->settings = $settings;
		$this->options = $options;
		$this->selectedOptions = $selectedOptions;
    $this->settings['field_msg'] = empty($settings['field_msg'])? '': $settings['field_msg'];
		
			
	}//end of construct
	
	public function createField(){
		
		switch ($this->type){
			
			case 'text':
				return $this->prepareTextbox();
			break;
			case 'hidden':
				return $this->prepareHiddenInput();
			break;
			case 'select':
				return $this->prepareSelect();
			break;
			case 'textarea':
				return $this->prepareTextarea();
			break;
			case 'empty':
				return $this->prepareEmptyRow();
			break;
      case 'date':
				return $this->DateField();
			break;
		}//end switch
		
	}//end of function
	
	
	/**
	 * Prepares empty field 
	 *
	 * This funciton will echo html code to display a text input element
	 *
	 * @access private
	 *
	 */
	
	private function prepareEmptyRow(){
		
				
		echo (isset($this->settings['before_field']))? $this->settings['before_field']:null;
		//echo '<br>'; // Why is this here? WCD
		echo (isset($this->settings['id']))? 'id = "'.$this->settings['id'].'" ':null;
		echo (isset($this->settings['value']))? 'value = "'.$this->settings['value'].'" ':null;
		echo (isset($this->settings['style']))? $this->settings['style']:null;
		echo (isset($this->settings['disabled']) && $this->settings['disabled'])? 'disabled="disabled"':null;	
		echo (isset($this->settings['after_field']))? $this->settings['after_field']:null;
		
		
	}//end of function
	
	
	
	
	/**
	 * Prepares text input element
	 *
	 * This funciton will echo html code to display a text input element
	 *
	 * @access private
	 *
	 */
	
	private function prepareTextbox(){
	
		echo (isset($this->settings['before_field']))? $this->settings['before_field']:null;
		echo '<input type="text" ';
		echo 'name = "'.$this->name.'" ';
		echo (isset($this->settings['id']))? 'id = "'.$this->settings['id'].'" ':null;
		echo (isset($this->settings['value']))? 'value = "'.$this->settings['value'].'" ':null;
		echo (isset($this->settings['style']))? $this->settings['style']:null;
		echo (isset($this->settings['disabled']) && $this->settings['disabled'])? 'disabled="disabled"':null;	
		echo ' />';
		echo (isset($this->settings['after_field']))? $this->settings['after_field']:null;
		echo $this->display_field_msg($this->name, $this->settings['field_msg']);
		
	}//end of function
	
	
	
	/**
	 * Prepares textarea input element
	 *
	 * This funciton will echo html code to display a textarea input element
	 *
	 * @access private
	 *
	 */
	
	private function prepareTextarea(){
	
		echo (isset($this->settings['before_field']))? $this->settings['before_field']:null;
		echo '<textarea ';
		echo 'name = "'.$this->name.'" ';
		echo (isset($this->settings['id']))? 'id = "'.$this->settings['id'].'" ':null;		
		echo (isset($this->settings['style']))? $this->settings['style']:null;
		echo (isset($this->settings['disabled']) && $this->settings['disabled'])? 'disabled="disabled"':null;
		echo (isset($this->settings['cols']))? ' cols="'.$this->settings['cols'].'"':'cols="40" ';
		echo (isset($this->settings['rows']))? ' rows="'.$this->settings['rows'].'"':'rows="5" ';
		echo ' >';
		echo (isset($this->settings['value']))? $this->settings['value']:null;
		echo '</textarea>';
		echo (isset($this->settings['after_field']))? $this->settings['after_field']:null;
		echo $this->display_field_msg($this->name, $this->settings['field_msg']);
	
	}//end of function
	
	
	
	/**
	 * Prepares hidden input element
	 *
	 * This funciton will echo html code to include a hidden text input element
	 *
	 * @access private
	 *
	 */
	
	private function prepareHiddenInput(){
	  echo (isset($this->settings['before_field']))? $this->settings['before_field']:null;  //Aded by WCD
		echo '<input type="hidden" ';
		echo 'name = "'.$this->name.'" ';
		echo (isset($this->settings['id']))? 'id = "'.$this->settings['id'].'" ':null;
		echo (isset($this->settings['value']))? 'value = "'.$this->settings['value'].'" ':null;
    echo (isset($this->settings['style']))? $this->settings['style']:null; //Aded by WCD
		echo ' />';
		echo (isset($this->settings['after_field']))? $this->settings['after_field']:null;  //Aded by WCD	
	}//end of function
	
	
	

	/**
	 * Prepares select input element
	 *
	 * This funciton will echo html code to prepare a select element
	 *
	 * @access private
	 *
	 */
	
	private function prepareSelect(){
	
		$selectedOption = $this->selectedOptions;
		$selected = '';
		
		echo '<select ';
		echo 'name = "'.$this->name.'" ';
		echo (isset($this->settings['id']))? 'id = "'.$this->settings['id'].'" ':null;
		echo (isset($this->settings['style']))? $this->settings['style']:null;
		echo (isset($this->settings['disabled']) && $this->settings['disabled'])? 'disabled="disabled"':null;	
		echo ' >';
		foreach($this->options as $option=>$optionText){
			
			if($selectedOption == $option){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}//end if
			
			echo '<option value="'.$option.'" '.$selected.'>'.$optionText.'</option>';
			
		}//end foreach
		echo ' </select>';
		echo $this->display_field_msg($this->name, $this->settings['field_msg']); // added by CD on 19/08/2013	
	}//end of function
	
	
	/**
	 * Prepares multiple select input element
	 *
	 * This funciton will echo html code to prepare a multiple select element
	 *
	 * @access private
	 * TODO: testing and modifying
	 *
	 */
	
	private function DropDown_multiple($data) {
	
		echo '<select ';
		foreach($data['attributes'] as $attribute => $value){
			echo $attribute .'= "'. $value;
			echo ($attribute == 'name')? '[]':'';
			echo '" ';
		}
		echo ' multiple>';
	
		foreach($data['options'] as $optionVal => $optionText){
			echo '<option value ="'.$optionVal.'" ';
			echo (in_array( $optionVal,$data['selected_vals'])) ? 'selected = "selected"' :'';
			echo '>'. $optionText. '</option>';
		}
		echo '</select>';
		//echo '<br>';
		$this->addMsgSpan($data['attributes']['name'],$data['field_msg']);
	
	}
	
	
	/**
	 * Prepares DHTMLX calander attached text field
	 * 
	 * TODO: modify and document
	 * 
	 * @access private
	 *
	 */
	

	private function DateField($data){
		?>		
		<!-- Load recourses of DHTMLX calander -->
		<script  src="<?php echo $this->config->item('dhtmlx_lib_url')?>codebase/dhtmlx.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('dhtmlx_lib_url')?>dhtmlxCalendar/codebase/dhtmlxcalendar.css"></link>
		<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('dhtmlx_lib_url')?>dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
		<script src="<?php echo $this->config->item('dhtmlx_lib_url')?>dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
		<?php
	
		echo form_input($data['attributes']);
		echo '<br>';
		$this->addMsgSpan($data['attributes']['name'],$data['field_msg']);
		echo '
		<script>
			var calendar;
			calendar = new dhtmlXCalendarObject(["'.$data['attributes']['id'].'"]);
			calendar.setDateFormat("'.$data['attributes']['date_format'].'");
		</script>';
	}
	
	
	
	
	
	
	
	
	/**
	 * Prepares field error message div.
	 *
	 * This function will return
	 *  
	 * @param string $name
	 * @param string $msg
	 * @return string A generated HTML <DIV> element with supplied string
	 * @access private
	 *
	 */
	
	
	private function display_field_msg($name,$msg){
		
		return '<div id="'.$name.'_msg_div" class="field_msg_div_class" >'.$msg.'</div>';		
	}//end of function
	
	
}//end of Fields();

?>