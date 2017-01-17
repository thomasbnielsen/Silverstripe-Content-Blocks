<?php

class Block extends DataObject
{

	private static $singular_name = 'Block';
	private static $plural_name = 'Blocks';
	private static $first_write = false;

	//public static $default_sort = 'SortOrder';

	private static $db = array(
		'Name'             => 'Varchar(255)',
		'Header'           => "Enum('None, h1, h2, h3, h4, h5, h6')",
		'Content'          => 'HTMLText',
		'Link'             => 'Varchar',
		'VideoURL'         => 'Varchar',
		'Template'         => 'Varchar',
		'Active'           => 'Boolean(1)',
		'ImageCropMethod'  => 'Enum("CroppedFocusedImage, SetRatioSize, CroppedImage, Fit, Fill", "CroppedFocusedImage")',
		'ContentAsColumns' => 'Boolean(0)',
		'ExtraCssClasses'  => 'Varchar',

		"RedirectionType" => "Enum('Internal,External','Internal')",
		"ExternalURL"     => "Varchar(2083)" // 2083 is the maximum length of a URL in Internet Explorer.
	);

	private static $many_many = array(
		'Images' => 'Image',
		'Files'  => 'File'
	);

	/**
	 * List of one-to-one relationships. {@link DataObject::$has_one}
	 *
	 * @var array
	 */
	private static $has_one = array(
		"LinkTo" => "SiteTree"
	);

	private static $many_many_extraFields = array(
		'Images' => array('SortOrder' => 'Int'),
		'Files'  => array('SortOrder' => 'Int')
	);

	private static $belongs_many_many = array(
		'Pages' => 'Page'
	);

	private static $defaults = array(
		'Active'                 => 1,
		'Page_Blocks[SortOrder]' => 999, // TODO: Fix sorting, new blocks should be added to the bottom of the list/gridfield
		"RedirectionType"        => "Internal"
	);

	private static $casting = array(
		'createStringAsHTML' => 'HTMLText'
	);

	public function createStringAsHTML($html)
	{
		$casted = HTMLText::create();
		$casted->setValue($html);

		return $casted;
	}

	public function populateDefaults()
	{
		$this->Template = $this->class;

		parent::populateDefaults();
	}

	public function canView($member = null)
	{
		return Permission::check('ADMIN') || Permission::check('CMS_ACCESS_BlockAdmin') || Permission::check('CMS_ACCESS_LeftAndMain');
	}

	public function canEdit($member = null)
	{
		return Permission::check('ADMIN') || Permission::check('CMS_ACCESS_BlockAdmin') || Permission::check('CMS_ACCESS_LeftAndMain');
	}

	public function canCreate($member = null)
	{
		return Permission::check('ADMIN') || Permission::check('CMS_ACCESS_BlockAdmin') || Permission::check('CMS_ACCESS_LeftAndMain');
	}

	public function canPublish($member = null)
	{
		return Permission::check('ADMIN') || Permission::check('CMS_ACCESS_BlockAdmin') || Permission::check('CMS_ACCESS_LeftAndMain');
	}

	private static $summary_fields = array(
		'ID'          => 'ID',
		'Thumbnail'   => 'Thumbnail',
		'Name'        => 'Name',
		'Template'    => 'Template',
		'ClassName'   => 'Type',
		'getIsActive' => 'Active'
	);

	private static $searchable_fields = array(
		'ID'     => 'PartialMatchFilter',
		'Name'   => 'PartialMatchFilter',
		'Header' => 'PartialMatchFilter',
		'Active'
	);

	public function validate()
	{
		$result = parent::validate();
		if ($this->Name == '') {
			$result->error('A block must have a name');
		}

		return $result;
	}

	public function getIsActive()
	{
		return $this->Active ? 'Yes' : 'No';
	}

