var count;
var active;
var popup_name = "w3pwwin";

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
    go_to("logout.php");
  };
}

function checkpop(winname_) {
  if (winname_ != popup_name) {
    $("#popup").css("visibility", "visible");
  }
}

function go_to(page_) {
  if (page_.length > 0) {
    window.document.location = page_;
  }
}

function go_home() {
  go_to("/");
}

/*
function checkForm_Required(req_, req_desc_) {
  var err__ = new Array();
  var tmp__ = "";
  var i;

  for (i=0; i < req_.length; i++) {
    tmp__ = $("#" + req_[i]).val();

    if (tmp__.length == 0) {
      err__[i] = req_desc_[i] + " is required.";

      $("#sysmsg").append("-" + err__[i] + "<br />").css("visibility", "visible");
      $("#" + req_[i] + "-msg").css("display", "inline");
    } else {
      $("#sysmsg").css("visibility", "hidden").text("");
      $("#" + req_[i] + "-msg").css("display", "none");
    }
  } //for loop
  
  return err__;
} //checkForm_Required()

function checkForm_Match(req_, req_desc_) {
  var err__ = new Array();
  var tmp1__ = "";
  var tmp2__ = "";
  var i;

  for (i=0; i < req_.length; (i + 2)) {
    tmp1__ = $("#" + req_[i]).val();
    tmp2__ = $("#" + req_[(i + 1)]).val();
alert(i)
    if (tmp1__.length > 0 || tmp2__.length > 0) {
      if (tmp1__ !== tmp2__) {
        err__[i] = req_desc_[i] + " and " + req_desc_[i + 1] + " must match";

        $("#sysmsg").append("-" + err__[i] + "<br />").css("visibility", "visible");
        $("#" + req_[i] + "-msg").css("display", "inline");
      } else {
        $("#sysmsg").css("visibility", "hidden").text("");
        $("#" + req_[i] + "-msg").css("display", "none");
      }
    }
    
  } //for loop
  
  return err__;
} //checkForm_Match()

function checkChangePW() {
  var ret1__ = "";
  var ret2__ = "";
  var err__ = "";
  var msg_good__ = "Are you sure you want to change the Master Password?";

  $("#sysmsg").text("");
  ret1__ = checkForm_Required(["pw", "newpw", "confirm"], ["Old password", "New Password", "Confirm New Password"]);
  //ret2__ = checkForm_Match(["newpw", "confirm"], ["New Password", "Confirm New Password"]);

  if (ret1__.length > 0 || ret2__.length) {
    return false;
    
  } else {
    return confirm(msg_good__);
    
  }
  
}
*/