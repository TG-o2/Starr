<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Quiz - <?= htmlspecialchars($lesson['title']) ?></title></head>
<body>
  <h1><?= htmlspecialchars($lesson['title']) ?> — Quiz</h1>

  <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitQuiz'])): 
      $score = 0;
      $total = count($questions);
      $results = [];
      foreach($questions as $q) {
          $qid = $q['questionId'];
          $user = $_POST['q'.$qid] ?? '';
          $ok = ($user === $q['goodAnswer']);
          $results[] = ['q'=>$q,'selected'=>$user,'ok'=>$ok];
          if ($ok) $score++;
      }
  ?>
      <p><strong>You scored <?= $score ?> / <?= $total ?></strong></p>

      <?php foreach($results as $i => $r): ?>
        <div style="padding:8px;border:1px solid #eee;margin-bottom:8px;">
          <p><strong>Q<?= $i+1 ?>:</strong> <?= htmlspecialchars($r['q']['questionText']) ?></p>
          <p>Your answer: <?= htmlspecialchars($r['selected']) ?></p>
          <p>Correct answer: <?= htmlspecialchars($r['q']['goodAnswer']) ?></p>
          <p><?= $r['ok'] ? '<span style="color:green">Correct</span>' : '<span style="color:red">Wrong</span>' ?></p>
        </div>
      <?php endforeach; ?>

      <p><a href="/lessons_project/views/front/lessonDisplay_direct.php">Back to lessons</a></p>

  <?php else: ?>

    <form method="post" action="/lessons_project/views/front/lessonQuiz_direct.php?lessonId=<?= $lesson['lessonId'] ?>">
      <?php foreach($questions as $q): ?>
        <div style="margin-bottom:10px;">
          <p><strong><?= htmlspecialchars($q['questionText']) ?></strong></p>
          <label><input type="radio" name="q<?= $q['questionId'] ?>" value="<?= htmlspecialchars($q['option1']) ?>"> <?= htmlspecialchars($q['option1']) ?></label><br>
          <label><input type="radio" name="q<?= $q['questionId'] ?>" value="<?= htmlspecialchars($q['option2']) ?>"> <?= htmlspecialchars($q['option2']) ?></label><br>
          <?php if(!empty($q['option3'])): ?>
            <label><input type="radio" name="q<?= $q['questionId'] ?>" value="<?= htmlspecialchars($q['option3']) ?>"> <?= htmlspecialchars($q['option3']) ?></label><br>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
      <button type="submit" name="submitQuiz">Submit Quiz</button>
    </form>

  <?php endif; ?>

</body>
</html>
