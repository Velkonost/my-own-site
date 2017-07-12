$('button').click(function(){
	if ($(this).children('i').text() === "favorite_border") {
		$(this).children('i').text('favorite');
	} else if ($(this).children('i').text() === "favorite") {
		$(this).children('i').text('favorite_border');
	}
});