
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btnLogout')

    btn.addEventListener('click', logout);

});



async function logout() {

    await fetch('../auth/logout.php');
    window.location.href = 'iniciarSesion.php';
}