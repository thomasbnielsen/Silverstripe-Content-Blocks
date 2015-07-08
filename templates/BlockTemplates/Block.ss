<div class="row">
	<div class="small-12 columns">
	<h2>$Title</{$Header}></h2>
	
	$Content
	
	<% if Images %>
		<% loop Images.Sort('SortOrder') %>
        	<a class="fancybox cboxElement" href="$Me.SetWidth(700).URL">
            $Me.SetWidth(1000)
            </a>
		<% end_loop %>
	<% end_if %>
	</div>
	
</div>