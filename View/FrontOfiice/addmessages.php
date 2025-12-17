<?php
    require_once "../../Controller/messagescontroller.php";
    if(isset($_POST['content'])){
        if(strlen($_POST['content'])>=3){
            $messages1=new messages(NULL,$_GET['post_id'],$_POST['content'],0,"iheb",222,"2024-01-15 10:30:00");
            $messagescontroller1=new messagescontroller();
            $messagescontroller1->addmessages($messages1);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Add Message - Speech to Text</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    
    <style>
        .speech-controls {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            align-items: center;
        }
        
        #speechButton {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        #speechButton:hover {
            background-color: #45a049;
        }
        
        #speechButton.listening {
            background-color: #f44336;
            animation: pulse 1.5s infinite;
        }
        
        #clearButton {
            background-color: #ff9800;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        #clearButton:hover {
            background-color: #e68900;
        }
        
        .speech-status {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
        
        .speech-status.active {
            display: block;
            background-color: #e7f3fe;
            border-left: 4px solid #2196F3;
        }
        
        .speech-status.error {
            display: block;
            background-color: #ffebee;
            border-left: 4px solid #f44336;
        }
        
        .speech-status.success {
            display: block;
            background-color: #e8f5e9;
            border-left: 4px solid #4CAF50;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(244, 67, 54, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(244, 67, 54, 0); }
            100% { box-shadow: 0 0 0 0 rgba(244, 67, 54, 0); }
        }
        
        .mic-icon {
            font-size: 18px;
        }
        
        .form-control-wrapper {
            position: relative;
        }
        
        .char-count {
            position: absolute;
            bottom: -25px;
            right: 0;
            font-size: 12px;
            color: #666;
        }
        
        #content {
            min-height: 150px;
            resize: vertical;
        }
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a href="index.html" class="navbar-brand">
                <h1 class="m-0 text-primary"><i class="fa fa-book-reader me-3"></i>Kider</h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.html" class="nav-item nav-link">Home</a>
                    <a href="about.html" class="nav-item nav-link">About Us</a>
                    <a href="classes.html" class="nav-item nav-link">Classes</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 shadow-sm m-0">
                            <a href="facility.html" class="dropdown-item">School Facilities</a>
                            <a href="team.html" class="dropdown-item">Popular Teachers</a>
                            <a href="call-to-action.html" class="dropdown-item">Become A Teachers</a>
                            <a href="appointment.html" class="dropdown-item active">Make Appointment</a>
                            <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                            <a href="404.html" class="dropdown-item">404 Error</a>
                        </div>
                    </div>
                    <a href="contact.html" class="nav-item nav-link">Contact Us</a>
                </div>
                <a href="" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Join Us<i class="fa fa-arrow-right ms-3"></i></a>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Page Header End -->
        <div class="container-xxl py-5 page-header position-relative mb-5">
            <div class="container py-5">
                <h1 class="display-2 text-white animated slideInDown mb-4">Add Message</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Add Message</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Appointment Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="bg-light rounded">
                    <div class="row g-0">
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                            <div class="h-100 d-flex flex-column justify-content-center p-5">
                                <h1 class="mb-4">Add Message</h1>
                                
                                <!-- Speech Status Messages -->
                                <div id="speechStatus" class="speech-status"></div>
                                
                                <form method="post" id="messageForm">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-floating form-control-wrapper">
                                                <textarea name="content" class="form-control border-0" 
                                                          placeholder="Leave a message here" 
                                                          id="content" 
                                                          style="height: 150px"
                                                          required></textarea>
                                                <label for="content">Message Content</label>
                                                <div class="char-count" id="charCount">0 characters</div>
                                                <p id="errMes3"></p>
                                            </div>
                                            
                                            <!-- Speech Control Buttons -->
                                            <div class="speech-controls">
                                                <button type="button" id="speechButton" class="btn">
                                                    <i class="fas fa-microphone mic-icon"></i>
                                                    <span id="speechButtonText">Start Speaking</span>
                                                </button>
                                                <button type="button" id="clearButton" class="btn">
                                                    <i class="fas fa-eraser"></i> Clear Text
                                                </button>
                                            </div>
                                            
                                            <!-- Browser Support Warning -->
                                            <div class="alert alert-info mt-3" role="alert">
                                                <small>
                                                    <i class="fas fa-info-circle"></i> 
                                                    Speech-to-text works best in Chrome, Edge, and Safari. 
                                                    Ensure you have a microphone connected and allow microphone access when prompted.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100 py-3" type="submit">
                                                <i class="fas fa-paper-plane"></i> Submit Message
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s" style="min-height: 400px;">
                            <div class="position-relative h-100">
                                <img class="position-absolute w-100 h-100 rounded" src="img/appointment.jpg" style="object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Appointment End -->

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Get In Touch</h3>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Quick Links</h3>
                        <a class="btn btn-link text-white-50" href="">About Us</a>
                        <a class="btn btn-link text-white-50" href="">Contact Us</a>
                        <a class="btn btn-link text-white-50" href="">Our Services</a>
                        <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Photo Gallery</h3>
                        <div class="row g-2 pt-2">
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-1.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-2.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-3.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-4.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-5.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/classes-6.jpg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Newsletter</h3>
                        <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                            <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">Your Site Name</a>, All Right Reserved. 
                            <!--/*** This template is free as long as you keep the footer author's credit link/attribution link/backlink. If you'd like to use the template without the footer author's credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                            Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="">Home</a>
                                <a href="">Cookies</a>
                                <a href="">Help</a>
                                <a href="">FQAs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Speech-to-Text Implementation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const speechButton = document.getElementById('speechButton');
            const speechButtonText = document.getElementById('speechButtonText');
            const clearButton = document.getElementById('clearButton');
            const contentTextarea = document.getElementById('content');
            const speechStatus = document.getElementById('speechStatus');
            const charCount = document.getElementById('charCount');
            
            let recognition = null;
            let isListening = false;
            
            // Check for browser support
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            
            if (!SpeechRecognition) {
                showSpeechStatus('Speech recognition is not supported in your browser. Please use Chrome, Edge, or Safari.', 'error');
                speechButton.disabled = true;
                speechButtonText.textContent = 'Not Supported';
                return;
            }
            
            // Initialize speech recognition
            recognition = new SpeechRecognition();
            recognition.continuous = true;
            recognition.interimResults = true;
            recognition.lang = 'en-US'; // You can change this to other languages
            
            // Update character count
            function updateCharCount() {
                const length = contentTextarea.value.length;
                charCount.textContent = `${length} characters`;
                charCount.style.color = length < 3 ? '#f44336' : '#666';
            }
            
            // Show status messages
            function showSpeechStatus(message, type = 'active') {
                speechStatus.textContent = message;
                speechStatus.className = 'speech-status ' + type;
                setTimeout(() => {
                    speechStatus.className = 'speech-status';
                }, 5000);
            }
            
            // Clear text area
            clearButton.addEventListener('click', function() {
                contentTextarea.value = '';
                updateCharCount();
                showSpeechStatus('Text cleared successfully.', 'success');
            });
            
            // Speech recognition events
            recognition.onstart = function() {
                isListening = true;
                speechButton.classList.add('listening');
                speechButtonText.textContent = 'Listening...';
                showSpeechStatus('Speech recognition started. Start speaking now!', 'active');
            };
            
            recognition.onresult = function(event) {
                let finalTranscript = '';
                let interimTranscript = '';
                
                for (let i = event.resultIndex; i < event.results.length; i++) {
                    const transcript = event.results[i][0].transcript;
                    if (event.results[i].isFinal) {
                        finalTranscript += transcript + ' ';
                    } else {
                        interimTranscript += transcript;
                    }
                }
                
                // Get current text and append new speech
                const currentText = contentTextarea.value;
                if (finalTranscript) {
                    contentTextarea.value = currentText + finalTranscript;
                    updateCharCount();
                }
                
                // Show interim results in status
                if (interimTranscript) {
                    showSpeechStatus('Interim: ' + interimTranscript, 'active');
                }
            };
            
            recognition.onerror = function(event) {
                console.error('Speech recognition error:', event.error);
                isListening = false;
                speechButton.classList.remove('listening');
                speechButtonText.textContent = 'Start Speaking';
                
                let errorMessage = 'Speech recognition error: ';
                switch(event.error) {
                    case 'no-speech':
                        errorMessage = 'No speech was detected. Please try again.';
                        break;
                    case 'audio-capture':
                        errorMessage = 'No microphone was found. Please ensure a microphone is connected.';
                        break;
                    case 'not-allowed':
                        errorMessage = 'Microphone access was denied. Please allow microphone access in your browser settings.';
                        break;
                    default:
                        errorMessage += event.error;
                }
                
                showSpeechStatus(errorMessage, 'error');
            };
            
            recognition.onend = function() {
                isListening = false;
                speechButton.classList.remove('listening');
                speechButtonText.textContent = 'Start Speaking';
                showSpeechStatus('Speech recognition ended.', 'success');
            };
            
            // Toggle speech recognition
            speechButton.addEventListener('click', function() {
                if (!isListening) {
                    try {
                        recognition.start();
                    } catch (error) {
                        showSpeechStatus('Error starting speech recognition: ' + error.message, 'error');
                    }
                } else {
                    recognition.stop();
                }
            });
            
            // Update character count on input
            contentTextarea.addEventListener('input', updateCharCount);
            
            // Initial character count
            updateCharCount();
            
            // Form submission validation
            document.getElementById('messageForm').addEventListener('submit', function(event) {
                if (contentTextarea.value.length < 3) {
                    event.preventDefault();
                    showSpeechStatus('Message must be at least 3 characters long.', 'error');
                    contentTextarea.focus();
                }
            });
            
            // Keyboard shortcut: Ctrl+Space to toggle speech recognition
            document.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.code === 'Space') {
                    event.preventDefault();
                    speechButton.click();
                }
                
                // Escape key to stop listening
                if (event.code === 'Escape' && isListening) {
                    recognition.stop();
                }
            });
            
            // Instructions tooltip
            speechButton.title = 'Click to start/stop speech recognition (Ctrl+Space)';
            clearButton.title = 'Clear all text from the message box';
        });
    </script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>