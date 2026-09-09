// load pas halaman diload
$(document).ready(function () {
	load_dashboard_permohonan("proses");

	// perusahaan change
	$("#perusahaan_id_filter").on("change", function () {
		let status = $('#formDashboard [name="status_proses"]').val();
		load_dashboard_permohonan(status);
	});
	// layanan_id change
	$("#layanan_id_filter").on("change", function () {
		let status = $('#formDashboard [name="status_proses"]').val();
		load_dashboard_permohonan(status);
	});
});

// Simpan revisi
function simpan_revisi(tombol) {
	const transaksi_id = tombol.dataset.transaksiid;
	const tahapan_id_awal = tombol.dataset.tahapanawal;
	const status_revisi = tombol.dataset.statusrevisi;
	const revisi = $(tombol)
		.closest("form")
		.find("textarea[name='revisi']")
		.val();

	// MODAL
	const modalEl = document.getElementById("modal_golab_view_id");
	let modal = bootstrap.Modal.getInstance(modalEl);
	if (!modal) {
		modal = new bootstrap.Modal(modalEl);
	}

	// ajax simpan
	$.ajax({
		url: url_simpan_ujilab,
		type: "POST",
		dataType: "json",
		data: {
			id: transaksi_id,
			revisi: revisi,
			tahapan_id: tahapan_id_awal,
			status_revisi: status_revisi,
		},

		success: function (response) {
			let html = "";
			if (response.status === true) {
				alert(response.status);
			} else {
				var pesan = `Status : ${response.status}, Message : ${response.message}`;
				alert(pesan);
			}
		},

		error: function () {
			// error
			alert("Error.....dashboard");
		},

		complete: function () {
			// $("#loading_dashboard").hide();
			modal.hide();
		},
	});
}

// simpan lanjutkan proses
function simpan_lanjutkan_proses(tombol) {
	const transaksi_id = tombol.dataset.transaksiid;
	const tahapan_id = tombol.dataset.tahapanid;
	const status_revisi = tombol.dataset.statusrevisi;
	const revisi = $(tombol)
		.closest("form")
		.find("textarea[name='revisi']")
		.val();

	// MODAL
	const modalEl = document.getElementById("modal_golab_view_id");
	let modal = bootstrap.Modal.getInstance(modalEl);
	if (!modal) {
		modal = new bootstrap.Modal(modalEl);
	}

	// ajax simpan
	$.ajax({
		url: url_simpan_ujilab,
		type: "POST",
		dataType: "json",
		data: {
			id: transaksi_id,
			revisi: revisi,
			tahapan_id: tahapan_id,
			status_revisi: status_revisi,
		},

		success: function (response) {
			let html = "";
			if (response.status === true) {
				alert(response.status);
			} else {
				var pesan = `Status : ${response.status}, Message : ${response.message}`;
				alert(pesan);
			}
		},

		error: function () {
			// error
			alert("Error.....dashboard");
		},

		complete: function () {
			modal.hide();
			// $("#loading_dashboard").hide();
		},
	});
}

// load dashboard permohonan
async function load_dashboard_permohonan(status_proses) {
	// caption status proses filter
	$("#captionStatusProses").html(convert_status(status_proses));

	$("#status_proses").val(status_proses);

	let form = $("#formDashboard");

	// load awal
	$("#loading_dashboard").show();
	$("#html_permohonan").html("");

	await $.ajax({
		url: url_dashboard,
		type: "POST",
		dataType: "json",
		data: form.serialize(),

		success: function (response) {
			// alert(response.total.total_proses);
			let html = "";
			if (response.status === true) {
				// alert(response.data[0].nama_tahapan);
				var tombol_aksi = "";
				$.each(response.data, function (i, item) {
					var tombol_aksi = "";
					// 		const perusahaanId =
					// 			document.forms["formDashboard"].perusahaan_id.value;
					if (item.total_transaksi > 0) {
						tombol_aksi = `
					            <button onclick="tampil_permohonan(this)" class="btn btn-sm btn-primary"
					                data-tahapanid="${item.tahapan_id}"
					                data-namatahapan="${item.nama_tahapan}"					                
                          data-perusahaanid="${$("#perusahaan_id_filter option:selected").data("perusahaanid")}"			
                          data-layananid="${$("#layanan_id_filter option:selected").data("layananid")}"	
					              >Tampilkan
					            </button>
					          `;
					}
					html += `
					          <tr>
					            <td>${i + 1}</td>
					            <td>${item.nama_tahapan}</td>
                      <td>${item.role}</td>
					            <td>${item.total_transaksi}</td>
					            <td>${tombol_aksi}</td>
					          <tr>
					        `;
				});

				$("#html_dashboard").html(html);
				$("#captionTotalDraft").html(response.total.total_draft);
				$("#captionTotalProses").html(response.total.total_proses);
				$("#captionTotalSelesai").html(response.total.total_selesai);
			} else {
				// 	// gagal simpan
				var pesan = `Status : ${response.status}, Gagal load : ${response.message}`;
				alert(pesan);
			}
		},

		error: function () {
			// error
			alert("Error.....dashboard");
		},

		complete: function () {
			$("#loading_dashboard").hide();
			// tampil_perusahaan();
			// Kembalikan tombol
			// btn.prop("disabled", false);
			// btn.html('<i class="fa fa-save"></i> Simpan');
			// document.getElementById("formPermohonan").reset();
		},
	});
}

