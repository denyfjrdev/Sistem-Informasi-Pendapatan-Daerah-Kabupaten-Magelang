// format tanggal
function formatTanggalIndo(tanggal, jam = true) {
	const d = new Date(tanggal.replace(" ", "T"));

	let hasil =
		`${String(d.getDate()).padStart(2, "0")}-` +
		`${String(d.getMonth() + 1).padStart(2, "0")}-` +
		`${d.getFullYear()}`;

	if (jam) {
		hasil +=
			` ${String(d.getHours()).padStart(2, "0")}:` +
			`${String(d.getMinutes()).padStart(2, "0")}`;
	}

	return hasil;
}

// convert status ke badge
function convert_status(status = "") {
	let sts = "";

	if (status === "draft") {
		sts = `<span class="badge bg-warning">${status}</span>`;
	} else if (status === "proses") {
		sts = `<span class="badge bg-primary">${status}</span>`;
	} else if (status === "selesai") {
		sts = `<span class="badge bg-success">${status}</span>`;
	} else {
		sts = `<span class="badge bg-secondary">${status}</span>`;
	}

	return sts;
}
