(function($) {

	// run the function for doc ready
	$(document).ready(function(){
			CurrentTemplateBlue();
	});
	// run the function after ajax request (for example after pressing the save button)
	$(document).ajaxComplete(function(){
			CurrentTemplateBlue();
	});	
	

	function CurrentTemplateBlue(){
		// make the current template blue when page is loaded	  
		$('#Root #Template ul li input:radio[name="Template"]:checked').parent().addClass('selectedBlock');
	
		// When template is selected, background blue
		$('#Root #Template ul li input:radio[name="Template"]').change(function(){	
		   if ($(this).is(':checked')) {
			   // reset all
			   $('#Root #Template ul li').removeClass('selectedBlock');
			   // apply background
			   $(this).addClass('checked').parent().addClass('selectedBlock');
			}					
		});
	}


})(jQuery);