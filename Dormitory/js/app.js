document.querySelectorAll('[data-toggle-password]').forEach(function (toggle) {
    toggle.addEventListener('click', function () {
        var input = document.getElementById(toggle.dataset.togglePassword);
        var showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        toggle.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
        toggle.textContent = showing ? 'o' : 'O';
    });
});

var menuToggle = document.querySelector('.menu-toggle');
var sideMenu = document.querySelector('.side-menu');

if (menuToggle && sideMenu) {
    menuToggle.addEventListener('click', function () {
        var isOpen = document.body.classList.toggle('menu-open');
        menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        menuToggle.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
    });
}
