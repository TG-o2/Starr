// Admin Panel JavaScript Functions

/**
 * Confirm delete action
 */
function confirmDelete(message) {
    return confirm(message || "Are you sure you want to delete this item?");
}

/**
 * Form validation
 */
document.addEventListener('DOMContentLoaded', () => {
    const lessonForm = document.getElementById('lessonForm');
    if (lessonForm) {
        lessonForm.addEventListener('submit', (e) => {
            const title = document.getElementById('title').value.trim();
            const ageRange = document.getElementById('ageRange').value.trim();
            const duration = parseInt(document.getElementById('duration').value);
            const description = document.getElementById('description').value.trim();

            if (!title || !ageRange || !duration || duration <= 0 || !description) {
                e.preventDefault();
                alert('Please fill in all required fields correctly.');
                return false;
            }
            return true;
        });
    }

    const questionForm = document.getElementById('questionForm');
    if (questionForm) {
        questionForm.addEventListener('submit', (e) => {
            const lessonId = document.getElementById('lessonId').value;
            const questionText = document.getElementById('questionText').value.trim();
            const option1 = document.getElementById('option1').value.trim();
            const option2 = document.getElementById('option2').value.trim();
            const goodAnswer = document.getElementById('goodAnswer').value.trim();

            if (!lessonId || !questionText || !option1 || !option2 || !goodAnswer) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }

            // Verify correct answer matches an option
            const option3 = document.getElementById('option3').value.trim();
            const validAnswers = [option1, option2];
            if (option3) validAnswers.push(option3);

            if (!validAnswers.includes(goodAnswer)) {
                e.preventDefault();
                alert('The correct answer must match one of the provided options exactly.');
                return false;
            }

            return true;
        });
    }
});

/**
 * Table row highlighting
 */
document.addEventListener('DOMContentLoaded', () => {
    const tableRows = document.querySelectorAll('.data-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', () => {
            row.style.backgroundColor = '#f0f0f0';
        });
        row.addEventListener('mouseleave', () => {
            row.style.backgroundColor = '';
        });
    });
});

/**
 * Smooth scroll behavior
 */
window.scrollTo = (function() {
    const original = window.scrollTo;
    return function() {
        if (arguments[0] === 0) {
            document.documentElement.scrollIntoView({ behavior: 'smooth' });
        } else {
            original.apply(window, arguments);
        }
    };
})();
