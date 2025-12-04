// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
    const subButton = document.getElementById('subButton');

    if (subButton) {
        subButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent submit until validated

            // Get elements
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const form = document.getElementById('myform');

            let invalids = [];
            let isValid = true;

            // EMAIL VALIDATION
            const EMAIL_REG = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!EMAIL_REG.test(email.value.trim())) {
                markInvalid(email);
                invalids.push(email);
                isValid = false;
            } else {
                markValid(email);
            }

            // PASSWORD (not empty)
            if (password.value.trim() === '') {
                markInvalid(password);
                invalids.push(password);
                isValid = false;
            } else {
                markValid(password);
            }

            // Shake invalid fields
            invalids.forEach(el => {
                el.classList.add('shake');
                setTimeout(() => el.classList.remove('shake'), 300);
            });

            // If valid, submit
            if (isValid) {
                form.submit();
            }
        });
    }

    // VALID / INVALID functions (same as above, add CSS classes)
    function markValid(el) {
        el.classList.remove('input-invalid');
        el.classList.add('input-valid');
    }

    function markInvalid(el) {
        el.classList.remove('input-valid');
        el.classList.add('input-invalid');
    }
});