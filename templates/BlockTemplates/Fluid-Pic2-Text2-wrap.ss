<div class="fluidsection clearfix picturefirst wrap item{$Pos}">
	<% if $SectionHeader != "None" %><{$SectionHeader}>$Name</{$SectionHeader}><% end_if %>
	<div class="textwrap span4of4">
		<div class="picturewrap span2of4">
			<% if Images %>
				<% loop Images %>
					$SetWidth(550)
				<% end_loop %>
			<% end_if %>
		</div>	
		$SectionContent
	</div>
</div>
