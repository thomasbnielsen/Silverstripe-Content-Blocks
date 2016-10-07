<div class="row $ExtraClasses">
	<div class="small-12 columns">
	<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>

    <% if Images %>
        <% loop Images.Sort('SortOrder') %>
        	<a class="fancybox cboxElement" href="$Me.SetWidth(700).URL">
            $Me.SetWidth(1000)
            </a>
        <% end_loop %>
    <% end_if %>
		<div class="content-wrap">
			<div class="content">
				$Content
			</div>
		</div>
	</div>
</div>