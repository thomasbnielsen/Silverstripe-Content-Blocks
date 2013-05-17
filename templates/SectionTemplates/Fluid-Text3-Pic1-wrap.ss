<div class="fluidsection clearfix textfirst wrap item{$Pos}">
	<% if $SectionHeader != "None" %><{$SectionHeader}>$Name</{$SectionHeader}><% end_if %>
	<div class="textwrap span4of4">
		<div class="picturewrap span1of4">
			<% if Images %>
				<% loop Images %>
					$SetWidth(500)
				<% end_loop %>
			<% end_if %>
		</div>	
		$SectionContent
	</div>
</div>
