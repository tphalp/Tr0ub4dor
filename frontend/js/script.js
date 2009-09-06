/* $Id$ */
var count;
var active;
var popup_name = "w3pwwin";
var page_logout = "logout.php";
var infomsg = "";

function init(timeout_, showit_) {
//----------------------------------------------
// the init function, which starts the timeout
// counter, and handles the initial visibility 
// of the timeout message.
//----------------------------------------------
  if (timeout_ == void(0) || showit_ == void(0)) {
    $("#timeout").html("Unable to start forced logoff routine.");
    $("#timeout").hide();
    window.clearInterval(active);
    return;
  }
  
  if ($("#timeout").length > 0) {
    count = timeout_;
    window.clearInterval(active);
    active = window.setInterval("counter(" + timeout_ + ", " + showit_ + ")", 1000);
    $("#timeout").hide();
  }
  
  // Setup the message, mouseover, and click bits
  // MouseOver
  $("#info").mouseover(function() {
    if ( $("#timeout").is(":visible") ) {
      this.style.zIndex = 100;
    }
  });
  //MouseOut
  $("#info").mouseout(function() {
    if ( $("#timeout").is(":visible") ) {
      $("#info").css("zIndex", 1);
    }
  });
  // OnClick
  $("#info").click(function() {
    $("#info").hide("fast");
  });
  
  return;
}

function counter(timeout_, showit_) {
//----------------------------------------------
// The counter function that is called in the
// setInterval method during the init() 
// function. This function handles the timeout
// message that appears, and also handles the 
// actual logout, if the timeout timer expires.
//----------------------------------------------
  // Decrement the counter.
  count--;
  
  // Check for proper params before showing the timeout element.
  if ($("#timeout").length > 0 && count <= showit_) {
    $("#timeout").html(count + " seconds left until forced logout | <a onclick=\"javascript:init(" + timeout_ + ", " + showit_ + ");\" href=\"javascript:void(0);\">reset<\/a>&nbsp;&nbsp;<a onclick=\"javascript:do_logout();\" href=\"javascript:void(0);\">logout<\/a>");
    $("#timeout").show();
  }
  
  // If the countdown has expired, then log the user out.
  if (count == 0) {
    do_logout();
  }
}

function checkpop(winname_) {
//----------------------------------------------
// Checks to see if the current window is the
// popup windows. If it is not, then is makes
// the popup notification visible.
//----------------------------------------------
  if (winname_ != popup_name) {
    $("#popup").css("visibility", "visible");    
  }
}

function go_to(page_) {
//----------------------------------------------
// Go to a specific page
//----------------------------------------------
  if (page_.length > 0) {
    window.document.location = page_;
  }
}

function do_logout() {
//----------------------------------------------
// Logs out of the system
//----------------------------------------------
  // Clear the interval that was setup to check for timeout.
  window.clearInterval(active);
  // Now logout.
  go_to(page_logout);
}

function set_info(msg, show, len) {
//----------------------------------------------
// Sets the info span that is used for 
// system messages.
//----------------------------------------------
  // Test for show bit.
  if (show == 1) {
    if (msg.length > 0) {
      // Set the default value for len (10 seconds).
      if (len== undefined) {len = 10000;}
      
      $("#info").text(msg);
      $("#info").show("fast");
      tmp_int = window.setInterval("set_info('', 0);window.clearInterval(tmp_int);", len);
    }
  } else {
    // Hide the element.
    $("#info").hide("fast");
  }

}

/* BACKUP for possible use later.
function checkForm_Required(req_, req_desc_) {
  var err__ = new Array();
  var tmp__ = "";
  var i;

  for (i=0; i < req_.length; i++) {
    tmp__ = $("#" + req_[i]).val();

    if (tmp__.length == 0) {
      err__[i] = req_desc_[i] + " is required.";

      $("#info").append("-" + err__[i] + "<br />").css("visibility", "visible");
      $("#" + req_[i] + "-msg").css("display", "inline");
    } else {
      $("#info").css("visibility", "hidden").text("");
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

        $("#info").append("-" + err__[i] + "<br />").css("visibility", "visible");
        $("#" + req_[i] + "-msg").css("display", "inline");
      } else {
        $("#info").css("visibility", "hidden").text("");
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

  $("#info").text("");
  ret1__ = checkForm_Required(["pw", "newpw", "confirm"], ["Old password", "New Password", "Confirm New Password"]);
  //ret2__ = checkForm_Match(["newpw", "confirm"], ["New Password", "Confirm New Password"]);

  if (ret1__.length > 0 || ret2__.length) {
    return false;
    
  } else {
    return confirm(msg_good__);
    
  }
  
}
END BACKUP */