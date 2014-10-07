<div class="row">
	<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>

	<% loop Blocks.Sort('SortOrder') %>
		        
		<div class="small-12 large-{$ColumnClass($TotalItems)} columns">
            $Me
      	</div>    

	<% end_loop %>

</div>