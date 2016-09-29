<div class="row picturelist $ExtraClasses">
	
	<div class="small-12 columns">
		<div class="content-wrap">
			<% if $Header != "None" %><{$Header}>$Name</{$Header}><% end_if %>
			<div class="content">
				$Content
			</div>
		</div>
		<ul class="small-block-grid-2 medium-block-grid-3 large-block-grid-4">
			<% if Images %>
				<% loop Images.Sort('SortOrder') %>
					<li>
						<a href="$Me.CroppedFocusedImage(940,700).URL" class="th fancybox cboxElement">
							$Top.FormattedBlockImage($ID, 300, 300)
						</a>
					</li>
				<% end_loop %>
			<% end_if %>
		</ul>
	</div>
</div>		