	public function getCMSFields()
	{
		$fields = parent::getCMSFields();

		$fields->removeByName('SortOrder');
		$fields->removeByName('Pages');
		$fields->removeByName('Active');
		$fields->removeByName('Header');
		$fields->removeByName('Images');
		$fields->removeByName('Files');

		// Media tab
		$fields->addFieldToTab('Root', new TabSet('Media'));

		// If this Block belongs to more than one page, show a warning
		// TODO: This is not working when a block is added under another block
		$pcount = $this->Pages()->Count();
		if ($pcount > 1) {
			$globalwarningfield = new LiteralField("IsGlobalBlockWarning", '<p class="message warning">This block is in use on ' . $pcount . ' pages - any changes made will also affect the block on these pages</p>');
			$fields->addFieldToTab("Root.Main", $globalwarningfield, 'Name');
			$fields->addFieldToTab("Root.Media.Images", $globalwarningfield);
			$fields->addFieldToTab("Root.Media.Files", $globalwarningfield);
			$fields->addFieldToTab("Root.Media.Video", $globalwarningfield);
			$fields->addFieldToTab("Root.Template", $globalwarningfield);
			$fields->addFieldToTab("Root.Settings", $globalwarningfield);
		}

		$fields->addFieldToTab("Root.Main", new TextField('Name', 'Name'));
		$fields->addFieldToTab("Root.Main", new DropdownField('Header', 'Use name as header', $this->dbObject('Header')->enumValues()), 'Content');
		$fields->addFieldToTab("Root.Main", new HTMLEditorField('Content', 'Content'));
		$fields->addFieldToTab('Root.Main', CheckboxField::create('ContentAsColumns'));

		$imgField = new SortableUploadField('Images', 'Images');
		$imgField->allowedExtensions = array('jpg', 'gif', 'png');

		$croppingmethodfield = DropdownField::create('ImageCropMethod', 'Image cropping method', singleton('Block')->dbObject('ImageCropMethod')->enumValues())->setDescription('Does not work on all blocks');

		$fields->addFieldToTab('Root.Media.Images', $imgField);
		$fields->addFieldToTab('Root.Media.Images', $croppingmethodfield);

		$fileField = new SortableUploadField('Files', 'Files');

		$fields->addFieldToTab('Root.Media.Files', $fileField);
		$fields->addFieldToTab('Root.Media.Video', new TextField('VideoURL', 'Video URL'));

		// Template tab
		$optionset = array();
		$theme = Config::inst()->get('SSViewer', 'theme');
		$src = BASE_PATH . "/themes/" . $theme . "/templates/" . CONTENTBLOCKS_TEMPLATE_DIR . '/';
		$theme_imgsrc = "/themes/" . $theme . "/templates/" . CONTENTBLOCKS_TEMPLATE_DIR . '/';
		$module_imgsrc = '/' . CONTENTBLOCKS_MODULE_DIR . '/templates/' . CONTENTBLOCKS_TEMPLATE_DIR . '/';
		if (!file_exists($src)) {
			$src = BASE_PATH . '/' . CONTENTBLOCKS_MODULE_DIR . '/templates/' . CONTENTBLOCKS_TEMPLATE_DIR . '/';
		}

		if (file_exists($src)) {
			foreach (glob($src . "*.ss") as $filename) {
				$name = $this->file_ext_strip(basename($filename));
				// Is there a template thumbnail, check first in theme, then in module
				$img_final_path = $module_imgsrc . $name;
				// appearently file_exists requires BASE_PATH infront of it........
				if (file_exists(BASE_PATH . $theme_imgsrc . $name . '.png')) {
					$img_final_path = $theme_imgsrc . $name;
				}
				if (!file_exists(BASE_PATH . $img_final_path . '.png')) {
					$img_final_path = $module_imgsrc . 'Blank';
				}
				$thumbnail = '<img src="' . $img_final_path . '.png" />';
				$html = '<div class="blockThumbnail">' . $thumbnail . '</div><strong class="title" title="Template file: ' . $filename . '">' . $name . '</strong>';
				$optionset[$name] = $this->createStringAsHTML($html);
			}

			$tplField = OptionsetField::create(
				"Template",
				"Choose a template",
				$optionset,
				$this->Template
			)->addExtraClass('stacked');
			$fields->addFieldsToTab("Root.Template", $tplField);

		} else {
			$fields->addFieldsToTab("Root.Template", new LiteralField ($name = "literalfield", $content = '<p class="message warning"><strong>Warning:</strong> The folder ' . $src . ' was not found.</div>'));
		}

		// Settings tab
		$fields->addFieldToTab("Root.Settings", new CheckboxField('Active', 'Active'));
		$fields->addFieldToTab("Root.Settings", new TextField('Link', 'Link'));
		$fields->addFieldToTab("Root.Settings", TextField::create('ExtraCssClasses'));

		// taken from RedirectorPage
		Requirements::javascript(CMS_DIR . '/javascript/RedirectorPage.js');
		$fields->addFieldsToTab('Root.Settings',
			array(
				new HeaderField('RedirectorDescHeader', "Set an external or internal link"),
				new OptionsetField(
					"RedirectionType",
					_t('RedirectorPage.REDIRECTTO', "Redirect to"),
					array(
						"Internal" => _t('RedirectorPage.REDIRECTTOPAGE', "A page on your website"),
						"External" => _t('RedirectorPage.REDIRECTTOEXTERNAL', "Another website"),
					),
					"Internal"
				),
				new TreeDropdownField(
					"LinkToID",
					_t('RedirectorPage.YOURPAGE', "Page on your website"),
					"SiteTree"
				),
				new TextField("ExternalURL", _t('RedirectorPage.OTHERURL', "Other website URL"))
			)
		);

		$PagesConfig = GridFieldConfig_RelationEditor::create(10);
		$PagesConfig->removeComponentsByType('GridFieldAddNewButton');
		$gridField = new GridField("Pages", "Related pages (This block is used on the following pages)", $this->Pages(), $PagesConfig);

		$fields->addFieldToTab("Root.Settings", $gridField);

		$this->extend('updateCMSFields', $fields);

		return $fields;
	}

