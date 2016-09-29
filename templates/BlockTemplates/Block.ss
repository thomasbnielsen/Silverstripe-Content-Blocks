<div class="row $ExtraClasses">
	<div class="small-12 columns">
	<div class="content-wrap">
		<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
		<div class="content">
			$Content
		</div>
	</div>
	<% if Images %>
		<% loop Images.Sort('SortOrder') %>
        	<a class="fancybox cboxElement" href="$Me.SetWidth(700).URL">
            $Me.SetWidth(1000)
            </a>
		<% end_loop %>
	<% end_if %>
	</div>
	
</div>