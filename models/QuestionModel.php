<?php
require_once __DIR__ . '/Database.php';

class QuestionModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll(): array {
        $stmt = $this->conn->query(
            "SELECT 
                q.id AS questionId,
                q.lesson_id AS lessonId,
                q.question_text AS questionText,
                q.question_type AS questionType,
                q.points AS points,
                q.time_limit AS timeLimit,
                q.order_index AS orderIndex,
                l.title AS lessonTitle
            FROM questions q
            LEFT JOIN lessons l ON q.lesson_id = l.lessonId
            ORDER BY q.id DESC"
        );
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        return $this->attachOptions($questions);
    }

    public function getOne(int $id) {
        $stmt = $this->conn->prepare(
            "SELECT 
                q.id AS questionId,
                q.lesson_id AS lessonId,
                q.question_text AS questionText,
                q.question_type AS questionType,
                q.points AS points,
                q.time_limit AS timeLimit,
                q.order_index AS orderIndex,
                ca.option_text AS goodAnswer
            FROM questions q
            LEFT JOIN question_options ca ON ca.question_id = q.id AND ca.is_correct = 1
            WHERE q.id = :id
            LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $question = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if (!$question) {
            return null;
        }
        $withOptions = $this->attachOptions([$question]);
        return $withOptions[0] ?? null;
    }

    public function getByLesson(int $lessonId): array {
        $stmt = $this->conn->prepare(
            "SELECT 
                q.id AS questionId,
                q.lesson_id AS lessonId,
                q.question_text AS questionText,
                q.question_type AS questionType,
                q.points AS points,
                q.time_limit AS timeLimit,
                q.order_index AS orderIndex,
                l.title AS lessonTitle
            FROM questions q
            LEFT JOIN lessons l ON l.lessonId = q.lesson_id
            WHERE q.lesson_id = :lessonId
            ORDER BY q.order_index ASC, q.id ASC"
        );
        $stmt->execute([':lessonId' => $lessonId]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        return $this->attachOptions($questions);
    }

    public function create(array $data): int {
        $lessonId = (int)($data['lessonId'] ?? 0);
        $questionText = trim((string)($data['questionText'] ?? ''));
        $goodAnswer = trim((string)($data['goodAnswer'] ?? ''));
        if ($lessonId <= 0 || $questionText === '') {
            throw new InvalidArgumentException('Invalid question data');
        }

        $orderStmt = $this->conn->prepare('SELECT COALESCE(MAX(order_index), 0) + 1 AS next_order FROM questions WHERE lesson_id = :lessonId');
        $orderStmt->execute([':lessonId' => $lessonId]);
        $nextOrder = (int)($orderStmt->fetch(PDO::FETCH_ASSOC)['next_order'] ?? 1);

        $stmt = $this->conn->prepare(
            "INSERT INTO questions (lesson_id, question_text, question_type, points, time_limit, order_index)
             VALUES (:lesson_id, :question_text, :question_type, :points, :time_limit, :order_index)"
        );
        $stmt->execute([
            ':lesson_id' => $lessonId,
            ':question_text' => $questionText,
            ':question_type' => 'multiple_choice',
            ':points' => (int)($data['points'] ?? 1),
            ':time_limit' => (int)($data['time_limit'] ?? 60),
            ':order_index' => $nextOrder,
        ]);

        $questionId = (int)$this->conn->lastInsertId();
        $options = $data['options'] ?? null;
        if (!is_array($options)) {
            $options = [
                $data['option1'] ?? null,
                $data['option2'] ?? null,
                $data['option3'] ?? null,
                $data['option4'] ?? null,
            ];
        }
        $correctIndices = null;
        if (isset($data['correctIndices']) && is_array($data['correctIndices'])) {
            $correctIndices = array_values(array_map('intval', $data['correctIndices']));
        } elseif (isset($data['correctIndex'])) {
            $correctIndices = [(int)$data['correctIndex']];
        }
        $this->replaceOptions($questionId, $options, $goodAnswer, $correctIndices);

        return $questionId;
    }

    public function update(int $id, array $data): bool {
        $questionText = trim((string)($data['questionText'] ?? ''));
        $goodAnswer = trim((string)($data['goodAnswer'] ?? ''));
        if ($questionText === '') {
            return false;
        }

        $stmt = $this->conn->prepare('UPDATE questions SET question_text = :question_text, points = :points, time_limit = :time_limit WHERE id = :id');
        $ok = $stmt->execute([
            ':question_text' => $questionText,
            ':points' => (int)($data['points'] ?? 1),
            ':time_limit' => (int)($data['time_limit'] ?? 60),
            ':id' => $id,
        ]);
        if (!$ok) {
            return false;
        }

        $options = $data['options'] ?? null;
        if (!is_array($options)) {
            $options = [
                $data['option1'] ?? null,
                $data['option2'] ?? null,
                $data['option3'] ?? null,
                $data['option4'] ?? null,
            ];
        }
        $correctIndices = null;
        if (isset($data['correctIndices']) && is_array($data['correctIndices'])) {
            $correctIndices = array_values(array_map('intval', $data['correctIndices']));
        } elseif (isset($data['correctIndex'])) {
            $correctIndices = [(int)$data['correctIndex']];
        }
        $this->replaceOptions($id, $options, $goodAnswer, $correctIndices);

        return true;
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM questions WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    private function attachOptions(array $questions): array {
        if (empty($questions)) {
            return [];
        }

        $ids = [];
        foreach ($questions as $q) {
            if (isset($q['questionId'])) {
                $ids[] = (int)$q['questionId'];
            }
        }
        $ids = array_values(array_unique(array_filter($ids)));
        if (empty($ids)) {
            return $questions;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $optStmt = $this->conn->prepare(
            "SELECT question_id, option_text, is_correct, order_index, id
             FROM question_options
             WHERE question_id IN ($placeholders)
             ORDER BY question_id ASC, order_index ASC, id ASC"
        );
        $optStmt->execute($ids);
        $rows = $optStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $byQuestion = [];
        foreach ($rows as $row) {
            $qid = (int)$row['question_id'];
            $byQuestion[$qid][] = $row;
        }

        foreach ($questions as &$q) {
            $qid = (int)($q['questionId'] ?? 0);
            $opts = $byQuestion[$qid] ?? [];

            $texts = [];
            $correctText = $q['goodAnswer'] ?? null;
            $correctIndex = null;
            $correctIndices = [];
            $correctOptions = [];
            foreach ($opts as $opt) {
                $texts[] = $opt['option_text'];
                if ((int)$opt['is_correct'] === 1 && $correctIndex === null) {
                    $correctIndex = count($texts) - 1;
                }
                if ((int)$opt['is_correct'] === 1) {
                    $correctIndices[] = count($texts) - 1;
                    $correctOptions[] = $opt['option_text'];
                }
                if ((int)$opt['is_correct'] === 1 && empty($correctText)) {
                    $correctText = $opt['option_text'];
                }
            }

            $q['option1'] = $texts[0] ?? null;
            $q['option2'] = $texts[1] ?? null;
            $q['option3'] = $texts[2] ?? null;
            $q['option4'] = $texts[3] ?? null;
            $q['goodAnswer'] = $correctText;
            $q['options'] = $texts;
            $q['correctIndex'] = $correctIndex;
            $q['correctIndices'] = $correctIndices;
            $q['correctOptions'] = $correctOptions;
        }
        unset($q);

        return $questions;
    }

    private function replaceOptions(int $questionId, array $options, string $goodAnswer, ?array $correctIndices = null): void {
        $delStmt = $this->conn->prepare('DELETE FROM question_options WHERE question_id = :id');
        $delStmt->execute([':id' => $questionId]);

        $clean = [];
        foreach ($options as $opt) {
            $opt = trim((string)$opt);
            if ($opt !== '') {
                $clean[] = $opt;
            }
        }

        $correctSet = [];
        if (is_array($correctIndices)) {
            foreach ($correctIndices as $ci) {
                if (is_int($ci) || ctype_digit((string)$ci)) {
                    $correctSet[(int)$ci] = true;
                }
            }
        }

        if (empty($correctSet) && $goodAnswer !== '') {
            $found = array_search($goodAnswer, $clean, true);
            if ($found !== false) {
                $correctSet[(int)$found] = true;
            }
        }

        if ($goodAnswer !== '' && !in_array($goodAnswer, $clean, true)) {
            $clean[] = $goodAnswer;
            if (empty($correctSet)) {
                $correctSet[count($clean) - 1] = true;
            }
        }

        if (empty($clean)) {
            return;
        }

        $ins = $this->conn->prepare(
            'INSERT INTO question_options (question_id, option_text, is_correct, order_index) VALUES (:question_id, :option_text, :is_correct, :order_index)'
        );

        $orderIndex = 1;
        $correctWritten = false;
        foreach ($clean as $idx => $text) {
            $isCorrect = isset($correctSet[(int)$idx]) ? 1 : 0;
            if ($isCorrect === 1) {
                $correctWritten = true;
            }
            $ins->execute([
                ':question_id' => $questionId,
                ':option_text' => $text,
                ':is_correct' => $isCorrect,
                ':order_index' => $orderIndex,
            ]);
            $orderIndex++;
        }

        if (!$correctWritten && !empty($clean)) {
            $fixStmt = $this->conn->prepare('UPDATE question_options SET is_correct = 1 WHERE question_id = :qid ORDER BY order_index ASC, id ASC LIMIT 1');
            $fixStmt->execute([':qid' => $questionId]);
        }
    }
}

?>
<?php
class Question
{
    private int $questionId;
    private string $questionText;
    private ?string $option1;
    private ?string $option2;
    private ?string $option3;
    private string $goodAnswer;
    private int $lessonId;

    public function __construct(
        int $questionId = 0,
        string $questionText = '',
        ?string $option1 = null,
        ?string $option2 = null,
        ?string $option3 = null,
        string $goodAnswer = '',
        int $lessonId = 0
    ) {
        $this->questionId = $questionId;
        $this->questionText = $questionText;
        $this->option1 = $option1;
        $this->option2 = $option2;
        $this->option3 = $option3;
        $this->goodAnswer = $goodAnswer;
        $this->lessonId = $lessonId;
    }

    // ======= GETTERS =======

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function getQuestionText(): string
    {
        return $this->questionText;
    }

    public function getOption1(): ?string
    {
        return $this->option1;
    }

    public function getOption2(): ?string
    {
        return $this->option2;
    }

    public function getOption3(): ?string
    {
        return $this->option3;
    }

    public function getGoodAnswer(): string
    {
        return $this->goodAnswer;
    }

    public function getLessonId(): int
    {
        return $this->lessonId;
    }

    // ======= SETTERS =======

    public function setQuestionId(int $questionId): void
    {
        $this->questionId = $questionId;
    }

    public function setQuestionText(string $questionText): void
    {
        $this->questionText = $questionText;
    }

    public function setOption1(?string $option1): void
    {
        $this->option1 = $option1;
    }

    public function setOption2(?string $option2): void
    {
        $this->option2 = $option2;
    }

    public function setOption3(?string $option3): void
    {
        $this->option3 = $option3;
    }

    public function setGoodAnswer(string $goodAnswer): void
    {
        $this->goodAnswer = $goodAnswer;
    }

    public function setLessonId(int $lessonId): void
    {
        $this->lessonId = $lessonId;
    }
}

?>
