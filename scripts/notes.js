document.getElementById('form-catatan').addEventListener('submit', function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch('api/catatan.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Catatan berhasil disimpan!');
      loadCatatanList();
      this.reset();
    } else {
      alert('Error: ' + data.message);
    }
  })
  .catch(err => alert('Request gagal: ' + err));
});

function loadCatatanList() {
  fetch('api/catatan.php?action=list')
  .then(res => res.json())
  .then(data => {
    const list = document.getElementById('catatan-list');
    if (!data.length) {
      list.innerHTML = 'Belum ada catatan.';
      return;
    }
    let html = '<ul>';
    data.forEach(c => {
      html += `<li><b>${escapeHtml(c.judul)}</b> - <i>${escapeHtml(c.deskripsi)}</i><br>${escapeHtml(c.isi_catatan)}</li>`;
    });
    html += '</ul>';
    list.innerHTML = html;
  });
}

function escapeHtml(text) {
  if (!text) return '';
  return text.replace(/[&<>"']/g, function(m) {
    return {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;'}[m];
  });
}

loadCatatanList();
