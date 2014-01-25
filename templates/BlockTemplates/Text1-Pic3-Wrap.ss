<div class="section clearfix textfirst wrap item{$Pos}">
<% if $SectionHeader != "None" %><{$SectionHeader}>$Name</{$SectionHeader}><% end_if %>
<div class="textwrap">
	<div class="picturewrap span3">
	<% if Images %>
		<% loop Images %>
			$SetWidth(370)
		<% end_loop %>
	<% end_if %>
	</div>
	$SectionContent</div>
</div>