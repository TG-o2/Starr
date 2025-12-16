<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($question) ? 'Edit Question' : 'Add Question' ?> - Admin</title>
    <link href="../front/kider-1.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="../front/kider-1.0.0/css/style.css" rel="stylesheet">
    <style>
        .timer-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .timer-input {
            padding-right: 45px !important;
            border-radius: 8px !important;
            border: 2px solid #e3e6f0 !important;
            transition: all 0.3s ease !important;
        }
        
        .timer-input:focus {
            border-color: #4e73df !important;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25) !important;
        }
        
        .timer-suffix {
            position: absolute;
            right: 12px;
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 500;
            pointer-events: none;
        }
        
        .timer-preset-buttons {
            display: flex;
            gap: 8px;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        
        .timer-preset-btn {
            padding: 4px 12px;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
            border-radius: 20px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .timer-preset-btn:hover {
            background: #4e73df;
            color: white;
            border-color: #4e73df;
        }
        
        .question-card {
            border: 2px solid #e3e6f0 !important;
            border-radius: 12px !important;
            transition: all 0.3s ease !important;
        }
        
        .question-card:hover {
            border-color: #4e73df !important;
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.15) !important;
        }
        
        .question-card.incomplete {
            border-color: #f6c23e !important;
            background-color: #fffbeb !important;
        }
        
        .question-card.incomplete .validation-alert {
            display: block !important;
        }
        
        .validation-alert {
            display: none;
            background: linear-gradient(135deg, #fff3cd, #fef5e7);
            border: 1px solid #f6c23e;
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 12px;
            font-size: 0.875rem;
            color: #856404;
            animation: slideDown 0.3s ease;
        }
        
        .validation-alert .alert-title {
            font-weight: 600;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .validation-alert .alert-icon {
            color: #f6c23e;
        }
        
        .validation-alert .alert-list {
            margin: 0;
            padding-left: 20px;
        }
        
        .validation-alert .alert-list li {
            margin-bottom: 2px;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="bg-white rounded shadow p-4 p-md-5">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="mb-0"><?= isset($question) ? 'Edit Question' : 'Add New Question' ?></h2>
                            <div class="d-flex gap-2">
                                <a href="lessonList_direct.php" class="btn btn-outline-secondary btn-sm">Lessons</a>
                            </div>
                        </div>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger mb-4"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="post" id="questionForm" class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Lesson *</label>
                        <select name="lessonId" class="form-select" required onchange="updateExistingQuestions(this.value)">
                            <option value="">-- Select Lesson --</option>
                            <?php foreach($lessons as $l): ?>
                                <option value="<?= $l['lessonId'] ?>" <?= ((int)($l['lessonId']) === (int)($_POST['lessonId'] ?? $_GET['lessonId'] ?? ($question['lessonId'] ?? 0) ?? 0)) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($l['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if (!isset($question)): ?>
                        <div class="col-12">
                            <label class="form-label">Quiz Timer (minutes)</label>
                            <input type="number" class="form-control" name="quiz_time_limit" min="1" max="180" value="<?= $_POST['quiz_time_limit'] ?? 30 ?>" placeholder="e.g., 30 for 30 minutes">
                            <small class="text-muted">Set the time limit for this quiz. If not set, defaults to 30 minutes.</small>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($questions) && !isset($question)): ?>
                        <div class="col-12">
                            <hr class="my-4">
                            <h5 class="mb-3">Existing Questions</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width:70px">ID</th>
                                            <th>Question</th>
                                            <th style="width:180px">Lesson</th>
                                            <th style="width:70px">Points</th>
                                            <th style="width:180px">Correct Answer(s)</th>
                                            <th style="width:120px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($questions as $q): ?>
                                            <tr>
                                                <td><?= htmlspecialchars((string)$q['questionId']) ?></td>
                                                <td><?= htmlspecialchars(substr($q['questionText'], 0, 70)) ?><?= (strlen($q['questionText']) > 70) ? '...' : '' ?></td>
                                                <td><?= htmlspecialchars($q['lessonTitle'] ?? 'Unknown') ?></td>
                                                <td><?= htmlspecialchars((string)($q['points'] ?? 1)) ?></td>
                                                <td><?= htmlspecialchars(implode(', ', (array)($q['correctOptions'] ?? []))) ?></td>
                                                <td>
                                                    <a href="questionForm_direct.php?questionId=<?= htmlspecialchars((string)$q['questionId']) ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                                                    <a href="questionForm_direct.php?delete=1&questionId=<?= htmlspecialchars((string)$q['questionId']) ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this question?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr class="my-4">
                        </div>
                    <?php endif; ?>

                    <?php if (isset($question)): ?>
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="fw-bold">Edit Question</div>
                                    <div class="text-muted small">Points-based scoring</div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Question</label>
                                        <textarea class="form-control" name="questionText" required><?= htmlspecialchars($question['questionText'] ?? '') ?></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Points</label>
                                        <input type="number" class="form-control" name="points" min="1" value="<?= htmlspecialchars((string)($question['points'] ?? 1)) ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Timer (sec)</label>
                                        <div class="timer-input-group">
                                            <input type="number" class="form-control timer-input" name="time_limit" min="10" max="300" value="<?= htmlspecialchars((string)($question['timeLimit'] ?? 60)) ?>" placeholder="60">
                                            <span class="timer-suffix">sec</span>
                                        </div>
                                        <div class="timer-preset-buttons">
                                            <button type="button" class="timer-preset-btn" onclick="setTimer(this, 30)">30s</button>
                                            <button type="button" class="timer-preset-btn" onclick="setTimer(this, 60)">1m</button>
                                            <button type="button" class="timer-preset-btn" onclick="setTimer(this, 120)">2m</button>
                                            <button type="button" class="timer-preset-btn" onclick="setTimer(this, 180)">3m</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Options (choose the correct ones)</label>
                                    <div id="editOptions" class="d-grid gap-2">
                                        <?php
                                            $opts = $question['options'] ?? [];
                                            if (!is_array($opts) || count($opts) < 2) {
                                                $opts = array_values(array_filter([
                                                    $question['option1'] ?? null,
                                                    $question['option2'] ?? null,
                                                    $question['option3'] ?? null,
                                                    $question['option4'] ?? null,
                                                ]));
                                            }
                                            $correctIndices = $question['correctIndices'] ?? [];
                                            if (!is_array($correctIndices)) {
                                                $correctIndices = [];
                                            }
                                        ?>

                                        <?php foreach ($opts as $i => $optText): ?>
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <input class="form-check-input mt-0" type="checkbox" name="correct[]" value="<?= (int)$i ?>" <?= in_array((int)$i, array_map('intval', $correctIndices), true) ? 'checked' : '' ?>>
                                                </div>
                                                <input type="text" class="form-control" name="options[<?= (int)$i ?>]" value="<?= htmlspecialchars((string)$optText) ?>" required>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addEditOption()">Add option</button>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-12">
                            <div id="questionsWrapper">
                                <div class="border rounded p-3 question-card" data-question-index="0">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="fw-bold">Question 1</div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <label class="form-label mb-0 small text-muted">Points</label>
                                            <input type="number" class="form-control form-control-sm" style="max-width: 90px" name="questions[0][points]" min="1" value="1" required>
                                            <label class="form-label mb-0 small text-muted ms-2">Timer</label>
                                            <div class="timer-input-group" style="max-width: 90px">
                                                <input type="number" class="form-control form-control-sm timer-input" name="questions[0][time_limit]" min="10" max="300" value="60" placeholder="60">
                                                <span class="timer-suffix" style="right: 8px; font-size: 0.75rem;">s</span>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestionBlock(this)" title="Remove question">
                                                X
                                            </button>
                                        </div>
                                    </div>

                                    <label class="form-label">Question</label>
                                    <textarea class="form-control mb-3 question-textarea" name="questions[0][questionText]" required oninput="validateQuestionBlock(this)"></textarea>

                                    <label class="form-label">Options (choose the correct ones)</label>
                                    <div class="optionsWrapper d-grid gap-2" data-next-option="2">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" type="checkbox" name="questions[0][correct][]" value="0" checked>
                                            </div>
                                            <input type="text" class="form-control question-option" name="questions[0][options][0]" placeholder="Option 1" required oninput="validateQuestionBlock(this)">
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" type="checkbox" name="questions[0][correct][]" value="1">
                                            </div>
                                            <input type="text" class="form-control question-option" name="questions[0][options][1]" placeholder="Option 2" required oninput="validateQuestionBlock(this)">
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addOption(0)">Add option</button>
                                    
                                    <div class="validation-alert">
                                        <div class="alert-title">
                                            <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                                            Please complete your question:
                                        </div>
                                        <ul class="alert-list">
                                            <li>Question text is required</li>
                                            <li>At least 2 options are needed</li>
                                            <li>Select at least one correct answer</li>
                                            <li>Set points and timer values</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!isset($question)): ?>
                        <div class="col-12">
                            <button type="button" class="btn btn-outline-primary" onclick="addQuestion()">Add another question</button>
                        </div>
                    <?php endif; ?>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save all questions</button>
                        <a href="questionList_direct.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
let questionIndex = 1;

function addOption(qIndex) {
    const card = document.querySelector(`.question-card[data-question-index="${qIndex}"]`);
    if (!card) return;
    const wrapper = card.querySelector('.optionsWrapper');
    if (!wrapper) return;

    const next = parseInt(wrapper.getAttribute('data-next-option') || '0', 10);
    const optionIndex = isNaN(next) ? 0 : next;
    wrapper.setAttribute('data-next-option', String(optionIndex + 1));

    const row = document.createElement('div');
    row.className = 'input-group';
    row.innerHTML = `
        <div class="input-group-text">
            <input class="form-check-input mt-0" type="checkbox" name="questions[${qIndex}][correct][]" value="${optionIndex}">
        </div>
        <input type="text" class="form-control" name="questions[${qIndex}][options][${optionIndex}]" placeholder="Option ${optionIndex + 1}" required>
        <button class="btn btn-outline-danger" type="button" onclick="removeOption(this)">Remove</button>
    `;
    wrapper.appendChild(row);
}

function removeOption(btn) {
    const row = btn.closest('.input-group');
    if (!row) return;
    const wrapper = row.parentElement;
    if (!wrapper) return;
    const rows = wrapper.querySelectorAll('.input-group');
    if (rows.length <= 2) {
        return;
    }
    row.remove();
}

function addEditOption() {
    const wrapper = document.getElementById('editOptions');
    if (!wrapper) return;
    const rows = wrapper.querySelectorAll('.input-group');
    const optionIndex = rows.length;
    const row = document.createElement('div');
    row.className = 'input-group';
    row.innerHTML = `
        <div class="input-group-text">
            <input class="form-check-input mt-0" type="checkbox" name="correct[]" value="${optionIndex}">
        </div>
        <input type="text" class="form-control" name="options[${optionIndex}]" placeholder="Option ${optionIndex + 1}" required>
        <button class="btn btn-outline-danger" type="button" onclick="removeOption(this)">Remove</button>
    `;
    wrapper.appendChild(row);
}

function addQuestion() {
         const wrapper = document.getElementById('questionsWrapper');

         const block = document.createElement('div');
         block.className = 'border rounded p-3 mt-3 question-card';
         block.setAttribute('data-question-index', String(questionIndex));
         block.innerHTML = `
             <div class="d-flex justify-content-between align-items-center mb-3">
                 <div class="fw-bold">Question ${questionIndex + 1}</div>
                 <div class="d-flex gap-2 align-items-center">
                     <label class="form-label mb-0 small text-muted">Points</label>
                     <input type="number" class="form-control form-control-sm" style="max-width: 90px" name="questions[${questionIndex}][points]" min="1" value="1" required>
                     <label class="form-label mb-0 small text-muted ms-2">Timer</label>
                     <div class="timer-input-group" style="max-width: 90px">
                         <input type="number" class="form-control form-control-sm timer-input" name="questions[${questionIndex}][time_limit]" min="10" max="300" value="60" placeholder="60">
                         <span class="timer-suffix" style="right: 8px; font-size: 0.75rem;">s</span>
                     </div>
                     <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestionBlock(this)" title="Remove question">
                         X
                     </button>
                 </div>
             </div>

             <label class="form-label">Question</label>
             <textarea class="form-control mb-3 question-textarea" name="questions[${questionIndex}][questionText]" required oninput="validateQuestionBlock(this)"></textarea>

             <label class="form-label">Options (choose the correct ones)</label>
             <div class="optionsWrapper d-grid gap-2" data-next-option="2">
                 <div class="input-group">
                     <div class="input-group-text">
                         <input class="form-check-input mt-0" type="checkbox" name="questions[${questionIndex}][correct][]" value="0" checked>
                     </div>
                     <input type="text" class="form-control question-option" name="questions[${questionIndex}][options][0]" placeholder="Option 1" required oninput="validateQuestionBlock(this)">
                 </div>
                 <div class="input-group">
                     <div class="input-group-text">
                         <input class="form-check-input mt-0" type="checkbox" name="questions[${questionIndex}][correct][]" value="1">
                     </div>
                     <input type="text" class="form-control question-option" name="questions[${questionIndex}][options][1]" placeholder="Option 2" required oninput="validateQuestionBlock(this)">
                 </div>
             </div>

             <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addOption(${questionIndex})">Add option</button>
             
             <div class="validation-alert">
                 <div class="alert-title">
                     <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                     Please complete your question:
                 </div>
                 <ul class="alert-list">
                     <li>Question text is required</li>
                     <li>At least 2 options are needed</li>
                     <li>Select at least one correct answer</li>
                     <li>Set points and timer values</li>
                 </ul>
             </div>`;
         wrapper.appendChild(block);
         questionIndex++;
     }

     function removeQuestionBlock(btn) {
         const wrapper = document.getElementById('questionsWrapper');
         const blocks = wrapper.querySelectorAll('.question-card');
         if (blocks.length <= 1) {
             return;
         }
         const block = btn.closest('.question-card');
         if (block) {
             block.remove();
             updateQuestionNumbers();
         }
     }

     function updateQuestionNumbers() {
         const wrapper = document.getElementById('questionsWrapper');
         const blocks = wrapper.querySelectorAll('.question-card');
         blocks.forEach((block, idx) => {
             const titleEl = block.querySelector('.fw-bold');
             if (titleEl) {
                 titleEl.textContent = 'Question ' + (idx + 1);
             }
             // Update data-question-index for future add/remove operations
             block.setAttribute('data-question-index', String(idx));
         });
         // Reset the global questionIndex to match the actual number of questions
         questionIndex = blocks.length;
     }

     function setTimer(button, seconds) {
         const timerInput = button.closest('.col-md-3').querySelector('.timer-input');
         if (timerInput) {
             timerInput.value = seconds;
         }
     }

     function validateQuestionBlock(element) {
         const questionCard = element.closest('.question-card');
         if (!questionCard) return;

         const questionText = questionCard.querySelector('.question-textarea').value.trim();
         const options = questionCard.querySelectorAll('.question-option');
         const correctCheckboxes = questionCard.querySelectorAll('input[type="checkbox"]:checked');
         
         let hasValidOptions = 0;
         options.forEach(option => {
             if (option.value.trim() !== '') {
                 hasValidOptions++;
             }
         });

         const isIncomplete = questionText !== '' && (
             hasValidOptions < 2 || 
             correctCheckboxes.length === 0
         );

         if (isIncomplete) {
             questionCard.classList.add('incomplete');
         } else {
             questionCard.classList.remove('incomplete');
         }
     }

     function updateExistingQuestions(lessonId) {
         if (!lessonId) {
             // Hide the existing questions table if no lesson is selected
             const existingQuestionsSection = document.querySelector('.table-responsive').closest('.col-12');
             if (existingQuestionsSection) {
                 existingQuestionsSection.style.display = 'none';
             }
             return;
         }

         // Make AJAX call to get questions for the selected lesson
         fetch(`/lessons_project/api/questions.php?lessonId=${lessonId}`)
             .then(response => response.json())
             .then(data => {
                 if (data.success && data.questions) {
                     renderExistingQuestions(data.questions);
                 }
             })
             .catch(error => {
                 console.error('Error fetching questions:', error);
             });
     }

     function renderExistingQuestions(questions) {
         const tbody = document.querySelector('.table tbody');
         if (!tbody) return;

         // Clear existing rows
         tbody.innerHTML = '';

         if (questions.length === 0) {
             tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No questions found for this lesson.</td></tr>';
             return;
         }

         questions.forEach(question => {
             const row = document.createElement('tr');
             row.innerHTML = `
                 <td>${question.questionId}</td>
                 <td>${question.questionText}</td>
                 <td>${question.lessonTitle}</td>
                 <td>${question.points}</td>
                 <td>${question.goodAnswer}</td>
                 <td>
                     <a href="/lessons_project/views/back/questionEdit_direct.php?id=${question.questionId}" class="btn btn-sm btn-outline-primary">Edit</a>
                     <a href="/lessons_project/views/back/questionDelete_direct.php?id=${question.questionId}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</a>
                 </td>
             `;
             tbody.appendChild(row);
         });

         // Make sure the section is visible
         const existingQuestionsSection = document.querySelector('.table-responsive').closest('.col-12');
         if (existingQuestionsSection) {
             existingQuestionsSection.style.display = 'block';
         }
     }
</script>
</body>
</html>
