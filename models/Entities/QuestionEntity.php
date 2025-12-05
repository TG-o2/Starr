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

    public function getQuestionId(): int { return $this->questionId; }
    public function getQuestionText(): string { return $this->questionText; }
    public function getOption1(): ?string { return $this->option1; }
    public function getOption2(): ?string { return $this->option2; }
    public function getOption3(): ?string { return $this->option3; }
    public function getGoodAnswer(): string { return $this->goodAnswer; }
    public function getLessonId(): int { return $this->lessonId; }

    public function setQuestionId(int $questionId): void { $this->questionId = $questionId; }
    public function setQuestionText(string $questionText): void { $this->questionText = $questionText; }
    public function setOption1(?string $option1): void { $this->option1 = $option1; }
    public function setOption2(?string $option2): void { $this->option2 = $option2; }
    public function setOption3(?string $option3): void { $this->option3 = $option3; }
    public function setGoodAnswer(string $goodAnswer): void { $this->goodAnswer = $goodAnswer; }
    public function setLessonId(int $lessonId): void { $this->lessonId = $lessonId; }
}

?>
