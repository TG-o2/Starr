// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const subButton = document.getElementById('subButton'); // Assuming you add id="subButton" to the signup button

    if (subButton) {
        subButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent submit until validated

            // Get elements (use [0] since getElementsByName returns a collection)
            const fname = document.getElementsByName('fname')[0];
            const lname = document.getElementsByName('lname')[0];
            const email = document.getElementsByName('email')[0];
            const password = document.getElementsByName('password')[0];
            const dob = document.getElementsByName('DOB')[0]; // Fixed: was 'age'
            const role = document.getElementsByName('role')[0];
            const avatar = document.getElementsByName('avatar')[0];
            const description = document.getElementsByName('description')[0];

            let invalids = [];
            let isValid = true;

            // Helper: Calculate age from DOB (YYYY-MM-DD)
            function calculateAge(dobStr) {
                if (!dobStr) return null;
                const birthDate = new Date(dobStr);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            }

            // NAME VALIDATION (letters only, min 2 chars)
            const NAME_REG = /^[A-Za-z]{2,}$/;
            if (!NAME_REG.test(fname.value.trim())) {
                markInvalid(fname);
                invalids.push(fname);
                isValid = false;
            } else {
                markValid(fname);
            }

            if (!NAME_REG.test(lname.value.trim())) {
                markInvalid(lname);
                invalids.push(lname);
                isValid = false;
            } else {
                markValid(lname);
            }

            // EMAIL VALIDATION
            const EMAIL_REG = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!EMAIL_REG.test(email.value.trim())) {
                markInvalid(email);
                invalids.push(email);
                isValid = false;
            } else {
                markValid(email);
            }

            // PASSWORD (min 6 chars, not empty)
            if (password.value.trim().length < 6) {
                markInvalid(password);
                invalids.push(password);
                isValid = false;
            } else {
                markValid(password);
            }

            // DOB (required, valid date, age rules based on role)
            const age = calculateAge(dob.value);
            if (!dob.value || age === null) {
                markInvalid(dob);
                invalids.push(dob);
                isValid = false;
            } else if (role.value === 'Kid' && age > 13) {
                alert('Kids must be 13 years old or younger.');
                markInvalid(dob);
                invalids.push(dob);
                isValid = false;
            } else if ((role.value === 'Teacher' || role.value === 'Parent') && age < 18) {
                alert('Teachers and Parents must be 18 years old or older.');
                markInvalid(dob);
                invalids.push(dob);
                isValid = false;
            } else {
                markValid(dob);
            }

            // ROLE (required)
            if (!role.value) {
                markInvalid(role);
                invalids.push(role);
                isValid = false;
            } else {
                markValid(role);
            }

            // AVATAR (optional, but if provided, check it's an image)
            if (avatar.files.length > 0 && !avatar.files[0].type.startsWith('image/')) {
                alert('Avatar must be an image file.');
                markInvalid(avatar);
                invalids.push(avatar);
                isValid = false;
            } else {
                markValid(avatar);
            }

            // DESCRIPTION (optional, no validation needed)
            markValid(description);

            // Shake invalid fields
            invalids.forEach(el => {
                el.classList.add('shake');
                setTimeout(() => el.classList.remove('shake'), 300);
            });

            // If all valid, submit the form
            if (isValid) {
                form.submit();
            }
        });
    }

    // VALID / INVALID border functions (add these CSS classes in your style.css if needed: .input-valid { border: 2px solid green; }, .input-invalid { border: 2px solid red; }, .shake { animation: shake 0.3s; })
    function markValid(el) {
        el.classList.remove('input-invalid');
        el.classList.add('input-valid');
    }

    function markInvalid(el) {
        el.classList.remove('input-valid');
        el.classList.add('input-invalid');
    }
});