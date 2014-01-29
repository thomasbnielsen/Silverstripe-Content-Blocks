<div class="block clearfix picturefirst item{$Pos}">
	<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
	<div class="picturewrap span2">
	<% if Images %>
		<% loop Images %>
			$SetWidth(240)
		<% end_loop %>
	<% end_if %>
	</div>
	<div class="textwrap span2">$Content</div>
</div>
