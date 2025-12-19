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
            (reportId, responderId, responseText, responseDate, status, actionTaken, allowUserReply)
            VALUES 
            (:reportId, :responderId, :responseText, :responseDate, :status, :actionTaken, :allowUserReply)";

        $db = Config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'reportId'         => $response->getReportId(),
                'responderId'      => $response->getResponderId(),
                'responseText'     => $response->getResponseText(),
                'responseDate'     => $response->getResponseDate(),
                'status'           => $response->getStatus(),
                'actionTaken'      => $response->getActionTaken(),
                'allowUserReply'   => $response->getAllowUserReply()
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
                    reportId = :reportId,
                    responderId = :responderId,
                    responseText = :responseText,
                    responseDate = :responseDate,
                    status = :status,
                    actionTaken = :actionTaken,
                    allowUserReply = :allowUserReply
                WHERE responseId = :id";

        try {
            $db = Config::getConnexion();
            $query = $db->prepare($sql);

            $query->execute([
                'reportId'         => $response->getReportId(),
                'responderId'      => $response->getResponderId(),
                'responseText'     => $response->getResponseText(),
                'responseDate'     => $response->getResponseDate(),
                'status'           => $response->getStatus(),
                'actionTaken'      => $response->getActionTaken(),
                'allowUserReply'   => $response->getAllowUserReply(),
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
                INNER JOIN report rp ON r.reportId = rp.reportId
                WHERE rp.reporterId = :id
                ORDER BY r.responseDate DESC
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

            $query = $db->prepare("SELECT * FROM responses WHERE reportId = :id ORDER BY responseDate ASC");
            $query->execute(['id' => $reportId]);

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            $responses = [];

            foreach ($rows as $row) {

                $response = new Response();

                // Support multiple possible column namings (responseId / responseID)
                $responseId = $row['responseId'] ?? $row['responseID'] ?? $row['id'] ?? null;
                $response->setResponseId($responseId);

                $response->setReportId($row['reportId'] ?? $row['reportID'] ?? null);

                // responderId is the column we expect; fall back to reporterId if present
                $responderId = $row['responderId'] ?? $row['reporterId'] ?? $row['responderId'] ?? null;
                $response->setResponderId($responderId);

                $response->setResponseText($row['responseText'] ?? $row['responseText'] ?? '');
                $response->setResponseDate($row['responseDate'] ?? $row['responseDate'] ?? '');
                $response->setStatus($row['status'] ?? $row['reportStatus'] ?? '');
                $response->setActionTaken($row['actionTaken'] ?? $row['actionTaken'] ?? '');
                $response->setAllowUserReply($row['allowUserReply'] ?? $row['allowUserReply'] ?? 0);  

                $responses[] = $response;
            }

            return $responses;

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }


    /* ---------------------------------------------------
       createResponseFromData 
       --------------------------------------------------- */
    public function createResponseFromData($data)
    {
        $response = new Response();
        $response->setReportId($data['reportId'] ?? $data['reportID'] ?? null);
        $response->setResponderId($data['responderId'] ?? $data['reporterId'] ?? $data['responderId'] ?? null);
        $response->setResponseText($data['responseText']);
        $response->setResponseDate($data['responseDate']);
        $response->setStatus($data['status'] ?? $data['reportStatus'] ?? null);
        $response->setActionTaken($data['actionTaken']);
        $response->setAllowUserReply($data['allowUserReply'] ?? 0); // NEW

        return $this->addResponse($response);
    }

    


}
