<div class="feedback-buttons">
	<p>
		<span class="small">Is this helpful? &nbsp;</span>
		<span class="btn-group">
			<% if $IsPositive %>
				<a rel="nofollow" class="active disabled btn btn-success btn-green" href="#">
			<% else %>
				<a rel="nofollow" class="btn btn-success btn-green" href="$Link('addPositive')">
			<% end_if %>
				<span class="big">&#10003;</span>
				<span class="small">($PosCount)</span>
			</a>
			<% if $IsNegative %>
				<a rel="nofollow" class="active disabled btn btn-danger btn-red" href="#">
			<% else %>
				<a rel="nofollow" class="btn btn-danger btn-red" href="$Link('addNegative')">
			<% end_if %>
				<span class="big">&#10007;</span>
				<span class="small">($NegCount)</span>
			</a>
		</span>
	</p>
</div>