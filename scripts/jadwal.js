document.getElementById('form-jadwal').addEventListener('submit', function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch('api/jadwal.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Jadwal berhasil disimpan!');
      loadJadwalList();
      this.reset();
    } else {
      alert('Error: ' + data.message);
    }
  })
  .catch(err => alert('Request gagal: ' + err));
});

function loadJadwalList() {
  fetch('api/jadwal.php?action=list')
  .then(res => res.json())
  .then(data => {
    const list = document.getElementById('jadwal-list');
    if (!data.length) {
      list.innerHTML = 'Belum ada jadwal.';
      return;
    }
    let html = '<table border="1" cellpadding="5" cellspacing="0"><thead><tr><th>Mata Kuliah</th><th>Hari</th><th>Jam Mulai</th><th>Jam Selesai</th></tr></thead><tbody>';
    data.forEach(j => {
      html += `<tr>
        <td>${escapeHtml(j.nama_mata_kuliah)}</td>
        <td>${escapeHtml(j.hari)}</td>
        <td>${j.jam_mulai}</td>
        <td>${j.jam_selesai}</td>
      </tr>`;
    });
    html += '</tbody></table>';
    list.innerHTML = html;
  });
}

function escapeHtml(text) {
  if (!text) return '';
  return text.replace(/[&<>"']/g, function(m) {
    return {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;'}[m];
  });
}

loadJadwalList();
