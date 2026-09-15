// function tampil_tahapan(url, layanan_id) {
// 	$("#loading_tahapan").show();
// 	$("#html_tahapan").html("");

// 	$.ajax({
// 		url: url,
// 		type: "POST",
// 		data: {
// 			layanan_id: layanan_id,
// 		},
// 		dataType: "json",
// 		success: function (response) {
// 			let html = "";

// 			$.each(response.data, function (i, item) {
// 				html += `<tr>`;
// 				html += `
//                   <td>${item.urutan}</td>
//                   <td>${item.nama_tahapan}</td>
//                   <td>${item.role}</td>
//                   <td>${item.proses}</td>
//                   <td>${item.keterangan}</td>
//                   <td>
//                     <button onclick="isi_model_tahapan(this)" type="button" class="btn btn-primary w-xs waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl"
//                       data-id="${item.layanan_id}"
//                       data-namatahapan="${item.nama_tahapan}"
//                       data-role="${item.role}"
//                       data-proses="${item.proses}"
//                       data-keterangan="${item.keterangan}"
//                       data-urutan="${item.urutan}"
//                       data-layananid="${item.layanan_id}"
//                     >
//                     <i class="fa-solid fa-edit me-2"></i>Edit</button>
//                   </td>
//                 `;
// 				html += `</tr>`;
// 			});

// 			$("#html_tahapan").html(html);
// 		},
// 	});
// }

function tampil_tahapan(layanan_id) {
	$("#header_tahapan").hide();
	$("#loading_tahapan").show();

	$("#html_tahapan").html("");

	$.ajax({
		url: url_load_data,
		type: "POST",
		dataType: "json",
		data: {
			layanan_id: layanan_id,
		},

		success: function (response) {
			let html = "";

			if (response.status === true) {
				$.each(response.data, function (i, item) {
					let tombol_hapus = "";
					if (`${item.cek_transaksi}` == "0") {
						tombol_hapus += `<button type="button" 
              onclick="isi_model_tahapan_hapus(this)" 
              class="btn btn-danger btn-sm" 
              data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl-hapus" 
              data-id="${item.tahapan_id}"
              data-namatahapan="${item.nama_tahapan}"               
              data-layananid="${item.layanan_id}"    
              data-role="${item.role}"  
              > 
              <i class="fa-solid fa-trash me-2"></i>Hapus</button> `;
					} else {
						tombol_hapus +=
							'<button disabled type="button" class="btn btn-danger btn-sm" > <i class="fa-solid fa-lock me-2"></i>Hapus</button> ';
					}
					html += `
                        <tr>
                            <td>${item.urutan}</td>
                            <td>${item.nama_tahapan}</td>
                            <td>${item.role}</td>
                            <td>
                                ${
																	item.proses === "manual"
																		? '<span class="badge bg-info text-dark">Manual</span>'
																		: item.proses === "sistem"
																			? '<span class="badge bg-success">Sistem</span>'
																			: item.proses
																}
                            </td>
                            <td>${item.keterangan}</td>
                            <td>
                              <table>
                                <tr>
                                  <td>
                                    <button onclick="isi_model_tahapan(this)" type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl"
                                      data-id="${item.tahapan_id}"
                                      data-namatahapan="${item.nama_tahapan}"
                                      data-role="${item.role}"
                                      data-proses="${item.proses}"
                                      data-keterangan="${item.keterangan}"
                                      data-urutan="${item.urutan}"
                                      data-layananid="${item.layanan_id}"
                                    >
                                    <i class="fa-solid fa-edit me-2"></i>Edit</button>
                                  </td>
                                  <td>${tombol_hapus}</td>
                                </tr>
                              </table>
                            </td>                            
                        </tr>
                    `;
				});
			} else {
				html = `
                    <tr>
                        <td colspan="6" class="text-center">
                            Data tidak ditemukan
                        </td>
                    </tr>
                `;
			}

			$("#html_tahapan").html(html);
		},

		error: function () {
			$("#html_tahapan").html(`
                <tr>
                    <td colspan="6" class="text-center text-danger">
                        Gagal mengambil data
                    </td>
                </tr>
            `);
		},

		complete: function () {
			$("#loading_tahapan").hide();
			$("#header_tahapan").show();
		},
	});
}

// mengisi elemen modal tahapan saat mau edit
function isi_model_tahapan(button) {
	document.getElementById("id_id").value = $(button).data("id");
	document.getElementById("nama_tahapan_id").value =
		$(button).data("namatahapan");
	document.getElementById("role_id").value = $(button).data("role");
	document.getElementById("keterangan_id").value = $(button).data("keterangan");
	document.getElementById("urutan_id").value = $(button).data("urutan");
	document.getElementById("layanan_id_id").value = $(button).data("layananid");
	document.getElementById("proses_id").value = $(button).data("proses");
}

// mengisi modal konfirmasi hapus tahapan
function isi_model_tahapan_hapus(button) {
	document.getElementById("id_id_hapus").value = $(button).data("id");
	document.getElementById("nama_tahapan_id_hapus").value =
		$(button).data("namatahapan");
	document.getElementById("layanan_id_id_hapus").value =
		$(button).data("layananid");
	document.getElementById("role_id_hapus").value = $(button).data("role");
}

function simpan_tahapan() {
	let form = $("#formTahapan");
	const modal = bootstrap.Modal.getInstance(
		document.getElementById("modal_id"),
	);
	let layanan_id = $("#layanan_id_id").val();

	$.ajax({
		url: url_simpan,
		type: "POST",
		dataType: "json",
		data: form.serialize(),

		success: function (response) {
			let html = "";

			if (response.status === true) {
				// berhasil simpan
				alert("Data berhasil disimpan");
			} else {
				// gagal simpan
				alert("Data gagal disimpan " + response.message);
			}

			// $("#html_tahapan").html(html);
			modal.hide();
			tampil_tahapan(layanan_id);
		},

		error: function () {
			// error
			alert("Terjadi kesalahan server");
		},

		// complete: function () {
		// 	modal.hide();
		// 	tampil_tahapan(url_load_data, layanan_id);
		// },
	});
}

function hapus_tahapan() {
	let form = $("#form_hapus_tahapan");
	const modal = bootstrap.Modal.getInstance(
		document.getElementById("modal_id_hapus"),
	);
	let layanan_id = $("#layanan_id_id_hapus").val();

	$.ajax({
		url: url_hapus,
		type: "POST",
		dataType: "json",
		data: form.serialize(),

		success: function (response) {
			let html = "";

			if (response.status === true) {
				// berhasil simpan
				alert("Data berhasil disimpan");
			} else {
				// gagal simpan
				alert("Data gagal disimpan " + response.message);
			}

			// $("#html_tahapan").html(html);
			modal.hide();
			tampil_tahapan(layanan_id);
		},

		error: function () {
			// error
			alert("Terjadi kesalahan server");
		},

		// complete: function () {
		// 	modal.hide();
		// 	tampil_tahapan(url_load_data, layanan_id);
		// },
	});
}
