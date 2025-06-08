document.getElementById('form-todo').addEventListener('submit', function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch('api/todo.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Kegiatan berhasil disimpan!');
      loadTodoList();
      this.reset();
    } else {
      alert('Error: ' + data.message);
    }
  })
  .catch(err => alert('Request gagal: ' + err));
});

function loadTodoList() {
  fetch('api/todo.php?action=list')
  .then(res => res.json())
  .then(data => {
    const list = document.getElementById('todo-list');
    if (!data.length) {
      list.innerHTML = 'Belum ada kegiatan.';
      return;
    }
    let html = '<ul>';
    data.forEach(t => {
      const checked = t.selesai == 1 ? 'checked' : '';
      html += `<li>
        <input type="checkbox" data-id="${t.id}" ${checked}> 
        <b>${escapeHtml(t.nama_kegiatan)}</b> - Deadline: ${t.deadline}
      </li>`;
    });
    html += '</ul>';
    list.innerHTML = html;

    document.querySelectorAll('#todo-list input[type=checkbox]').forEach(chk => {
      chk.addEventListener('change', e => {
        const id = e.target.getAttribute('data-id');
        fetch('api/todo.php?action=toggle&id=' + id)
          .then(res => res.json())
          .then(data => {
            if (!data.success) alert('Gagal mengubah status');
          });
      });
    });
  });
}

function escapeHtml(text) {
  if (!text) return '';
  return text.replace(/[&<>"']/g, function(m) {
    return {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;'}[m];
  });
}

loadTodoList();
