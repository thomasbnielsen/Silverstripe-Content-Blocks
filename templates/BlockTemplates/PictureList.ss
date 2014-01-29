<div class="block item{$Pos}">
	<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
	<h2>$Name</h2>
	<% if Images %>
		<% loop Images %>
			$CroppedImage(150,100)
		<% end_loop %>
	<% end_if %>
	$Content
</div>