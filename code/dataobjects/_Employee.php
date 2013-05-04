<?php
class Employee extends Section {
    
	static $db = array(
		'EmployeeName' => 'Varchar',
		'EmployeePhone' => 'Varchar',
		'EmployeeEmail' => 'Varchar'
		
    );
    
	static $has_one = array(
    );

	static $many_many = array(
    );
	
	
	public function getCMSFields() {
		
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab("Root.Employee", new TextField('EmployeeName', 'Name')); 
		$fields->addFieldsToTab("Root.Employee", new TextField('EmployeePhone', 'Phone')); 
		$fields->addFieldsToTab("Root.Employee", new TextField('EmployeeEmail', 'Email')); 

		return $fields;
	}	
}