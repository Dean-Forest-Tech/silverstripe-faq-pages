<div class="feedback-buttons">
	<p>
		<span class="small">Is this helpfull? &nbsp;</span>
		<span class="btn-group">
			<% if $IsPositive %>
				<a class="active disabled btn btn-success btn-green" href="#">
			<% else %>
				<a class="btn btn-success btn-green" href="$Link('addPositive')">
			<% end_if %>
				<span class="big">&#10003;</span>
				<span class="small">($PosCount)</span>
			</a>
			<% if $IsNegative %>
				<a class="active disabled btn btn-danger btn-red" href="#">
			<% else %>
				<a class="btn btn-danger btn-red" href="$Link('addNegative')">
			<% end_if %>
				<span class="big">&#10007;</span>
				<span class="small">($NegCount)</span>
			</a>
		</span>
	</p>
</div>  
