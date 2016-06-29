<% include SideBar %>

<div class="content-container <% if $Menu(2) %>unit-75<% end_if %>">
    <article>
        <h1>$Title</h1>
        <div class="content">$Content</div>
        <% if $AllChildren %>
			<div class="qandas">
				<% loop $AllChildren.Sort('FeedbackScore',DESC) %>
					<div class="qanda units-row row">
						<div class="question col-md-6 unit-50">
							<h2>$Title</h2>
							<% include FeedbackButtons %>
						</div>
						<div class="answer col-md-6 unit-50">
							$Content
						</div>
					</div>
				<% end_loop %>
			</div>
        <% end_if %>
        $CalendarWidget
    </article>
        $Form
        $PageComments
</div>
