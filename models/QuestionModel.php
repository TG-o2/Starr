<?php
require_once __DIR__ . '/Database.php';

class QuestionModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT q.*, l.title as lessonTitle FROM questions q LEFT JOIN lessons l ON q.lessonId = l.lessonId ORDER BY q.questionId DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getOne(int $id) {
        $stmt = $this->conn->prepare("SELECT * FROM questions WHERE questionId = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByLesson(int $lessonId): array {
        $stmt = $this->conn->prepare("SELECT * FROM questions WHERE lessonId = :lessonId ORDER BY questionId ASC");
        $stmt->execute([':lessonId' => $lessonId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function create(array $data): int {
        $stmt = $this->conn->prepare("INSERT INTO questions (lessonId, questionText, option1, option2, option3, goodAnswer) VALUES (:lessonId, :questionText, :option1, :option2, :option3, :goodAnswer)");
        $stmt->execute([
            ':lessonId' => $data['lessonId'],
            ':questionText' => $data['questionText'],
            ':option1' => $data['option1'] ?? null,
            ':option2' => $data['option2'] ?? null,
            ':option3' => $data['option3'] ?? null,
            ':goodAnswer' => $data['goodAnswer']
        ]);
        return (int)$this->conn->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->conn->prepare("UPDATE questions SET questionText = :questionText, option1 = :option1, option2 = :option2, option3 = :option3, goodAnswer = :goodAnswer WHERE questionId = :id");
        return $stmt->execute([
            ':questionText' => $data['questionText'],
            ':option1' => $data['option1'] ?? null,
            ':option2' => $data['option2'] ?? null,
            ':option3' => $data['option3'] ?? null,
            ':goodAnswer' => $data['goodAnswer'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM questions WHERE questionId = :id");
        return $stmt->execute([':id' => $id]);
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
