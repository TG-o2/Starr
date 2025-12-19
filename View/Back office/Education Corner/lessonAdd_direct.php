<?php
require_once __DIR__ . '/init.php';

(new LessonController())->add($_POST);
