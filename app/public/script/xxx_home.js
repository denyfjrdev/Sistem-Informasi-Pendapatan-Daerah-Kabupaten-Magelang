$(function () {
  //Initialize Select2 Elements
  $(".select2").select2();

  //Initialize Select2 Elements
  $(".select2bs4").select2({
    theme: "bootstrap4",
  });
});

function direk(url) {
  window.location = url;
}

function direk_newtab(url) {
  window.open(url, "_blank");
}

function createFrame(url, judul, tinggi) {
  var myobj = document.getElementById("frame");
  if (myobj != null) {
    myobj.remove();
  }

  frame = document.createElement("iframe");
  frame.setAttribute("src", url);
  frame.setAttribute("name", "Hello");
  frame.setAttribute("id", "frame");
  frame.frameBorder = 0;
  frame.style.width = 95 + "%";
  frame.style.height = tinggi + "vh";
  frame.style.overflow = "hidden";
  frame.style.position = "absolute";
  document.getElementById("iframeHolder").appendChild(frame);

  var body = document.getElementById("modal_body");
  body.style.height = tinggi + 5 + "vh";
  // body.style.overflow = "hidden";
  // body.style.width = 100 +"%";

  // body.style.overflow-y = "hidden";

  document.getElementById("judul_modal").innerHTML = judul;
}

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

// ===== frame modal full screen =====
function createFrame_fullscreen(url, judul, tinggi) {
  var myobj = document.getElementById("frame");
  if (myobj != null) {
    myobj.remove();
  }

  frame = document.createElement("iframe");
  frame.setAttribute("src", url);
  frame.setAttribute("name", "Hello");
  frame.setAttribute("id", "frame");
  frame.frameBorder = 0;
  frame.style.width = 95 + "%";
  frame.style.height = tinggi + "vh";
  frame.style.overflow = "hidden";
  frame.style.position = "absolute";
  document.getElementById("iframeHolder_fullscreen").appendChild(frame);

  var body = document.getElementById("modal_body_fullscreen");
  body.style.height = tinggi + 10 + "vh";
  // body.style.overflow = "hidden";
  // body.style.width = 100 +"%";

  // body.style.overflow-y = "hidden";

  document.getElementById("judul_modal_fullscreen").innerHTML = judul;
}

// ====== konfirmasi ======
function konfirmasi(url, judul) {
  return Swal.fire({
    title: "Konfirmasi !",
    text: judul,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, lanjut!",
  }).then((result) => {
    if (result.value) {
      location = url;
    }
  });
}
