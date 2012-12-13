<div class="flag_container" >
<img src="image.flag.php?size=16&color=<% 
    x=1; _(data).each(function( line ){  if(x == 1){ %><%= line.color %><% x=0; } else { %>|<%= line.color %><% } %><% }) %>" />
</div>