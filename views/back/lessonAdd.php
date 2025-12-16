<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lesson - Admin</title>
    <link href="../front/kider-1.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="../front/kider-1.0.0/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="bg-white rounded shadow p-4 p-md-5">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="mb-0">Add New Lesson</h2>
                            <a href="/lessons_project/views/back/lessonList_direct.php" class="btn btn-outline-primary btn-sm">Back</a>
                        </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mb-4"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="/lessons_project/views/back/lessonAdd_direct.php" class="row g-3" id="lessonForm" enctype="multipart/form-data">
                    <div class="col-12">
                        <style>
                        .lesson-cover{position:relative;width:100%;height:240px;border-radius:16px;overflow:hidden;background:linear-gradient(135deg, rgba(15,23,42,.06), rgba(15,23,42,.02));border:1px dashed rgba(15,23,42,.18);cursor:pointer}
                        .lesson-cover.has-image{border:0}
                        .lesson-cover-img{width:100%;height:100%;object-fit:cover;display:block}
                        .lesson-cover-overlay{position:absolute;inset:0;background:linear-gradient(180deg, rgba(0,0,0,.28), rgba(0,0,0,0));opacity:0;transition:opacity .2s ease}
                        .lesson-cover.has-image:hover .lesson-cover-overlay{opacity:1}
                        .lesson-cover-action{position:absolute;top:14px;right:14px;width:42px;height:42px;border-radius:999px;background:rgba(255,255,255,.92);display:flex;align-items:center;justify-content:center;box-shadow:0 10px 24px rgba(15,23,42,.18)}
                        .lesson-cover-action i{font-size:18px;color:#0f172a}
                        .lesson-cover-placeholder{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;color:#0f172a}
                        .lesson-cover-placeholder .plus{width:54px;height:54px;border-radius:999px;background:rgba(43,124,255,.10);display:flex;align-items:center;justify-content:center}
                        .lesson-cover-placeholder .plus i{font-size:22px;color:#2b7cff}
                        .lesson-cover-placeholder .title{font-weight:700}
                        .lesson-cover-placeholder .hint{font-size:.95rem;opacity:.75}
                        .lesson-cover-placeholder .arrow i{font-size:18px;color:#2b7cff}
                        @keyframes coverBounce{0%,100%{transform:translateY(0)}50%{transform:translateY(10px)}}
                        .lesson-cover-placeholder .arrow{animation:coverBounce 1.2s infinite}
                        @media (max-width: 576px){.lesson-cover{height:190px}}
                        </style>

                        <input type="file" class="d-none" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/gif">
                        <div id="lessonCover" class="lesson-cover">
                            <div id="lessonCoverPlaceholder" class="lesson-cover-placeholder">
                                <div class="plus"><i class="bi bi-plus-lg"></i></div>
                                <div class="title">Add image cover to your lesson</div>
                                <div class="arrow"><i class="bi bi-arrow-down"></i></div>
                                <div class="hint">Click to upload</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required placeholder="Enter lesson title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Age Range *</label>
                        <div id="age-range-slider" class="mb-3"></div>
                        <div class="d-flex justify-content-between mb-3">
                            <span id="age-min-display" class="badge bg-primary">5</span>
                            <span class="text-muted">to</span>
                            <span id="age-max-display" class="badge bg-primary">18</span>
                        </div>
                        <input type="hidden" name="ageRange" id="age-range-value" value="<?php echo htmlspecialchars($_POST['ageRange'] ?? '5-18'); ?>">
                    </div>
                    
                    <style>
                    .noUi-connect {
                        background: #4e73df;
                    }
                    .noUi-horizontal {
                        height: 8px;
                        margin: 15px 0;
                    }
                    .noUi-horizontal .noUi-handle {
                        width: 20px;
                        height: 20px;
                        right: -10px;
                        top: -7px;
                        border-radius: 50%;
                        background: #4e73df;
                        border: 2px solid white;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    }
                    .noUi-handle:before, .noUi-handle:after {
                        display: none;
                    }
                    .badge {
                        padding: 0.35em 0.65em;
                        font-size: 0.9em;
                    }
                    </style>

                    <div class="col-md-6">
                        <label for="duration" class="form-label">Duration (minutes) *</label>
                        <input type="number" class="form-control" id="duration" name="duration" min="1" required placeholder="Enter duration" value="<?php echo htmlspecialchars($_POST['duration'] ?? ''); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="quiz_time_limit" class="form-label">Quiz Timer (minutes)</label>
                        <input type="number" class="form-control" id="quiz_time_limit" name="quiz_time_limit" min="1" max="180" placeholder="e.g., 30" value="<?php echo htmlspecialchars($_POST['quiz_time_limit'] ?? 30); ?>">
                        <small class="text-muted">Set time limit for quiz (1-180 minutes). Default: 30 minutes.</small>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required placeholder="Enter lesson description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="col-12 d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-primary">Add Lesson</button>
                        <a href="/lessons_project/views/back/lessonList_direct.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.0/nouislider.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.0/nouislider.min.css">
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ageSlider = document.getElementById('age-range-slider');
        const ageMinDisplay = document.getElementById('age-min-display');
        const ageMaxDisplay = document.getElementById('age-max-display');
        const ageRangeValue = document.getElementById('age-range-value');
        
        // Parse current value or use default
        let currentValue = ageRangeValue.value;
        let [startMin, startMax] = currentValue ? currentValue.split('-').map(Number) : [5, 18];
        
        noUiSlider.create(ageSlider, {
            start: [startMin, startMax],
            connect: true,
            range: {
                'min': 3,
                'max': 30
            },
            step: 1,
            tooltips: false
        });

        ageSlider.noUiSlider.on('update', function(values, handle) {
            const minAge = Math.round(values[0]);
            const maxAge = Math.round(values[1]);
            
            ageMinDisplay.textContent = minAge;
            ageMaxDisplay.textContent = maxAge;
            ageRangeValue.value = `${minAge}-${maxAge}`;
        });

        const thumbnailInput = document.getElementById('thumbnail');
        const cover = document.getElementById('lessonCover');
        if (thumbnailInput && cover) {
            cover.addEventListener('click', function() {
                thumbnailInput.click();
            });

            thumbnailInput.addEventListener('change', function() {
                const file = thumbnailInput.files && thumbnailInput.files[0];
                if (!file) {
                    return;
                }

                const placeholder = document.getElementById('lessonCoverPlaceholder');
                const url = URL.createObjectURL(file);

                if (placeholder) {
                    placeholder.remove();
                }

                let img = document.getElementById('lessonCoverImg');
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'lessonCoverImg';
                    img.className = 'lesson-cover-img';
                    img.alt = 'Lesson cover';
                    cover.prepend(img);

                    const overlay = document.createElement('div');
                    overlay.className = 'lesson-cover-overlay';
                    overlay.innerHTML = '<div class="lesson-cover-action" aria-hidden="true"><i class="bi bi-pencil"></i></div>';
                    cover.appendChild(overlay);
                }
                img.src = url;
                cover.classList.add('has-image');
            });
        }
    });
    </script>
</body>
</html>