// tampil permohonan
function tampil_permohonan(btn) {
	const perusahaan_id = btn.dataset.perusahaanid;
	const layanan_id = btn.dataset.layananid;
	const tahapan_id = btn.dataset.tahapanid;

	if (
		!perusahaan_id ||
		perusahaan_id === "undefined" ||
		perusahaan_id === "null"
	) {
		Swal.fire({
			icon: "warning",
			title: "Peringatan",
			text: "Silakan pilih perusahaan terlebih dahulu.",
		});
		return;
	}

	$("#caption_status_filter").html(btn.dataset.namatahapan);
	$("#loading_permohonan").show();
	$("#html_permohonan").html("");

	$.ajax({
		url: url_permohonan,
		type: "POST",
		dataType: "json",
		data: {
			perusahaan_id: perusahaan_id,
			layanan_id: layanan_id,
			tahapan_id: tahapan_id,
		},

		success: function (response) {
			let html = "";
			if (response.status === true) {
				var tombol_aksi = "";
				$.each(response.data, function (i, item) {
					// 	disabel_hapus = item.status_proses == "draft" ? "" : " disabled "; // hanya draft yang bisa dihapus
					// 	disabel_edit = item.status_proses == "draft" ? "" : " disabled "; // hanya draft yang bisa dihapus
					tombol_aksi = `<div class="dropdown">
					        <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
					            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
					            <i class="bx bx-menu"></i> Menu
					        </button>
					        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">					            
					            <li>
					              <button type="button"
					                class="dropdown-item"
					                onclick="isi_elemen_modal_view(this)"					                
					                data-transaksiid="${item.transaksi_id}"
					              >
					                <i class="fa fa-eye me-2"></i>View
					              </button>
					            </li>					            					            
					        </ul>
					    </div>`;
					html += `
					        <tr>
					          <td>${item.transaksi_id}</td>
					          <td>
					            ${item.nama_paket}<br>
					            <small><i class="fa fa-clock"></i> ${formatTanggalIndo(item.created_at)} </small>
					            <br>${convert_status(item.status_proses)}
					          </td>
					          <td>
					            <table>
					              <tr>
					                ${tombol_aksi}
					              </tr>
					            </table>
					          </td>
					        <tr>
					      `;
				});

				$("#html_permohonan").html(html);
			} else {
				// gagal simpan
				var pesan = `Status : ${response.status}, ${response.message}`;
				if (response.message?.details) {
					pesan += `\nDetail: ${response.message.details}`;
				}
				alert(pesan);
			}
		},

		error: function () {
			// error
			alert("Terjadi kesalahan server");
		},

		complete: function () {
			$("#loading_permohonan").hide();
		},
	});
}

