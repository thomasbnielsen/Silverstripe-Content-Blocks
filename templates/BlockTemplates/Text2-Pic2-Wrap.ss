<div class="row">
	<% if $Header != "None" %>
		<div class="small-12 columns">
			<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
		</div>
	<% end_if %>

	<div class="small-12 columns">
        <% if $Images %>
            <% loop $Images.Sort('SortOrder') %>
                <a class="small-12 medium-6 columns block-right block-wrap lightbox" href="$Me.SetWidth(700).URL">
					$Top.FormattedBlockImage($ID, 600, 600)
                </a>
            <% end_loop %>
        <% end_if %>

		$Content
	</div>
</div>