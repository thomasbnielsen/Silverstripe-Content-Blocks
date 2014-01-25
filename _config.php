<?php
define('SECTION_MODULE_DIR',basename(dirname(__FILE__)));
define('CONTENTBLOCKS_TEMPLATE_DIR','BlockTemplates');

// Test if we have required dependencies. Test if installed, else just use basic GridField
if (!class_exists('GridFieldExtensions')) {
	exit('<div style="color: red"><strong>Section Module Error: </strong>The <a href="https://github.com/ajshort/silverstripe-gridfieldextensions" target="_blank">GridField Extension module</a> by ajshort is required.</div>');
}