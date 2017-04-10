function update(str) {
	var inspect_container = document.getElementsByClassName("inspect");

	for (var i = 0; i < inspect_container.length; i++) {
		inspect_container[i].style.transition = "opacity 1s ease-in-out";
		inspect_container[i].style.opacity = "0.4";
		inspect_container[i].style.filter = "alpha(opacity=40)";

		var all_active_sublinks = inspect_container[i].getElementsByTagName("a");
		for (var k = 0; k < all_active_sublinks.length; k++) {
			all_active_sublinks[k].style.pointerEvents = "none";
		}

		var all_active_selects = inspect_container[i].getElementsByTagName("select");
		for (var k = 0; k < all_active_selects.length; k++) {
			all_active_selects[k].style.pointerEvents = "none";
		}
	}

	var patience_container = document.getElementById("patience");
	patience_container.style.transition = "opacity 1s ease-in-out";
	patience_container.style.opacity = "1.0";
	patience_container.style.filter = "alpha(opacity=100)";
	patience_container.style.display = "block";

	var wowhead_container = document.getElementsByClassName("wowhead-tooltip");
	for (var i = 0; i < wowhead_container.length; i++) {
		if (wowhead_container[i]) {
			wowhead_container[i].parentNode.removeChild(wowhead_container[i]);
		}
	}

	$.ajax({
		type: "GET",
		dataType: "html",
		url: "var/ajax/update.php",
		data: {
			character: +str
		},
		success: function (data) {
			var patience_text = document.getElementById("patience_text");
			patience_text.style.display = "none";

			$("#answer").html(data);

			setTimeout(function () {
				location.reload();
			}, 5000);
		}
	});
};