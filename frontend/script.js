var count;
var active;

function init(timeout_, showit_) {
  if (timeout_ == void(0) || showit_ == void(0)) {
		$("#timeout").html("Unable to start forced logoff routine.");
		$("#timeout").css("visibility", "visible");
    window.clearInterval(active);
    return;
  }
  
	if ($("#timeout").length > 0) {
		count = timeout_;
		window.clearInterval(active);
		active = window.setInterval("counter(" + timeout_ + ", " + showit_ + ")", 1000);
		$("#timeout").css("visibility", "hidden");
	}
}

function counter(timeout_, showit_) {
	count--;
	
	if ($("#timeout").length > 0 && count <= showit_) {
		$("#timeout").html(count + " seconds left until forced logout | <a onclick=\"javascript:init(" + timeout_ + ", " + showit_ + ");\" href=\"#\">reset<\/a>");
		$("#timeout").css("visibility", "visible");
	}
	
	if(count == 0) {
		window.clearInterval(active);
		window.document.location = "logout.php";
	};
}

function checkpop(winname_) {
  if (winname_ != "w3pwwin") {
    $("#popup").css("visibility", "visible");
  }
}

function go_to(page_) {
  window.document.location = page_;
}

function go_home() {
  go_to("/");
}