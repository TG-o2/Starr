document.getElementById("startQuiz").addEventListener("click", function() {
  document.getElementById("quizSection").style.display = "block";
});

document.getElementById("quizForm").addEventListener("submit", function(event) {
  event.preventDefault();

  const answers = {
    q1: "egg",
    q2: "pupa",
    q3: "nectar"
  };

  let score = 0;
  Object.keys(answers).forEach(q => {
    const userAnswer = document.querySelector(`input[name="${q}"]:checked`);
    if (userAnswer && userAnswer.value === answers[q]) score++;
  });

  document.getElementById("quizResult").textContent =
    `You got ${score} out of 3 correct!`;
});