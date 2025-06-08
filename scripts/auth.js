// Fungsi untuk handle submit form register
async function handleRegister(event) {
  event.preventDefault();
  const form = event.target;

  const data = {
    username: form.username.value.trim(),
    email: form.email.value.trim(),
    password: form.password.value.trim()
  };

  // Validasi sederhana
  if (!data.username || !data.email || !data.password) {
    alert('Mohon isi semua field.');
    return;
  }

  try {
    const response = await fetch('api/register.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    const result = await response.json();

    if (response.ok) {
      alert('Registrasi berhasil! Silakan login.');
      window.location.href = 'login.php';
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    alert('Terjadi kesalahan: ' + error.message);
  }
}

// Fungsi untuk handle submit form login
async function handleLogin(event) {
  event.preventDefault();
  const form = event.target;

  const data = {
    username: form.username.value.trim(),
    password: form.password.value.trim()
  };

  if (!data.username || !data.password) {
    alert('Mohon isi username dan password.');
    return;
  }

  try {
    const response = await fetch('api/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    const result = await response.json();

    if (response.ok) {
      alert('Login berhasil!');
      // Simpan token/session jika ada (sesuai backend)
      // Contoh redirect ke dashboard
      window.location.href = 'dashboard.php';
    } else {
      alert('Login gagal: ' + result.message);
    }
  } catch (error) {
    alert('Terjadi kesalahan: ' + error.message);
  }
}

// Pasang event listener sesuai halaman
document.addEventListener('DOMContentLoaded', () => {
  const registerForm = document.getElementById('register-form');
  const loginForm = document.getElementById('login-form');

  if (registerForm) {
    registerForm.addEventListener('submit', handleRegister);
  }

  if (loginForm) {
    loginForm.addEventListener('submit', handleLogin);
  }
});
