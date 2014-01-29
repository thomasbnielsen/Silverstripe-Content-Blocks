<div class="block clearfix textfirst item{$Pos}">
	<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
	<div class="textwrap span1">$Content</div>
	<div class="picturewrap span3">
	<% if Images %>
		<% loop Images %>
			$SetWidth(370)
		<% end_loop %>
	<% end_if %>
	</div>
</div>