<div class="section clearfix textfirst wrap item{$Pos}">
<% if $SectionHeader != "None" %><{$SectionHeader}>$Name</{$SectionHeader}><% end_if %>
<div class="textwrap">
	<div class="picturewrap span2">
	<% if Images %>
		<% loop Images %>
			$SetWidth(220)
		<% end_loop %>
	<% end_if %>
	</div>
	$SectionContent</div>
</div>