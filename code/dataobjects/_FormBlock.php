<?php
class FormBlock extends Block {

	private static $singular_name = 'FormBlock';
	private static $plural_name = 'FormBlocks';	

	static $db = array(
		//'Name' => 'Varchar',
		//'Phone' => 'Varchar',
		//'Email' => 'Varchar'
    );
    
	static $has_one = array(
    );

	static $many_many = array(
    );

	public function getCMSFields() {
		
		$fields = parent::getCMSFields();
		//$fields->addFieldsToTab("Root.Employee", new TextField('Name', 'Name')); 
		//$fields->addFieldsToTab("Root.Employee", new TextField('Phone', 'Phone')); 
		//$fields->addFieldsToTab("Root.Employee", new TextField('Email', 'Email')); 

		return $fields;
	}	
	
	public function Form() {
		
		$fields = new FieldList(
			new TextField('Name'),
			new EmailField('Email'),
			new TextareaField('Message')
		);

		$actions = new FieldList(
			new FormAction('submit', 'Submit')
		);
		
		$form = new Form($this, 'form', $fields, $actions);
		$form->setFormAction('/blocks/form');
		$form->setFormMethod('POST');
		 
		return $form; 
    }	
	
/*	public function Link () {
		return "/blocks/";
	}
*/	
	public function status() {
        return isset($_REQUEST['status']) ? $_REQUEST['status'] : "";
    }
		
}