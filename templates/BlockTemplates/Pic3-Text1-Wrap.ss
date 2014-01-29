<div class="block clearfix picturefirst wrap item{$Pos}">
<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
<div class="textwrap">
	<div class="picturewrap span3">
	<% if Images %>
		<% loop Images %>
			$SetWidth(370)
		<% end_loop %>
	<% end_if %>
	</div>
	$Content</div>
</div>