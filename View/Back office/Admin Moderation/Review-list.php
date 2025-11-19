<?php

require_once __DIR__ . '/../../../Controller/ReportController.php';
require_once __DIR__ . '/../../../Model/Report.php';



$reportController = new ReportController();
$reports = $reportController->getAllReports();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>View Report</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
    /* Let report list grow naturally (override chart-area fixed height) */
    .chart-area { height: auto !important; min-height: 60px; }
    #review-list { max-height: none; overflow: visible; }
    </style>

    <script src="https://kit.fontawesome.com/9d17856d97.js" crossorigin="anonymous"></script>

<style type="text/css" id="operaUserStyle"></style></head>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Admin Starr<sup>*</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link">
                    <span>Report list</span></a>
            </li>   

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Work
            </div>
            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="View-profiles.html">
                    <i class="fas fa-fw fa-user"></i>
                    <span>View Profiles</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Handle-report.html">
                <i class="fa-solid fa-flag"></i>
                <span>handle Reports</span>
                </a>
            </li>
            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="../Admin Dashboard/Dashboard.html">
                    <i class="fas fa-fw fa-tachometer-alt"  ></i>
                    <span>Admin Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Reports to Review Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Reports to review</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($reports) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Report List</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <!-- Report Review List Example -->
                                        <div class="mt-4" id="review-list">
                                            <?php if (!empty($reports)): ?>
                                                <?php foreach ($reports as $r): ?>
                                                    <div class="card shadow-sm mb-3 border-left-danger">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <h6 class="font-weight-bold text-danger mb-1">
                                                                        <i class="fas fa-user-shield mr-1"></i>
                                                                        Report by: <span class="text-dark">@<?= $r['reporterId'] ?></span>
                                                                    </h6>

                                                                    <?php if ($r['reportedUserId']): ?>
                                                                        <p class="small mb-1"><strong>Reported User:</strong> <span class="text-muted"><?= $r['reportedUserId'] ?></span></p>
                                                                    <?php endif; ?>

                                                                    <p class="small mb-1"><strong>Reason:</strong> <?= $r['reportReason'] ?></p>
                                                                    <p class="small mb-0"><strong>Date:</strong> <?= $r['reportDate'] ?></p>
                                                                </div>

                                                                <div class="text-right">
                                                                    <span class="badge badge-danger p-2"><?= $r['reportStatus'] ?></span>
                                                                </div>
                                                            </div>

                                                            <hr>

                                                            <p class="small text-muted mb-2">
                                                                <strong>Description:</strong>
                                                                <?= $r['reportDescription'] ?>
                                                            </p>

                                                            <div class="mt-3">
                                                                <button class="btn btn-sm btn-success mr-2"><i class="fas fa-check mr-1"></i>Approve Report</button>
                                                                <button class="btn btn-sm btn-secondary"  onclick="window.location.href='Delete-report.php?id=<?= $r['reportId'] ?>'">
                                                                    <i class="fas fa-times mr-1"></i>Dismiss</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-muted">No reports available.</p>
                                            <?php endif; ?>
                                        </div> <!-- review-list -->
                                    </div> <!-- chart-area -->
                                </div> <!-- card-body -->
                            </div> <!-- card -->
                        </div> <!-- col-xl-8 -->

                        <!--
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                               
                            </div>
                        </div> -->
                    </div>

                    <!-- Sample Reports Row -->
                    <div class="row">

                        <!-- Teacher Report Card -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4 border-left-danger">
                                <div class="card-header py-3 bg-light">
                                    <h6 class="m-0 font-weight-bold text-danger">
                                        <i class="fas fa-exclamation-circle mr-2"></i>Report: Teacher Profile
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-sm-8">
                                            <h5 class="font-weight-bold mb-2">Karim Messaoud</h5>
                                            <p class="text-muted small mb-1">
                                                <strong>Profile Type:</strong> <span class="badge badge-warning">Teacher</span>
                                            </p>
                                            <p class="text-muted small mb-1">
                                                <strong>Username:</strong> @MrKarim
                                            </p>
                                            <p class="text-muted small mb-1">
                                                <strong>Report Date:</strong> Nov 12, 2025
                                            </p>
                                            <p class="text-muted small">
                                                <strong>Reason:</strong> Inappropriate content in classroom materials
                                            </p>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <div class="badge badge-danger badge-lg p-3" style="font-size: 24px;">⚠</div>
                                            <p class="text-danger font-weight-bold mt-2">URGENT</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-top">
                                        <p class="small mb-2"><strong>Description:</strong></p>
                                        <p class="small text-muted">Multiple students reported offensive language and images in the lesson materials provided last week. The content violates community guidelines.</p>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-danger mr-2"><i class="fas fa-check mr-1"></i>Approve Report</button>
                                        <button class="btn btn-sm btn-secondary"><i class="fas fa-times mr-1"></i>Dismiss</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Student Report Card -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4 border-left-warning">
                                <div class="card-header py-3 bg-light">
                                    <h6 class="m-0 font-weight-bold text-warning">
                                        <i class="fas fa-flag mr-2"></i>Report: Student Profile
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-sm-8">
                                            <h5 class="font-weight-bold mb-2">Sana Ben Amor</h5>
                                            <p class="text-muted small mb-1">
                                                <strong>Profile Type:</strong> <span class="badge badge-danger">Student</span>
                                            </p>
                                            <p class="text-muted small mb-1">
                                                <strong>Username:</strong> @SanaSmile
                                            </p>
                                            <p class="text-muted small mb-1">
                                                <strong>Report Date:</strong> Nov 11, 2025
                                            </p>
                                            <p class="text-muted small">
                                                <strong>Reason:</strong> Cyberbullying in chat
                                            </p>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <div class="badge badge-warning badge-lg p-3" style="font-size: 24px;">⚠</div>
                                            <p class="text-warning font-weight-bold mt-2">MODERATE</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-top">
                                        <p class="small mb-2"><strong>Description:</strong></p>
                                        <p class="small text-muted">Peer reported harassing messages sent to multiple classmates during group project discussion. Student was warned previously about behavior.</p>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-warning mr-2"><i class="fas fa-check mr-1"></i>Approve Report</button>
                                        <button class="btn btn-sm btn-secondary"><i class="fas fa-times mr-1"></i>Dismiss</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright © Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../assets/vendor/chart.js/Chart.min.js"></script>

</body>
</html>