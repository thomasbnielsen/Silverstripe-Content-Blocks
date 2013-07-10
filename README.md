Silverstripe-Section-Module
===========================

Split your page content into manageable sections/blocks of content, each with their own template.

## Create compelling and unique pages ##
This module gives you the option to create your content, in little blocks, instead of just one big content area.

When the module is installed, a "Sections" tab will be added to all pages. The sections tab holds a GridField, that allows you to create as many blocks/sections of content as you would like.
Each section/block of content can have it's own template assigned. The module commes with a set of standard templates.

![ScreenShot]({http://hosted.nobrainer.dk/section-module.jpg})

You can easily create your own section templates and even your own sections. This allows for some very flexible sections.
Create your own section templates and/or extend the Section DataObject to create:
- Image lists (simple gallery)
- Employee listings
- Product listings
- and much more

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

- The module will copy sectionmodule/templates/SectionTemplates to themes/your_design/templates/SectionTemplates, should this fail, please copy the files manually.
- The module will copy sections/css/sections.css to themes/your_design/sections.css, should this fail, please copy the file manually.

### Usage and customization: ###
- add your own templates to themes/your_design/templates/SectionTemplates, they need to have the extension .ss and delete any unwanted templates (there is full example set of fixed width and fluid width templates included in the module)
- allways run dev/build?flush=1 after adding templates
- remember to ?flush=1 after modification of templates

### TODO: ###
- Save available templates in database (enum field) - create on dev/build or use template manifest
- Make inline editing of sections work (edit name, template, active - show id, picture, DO Type)
- Option to add more content placeholders without coding - site config?
- Build in template generator
