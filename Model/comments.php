<?php
class Comments {
    private $id;
    private $news_id;
    private $content;
    private $created_at;

    public function __construct($id = null, $news_id = null, $content = null, $created_at = null) {
        $this->id = $id;
        $this->news_id = $news_id;
        $this->content = $content;
        $this->created_at = $created_at;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getNewsId()
    {
        return $this->news_id;
    }

    public function setNewsId($news_id)
    {
        $this->news_id = $news_id;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }
}
