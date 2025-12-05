<?php
require_once __DIR__ . '/Database.php';

class LessonModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM lessons ORDER BY lessonId DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getById(int $id) {
        $stmt = $this->conn->prepare("SELECT * FROM lessons WHERE lessonId = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->conn->prepare("INSERT INTO lessons (title, ageRange, duration, description, image) VALUES (:title, :ageRange, :duration, :description, :image)");
        $stmt->execute([
            ':title' => $data['title'],
            ':ageRange' => $data['ageRange'],
            ':duration' => $data['duration'],
            ':description' => $data['description'],
            ':image' => $data['image'] ?? null
        ]);
        return (int)$this->conn->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->conn->prepare("UPDATE lessons SET title = :title, ageRange = :ageRange, duration = :duration, description = :description, image = :image WHERE lessonId = :id");
        return $stmt->execute([
            ':title' => $data['title'],
            ':ageRange' => $data['ageRange'],
            ':duration' => $data['duration'],
            ':description' => $data['description'],
            ':image' => $data['image'] ?? null,
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM lessons WHERE lessonId = :id");
        return $stmt->execute([':id' => $id]);
    }
}

?>
<?php

class Lesson
{
    private int $lessonId;
    private string $title;
    private string $ageRange;
    private int $duration;
    private string $description;
    private ?string $image;
    private ?int $teacherId;
    private string $created_at;

    public function __construct(
        int $lessonId = 0,
        string $title = '',
        string $ageRange = '',
        int $duration = 0,
        string $description = '',
        ?string $image = null,
        ?int $teacherId = null,
        string $created_at = ''
    ) {
        $this->lessonId = $lessonId;
        $this->title = $title;
        $this->ageRange = $ageRange;
        $this->duration = $duration;
        $this->description = $description;
        $this->image = $image;
        $this->teacherId = $teacherId;
        $this->created_at = $created_at;
    }



    // =================== GETTERS ===================

    public function getLessonId(): int {
        return $this->lessonId;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getAgeRange(): string {
        return $this->ageRange;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function getTeacherId(): ?int {
        return $this->teacherId;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    // =================== SETTERS ===================

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setAgeRange(string $ageRange): void {
        $this->ageRange = $ageRange;
    }

    public function setDuration(int $duration): void {
        $this->duration = $duration;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setImage(?string $image): void {
        $this->image = $image;
    }

    public function setTeacherId(?int $teacherId): void {
        $this->teacherId = $teacherId;
    }
}

?>
