<div class="block clearfix picturefirst item{$Pos}">
	<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
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
		$Content
	</div>
</div>