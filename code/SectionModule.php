<?php
class SectionModule extends DataExtension {

	private static $db = array();

	private static $has_one = array();
	
	private static $many_many = array(
		'Sections' => 'Section'
	);
	
	public static $many_many_extraFields=array(
        'Sections'=>array(
            'SortOrder'=>'Int'
        )
    );
	
	public function updateCMSFields(FieldList $fields) {

		// Relation handler for sections		
		$SConfig = GridFieldConfig_RecordEditor::create(10);
		$SConfig->addComponent(new GridFieldOrderableRows('SortOrder'));

		$SConfig->addComponent(new GridFieldDetailFormCustom());
		
		// If the copy button module is installed, add copy as option
		if (!class_exists('GridFieldCopyButton')) {
			$SConfig->addComponent(new GridFieldCopyButton(), 'GridFieldDeleteAction');
		}

		$gridField = new GridField("Sections", "Sections (Content blocks)", $this->owner->Sections()->sort('Page_Sections.SortOrder'), $SConfig);
		
		$classes = array_values(ClassInfo::subclassesFor($gridField->getModelClass()));
		
		if (count($classes) > 1 && class_exists('GridFieldAddNewMultiClass')) {
			$gridFieldConfig->removeComponentsByType('GridFieldAddNewButton');
			$gridFieldConfig->addComponent(new GridFieldAddNewMultiClass());
		}

		$fields->addFieldToTab("Root.Sections", $gridField);

		return $fields;
	}

/*	function ActiveSections() {
		$sections = $this->owner->Sections()->filter(array('Active' => '1'));
		return $sections;
	}
*/	
	public function ActiveSections() {
		return $this->owner->Sections()->filter(array('Active' => '1'))->sort('Page_Sections.SortOrder');
	}
	
	public function Sections() {
		return $this->owner->Sections()->filter(array('Active' => '1'))->sort('Page_Sections.SortOrder');
	}
	
/*	function ShowTestimonialCategories() {
		return $this->TestimonialCategories()->sort('TestimonialsHolder_TestimonialCategories.SortOrder');   
	}	
	
	public function Sections() {
		return $this->owner->getManyManyComponents('Sections')->filter(array('Active' => '1'))->sort('SortOrder');
	}	
*/	
	// Run on dev buld
	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		// If css file does not exist on current theme, copy from module
		$copyfrom = BASE_PATH . "/".SECTION_MODULE_DIR."/css/section.css";
		$theme = SSViewer::current_theme();
		$copyto    = "../themes/".$theme."/css/section.css";
		
		if(!file_exists($copyto)) {
			if(file_exists($copyfrom)) {
				copy($copyfrom,$copyto);
				echo '<li style="green: green">section.css copied to: ' . $copyto . '</li>';
			} else {
				echo '<li style="red">The default css file was not found: ' . $copyfrom . '</li>';
			}
		}
	}	
	
	public function contentcontrollerInit($controller) {
		Requirements::themedCSS('section');
		//Requirements::themedCSS('fluidsection');
	}
}