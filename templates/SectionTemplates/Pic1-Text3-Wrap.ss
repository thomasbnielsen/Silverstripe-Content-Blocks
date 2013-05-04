<div class="section clearfix picturefirst wrap item{$Pos}">
<% if $SectionHeader != "None" %><{$SectionHeader}>$SectionHeader</{$SectionHeader}><% end_if %>
<div class="textwrap">
	<div class="picturewrap span1">
	<% if Images %>
		<% loop Images %>
			$SetWidth(110)
		<% end_loop %>
	<% end_if %>
	</div>
	$SectionContent</div>
</div>