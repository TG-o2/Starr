<?php
class News {
    private $newsid;
    private $title;
    private $content;
    private $published_date;
    private $updated_date;
    private $status;
    private $teacherid;
    private $category;
    private $image;

    public function __construct($title = null, $content = null, $published_date = null, $updated_date = null, $status = 'draft', $teacherid = null, $category = null, $image = null) {
        $this->title = $title;
        $this->content = $content;
        $this->published_date = $published_date;
        $this->updated_date = $updated_date;
        $this->status = $status;
        $this->teacherid = $teacherid;
        $this->category = $category;
        $this->image = $image;
    }

    /**
     * Get the value of newsid
     */ 
    public function getNewsid()
    {
        return $this->newsid;
    }

    /**
     * Set the value of newsid
     *
     * @return  self
     */ 
    public function setNewsid($newsid)
    {
        $this->newsid = $newsid;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of published_date
     */ 
    public function getPublished_date()
    {
        return $this->published_date;
    }

    /**
     * Set the value of published_date
     *
     * @return  self
     */ 
    public function setPublished_date($published_date)
    {
        $this->published_date = $published_date;

        return $this;
    }

    /**
     * Get the value of updated_date
     */ 
    public function getUpdated_date()
    {
        return $this->updated_date;
    }

    /**
     * Set the value of updated_date
     *
     * @return  self
     */ 
    public function setUpdated_date($updated_date)
    {
        $this->updated_date = $updated_date;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of teacherid
     */ 
    public function getTeacherid()
    {
        return $this->teacherid;
    }

    /**
     * Set the value of teacherid
     *
     * @return  self
     */ 
    public function setTeacherid($teacherid)
    {
        $this->teacherid = $teacherid;

        return $this;
    }

    /**
     * Get the value of category
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}