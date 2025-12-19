<?php
require_once __DIR__ . '/../config/config.php';

class QuestionModel
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Config::getConnexion();
    }

    // ======== ATTRIBUTES (optional but okay) ========
    public int $questionId;
    public string $questionText;
    public ?string $option1;
    public ?string $option2;
    public ?string $option3;
    public string $goodAnswer;
    public int $lessonId;
    public int $points = 5;

    // ======== CRUD METHODS ========

    public function getAll(): array
    {
        $stmt = $this->conn->query(
            'SELECT q.*, l.title AS lessonTitle 
             FROM questions q 
             LEFT JOIN lessons l ON q.lessonId = l.lessonId 
             ORDER BY q.questionId DESC'
        );
        return $stmt->fetchAll() ?: [];
    }

    public function getOne(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM questions WHERE questionId = :id'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getByLesson(int $lessonId): array
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM questions WHERE lessonId = :lessonId'
        );
        $stmt->execute([':lessonId' => $lessonId]);
        return $stmt->fetchAll() ?: [];
    }

    public function create(array $data): int
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO questions 
             (lessonId, questionText, option1, option2, option3, goodAnswer, points) 
             VALUES 
             (:lessonId, :questionText, :option1, :option2, :option3, :goodAnswer, :points)'
        );

        $stmt->execute([
            ':lessonId' => $data['lessonId'],
            ':questionText' => $data['questionText'],
            ':option1' => $data['option1'] ?? null,
            ':option2' => $data['option2'] ?? null,
            ':option3' => $data['option3'] ?? null,
            ':goodAnswer' => $data['goodAnswer'],
            ':points' => $data['points'] ?? 5,
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->conn->prepare(
            'UPDATE questions 
             SET questionText = :questionText,
                 option1 = :option1,
                 option2 = :option2,
                 option3 = :option3,
                 goodAnswer = :goodAnswer,
                 points = :points
             WHERE questionId = :id'
        );

        return $stmt->execute([
            ':questionText' => $data['questionText'],
            ':option1' => $data['option1'] ?? null,
            ':option2' => $data['option2'] ?? null,
            ':option3' => $data['option3'] ?? null,
            ':goodAnswer' => $data['goodAnswer'],
            ':points' => $data['points'] ?? 5,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare(
            'DELETE FROM questions WHERE questionId = :id'
        );
        return $stmt->execute([':id' => $id]);
    }
}
