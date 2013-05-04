<div class="section clearfix picturefirst item{$Pos}">
	<% if $SectionHeader != "None" %><{$SectionHeader}>$Name</{$SectionHeader}><% end_if %>
	<div class="picturewrap span1">
	<% if Images %>
		<% loop Images %>
			$SetWidth(110)
		<% end_loop %>
	<% end_if %>
	</div>
	<div class="textwrap span3">
		<div><strong>Name: </strong>$EmployeeName</div>
		<div><strong>Phone: </strong>$EmployeePhone</div>
		<div><strong>Email: </strong>$EmployeeEmail</div>
		$SectionContent
	</div>
</div>