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

    public function getLessonId(): int { return $this->lessonId; }
    public function getTitle(): string { return $this->title; }
    public function getAgeRange(): string { return $this->ageRange; }
    public function getDuration(): int { return $this->duration; }
    public function getDescription(): string { return $this->description; }
    public function getImage(): ?string { return $this->image; }
    public function getTeacherId(): ?int { return $this->teacherId; }
    public function getCreatedAt(): string { return $this->created_at; }

    public function setTitle(string $title): void { $this->title = $title; }
    public function setAgeRange(string $ageRange): void { $this->ageRange = $ageRange; }
    public function setDuration(int $duration): void { $this->duration = $duration; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setImage(?string $image): void { $this->image = $image; }
    public function setTeacherId(?int $teacherId): void { $this->teacherId = $teacherId; }
}

?>