// tombol view
function isi_elemen_modal_view(btn) {
	$("#isiModalGolabView").html(""); // inisial awal
	const transaksi_id = btn.dataset.transaksiid;
	// $("#transaksi_id_view").val(transaksi_id);
	const modalEl = document.getElementById("modal_golab_view_id");
	let modal = bootstrap.Modal.getInstance(modalEl);
	if (!modal) {
		modal = new bootstrap.Modal(modalEl);
	}

	$.ajax({
		url: url_get_transaksi,
		type: "POST",
		dataType: "json",
		data: {
			transaksi_id: transaksi_id,
		},

		success: function (response) {
			let html = ``;

			if (response.status === true) {
				// console.log(response.data.tahapan);

				// detail
				let detil = response.data.detil;
				// tahapan sekarang
				const tahapanSekarang = JSON.parse(
					response.data.tahapan.tahapan_sekarang,
				);
				// tahapan selanjutnya
				const tahapanBerikutnya = JSON.parse(
					response.data.tahapan.tahapan_berikutnya,
				);
				const tahapanAwal = JSON.parse(response.data.tahapan.tahapan_awal);
				let tahapan = response.data.tahapan;
				let disabel = `disabled`;
				let icon_kirim = `<i class="fa fa-lock"></i>`;
				let bg_btn_kirim = `danger`;
				if (tahapanSekarang.role == "admin") {
					disabel = ``;
					icon_kirim = `<i class="fa fa-arrow-right"></i>`;
					bg_btn_kirim = `primary`;
				}

				html += `<div class="card">`;
				html += `<div class="col-md-12">`;
				html += `<div class="card-body">`;
				html += `
          <h5 class="card-title mb-0 text-primary">
            <li class="fa fa-list"></li> Tahapan
          </h5>
          <table style="width:100%" class="table table-sm table-striped table-bordered">`;
				html += `				    
					  <tr>
					    <td>1. Sekarang</td><td>:</td><td>${tahapanSekarang.nama_tahapan}</td><td><i class="fa fa-user"></i> ${tahapanSekarang.role}</td>
					  </tr>
					  <tr>
					    <td>2. Berikutnya</td><td>:</td><td>${tahapanBerikutnya.nama_tahapan}</td>
				      <td>
				        <button
				          ${disabel}
				          type="button"
				          class="btn btn-sm btn-${bg_btn_kirim}"
				          id="btnLanjutkanProses"
                  data-transaksiid="${detil.transaksi_id}"                                                       
				          data-tahapanid="${tahapanBerikutnya.id}"     
                  data-statusrevisi="tidak"                
				          onclick="simpan_lanjutkan_proses(this)"
				        >${icon_kirim} Lanjutkan proses selanjutnya
				        </button>
                <br><small><strong class="text-primary"><i>Tekan tombol ini untuk meneruskan proses ke tahap berikutnya</i></strong></small>
				      </td>
					  </tr>
					  `;
				html += `</table>`;
				html += `</div>`; // body
				html += `</div>`; //col-md-6
				html += `</div>`; // card

				let no = 1;
				html += `<div class="card">`;
				html += `<div class="row">`;
				html += `<div class="col-md-6">`;
				html += `                  
          <div class="card-body">   
            <h5 class="card-title mb-0 text-primary">
              <li class="fa fa-list"></li> Detail data permohonan              
            </h5>
            <table style="width:100%" class="table table-sm table-striped table-bordered">
              <tr>
                <td width="10px">${no++}</td><td>Nomor</td><td width="5px">:</td><td>${detil.nomor}</td>
              </tr>
              <tr>
                <td>${no++}</td><td>Nama paket</td><td>:</td><td>${detil.nama_paket}</td>
              </tr>
              <tr>
                <td>${no++}</td><td>Jenis uji</td><td>:</td><td>${detil.nama_jenis_uji}</td>
              </tr>
              <tr>
                <td>${no++}</td><td>Jenis uji sub beton</td><td>:</td><td>${detil.nama_jenis_uji_sub}</td>
              </tr>              
              <tr>
                <td>${no++}</td><td>Tgl permohonan uji</td><td>:</td><td>${formatTanggalIndo(detil.tanggal_permohonan_pengujian, false)}</td>
              </tr>
              <tr>
                <td>${no++}</td><td>Tgl pembuatan sampel</td><td>:</td><td>${detil.tanggal_pembuatan_sampel}</td>
              </tr>
              <tr>
                <td>${no++}</td><td>Jumlah pengujian</td><td>:</td><td>${detil.jumlah_pengujian}</td>
              </tr>              
            </table>
          </div>`;
				html += `</div>`; // class col-md-7

				html += `<div class="col-md-6">`;
				html += `
            <h5 class="card-title mb-0 text-primary">
              <li class="fa fa-list"></li> Form revisi permohonan        
            </h5>
            <form action="POST" id="formRevisi" name="formRevisi">
              <table style="width:100%" class="table table-sm table-striped table-bordered">
                <tr>
                  <td>
                    <textarea
                        class="form-control revisi w-100"     
                        name="revisi"                         
                        id="revisi_id"
                        rows="4"
                        placeholder="Masukkan keterangan..."
                    ></textarea>                        
                    <button 
                      ${disabel}
                      onclick="simpan_revisi(this)" 
                      id="btnRevisi" 
                      type="button" 
                      class="btn btn-sm btn-primary"
                      data-transaksiid="${detil.transaksi_id}"                                                       
                      data-tahapanawal="${tahapanAwal.id}"    
                      data-statusrevisi="ya"    
                    ><i class="fa fa-edit"></i> Kirim revisi
                    </button>
                  </td>
                </tr>
              </table>
            </form>        
        `;

				html += `</div>`; // class col-md-5
				html += `</div>`; // class row
				html += `</div>`; // card

				$("#isiModalGolabView").html(html);
			} else {
				alert(response.message);
			}
		},

		error: function () {
			// error
			alert("Terjadi kesalahan server");
		},

		complete: function () {
			modal.show();
		},
	});
}

// create PDF
function create_pdf(tombol) {
	const transaksi_id = tombol.dataset.transaksiid;

	$.ajax({
		url: url_create_pdf,
		type: "POST",
		dataType: "json",
		data: {
			transaksi_id: transaksi_id,
		},

		success: function (response) {
			let html = ``;

			if (response.status === true) {
				// console.log(response.data.tahapan);
				// $("#isiModalGolabView").html(html);
			} else {
				alert(response.message);
			}
		},

		error: function () {
			// error
			alert("Terjadi kesalahan server");
		},

		complete: function () {
			modal.show();
		},
	});
}
