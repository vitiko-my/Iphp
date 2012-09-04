<?php

namespace Iphp\ContentBundle\Entity;



abstract class BaseContentLink
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var integer $pos
     */
    protected $pos;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var string $url
     */
    protected $url;

    /**
     * @var \Datetime
     */
    protected $date;


    /**
     * @var \Application\Iphp\ContentBundle\Entity\Content
     */
    protected $content;



    /**
     * Set title
     *
     * @param string $title
     * @return BaseContentFile
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Application\Iphp\ContentBundle\Entity\Content $content
     * @return BaseContentFile
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return \Application\Iphp\ContentBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param \Datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param int $pos
     */
    public function setPos($pos)
    {
        $this->pos = $pos;
        return $this;
    }

    /**
     * @return int
     */
    public function getPos()
    {
        return $this->pos;
    }


}