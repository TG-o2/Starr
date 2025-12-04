
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('subButton');
    if (!btn) return;

    btn.addEventListener('click', function (e) {
        e.preventDefault();

        const fname    = document.querySelector('input[name="fname"]');
        const lname    = document.querySelector('input[name="lname"]');
        const email    = document.querySelector('input[name="email"]');
        const password = document.querySelector('input[name="password"]');
        const file     = document.querySelector('input[name="profile_image"]');

        let invalids = [];

        if (!/^[A-Za-zÀ-ÖØ-öø-ÿ\s-]{2,}$/.test(fname.value.trim())) invalids.push(fname);
        if (!/^[A-Za-zÀ-ÖØ-öø-ÿ\s-]{2,}$/.test(lname.value.trim())) invalids.push(lname);
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) invalids.push(email);
        if (password.value.length < 6) invalids.push(password);
        if (!file.files[0]) invalids.push(file);

        invalids.forEach(el => {
            el.classList.add('is-invalid', 'shake');
            setTimeout(() => el.classList.remove('shake'), 400);
        });

        if (invalids.length === 0) {
            document.querySelector('form').submit();
        }
    });
});