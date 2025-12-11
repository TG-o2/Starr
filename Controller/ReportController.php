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
                 reportReason, reportDescription, reportDate, reporterId, reportStatus, evidencePath, severity)
                VALUES 
                (:reportType, :reportedUserId, :reportedPostId, :reportedCommentId, :reportedLessonId,
                 :reportReason, :reportDescription, :reportDate, :reporterId, :reportStatus, :evidencePath, :severity)";

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
                'evidencePath'      => $report->getEvidencePath(),
                'severity'          => $report->getSeverity()
            ]);
             return $db->lastInsertId();
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
                    reporterId = :reporterId,
                    severity = :severity
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
                'reporterId'        => $report->getReporterId(),
                'severity'          => $report->getSeverity()
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

    public function getReportById($id) {
        try {
            $db = Config::getConnexion();
            $query = $db->prepare("SELECT * FROM Report WHERE reportId = :id");
            $query->execute(['id' => $id]);
            $data = $query->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $report = new Report();
                $report->setReportId($data['reportId']);
                $report->setReportType($data['reportType']);
                $report->setReportedUserId($data['reportedUserId']);
                $report->setReportedPostId($data['reportedPostId']);
                $report->setReportedCommentId($data['reportedCommentId']);
                $report->setReportedLessonId($data['reportedLessonId']);
                $report->setReportStatus($data['reportStatus']);
                $report->setReportReason($data['reportReason']);
                $report->setEvidencePath($data['evidencePath']);
                $report->setReportDescription($data['reportDescription']);
                $report->setReportDate($data['reportDate']);
                $report->setReporterId($data['reporterId']);
                $report->setSeverity($data['severity']);

                return $report;
            } else {
                return null; // Report not found
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function getReportsByReporter($userId) {
        try {
            $db = Config::getConnexion();
            $query = $db->prepare("SELECT * FROM Report WHERE reporterId = :userId ORDER BY reportDate DESC");
            $query->execute(['userId' => $userId]);
            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            $reports = [];

            foreach ($results as $row) {
                $report = new Report();
                $report->setReportId($row['reportId']);
                $report->setReporterId($row['reporterId']);
                $report->setReportType($row['reportType']);
                $report->setReportReason($row['reportReason']);
                $report->setReportStatus($row['reportStatus']);
                $report->setReportDescription($row['reportDescription']);
                $report->setReportedUserId($row['reportedUserId']);
                $report->setReportedPostId($row['reportedPostId']);
                $report->setReportedCommentId($row['reportedCommentId']);
                $report->setReportedLessonId($row['reportedLessonId']);
                $report->setReportDate($row['reportDate']);
                $report->setEvidencePath($row['evidencePath']);
                $report->setSeverity($row['severity']);

                $reports[] = $report;
            }

            return $reports;

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function updateReportStatus($reportId, $status) {
        $db = Config::getConnexion();
        $sql = "UPDATE Report SET reportStatus = :status WHERE reportId = :id";
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'status' => $status,
                'id' => $reportId
            ]);
        } catch (PDOException $e) {
            die("Error updating report status: " . $e->getMessage());
        }
    }

    /* ---------------------------------------------------
       Filter Reports
       --------------------------------------------------- */
    public function filterReports($search = null, $status = null, $reporter = null, $severity = null) {
        $db = Config::getConnexion();

        $sql = "SELECT * FROM Report WHERE 1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (
                reportReason LIKE :search 
                OR reportDescription LIKE :search 
                OR reporterId LIKE :search
                OR reportedUserId LIKE :search
                OR CAST(reportId AS CHAR) LIKE :search";

            // If the search looks numeric, also allow exact match on reportId
            if (ctype_digit(trim($search))) {
                $sql .= " OR reportId = :reportIdExact";
                $params[':reportIdExact'] = (int)trim($search);
            }

            $sql .= ")";
            $params[':search'] = "%$search%";
        }

        if (!empty($status)) {
            $sql .= " AND reportStatus = :status";
            $params[':status'] = $status;
        }

        if (!empty($reporter)) {
            $sql .= " AND reporterId = :reporter";
            $params[':reporter'] = $reporter;
        }

        if (!empty($severity)) {
            $sql .= " AND severity = :severity";
            $params[':severity'] = $severity;
        }

        $sql .= " ORDER BY reportDate DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countReportsForEntity($userId, $postId, $commentId, $lessonId, $type) {
        $field = null;
        $value = null;

        switch ($type) {
            case "user":
                $field = "reportedUserId";
                $value = $userId;
                break;
            case "post":
                $field = "reportedPostId";
                $value = $postId;
                break;
            case "comment":
                $field = "reportedCommentId";
                $value = $commentId;
                break;
            case "lesson":
                $field = "reportedLessonId";
                $value = $lessonId;
                break;
        }

        if (!$field || !$value) return 0;

        $db = Config::getConnexion();
        $sql = "SELECT COUNT(*) FROM Report WHERE $field = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$value]);

        return $stmt->fetchColumn();
    }
    public function updateReportSeverity($reportId, $severity) {
    $db = Config::getConnexion();
    $sql = "UPDATE Report SET severity = :severity WHERE reportId = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':severity' => $severity,
        ':id' => $reportId
    ]);
}

}
?>
