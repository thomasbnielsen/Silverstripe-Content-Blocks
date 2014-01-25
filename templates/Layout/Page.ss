<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<h1>$Title</h1>
		<div class="content">$Content</div>
		<div id="Sections">
			<% loop ActiveBlocks %>
				$Me
			<% end_loop %>
		</div>	
	</article>
	$Form
	$PageComments
</div>