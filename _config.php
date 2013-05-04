<?php
// Extend all pages with the section module
Object::add_extension('Page', 'SectionModule');
// Add custom styles to the CMS / Admin area
LeftAndMain::require_css('sectionmodule/css/SectionModule.css');
?>