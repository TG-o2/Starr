<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Starr - Edit Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="../assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="../assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

    <style>
        .avatar-preview {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border: 8px solid var(--primary);
            transition: all 0.3s;
        }
        .avatar-preview:hover { transform: scale(1.05); }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(254, 93, 55, 0.25);
        }
    </style>
</head>

<body>
<div class="container-xxl p-0" style="background-color: #fbefdf;">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center">
            <h1 class="m-0 text-primary">
                <img src="../assets/img/starr.jpg" alt="Starr Logo" style="height:45px; margin-right:8px;">
                Starr
            </h1>
        </a>

        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto">
                <a href="index.html" class="nav-item nav-link">Home</a>
                <a href="about.html" class="nav-item nav-link">About Us</a>
                <a href="classes.html" class="nav-item nav-link">Classes</a>
                <a href="contact.html" class="nav-item nav-link">Contact Us</a>
            </div>

            <a href="profile.html" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">
                <i class="fa fa-user me-2"></i>My Profile
            </a>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="container-fluid py-5 mb-5 text-white text-center"
         style="background: linear-gradient(rgba(0,0,0,.4), rgba(0,0,0,.4)), url('../assets/img/carousel-1.jpg') center/cover no-repeat;">
        <div class="container py-5">
            <h1 class="display-3 mb-3 animated slideInDown">Edit Profile</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center text-uppercase">
                    <li class="breadcrumb-item"><a class="text-white" href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="profile.html">Profile</a></li>
                    <li class="breadcrumb-item text-white active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="bg-light rounded p-5 wow fadeInUp">

                        <form action="update-profile.php" method="POST" enctype="multipart/form-data">

                            <!-- Avatar -->
                            <div class="text-center mb-5">
                                <img src="../assets/img/team-2.jpg" id="avatarPreview" class="rounded-circle shadow avatar-preview" alt="Avatar">
                                <div class="mt-3">
                                    <label class="btn btn-primary rounded-pill px-4">
                                        Change Avatar
                                        <input type="file" name="avatar" accept="image/*" hidden onchange="previewAvatar(event)">
                                    </label>
                                </div>
                            </div>

                            <div class="row g-4">

                                <!-- First & Last Name -->
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control form-control-lg" value="Emma" >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control form-control-lg" value="Johnson" >
                                </div>

                                <!-- About Me -->
                                <div class="col-12">
                                    <label class="form-label">About Me</label>
                                    <textarea name="description" class="form-control" rows="5" >
Hi! I'm 5 years old and I love painting rainbows, playing with dinosaurs, and building the tallest block towers! My favorite color is orange and I'm always ready for new adventures
                                    </textarea>
                                </div>

                                <!-- Star Points (hidden for kids) -->
                                <div class="col-md-6 d-none" id="starPointsField">
                                    <label class="form-label">Star Points</label>
                                    <input type="number" name="starPoints" class="form-control form-control-lg" value="420" min="0">
                                </div>

                                <!-- Buttons -->
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill py-3 px-5">Save Changes</button>
                                    <a href="profile.html" class="btn btn-outline-secondary rounded-pill py-3 px-5 ms-3">Cancel</a>
                                </div>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
        <div class="container py-5">
            <div class="row g-5">

                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Get In Touch</h3>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                </div>

            </div>
        </div>

        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">Starr</a>, All Rights Reserved.
                        Designed by <a class="border-bottom" href="#">HTML Codex</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
        <i class="bi bi-arrow-up"></i>
    </a>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/lib/wow/wow.min.js"></script>
<script src="../assets/lib/easing/easing.min.js"></script>
<script src="../assets/lib/waypoints/waypoints.min.js"></script>
<script src="../assets/lib/owlcarousel/owl.carousel.min.js"></script>
<script src="../assets/js/main.js"></script>

<script>
// Avatar live preview
function previewAvatar(event) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('avatarPreview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
