<?php
class SectionModule extends DataExtension {

	public static $db = array(
	);

	public static $has_one = array(
	);
	
	static $has_many = array(
		'Sections' => 'Section'
	);

	//public function getCMSFields() {
	public function updateCMSFields(FieldList $fields) {
		//$fields = parent::getCMSFields();
		$gridFieldConfig = GridFieldConfig::create()->addComponents(
			new GridFieldToolbarHeader(),
			new GridFieldAddNewButton('toolbar-header-right'),
			new GridFieldSortableHeader(),
			new GridFieldDataColumns(),
			new GridFieldPaginator(20)
			//new GridFieldEditButton(),
			//new GridFieldDeleteAction(),
			//new GridFieldDetailForm()
		);
		
		//if (class_exists('GridFieldExtensions')) {}
	
		//$gridFieldConfig->removeComponentsByType('GridFieldEditButton');
		//$gridFieldConfig->addComponent(new GridFieldDetailFormCustom());
		
		$gridFieldConfig->addComponent(new GridFieldDetailFormCustom());
		$gridFieldConfig->addComponent(new GridFieldEditButton());
		$gridFieldConfig->addComponent(new GridFieldCopyButton());
		$gridFieldConfig->addComponent(new GridFieldDeleteAction());
		$gridFieldConfig->addComponent(new GridFieldOrderableRows('Sort'));
			
	
		$gridField = new GridField("Sections", "Sections (Content blocks)", $this->owner->Sections(), $gridFieldConfig);
		
		//Inline editing - not working
		
/*		$gfConfig = GridFieldConfig::create()->addComponents(
			new GridFieldButtonRow('before'),
			new GridFieldToolbarHeader(),
			new GridFieldSortableHeader(),
			new GridFieldPaginator(20)
			//new GridFieldEditButton(),
			//new GridFieldDeleteAction(),
			//new GridFieldDetailForm()
		);
		
		//if (class_exists('GridFieldExtensions')) {}
	
		//$gridFieldConfig->removeComponentsByType('GridFieldEditButton');
		//$gridFieldConfig->addComponent(new GridFieldDetailFormCustom());

		$gfConfig->addComponent(new GridFieldDetailFormCustom());		
		$gfConfig->addComponent(new GridFieldOrderableRows('Sort'));
		$gfConfig->addComponent(new GridFieldEditableColumns());
		$gfConfig->addComponent(new GridFieldAddNewInlineButton('toolbar-header-right'));

		$gfConfig->addComponent(new GridFieldEditButton());
		$gfConfig->addComponent(new GridFieldCopyButton());
		$gfConfig->addComponent(new GridFieldDeleteAction());
		
		
				
		$gridField = new GridField("Sections", "Sections (Content blocks)", $this->owner->Sections(), $gfConfig);
		$gridField->getConfig()->getComponentByType('GridFieldEditableColumns')->setDisplayFields(array(
			'ID' => array(
				'title' => 'ID',
				'field' => 'ReadonlyField'
			),
			'Name'  => function($record, $column, $grid) {
				return new TextField($column);
			},
			'Thumbnail' => 'Thumbnail'

		));		
*/		
		$classes = array_values(ClassInfo::subclassesFor($gridField->getModelClass()));
		
		if (count($classes) > 1 && class_exists(GridFieldAddNewMultiClass)) {
			$gridFieldConfig->removeComponentsByType('GridFieldAddNewButton');
			$gridFieldConfig->addComponent(new GridFieldAddNewMultiClass());
		}

		$fields->addFieldToTab("Root.Sections", $gridField);
		//$this->extend('updateCMSFields', $fields);
		return $fields;
	}

	function ActiveSections() {
		$sections = $this->owner->Sections()->filter(array('Active' => '1'));
		return $sections;
	}
	
	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		// Run on dev buld		
		
		// If css file does not exist on current theme, copy from module
		$copyfrom = BASE_PATH . "/sectionmodule/css/section.css";
		$theme = SSViewer::current_theme();
		$copyto    = "../themes/".$theme."/css/section.css";
		
		if(!file_exists($copyto)) {
			if(file_exists($copyfrom)) {
				copy($copyfrom,$copyto);
				echo '<li style="color: green">section.css copied to: '.$copyto.'</li>';
			} else {
				echo "The default css file was not found: " . $copyfrom;
			}
		}
	
	}	
	
	
	public function contentcontrollerInit($controller) {
		Requirements::themedCSS('section');
	}
}