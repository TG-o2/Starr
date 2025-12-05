// Front-End JavaScript for Lessons and Quiz

/**
 * Quiz Form Handling and Progress Tracking
 */
document.addEventListener('DOMContentLoaded', () => {
    const quizForm = document.getElementById('quizForm');
    
    if (quizForm) {
        // Update progress bar as user answers questions
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        const totalQuestions = document.querySelectorAll('.question-block').length;
        
        radioButtons.forEach(button => {
            button.addEventListener('change', updateProgress);
        });

        quizForm.addEventListener('submit', (e) => {
            const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
            if (answeredQuestions < totalQuestions) {
                e.preventDefault();
                alert(`Please answer all ${totalQuestions} questions before submitting.`);
                return false;
            }
        });
    }
});

/**
 * Update progress bar based on answered questions
 */
function updateProgress() {
    const quizForm = document.getElementById('quizForm');
    if (!quizForm) return;

    const totalQuestions = document.querySelectorAll('.question-block').length;
    const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
    const progressPercentage = (answeredQuestions / totalQuestions) * 100;
    
    const progressFill = document.querySelector('.progress-fill');
    if (progressFill) {
        progressFill.style.width = progressPercentage + '%';
    }
}

/**
 * Smooth scroll to question
 */
function scrollToQuestion(questionNumber) {
    const questionBlock = document.querySelector(`[data-question="${questionNumber}"]`);
    if (questionBlock) {
        questionBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

/**
 * Lesson card interactivity
 */
document.addEventListener('DOMContentLoaded', () => {
    const lessonCards = document.querySelectorAll('.lesson-card');
    
    lessonCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
});

/**
 * Quiz Results Animation
 */
document.addEventListener('DOMContentLoaded', () => {
    const resultItems = document.querySelectorAll('.result-item');
    resultItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.animation = `fadeInUp 0.5s ease forwards ${index * 0.1}s`;
    });
});

/**
 * Add animation to page
 */
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
`;
document.head.appendChild(style);

/**
 * Question timer (optional feature)
 */
class QuizTimer {
    constructor(totalMinutes = null) {
        this.totalSeconds = totalMinutes ? totalMinutes * 60 : null;
        this.timeRemaining = this.totalSeconds;
        this.timerElement = null;
        this.interval = null;
    }

    start(elementId) {
        this.timerElement = document.getElementById(elementId);
        if (!this.timerElement || !this.totalSeconds) return;

        this.interval = setInterval(() => {
            this.timeRemaining--;
            this.updateDisplay();

            if (this.timeRemaining <= 0) {
                this.stop();
                this.onTimeUp();
            }
        }, 1000);
    }

    stop() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    }

    updateDisplay() {
        if (!this.timerElement) return;
        const minutes = Math.floor(this.timeRemaining / 60);
        const seconds = this.timeRemaining % 60;
        this.timerElement.textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (this.timeRemaining <= 60) {
            this.timerElement.style.color = '#dc3545';
        }
    }

    onTimeUp() {
        alert('Time is up! Your quiz will be submitted.');
        const quizForm = document.getElementById('quizForm');
        if (quizForm) quizForm.submit();
    }
}

/**
 * Initialize timer if time limit is set
 */
document.addEventListener('DOMContentLoaded', () => {
    const quizForm = document.getElementById('quizForm');
    if (quizForm && quizForm.dataset.timeLimit) {
        const timer = new QuizTimer(parseInt(quizForm.dataset.timeLimit));
        timer.start('quiz-timer');
    }
});

/**
 * Smooth scroll behavior
 */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});

/**
 * Export quiz results to clipboard
 */
function copyQuizResults() {
    const resultsText = document.body.innerText;
    navigator.clipboard.writeText(resultsText).then(() => {
        alert('Results copied to clipboard!');
    }).catch(() => {
        alert('Failed to copy results.');
    });
}
