function update(str) {
	var row = document.getElementsByClassName(str);
	
	row[0].style.transition = "opacity 1s ease-in-out";
	row[0].style.opacity = "0.4";
	row[0].style.filter = "alpha(opacity=40)";
	
	var still = document.getElementsByClassName("still"+str);
	still[0].style.display = "none";
		
	var all_active_sublinks = row[0].getElementsByTagName("a");
	for (var k = 0; k < all_active_sublinks.length; k++) {
			all_active_sublinks[k].style.pointerEvents = "none";
	}	
	
	var name = document.getElementsByClassName("name"+str);
	
	$(name).html('<span style="color: white;"><img src="img/load.gif" alt="404" title="loading" /></span>');

	$.ajax({
		type: "GET",
		dataType: "html",
		url: "var/ajax/update.php",
		data: {
			character: +str
		},
		success: function (data) {
			$(name).html('<span style="color: white; font-size: 12px;">Updated! Please refresh the page.</span>');
			row[0].style.transition = "opacity 1s ease-in-out";
			row[0].style.opacity = "1";
			row[0].style.filter = "alpha(opacity=100)";
		},
		error: function (data) {
			$(name).html('<span style="color: coral; font-size: 12px;">Error! Still in guild?</span>');
			row[0].style.transition = "opacity 1s ease-in-out";
			row[0].style.opacity = "1";
			row[0].style.filter = "alpha(opacity=100)";
		}
	});
};