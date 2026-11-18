// const passwordInput = document.getElementById('password');
// const toggleIcon  = document.getElementById('togglePassword');

// toggleIcon.addEventListener('click', () => {
//     const isHidden = passwordInput.type === 'password';
//     passwordInput.type = isHidden ? 'text' : 'password';
//     toggleIcon.setAttribute('fill', isHidden ? 'black' : 'red');
// });
const passwordInput = document.getElementById('password');
  const toggleIcon = document.getElementById('togglePassword');

  toggleIcon.addEventListener('click', () => {
    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';

    // Opsional: ubah warna icon saat password terlihat
    toggleIcon.setAttribute('fill', isHidden ? 'black' : 'gray');
  });