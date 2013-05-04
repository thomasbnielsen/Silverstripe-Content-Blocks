<div class="section clearfix picturefirst item{$Pos}">
	<% if $SectionHeader != "None" %><{$SectionHeader}>$Name</{$SectionHeader}><% end_if %>
	<div class="picturewrap span2">
	<% if Images %>
		<% loop Images %>
			$SetWidth(220)
		<% end_loop %>
	<% end_if %>
	</div>
	<div class="textwrap span2">$SectionContent</div>
</div>
