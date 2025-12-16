<?php
// Back-office: questionMultiForm.php - Add multiple questions at once
require_once __DIR__ . '/../../init.php';

$controller = new QuestionController();
$lessonModel = new LessonModel();
$lessons = $lessonModel->getAll();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lessonId = (int)($_POST['lessonId'] ?? 0);
    $questionsData = $_POST['questions'] ?? [];
    
    $successCount = 0;
    $errors = [];
    
    if ($lessonId <= 0) {
        $errors[] = "Please select a lesson.";
    }
    
    if (empty($questionsData)) {
        $errors[] = "No questions provided.";
    }
    
    if (empty($errors)) {
        foreach ($questionsData as $index => $questionData) {
            // Skip empty questions
            if (empty(trim($questionData['questionText'] ?? ''))) {
                continue;
            }
            
            $data = [
                'lessonId' => $lessonId,
                'questionText' => trim($questionData['questionText']),
                'option1' => trim($questionData['option1'] ?? ''),
                'option2' => trim($questionData['option2'] ?? ''),
                'option3' => trim($questionData['option3'] ?? ''),
                'goodAnswer' => trim($questionData['goodAnswer'] ?? '')
            ];
            
            // Validate
            if ($data['questionText'] === '' || $data['option1'] === '' || 
                $data['option2'] === '' || $data['goodAnswer'] === '') {
                $errors[] = "Question " . ($index + 1) . " missing required fields.";
                continue;
            }
            
            // Add to database
            try {
                $controller->model->create($data);
                $successCount++;
            } catch (Exception $e) {
                $errors[] = "Failed to add question " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        
        if ($successCount > 0) {
            $successMessage = "Successfully added $successCount question(s)!";
            if (!empty($errors)) {
                $successMessage .= " Some errors: " . implode(", ", $errors);
            }
        }
    }
    
    if (!empty($errors) && $successCount == 0) {
        $errorMessage = implode("<br>", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Multiple Questions - Admin</title>
    <link rel="stylesheet" href="../../public/css/back.css">
    <style>
        .multi-question-form {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .question-block {
            background: #f8f9ff;
            border: 2px solid #e7f0ff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            position: relative;
        }
        
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4e73df;
        }
        
        .question-number {
            font-weight: bold;
            color: #4e73df;
            font-size: 1.3rem;
        }
        
        .remove-question {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .add-question-btn {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px 0;
            width: 100%;
            justify-content: center;
        }
        
        .add-question-btn:hover {
            background: #218838;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .form-field {
            margin-bottom: 15px;
        }
        
        .form-field label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .form-field input,
        .form-field textarea,
        .form-field select {
            width: 100%;
            padding: 10px;
            border: 2px solid #eaeaea;
            border-radius: 6px;
            font-size: 1rem;
        }
        
        .form-field textarea {
            min-height: 80px;
            resize: vertical;
        }
        
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 1.1rem;
        }
        
        .btn-primary {
            background: #4e73df;
            color: white;
        }
        
        .btn-primary:hover {
            background: #3a56c4;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <h1>🎓 Admin Panel - Add Multiple Questions</h1>
            <ul>
                <li><a href="lessonList_direct.php" class="nav-link">Lessons</a></li>
                <li><a href="questionList_direct.php" class="nav-link">Questions</a></li>
                <li><a href="questionForm_direct.php" class="nav-link">Add Single</a></li>
                <li><a href="questionMultiForm_direct.php" class="nav-link active">Add Multiple</a></li>
            </ul>
        </nav>

        <main class="content">
            <div class="multi-question-form">
                <h2>📝 Add Multiple Questions at Once</h2>
                
                <?php if(isset($errorMessage)): ?>
                    <div class="alert alert-error"><?= $errorMessage ?></div>
                <?php endif; ?>
                
                <?php if(isset($successMessage)): ?>
                    <div class="alert alert-success"><?= $successMessage ?></div>
                <?php endif; ?>

                <form method="post" id="multiQuestionForm">
                    <div class="form-field">
                        <label for="lessonId">Select Lesson *</label>
                        <select id="lessonId" name="lessonId" required>
                            <option value="">-- Choose a Lesson --</option>
                            <?php foreach($lessons as $lesson): ?>
                                <option value="<?= $lesson['lessonId'] ?>" 
                                    <?= isset($_POST['lessonId']) && $_POST['lessonId'] == $lesson['lessonId'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lesson['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="questionsContainer">
                        <!-- Questions will be added here dynamically -->
                    </div>

                    <button type="button" id="addQuestionBtn" class="add-question-btn">
                        <i class="fas fa-plus-circle"></i> Add Another Question
                    </button>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save All Questions
                        </button>
                        <a href="questionList_direct.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        let questionCounter = 0;
        
        // Template for a question block
        function createQuestionBlock(number) {
            return `
                <div class="question-block" id="questionBlock${number}">
                    <div class="question-header">
                        <div class="question-number">Question ${number}</div>
                        ${number > 1 ? `
                            <button type="button" class="remove-question" onclick="removeQuestion(${number})">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        ` : ''}
                    </div>
                    
                    <div class="form-field">
                        <label for="questionText${number}">Question Text *</label>
                        <textarea id="questionText${number}" 
                                  name="questions[${number}][questionText]" 
                                  rows="2" 
                                  required 
                                  placeholder="Enter the question text..."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="option1_${number}">Option 1 *</label>
                            <input type="text" 
                                   id="option1_${number}" 
                                   name="questions[${number}][option1]" 
                                   required 
                                   placeholder="First option">
                        </div>
                        <div class="form-field">
                            <label for="option2_${number}">Option 2 *</label>
                            <input type="text" 
                                   id="option2_${number}" 
                                   name="questions[${number}][option2]" 
                                   required 
                                   placeholder="Second option">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="option3_${number}">Option 3 (optional)</label>
                            <input type="text" 
                                   id="option3_${number}" 
                                   name="questions[${number}][option3]" 
                                   placeholder="Third option">
                        </div>
                        <div class="form-field">
                            <label for="goodAnswer${number}">Correct Answer *</label>
                            <input type="text" 
                                   id="goodAnswer${number}" 
                                   name="questions[${number}][goodAnswer]" 
                                   required 
                                   placeholder="Must match one of the options exactly">
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Add a new question block
        document.getElementById('addQuestionBtn').addEventListener('click', function() {
            questionCounter++;
            const container = document.getElementById('questionsContainer');
            container.insertAdjacentHTML('beforeend', createQuestionBlock(questionCounter));
        });
        
        // Remove a question block
        function removeQuestion(blockNumber) {
            const block = document.getElementById(`questionBlock${blockNumber}`);
            if (block) {
                block.remove();
                renumberQuestions();
            }
        }
        
        // Renumber questions after removal
        function renumberQuestions() {
            const blocks = document.querySelectorAll('.question-block');
            questionCounter = 0;
            
            blocks.forEach((block, index) => {
                questionCounter++;
                const newNumber = questionCounter;
                
                // Update block ID
                block.id = `questionBlock${newNumber}`;
                
                // Update question number display
                block.querySelector('.question-number').textContent = `Question ${newNumber}`;
                
                // Update all input names
                const inputs = block.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    const name = input.name;
                    const newName = name.replace(/\[\d+\]/, `[${newNumber}]`);
                    input.name = newName;
                });
                
                // Update remove button if not first
                const removeBtn = block.querySelector('.remove-question');
                if (removeBtn) {
                    removeBtn.setAttribute('onclick', `removeQuestion(${newNumber})`);
                }
            });
        }
        
        // Initialize with first question
        document.addEventListener('DOMContentLoaded', function() {
            questionCounter = 1;
            document.getElementById('questionsContainer').innerHTML = createQuestionBlock(1);
        });
    </script>
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</body>
</html>