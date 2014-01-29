<div class="block clearfix textfirst wrap item{$Pos}">
<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
<div class="textwrap">
	<div class="picturewrap span1">
	<% if Images %>
		<% loop Images %>
			$SetWidth(270)
		<% end_loop %>
	<% end_if %>
	</div>
	$Content</div>
</div>