<?php
require_once __DIR__ . '/../config/Config.php';

class LessonModel
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Config::getConnexion();
    }

    // ====== ATTRIBUTES (optional, fine to keep) ======
    public int $lessonId;
    public string $title;
    public string $ageRange;
    public int $duration;
    public string $description;
    public ?string $image;
    public ?int $teacherId;
    public string $created_at;

    // ====== CRUD METHODS ======

    public function getAll(): array
    {
        $stmt = $this->conn->query(
            'SELECT * FROM lessons ORDER BY lessonId DESC'
        );
        return $stmt->fetchAll() ?: [];
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM lessons WHERE lessonId = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO lessons 
            (title, ageRange, duration, description, image, teacherId) 
            VALUES 
            (:title, :ageRange, :duration, :description, :image, :teacherId)'
        );

        $stmt->execute([
            ':title' => $data['title'],
            ':ageRange' => $data['ageRange'],
            ':duration' => $data['duration'],
            ':description' => $data['description'],
            ':image' => $data['image'] ?? null,
            ':teacherId' => $data['teacherId'] ?? null,
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->conn->prepare(
            'UPDATE lessons SET 
                title = :title,
                ageRange = :ageRange,
                duration = :duration,
                description = :description,
                image = :image,
                teacherId = :teacherId
             WHERE lessonId = :id'
        );

        return $stmt->execute([
            ':title' => $data['title'],
            ':ageRange' => $data['ageRange'],
            ':duration' => $data['duration'],
            ':description' => $data['description'],
            ':image' => $data['image'] ?? null,
            ':teacherId' => $data['teacherId'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare(
            'DELETE FROM lessons WHERE lessonId = :id'
        );
        return $stmt->execute([':id' => $id]);
    }
}
