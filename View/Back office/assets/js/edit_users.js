
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const email = document.querySelector('input[name="email"]');
        const fname = document.querySelector('input[name="fname"]');
        const lname = document.querySelector('input[name="lname"]');
        const dob   = document.querySelector('input[name="DOB"]');
        const role  = document.querySelector('select[name="role"]');

        let invalids = [];

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) invalids.push(email);
        if (!/^[A-Za-zÀ-ÖØ-öø-ÿ\s-]{2,}$/.test(fname.value.trim())) invalids.push(fname);
        if (!/^[A-Za-zÀ-ÖØ-öø-ÿ\s-]{2,}$/.test(lname.value.trim())) invalids.push(lname);
        if (!dob.value) invalids.push(dob);
        if (!role.value) invalids.push(role);

        if (invalids.length > 0) {
            e.preventDefault();
            invalids.forEach(el => {
                markInvalid(el);                     
                el.classList.add('shake');
                setTimeout(() => el.classList.remove('shake'), 400);
            });
        }
    });

   
    const markInvalid = (el) => el.classList.add('is-invalid');
});