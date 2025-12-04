// assets/js/editUser.js
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('button[type="submit"]');

    if (!form || !submitBtn) return;

    submitBtn.addEventListener('click', function (e) {
        e.preventDefault();

        const fname    = document.querySelector('input[name="fname"]');
        const lname    = document.querySelector('input[name="lname"]');
        const email    = document.querySelector('input[name="email"]');
        const password = document.querySelector('input[name="password"]');
        const avatar   = document.querySelector('input[name="avatar"]');

        let invalids = [];

       
        const nameRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s-]{2,}$/;
        if (!nameRegex.test(fname.value.trim())) { markInvalid(fname); invalids.push(fname); }
        else markValid(fname);

        if (!nameRegex.test(lname.value.trim())) { markInvalid(lname); invalids.push(lname); }
        else markValid(lname);

      
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) { markInvalid(email); invalids.push(email); }
        else markValid(email);

        
        if (password.value !== '' && password.value.length < 6) {
            alert('Password must be at least 6 characters');
            markInvalid(password);
            invalids.push(password);
        } else if (password.value !== '') {
            markValid(password);
        }

        
        if (avatar.files[0] && !avatar.files[0].type.startsWith('image/')) {
            alert('Please upload a valid image');
            markInvalid(avatar);
            invalids.push(avatar);
        }

        
        invalids.forEach(el => {
            el.classList.add('shake');
            setTimeout(() => el.classList.remove('shake'), 400);
        });

        
        if (invalids.length === 0) form.submit();
    });

    function markValid(el) {
        el.classList.remove('input-invalid');
        el.classList.add('input-valid');
    }
    function markInvalid(el) {
        el.classList.remove('input-valid');
        el.classList.add('input-invalid');
    }
});