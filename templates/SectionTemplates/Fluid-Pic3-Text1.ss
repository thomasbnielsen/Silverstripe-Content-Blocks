<div class="fluidsection clearfix picturefirst item{$Pos}">
	<% if $SectionHeader != "None" %><{$SectionHeader}>$Name</{$SectionHeader}><% end_if %>
	<div class="picturewrap span2of2">
	<% if Images %>
		<% loop Images %>
			$SetWidth(500)
		<% end_loop %>
	<% end_if %>
	</div>
	<div class="textwrap span2of2">$SectionContent</div>
</div>
