<div class="fluidblock clearfix picturefirst wrap item{$Pos}">
	<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
	<div class="textwrap span4of4">
		<div class="picturewrap span1of4">
			<% if Images %>
				<% loop Images %>
					$SetWidth(500)
				<% end_loop %>
			<% end_if %>
		</div>	
		$Content
	</div>
</div>
