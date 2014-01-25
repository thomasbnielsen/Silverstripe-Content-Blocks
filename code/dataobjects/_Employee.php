<?php
class Employee extends Block {
    
	static $db = array(
		'Name' => 'Varchar',
		'Phone' => 'Varchar',
		'Email' => 'Varchar'
    );
    
	static $has_one = array(
    );

	static $many_many = array(
    );
	
	
	public function getCMSFields() {
		
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab("Root.Employee", new TextField('Name', 'Name')); 
		$fields->addFieldsToTab("Root.Employee", new TextField('Phone', 'Phone')); 
		$fields->addFieldsToTab("Root.Employee", new TextField('Email', 'Email')); 

		return $fields;
	}	
}