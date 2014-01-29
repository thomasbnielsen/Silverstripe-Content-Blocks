<div class="fluidblock clearfix textfirst wrap item{$Pos}">
	<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
	<div class="textwrap span1of4">
		<div class="picturewrap span3of4">
			<% if Images %>
				<% loop Images %>
					$SetWidth(500)
				<% end_loop %>
			<% end_if %>
		</div>	
		$Content
	</div>
</div>