	/**
	 * Return the link that we should redirect to.
	 * Only return a value if there is a legal redirection destination.
	 */
	public function getInternalExternalLink()
	{
		if ($this->RedirectionType == 'External') {
			if ($this->ExternalURL) {
				return $this->ExternalURL;
			}

		} else {
			$linkTo = $this->LinkToID ? DataObject::get_by_id("SiteTree", $this->LinkToID) : null;

			if ($linkTo) {
				// We shouldn't point to ourselves - that would create an infinite loop!  Return null since we have a
				// bad configuration
				if ($this->ID == $linkTo->ID) {
					return null;

					// If we're linking to another redirectorpage then just return the URLSegment, to prevent a cycle of redirector
					// pages from causing an infinite loop.  Instead, they will cause a 30x redirection loop in the browser, but
					// this can be handled sufficiently gracefully by the browser.
				} elseif ($linkTo instanceof RedirectorPage) {
					return $linkTo->regularLink();

					// For all other pages, just return the link of the page.
				} else {
					return $linkTo->Link();
				}
			}
		}
	}

	function onBeforeWrite()
	{
		parent::onBeforeWrite();

		if (!$this->ID) {
			$this->first_write = true;
		}

	}

	function onAfterWrite()
	{
		parent::onAfterWrite();

	}

	/* Clean the relation table when deleting a Block */
	public function onBeforeDelete()
	{
		parent::onBeforeDelete();
		$this->Pages()->removeAll();
		$this->Files()->removeAll();
		$this->Images()->removeAll();
	}

	// Should only unlink if a block is on more than one page
	public function canDelete($member = null)
	{
		if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) $member = Member::currentUser();

		// extended access checks
		$results = $this->extend('canDelete', $member);

		if ($results && is_array($results)) {
			if (!min($results)) return false;
			else return true;
		}

		// No member found
		if (!($member && $member->exists())) return false;

		$pcount = $this->Pages()->Count();
		if ($pcount > 1) {
			return false;
		} else {
			return true;
		}


		return $this->canEdit($member);
	}

	function recurse_copy($src, $dst)
	{
		$dir = opendir($src);
		@mkdir($dst);
		while (false !== ($file = readdir($dir))) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir($src . '/' . $file)) {
					$this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
				} else {
					copy($src . '/' . $file, $dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}

	/* TODO: add function to calculate image widths based on columns? */
	public function ColumnClass($totalitems)
	{
		$totalcolumns = 12; // should be configurable
		$columns = $totalcolumns / $totalitems;

		return $columns;
	}

	public function getThumbnail()
	{
		if ($this->Images()->Count() >= 1) {
			return $this->Images()->First()->croppedImage(50, 40);
		}
	}

	function forTemplate()
	{

		// can we include the Parent page for rendering? Perhaps use a checkbox in the CMS on the block if we should include the Page data.
		// $page = Controller::curr();
		// return $this->customise(array('Page' => $page))->renderwith($this->Template);
		return $this->renderWith(array($this->Template, 'Block')); // Fall back to Block if selected does not exist
	}

	// Returns only the file extension (without the period).
	function file_ext($filename)
	{
		if (!preg_match('/\./', $filename)) return '';

		return preg_replace('/^.*\./', '', $filename);
	}

	// Returns the file name, without the extension.
	function file_ext_strip($filename)
	{
		return preg_replace('/\.[^.]*$/', '', $filename);
	}

	/**
	 * @return string
	 */
	public function getExtraClasses()
	{
		$classes = $this->ExtraCssClasses;
		if ($this->ContentAsColumns) {
			$classes .= ' css-columns';
		}

		return $classes;
	}

	/**
	 * @param int   $img_id
	 * @param       $width
	 * @param       $height
	 * @return Image
	 */
	public function FormattedBlockImage($img_id, $width, $height)
	{
		$method = $this->ImageCropMethod;
		$img = $this->Images()->filter('ID', $img_id)->first();

		return $img->$method($width, $height);
	}

	/**
	 * Returns the page object (SiteTree) that we are currently on
	 * Allow us to loop on children of the page and other page related data
	 *
	 * @return SiteTree
	 */
	public function CurrentPage()
	{
		return Director::get_current_page();
	}
}
