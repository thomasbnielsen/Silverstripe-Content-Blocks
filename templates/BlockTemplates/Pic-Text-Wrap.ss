<div class="row">
	<div class="small-12 columns">
		<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
	</div>

	<div class="small-12 columns">
        <% if Images %>
            <% loop Images.Sort('SortOrder') %>
                <a class="small-12 medium-3 columns block-left block-wrap" href="$Me.SetWidth(700).URL">
               		 $Me.CroppedFocusedImage(600,600)
                </a>
            <% end_loop %>
        <% end_if %>

		$Content
	</div>
</div>