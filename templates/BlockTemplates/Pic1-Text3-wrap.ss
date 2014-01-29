<div class="block clearfix picturefirst wrap item{$Pos}">
<% if $Header != "None" %><{$Header}>$Header</{$Header}><% end_if %>
<div class="textwrap">
	<div class="picturewrap span1">
	<% if Images %>
		<% loop Images %>
			$SetWidth(110)
		<% end_loop %>
	<% end_if %>
	</div>
	$Content</div>
</div>