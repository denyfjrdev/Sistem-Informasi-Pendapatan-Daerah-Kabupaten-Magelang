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
  $(".summernote").summernote({
    toolbar: [
      // [groupName, [list of button]]
      ["style", ["bold", "italic", "underline", "clear"]],
      ["font", ["strikethrough", "superscript", "subscript"]],
      ["fontsize", ["fontsize"]],
      ["color", ["color"]],
      ["para", ["ul", "ol", "paragraph"]],
      ["height", ["height"]],
    ],
    height: 200,
    placeholder: "Ketik keterangan tambahan di sini... (jika diperlukan)",
  });
});

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
        html += '<i class="fa fa-file"></i> ' + caption_upload;
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

//-----ajax desa dan kecamatan
function load_desa_kecamatan(url = "") {
  var kode_desa = document.getElementById("kode_desa").value;
  $.ajax({
    url: url,
    method: "POST",
    data: { kode_desa: kode_desa },
    dataType: "JSON",
    success: function (data) {
      if (data.status == true) {
        var isi = data.data;

        document.getElementById("nama_desa").value = isi["nama_desa"];
        document.getElementById("nama_kecamatan").value = isi["nama_kecamatan"];
      } else {
        document.getElementById("nama_desa").value = "---";
        document.getElementById("nama_kecamatan").value = "---";
      }
    },
  });
}

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
  return html;
}
