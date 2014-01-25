<div class="section clearfix textfirst item{$Pos}">
	<% if $SectionHeader != "None" %><{$SectionHeader}>$Name</{$SectionHeader}><% end_if %>
	<div class="textwrap span2">$SectionContent</div>
	<div class="picturewrap span2">
	<% if Images %>
		<% loop Images %>
			$SetWidth(240)
		<% end_loop %>
	<% end_if %>
	</div>
</div>