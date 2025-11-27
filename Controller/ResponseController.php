<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Model/Report.php';
require_once __DIR__ . '/../Model/Response.php';

class ResponseController {

    public function listResponses() {
        $sql = "SELECT * FROM `responses`";
        $db = Config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list->fetchAll();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    /* ---------------------------------------------------
       ADD RESPONSE (NOW INCLUDES allow_user_reply)
       --------------------------------------------------- */
    public function addResponse(Response $response) {

        $sql = "INSERT INTO `responses`
                (reportID, reporter_id, response_text, response_date, report_status, action_taken, allow_user_reply)
                VALUES 
                (:reportID, :reporter_id, :response_text, :response_date, :report_status, :action_taken, :allow_user_reply)";

        $db = Config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'reportID'         => $response->getReportId(),
                'reporter_id'      => $response->getResponderId(),
                'response_text'    => $response->getResponseText(),
                'response_date'    => $response->getResponseDate(),
                'report_status'    => $response->getStatus(),
                'action_taken'     => $response->getActionTaken(),
                'allow_user_reply' => $response->getAllowUserReply()
            ]);

            return $db->lastInsertId();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }
    /* ---------------------------------------------------
   UPDATE PICKED UP RESPONSE OR CREATE NEW
   --------------------------------------------------- */
public function updatePickedUpResponse($reportId, $actionTaken, $status, $allowUserReply, $messageText, $adminId) {
    // Get all responses for this report
    $responses = $this->getResponsesByReportId($reportId);

    // Find the "Picked Up" response
    $pickedUpResponse = null;
    foreach ($responses as $resp) {
        if ($resp->getActionTaken() === 'Picked Up') {
            $pickedUpResponse = $resp;
            break;
        }
    }

    if ($pickedUpResponse) {
        // Update the existing response
        $pickedUpResponse->setActionTaken($actionTaken);
        $pickedUpResponse->setStatus($status);
        $pickedUpResponse->setAllowUserReply($allowUserReply);
        $pickedUpResponse->setResponseText($messageText);

        $this->updateResponse($pickedUpResponse, $pickedUpResponse->getResponseId());
    } else {
        // Create a new response if none exists
        $newResponse = new Response();
        $newResponse->setReportId($reportId);
        $newResponse->setResponderId($adminId);
        $newResponse->setResponseDate(date('Y-m-d H:i:s'));
        $newResponse->setActionTaken($actionTaken);
        $newResponse->setStatus($status);
        $newResponse->setAllowUserReply($allowUserReply);
        $newResponse->setResponseText($messageText);

        $this->addResponse($newResponse);
    }
    }


    /* ---------------------------------------------------
       DELETE RESPONSE
       --------------------------------------------------- */
    public function deleteResponse($responseId) {
        $sql = "DELETE FROM `responses` WHERE responseId = :id";
        $db = Config::getConnexion();
        try {
            $req = $db->prepare($sql);
            $req->bindValue(':id', $responseId);
            $req->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    /* ---------------------------------------------------
       UPDATE RESPONSE (NOW UPDATES allow_user_reply)
       --------------------------------------------------- */
    public function updateResponse(Response $response, $id) {
        $sql = "UPDATE `responses` SET 
                    reportID = :reportID,
                    reporter_id = :reporter_id,
                    response_text = :response_text,
                    response_date = :response_date,
                    report_status = :report_status,
                    action_taken = :action_taken,
                    allow_user_reply = :allow_user_reply
                WHERE responseId = :id";

        try {
            $db = Config::getConnexion();
            $query = $db->prepare($sql);

            $query->execute([
                'reportID'         => $response->getReportId(),
                'reporter_id'      => $response->getResponderId(),
                'response_text'    => $response->getResponseText(),
                'response_date'    => $response->getResponseDate(),
                'report_status'    => $response->getStatus(),
                'action_taken'     => $response->getActionTaken(),
                'allow_user_reply' => $response->getAllowUserReply(),
                'id'               => $id
            ]);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    /* ---------------------------------------------------
       GET RESPONSES BY REPORTER ID
       --------------------------------------------------- */
    public function getResponsesByReporter($reporterId) {
        try {
            $db = Config::getConnexion();
            $query = $db->prepare("
                SELECT r.* 
                FROM responses r
                INNER JOIN report rp ON r.reportID = rp.reportID
                WHERE rp.reporterId = :id
                ORDER BY r.response_date DESC
            ");
            $query->execute(['id' => $reporterId]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }


    /* ---------------------------------------------------
       GET RESPONSES BY REPORT ID
       (NOW LOADS allow_user_reply)
       --------------------------------------------------- */
    public function getResponsesByReportId($reportId)
    {
        try {
            $db = Config::getConnexion();

            $query = $db->prepare("SELECT * FROM responses WHERE reportID = :id ORDER BY response_date ASC");
            $query->execute(['id' => $reportId]);

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            $responses = [];

            foreach ($rows as $row) {

                $response = new Response();

                $response->setResponseId($row['responseID']);
                $response->setReportId($row['reportID']);
                $response->setResponderId($row['reporter_id']);
                $response->setResponseText($row['response_text']);
                $response->setResponseDate($row['response_date']);
                $response->setStatus($row['report_status']);
                $response->setActionTaken($row['action_taken']);
                $response->setAllowUserReply($row['allow_user_reply']);  

                $responses[] = $response;
            }

            return $responses;

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }


    /* ---------------------------------------------------
       createResponseFromData SUPPORTS allow_user_reply
       --------------------------------------------------- */
    public function createResponseFromData($data)
    {
        $response = new Response();
        $response->setReportId($data['reportID']);
        $response->setResponderId($data['reporter_id']);
        $response->setResponseText($data['response_text']);
        $response->setResponseDate($data['response_date']);
        $response->setStatus($data['report_status']);
        $response->setActionTaken($data['action_taken']);
        $response->setAllowUserReply($data['allow_user_reply'] ?? 0); // NEW

        return $this->addResponse($response);
    }

   

}
