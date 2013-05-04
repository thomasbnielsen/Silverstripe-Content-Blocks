<div class="section clearfix picturefirst item{$Pos}">
	<% if $SectionHeader != "None" %><{$SectionHeader}>$Name</{$SectionHeader}><% end_if %>
	<div class="picturewrap span1">
	<% if Images %>
		<% loop Images %>
			$SetWidth(110)
		<% end_loop %>
	<% end_if %>
	</div>
	<div class="textwrap span3">$SectionContent</div>
</div>
