
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.querySelector('#subButton');
    const form = document.querySelector('form.user');

    if (!btn) return;

    btn.addEventListener('click', function (e) {
        e.preventDefault();

        const email    = document.querySelector('input[name="email"]');
        const password = document.querySelector('input[name="password"]');

        let invalids = [];

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
            markInvalid(email);
            invalids.push(email);
        } else markValid(email);

        if (password.value.trim() === '') {
            markInvalid(password);
            invalids.push(password);
        } else markValid(password);

        invalids.forEach(el => el.classList.add('shake') && setTimeout(() => el.classList.remove('shake'), 400));

        if (invalids.length === 0) form.submit();
    });

    function markValid(el)   { el.classList.remove('input-invalid'); el.classList.add('input-valid'); }
    function markInvalid(el) { el.classList.remove('input-valid');   el.classList.add('input-invalid'); }
});