<% if $Posts %>
<ul>
	<% loop $Posts %>
		<li><a href="$Link"><img src="$ImageURL" alt=""></a></li>
	<% end_loop %>
</ul>
<% end_if %>