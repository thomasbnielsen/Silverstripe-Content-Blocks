<div class="section">
	<% if $SectionHeader != "None" %><{$SectionHeader}>$Name</{$SectionHeader}><% end_if %>
	<h2>$Name</h2>
	<% if Images %>
		<% loop Images %>
			$CroppedImage(150,100)
		<% end_loop %>
	<% end_if %>
	$SectionContent
</div>