<div class="block clearfix textfirst item{$Pos}">
	<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
	<div class="textwrap span4">$Content</div>
	<div class="picturewrap span4">
	<% if Images %>
		<% loop Images %>
			$SetWidth(500)
		<% end_loop %>
	<% end_if %>
	</div>
</div>