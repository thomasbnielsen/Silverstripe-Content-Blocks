<div class="fluidblock clearfix picturefirst item{$Pos}">
	<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
	<div class="picturewrap span4of4">
	<% if Images %>
		<% loop Images %>
			$SetWidth(500)
		<% end_loop %>
	<% end_if %>
	</div>
	<div class="textwrap span4of4">$Content</div>
</div>
