function global_update() {
	
	var text = document.getElementsByClassName("global_update");
	
	$(text).html('updating all current entries - please be patient!');
	$(text).css('color', 'orange');
		
	var row = document.getElementsByClassName(str);
	
	
	for (var i = 0; i < row.length; i++) {
		row[i].style.transition = "opacity 1s ease-in-out";
		row[i].style.opacity = "0.4";
		row[i].style.filter = "alpha(opacity=40)";
	
		var still = document.getElementsByClassName("still");
		still[0].style.display = "none";
		
		var all_active_sublinks = row[i].getElementsByTagName("a");
		for (var k = 0; k < all_active_sublinks.length; k++) {
			all_active_sublinks[k].style.pointerEvents = "none";
		}
		
		var name = document.getElementsByClassName("name"+str);
	
		$(name).html('<span style="color: white;">Updating...</span>');

		$.ajax({
			type: "GET",
			dataType: "html",
			url: "var/ajax/update.php",
			data: {
				character: +str
			},
			success: function (data) {
				$(name).html('<span style="color: white; font-size: 12px;">Updated! Please refresh the page.</span>');
				row[i].style.transition = "opacity 1s ease-in-out";
				row[i].style.opacity = "1";
				row[i].style.filter = "alpha(opacity=100)";
			}
		});
	}	
}