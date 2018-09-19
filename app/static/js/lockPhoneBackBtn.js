
/**
 * 手机物理返回键失效
 */
pushHistory();
setTimeout(function() {
	window.addEventListener("popstate", function(e) {
		showBox("", 10, function() {
			pushHistory();
		});
	}, false);
}, 10);

function pushHistory() {
	var state = {
		title : "title",
		url : "#"
	};
	window.history.pushState(state, "title", "#");
}

function showBox(msg, timeOut, onTimeOut) {
	if (typeof alertBoxDiv === "undefined") {
		alertBoxDiv = $("<div/>").addClass("alert-box hide").append(
				$("<div/>").addClass("label label-primary"))
				.appendTo($("body"));
	}
	alertBoxDiv.children(".label").html(msg);
	alertBoxDiv.removeClass("hide");
	if (typeof timeOut === "undefined")
		timeOut = 10;
	setTimeout(function() {
		alertBoxDiv.addClass("hide");
		if (typeof onTimeOut !== "undefined")
			onTimeOut();
	}, timeOut);
}