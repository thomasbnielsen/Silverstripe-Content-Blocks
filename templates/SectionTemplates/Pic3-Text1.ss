<div class="section clearfix picturefirst item{$Pos}">
	<% if $SectionHeader != "None" %><{$SectionHeader}>$Name</{$SectionHeader}><% end_if %>
	<div class="picturewrap span3">
	<% if Images %>
		<% loop Images %>
			$SetWidth(370)
		<% end_loop %>
	<% end_if %>
	</div>
	<div class="textwrap span1">$SectionContent</div>
</div>