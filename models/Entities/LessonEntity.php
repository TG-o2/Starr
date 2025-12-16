<?php

declare(strict_types=1);

namespace App\Models\Entities;

class Lesson
{
    public const DIFFICULTY_BEGINNER = 'beginner';
    public const DIFFICULTY_INTERMEDIATE = 'intermediate';
    public const DIFFICULTY_ADVANCED = 'advanced';
    
    private int $id;
    private string $title;
    private string $description;
    private string $content;
    private string $category;
    private string $difficulty;
    private int $durationMinutes;
    private ?string $thumbnailUrl;
    private ?string $videoUrl;
    private bool $isPublished;
    private bool $isFeatured;
    private int $createdBy;
    private ?int $updatedBy;
    private string $createdAt;
    private ?string $updatedAt;
    private ?float $averageRating = null;
    private int $enrolledStudents = 0;
    private array $prerequisites = [];
    private array $learningObjectives = [];
    private array $resources = [];
    private array $sections = [];

    public function __construct(
        int $id = 0,
        string $title = '',
        string $description = '',
        string $content = '',
        string $category = 'General',
        string $difficulty = self::DIFFICULTY_BEGINNER,
        int $durationMinutes = 0,
        ?string $thumbnailUrl = null,
        ?string $videoUrl = null,
        bool $isPublished = false,
        bool $isFeatured = false,
        int $createdBy = 0,
        ?int $updatedBy = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->setTitle($title);
        $this->setDescription($description);
        $this->content = $content;
        $this->setCategory($category);
        $this->setDifficulty($difficulty);
        $this->setDurationMinutes($durationMinutes);
        $this->thumbnailUrl = $thumbnailUrl;
        $this->videoUrl = $videoUrl;
        $this->isPublished = $isPublished;
        $this->isFeatured = $isFeatured;
        $this->createdBy = $createdBy;
        $this->updatedBy = $updatedBy;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
        $this->updatedAt = $updatedAt;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getContent(): string { return $this->content; }
    public function getCategory(): string { return $this->category; }
    public function getDifficulty(): string { return $this->difficulty; }
    public function getDurationMinutes(): int { return $this->durationMinutes; }
    public function getThumbnailUrl(): ?string { return $this->thumbnailUrl; }
    public function getVideoUrl(): ?string { return $this->videoUrl; }
    public function isPublished(): bool { return $this->isPublished; }
    public function isFeatured(): bool { return $this->isFeatured; }
    public function getCreatedBy(): int { return $this->createdBy; }
    public function getUpdatedBy(): ?int { return $this->updatedBy; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function getUpdatedAt(): ?string { return $this->updatedAt; }
    public function getAverageRating(): ?float { return $this->averageRating; }
    public function getEnrolledStudents(): int { return $this->enrolledStudents; }
    public function getPrerequisites(): array { return $this->prerequisites; }
    public function getLearningObjectives(): array { return $this->learningObjectives; }
    public function getResources(): array { return $this->resources; }
    public function getSections(): array { return $this->sections; }

    // Setters with validation
    public function setTitle(string $title): self {
        if (empty(trim($title))) {
            throw new \InvalidArgumentException('Title cannot be empty');
        }
        $this->title = trim($title);
        return $this;
    }

    public function setDescription(string $description): self {
        $this->description = trim($description);
        return $this;
    }

    public function setContent(string $content): self {
        $this->content = $content;
        return $this;
    }

    public function setCategory(string $category): self {
        if (empty(trim($category))) {
            throw new \InvalidArgumentException('Category cannot be empty');
        }
        $this->category = trim($category);
        return $this;
    }

    public function setDifficulty(string $difficulty): self {
        $validDifficulties = [
            self::DIFFICULTY_BEGINNER,
            self::DIFFICULTY_INTERMEDIATE,
            self::DIFFICULTY_ADVANCED
        ];
        
        if (!in_array($difficulty, $validDifficulties, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid difficulty. Must be one of: %s',
                implode(', ', $validDifficulties)
            ));
        }
        
        $this->difficulty = $difficulty;
        return $this;
    }

    public function setDurationMinutes(int $minutes): self {
        if ($minutes < 0) {
            throw new \InvalidArgumentException('Duration cannot be negative');
        }
        $this->durationMinutes = $minutes;
        return $this;
    }

    public function setThumbnailUrl(?string $url): self {
        $this->thumbnailUrl = $url ? filter_var($url, FILTER_SANITIZE_URL) : null;
        return $this;
    }

    public function setVideoUrl(?string $url): self {
        $this->videoUrl = $url ? filter_var($url, FILTER_SANITIZE_URL) : null;
        return $this;
    }

    public function setPublished(bool $published): self {
        $this->isPublished = $published;
        return $this;
    }

    public function setFeatured(bool $featured): self {
        $this->isFeatured = $featured;
        return $this;
    }

    public function setAverageRating(?float $rating): self {
        if ($rating !== null && ($rating < 0 || $rating > 5)) {
            throw new \InvalidArgumentException('Rating must be between 0 and 5');
        }
        $this->averageRating = $rating;
        return $this;
    }

    public function setEnrolledStudents(int $count): self {
        if ($count < 0) {
            throw new \InvalidArgumentException('Student count cannot be negative');
        }
        $this->enrolledStudents = $count;
        return $this;
    }

    public function addPrerequisite(string $prerequisite): self {
        $prerequisite = trim($prerequisite);
        if (!empty($prerequisite) && !in_array($prerequisite, $this->prerequisites, true)) {
            $this->prerequisites[] = $prerequisite;
        }
        return $this;
    }

    public function addLearningObjective(string $objective): self {
        $objective = trim($objective);
        if (!empty($objective) && !in_array($objective, $this->learningObjectives, true)) {
            $this->learningObjectives[] = $objective;
        }
        return $this;
    }

    public function addResource(string $name, string $url, string $type = 'file'): self {
        $this->resources[] = [
            'name' => trim($name),
            'url' => filter_var($url, FILTER_SANITIZE_URL),
            'type' => $type,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this;
    }

    public function addSection(string $title, string $content, int $order = 0): self {
        $this->sections[] = [
            'title' => trim($title),
            'content' => $content,
            'order' => $order,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Sort sections by order
        usort($this->sections, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });
        
        return $this;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'category' => $this->category,
            'difficulty' => $this->difficulty,
            'duration' => $this->durationMinutes,
            'thumbnail_url' => $this->thumbnailUrl,
            'video_url' => $this->videoUrl,
            'is_published' => $this->isPublished,
            'is_featured' => $this->isFeatured,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'average_rating' => $this->averageRating,
            'enrolled_students' => $this->enrolledStudents,
            'prerequisites' => $this->prerequisites,
            'learning_objectives' => $this->learningObjectives,
            'resources' => $this->resources,
            'sections' => $this->sections
        ];
    }

    public static function fromArray(array $data): self {
        $lesson = new self(
            $data['id'] ?? 0,
            $data['title'] ?? '',
            $data['description'] ?? '',
            $data['content'] ?? '',
            $data['category'] ?? 'General',
            $data['difficulty'] ?? self::DIFFICULTY_BEGINNER,
            $data['duration'] ?? 0,
            $data['thumbnail_url'] ?? null,
            $data['video_url'] ?? null,
            $data['is_published'] ?? false,
            $data['is_featured'] ?? false,
            $data['created_by'] ?? 0,
            $data['updated_by'] ?? null,
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null
        );

        if (isset($data['average_rating'])) {
            $lesson->setAverageRating((float)$data['average_rating']);
        }

        if (isset($data['enrolled_students'])) {
            $lesson->setEnrolledStudents((int)$data['enrolled_students']);
        }

        foreach ($data['prerequisites'] ?? [] as $prerequisite) {
            $lesson->addPrerequisite($prerequisite);
        }

        foreach ($data['learning_objectives'] ?? [] as $objective) {
            $lesson->addLearningObjective($objective);
        }

        foreach ($data['resources'] ?? [] as $resource) {
            $lesson->addResource(
                $resource['name'] ?? '',
                $resource['url'] ?? '',
                $resource['type'] ?? 'file'
            );
        }

        foreach ($data['sections'] ?? [] as $section) {
            $lesson->addSection(
                $section['title'] ?? '',
                $section['content'] ?? '',
                $section['order'] ?? 0
            );
        }

        return $lesson;
    }
}

