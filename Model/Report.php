<?php 

class Report
{

private $reportId;
private $reportType;

private $reportedUserId;
private $reportedPostId;
private $reportedCommentId;
private $reportedLessonId;

private $reportReason;
private $reportDescription;
private $reportDate;

private $reporterId;       
private $reportStatus;    
private $evidencePath;     

    public function __construct( $reportId = null, $reportType = null, $reportedUserId = null, $reportedPostId = null, $reportedCommentId = null, $reportedLessonId = null, $reportReason = null, $reportDescription = null, $reportDate = null, $reporterId = null, $reportStatus = null, $evidencePath = null)
    {
        $this->reportId = $reportId;
        $this->reportType = $reportType;
        $this->reportedUserId = $reportedUserId;
        $this->reportedPostId = $reportedPostId;
        $this->reportedCommentId = $reportedCommentId;
        $this->reportedLessonId = $reportedLessonId;
        $this->reportReason = $reportReason;
        $this->reportDescription = $reportDescription;
        $this->reportDate = $reportDate;
        $this->reporterId = $reporterId;
        $this->reportStatus = $reportStatus;
        $this->evidencePath = $evidencePath;
    }


    /**
     * Get the value of reportId
     */ 
    public function getReportId()
    {
        return $this->reportId;
    }

    /**
     * Set the value of reportId
     *
     * @return  self
     */ 
    public function setReportId($reportId)
    {
        $this->reportId = $reportId;

        return $this;
    }

    /**
     * Get the value of reportType
     */ 
    public function getReportType()
    {
        return $this->reportType;
    }

    /**
     * Set the value of reportType
     *
     * @return  self
     */ 
    public function setReportType($reportType)
    {
        $this->reportType = $reportType;

        return $this;
    }

    /**
     * Get the value of reportedUserId
     */ 
    public function getReportedUserId()
    {
        return $this->reportedUserId;
    }

    /**
     * Set the value of reportedUserId
     *
     * @return  self
     */ 
    public function setReportedUserId($reportedUserId)
    {
        $this->reportedUserId = $reportedUserId;

        return $this;
    }

    /**
     * Get the value of reportedPostId
     */ 
    public function getReportedPostId()
    {
        return $this->reportedPostId;
    }

    /**
     * Set the value of reportedPostId
     *
     * @return  self
     */ 
    public function setReportedPostId($reportedPostId)
    {
        $this->reportedPostId = $reportedPostId;

        return $this;
    }

    /**
     * Get the value of reportedCommentId
     */ 
    public function getReportedCommentId()
    {
        return $this->reportedCommentId;
    }

    /**
     * Set the value of reportedCommentId
     *
     * @return  self
     */ 
    public function setReportedCommentId($reportedCommentId)
    {
        $this->reportedCommentId = $reportedCommentId;

        return $this;
    }

    /**
     * Get the value of reportedLessonId
     */ 
    public function getReportedLessonId()
    {
        return $this->reportedLessonId;
    }

    /**
     * Set the value of reportedLessonId
     *
     * @return  self
     */ 
    public function setReportedLessonId($reportedLessonId)
    {
        $this->reportedLessonId = $reportedLessonId;

        return $this;
    }

    /**
     * Get the value of reportReason
     */ 
    public function getReportReason()
    {
        return $this->reportReason;
    }

    /**
     * Set the value of reportReason
     *
     * @return  self
     */ 
    public function setReportReason($reportReason)
    {
        $this->reportReason = $reportReason;

        return $this;
    }

    /**
     * Get the value of reportDescription
     */ 
    public function getReportDescription()
    {
        return $this->reportDescription;
    }

    /**
     * Set the value of reportDescription
     *
     * @return  self
     */ 
    public function setReportDescription($reportDescription)
    {
        $this->reportDescription = $reportDescription;

        return $this;
    }

    /**
     * Get the value of reportDate
     */ 
    public function getReportDate()
    {
        return $this->reportDate;
    }

    /**
     * Set the value of reportDate
     *
     * @return  self
     */ 
    public function setReportDate($reportDate)
    {
        $this->reportDate = $reportDate;

        return $this;
    }

/**
 * Get the value of reporterId
 */ 
public function getReporterId()
{
return $this->reporterId;
}

/**
 * Set the value of reporterId
 *
 * @return  self
 */ 
public function setReporterId($reporterId)
{
$this->reporterId = $reporterId;

return $this;
}

/**
 * Get the value of reportStatus
 */ 
public function getReportStatus()
{
return $this->reportStatus;
}

/**
 * Set the value of reportStatus
 *
 * @return  self
 */ 
public function setReportStatus($reportStatus)
{
$this->reportStatus = $reportStatus;

return $this;
}

/**
 * Get the value of evidencePath
 */ 
public function getEvidencePath()
{
return $this->evidencePath;
}

/**
 * Set the value of evidencePath
 *
 * @return  self
 */ 
public function setEvidencePath($evidencePath)
{
$this->evidencePath = $evidencePath;

return $this;
}
}   



?>