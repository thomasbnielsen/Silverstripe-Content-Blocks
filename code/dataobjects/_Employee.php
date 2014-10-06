<?php
class Employee extends Block {

	private static $singular_name = 'Employee';
	private static $plural_name = 'Emplyees';	

	private static $db = array(
		'Name' => 'Varchar',
		'Phone' => 'Varchar',
		'Email' => 'Varchar'
    );
    
	private static $has_one = array(
    );

	private static $many_many = array(
    );
	
	public function getCMSFields() {
		
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab("Root.Employee", new TextField('Name', 'Name')); 
		$fields->addFieldsToTab("Root.Employee", new TextField('Phone', 'Phone')); 
		$fields->addFieldsToTab("Root.Employee", new TextField('Email', 'Email')); 

		return $fields;
	}	
}