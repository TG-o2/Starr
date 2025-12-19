<?php
class Response {

    private $responseId;
    private $reportId;
    private $responderId;
    private $responseText;
    private $responseDate;
    private $status;
    private $actionTaken;
    private $allowUserReply;


    public function __construct(
        $responseId = null,
        $reportId = null,
        $responderId = null,
        $responseText = "",
        $responseDate = "",
        $status = "unread",
        $actionTaken = "",
        $allowUserReply = 0   // <-- NEW (default 0)
    ) {
        $this->responseId = $responseId;
        $this->reportId = $reportId;
        $this->responderId = $responderId;
        $this->responseText = $responseText;
        $this->responseDate = $responseDate;
        $this->status = $status;
        $this->actionTaken = $actionTaken;
        $this->allowUserReply = $allowUserReply; // NEW
    }

    public function getResponseId() {
        return $this->responseId;
    }

    public function getReportId() {
        return $this->reportId;
    }

    public function getResponderId() {
        return $this->responderId;
    }

    public function getResponseText() {
        return $this->responseText;
    }

    public function getResponseDate() {
        return $this->responseDate;
    }

    public function setResponseId($responseId) {
        $this->responseId = $responseId;
    }

    public function setReportId($reportId) {
        $this->reportId = $reportId;
    }

    public function setResponderId($responderId) {
        $this->responderId = $responderId;
    }

    public function setResponseText($responseText) {
        $this->responseText = $responseText;
    }

    public function setResponseDate($responseDate) {
        $this->responseDate = $responseDate;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function getActionTaken() {
        return $this->actionTaken;
    }

    public function setActionTaken($actionTaken) {
        $this->actionTaken = $actionTaken;
        return $this;
    }

    // NEW GETTER
    public function getAllowUserReply() {
        return $this->allowUserReply;
    }

    // NEW SETTER
    public function setAllowUserReply($allowUserReply) {
        $this->allowUserReply = $allowUserReply;
        return $this;
    }
}
