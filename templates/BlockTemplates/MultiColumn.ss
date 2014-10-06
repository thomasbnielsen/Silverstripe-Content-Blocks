<div class="row" style="margin-bottom: 20px;">
	<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
  
	<% loop Blocks.Sort('Sort') %>
		        
		<div class="small-12 large-{$ColumnClass($TotalItems)} columns">
            $Me
      	</div>    

	<% end_loop %>

</div>