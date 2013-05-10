Silverstripe-Section-Module
===========================

Split your page content into manageable sections/blocks of content, each with their own template.

### Version compatibility ###
Tested on Silverstripe 3.1 beta3

### Installation instructions ###

- Put this module under the root folder of site, named sectionmodule.
- Add the following code to your themes/your_design/templates/Layout/Page.ss where you want the content sections to be rendered:
```
<div id="Sections"><% loop ActiveSections %>$Me<% end_loop %></div>
```

- install the following dependent module(s)
	- GridField Extensions
	https://github.com/ajshort/silverstripe-gridfieldextensions/
	
	- silverstripe-copybutton
	https://github.com/uniun/silverstripe-copybutton
	
	- Better buttons for GridField by unclecheese (Not required but improves usability)
	https://github.com/unclecheese/silverstripe-gridfield-betterbuttons

- run sitename.com/dev/build?flush=all

- The module will copy sections/templates/SectionTemplates to themes/your_design/templates/SectionTemplates, should this fail, please copy the files manually.
- The module will copy sections/css/sections.css to themes/your_design/sections.css, should this fail, please copy the file manually.

### Usage: ###
- add your own templates to themes/your_design/templates/SectionTemplates, they need to have the extension .ss
- allways run dev/build?flush=1 after adding templates
- remember to ?flush=1 after modification of templates

### TODO: ###
- Save available templates in database (enum field) - create on dev/build
- Make inline editing of sections work (edit name, template, active - show id, picture, DO Type)
- Option to add more content placeholders without coding - site config?
- Build in template generator
- Create a set of percentage width based SectionTemplates
- Add a rightclick menu / action button to GridField, copy, move and so on
