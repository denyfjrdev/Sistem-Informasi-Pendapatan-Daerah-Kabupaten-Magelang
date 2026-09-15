$(document).ready(function () {
	$("#table-user").DataTable({
		processing: true,
		serverSide: true,
		// ajax: {
		// 	url: url_list,
		// 	type: "POST",
		// 	data: {
		// 		role_filter: role_filter,
		// 	},
		// 	dataSrc: function (res) {
		// 		console.log(res);

		// 		return res.data;
		// 	},
		// },
		ajax: {
			url: url_list,
			type: "POST",
			data: {
				role_filter: role_filter,
			},
		},
		columns: [
			// 			{
			// 				data: "user_id",
			// 				render: function (data, type, row, meta) {
			// 					//return meta.row + meta.settings._iDisplayStart + 1;
			// 					return row.user_id;
			// 				},
			// 			},
			{ data: "user_id" },
			{ data: "nama_dekrip" },
			{ data: "nohp_dekrip" },
			{ data: "role" },
			{
				data: "user_id",
				render: function (data, type, row) {
					let btn = `<button onclick="isi_user(this)" type="button" class="btn btn-primary btn-sm"    
              data-bs-toggle="modal" 
              data-bs-target=".bs-example-modal-xl-roles"            
              data-id="${row.user_id}" 
              data-namadekrip="${row.nama_dekrip}" 
              data-nohpdekrip="${row.nohp_dekrip}" 
              >
              <i class="fa-solid fa-user me-2"></i>Edit role</button>`;
					return btn;
				},
			},
		],
		order: [[0, "desc"]],
	});
});

// simpan users
function simpan_user() {
	let form = $("#formUser");
	const modal = bootstrap.Modal.getInstance(
		document.getElementById("modal_id"),
	);
	// let layanan_id = $("#layanan_id_id").val();

	$.ajax({
		url: url_simpan_user,
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
				// alert("Data gagal disimpan " + response.message);
				alert(JSON.stringify(response.message));
			}

			// $("#html_tahapan").html(html);
			// modal.hide();
			// tampil_user();
		},

		error: function () {
			// error
			alert("Terjadi kesalahan server");
		},

		complete: function () {
			modal.hide();
			$("#table-user").DataTable().ajax.reload();
			// tampil_user();
		},
	});
}

function isi_user(button) {
	document.getElementById("id_id_roles").value = $(button).data("id");
	document.getElementById("nama_user_id_roles").value =
		$(button).data("namadekrip");
	document.getElementById("nohp_id_roles").value = $(button).data("nohpdekrip");
}

// simpan roles
function simpan_roles() {
	let form = $("#form_user_roles");
	const modal = bootstrap.Modal.getInstance(
		document.getElementById("modal_id_roles"),
	);
	// let layanan_id = $("#layanan_id_id").val();

	$.ajax({
		url: url_simpan_roles,
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
				// alert("Data gagal disimpan " + response.message);
				alert(JSON.stringify(response.message));
			}

			// $("#html_tahapan").html(html);
			// modal.hide();
			// tampil_user();
		},

		error: function () {
			// error
			alert("Terjadi kesalahan server");
		},

		complete: function () {
			modal.hide();
			$("#table-user").DataTable().ajax.reload();
			// tampil_user();
		},
	});
}
