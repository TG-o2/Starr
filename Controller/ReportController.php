<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Model/Report.php';


class ReportController {

    public function listReports() {
        $sql = "SELECT * FROM Report";
        $db = Config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function deleteReport($reportId) {
        $sql = "DELETE FROM Report WHERE reportId = :id";
        $db = Config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $reportId);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function addReport(Report $report) {
        $sql = "INSERT INTO Report 
                (reportType, reportedUserId, reportedPostId, reportedCommentId, reportedLessonId, 
                 reportReason, reportDescription, reportDate, reporterId, reportStatus, evidencePath)
                VALUES 
                (:reportType, :reportedUserId, :reportedPostId, :reportedCommentId, :reportedLessonId,
                 :reportReason, :reportDescription, :reportDate, :reporterId, :reportStatus, :evidencePath)";

        $db = Config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'reportType'        => $report->getReportType(),
                'reportedUserId'    => $report->getReportedUserId(),
                'reportedPostId'    => $report->getReportedPostId(),
                'reportedCommentId' => $report->getReportedCommentId(),
                'reportedLessonId'  => $report->getReportedLessonId(),
                'reportReason'      => $report->getReportReason(),
                'reportDescription' => $report->getReportDescription(),
                'reportDate'        => $report->getReportDate(),
                'reporterId'        => $report->getReporterId(),
                'reportStatus'      => $report->getReportStatus(),
                'evidencePath'      => $report->getEvidencePath()
            ]);

        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateReport(Report $report, $id) {
        try {
            $db = Config::getConnexion();
            $query = $db->prepare(
                'UPDATE Report SET 
                    reportType = :reportType,
                    reportedUserId = :reportedUserId,
                    reportedPostId = :reportedPostId,
                    reportedCommentId = :reportedCommentId,
                    reportedLessonId = :reportedLessonId,
                    reportStatus = :reportStatus,
                    reportReason = :reportReason,
                    evidencePath = :evidencePath,
                    reportDescription = :reportDescription,
                    reportDate = :reportDate,
                    reporterId = :reporterId
                WHERE reportId = :id'
            );

            $query->execute([
                'id'                => $id,
                'reportType'        => $report->getReportType(),
                'reportedUserId'    => $report->getReportedUserId(),
                'reportedPostId'    => $report->getReportedPostId(),
                'reportedCommentId' => $report->getReportedCommentId(),
                'reportedLessonId'  => $report->getReportedLessonId(),
                'reportStatus'      => $report->getReportStatus(),
                'reportReason'      => $report->getReportReason(),
                'evidencePath'      => $report->getEvidencePath(),
                'reportDescription' => $report->getReportDescription(),
                'reportDate'        => $report->getReportDate(),
                'reporterId'        => $report->getReporterId()
            ]);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function showReport($id) {
        $sql = "SELECT * FROM Report WHERE reportId = :id";
        $db = Config::getConnexion();
        $query = $db->prepare($sql);

        try {
            $query->execute(['id' => $id]);
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    public function getAllReports() {
    $sql = "SELECT * FROM Report ORDER BY reportDate DESC";
    $db = Config::getConnexion();

    try {
        $query = $db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }
    }
}

?>