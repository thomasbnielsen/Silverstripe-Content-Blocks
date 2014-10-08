<div class="row block">
	<% if $Header != "None" %>
	<div class="small-12 columns">
		<{$Header}>$Name</{$Header}>
  	</div>
	<% end_if %>
	
	<% loop Blocks.Sort('SortOrder') %>
		        
		<div class="small-12 large-{$ColumnClass($TotalItems)} columns">
            $Me
      	</div>    

	<% end_loop %>

</div>