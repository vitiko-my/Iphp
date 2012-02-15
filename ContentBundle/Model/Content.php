<?php



namespace Iphp\ContentBundle\Model;



abstract class Content implements ContentInterface
{
    protected $title;

    protected $slug;

    protected $abstract;

    protected $content;

    protected $rawContent;

    protected $contentFormatter;

   /* protected $tags;

    protected $comments;*/

    protected $enabled;

    protected $publicationDateStart;

    protected $createdAt;

    protected $updatedAt;

    protected $commentsEnabled = true;

    protected $commentsCloseAt;

    protected $commentsDefaultStatus;

    protected $author;

    protected $images;


    protected $rubric;

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

       $this->setSlug(self::slugify($title));
    }


     /**
     * source : http://snipplr.com/view/22741/slugify-a-string-in-php/
     *
     * @static
     * @param  $text
     * @return mixed|string
     */
    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);


        $iso = array(
   "Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
   "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
   "Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
   "З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
   "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
   "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"H",
   "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
   "Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
   "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
   "е"=>"e","ё"=>"yo","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
   "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
   "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
   "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya","«"=>"","»"=>"","—"=>"-"
  );
        $text = strtr(   $text, $iso);
        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }


    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set abstract
     *
     * @param text $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }

    /**
     * Get abstract
     *
     * @return text $abstract
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set slug
     *
     * @param integer $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return integer $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set publication_date_start
     *
     * @param \DateTime $publicationDateStart
     */
    public function setPublicationDateStart(\DateTime $publicationDateStart = null)
    {
        $this->publicationDateStart = $publicationDateStart;
    }

    /**
     * Get publication_date_start
     *
     * @return \DateTime $publicationDateStart
     */
    public function getPublicationDateStart()
    {
        return $this->publicationDateStart;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return datetime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    public function prePersist()
    {
        if (!$this->getPublicationDateStart()) {
            $this->setPublicationDateStart(new \DateTime);
        }

        $this->setCreatedAt(new \DateTime);
        $this->setUpdatedAt(new \DateTime);
    }

    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime);
    }

    public function getYear()
    {
        return $this->getCreatedAt()->format('Y');
    }

    public function getMonth()
    {
        return $this->getCreatedAt()->format('m');
    }

    public function getDay()
    {
        return $this->getCreatedAt()->format('d');
    }
    /**
     * Set comments_enabled
     *
     * @param boolean $commentsEnabled
     */
    public function setCommentsEnabled($commentsEnabled)
    {
        $this->commentsEnabled = $commentsEnabled;
    }

    /**
     * Get comments_enabled
     *
     * @return boolean $commentsEnabled
     */
    public function getCommentsEnabled()
    {
        return $this->commentsEnabled;
    }

    /**
     * Set comments_close_at
     *
     * @param \DateTime|null $commentsCloseAt
     */
    public function setCommentsCloseAt(\DateTime $commentsCloseAt = null)
    {
        $this->commentsCloseAt = $commentsCloseAt;
    }

    /**
     * Get comments_close_at
     *
     * @return datetime $commentsCloseAt
     */
    public function getCommentsCloseAt()
    {
        return $this->commentsCloseAt;
    }

    /**
     * Set comments_default_status
     *
     * @param integer $commentsDefaultStatus
     */
    public function setCommentsDefaultStatus($commentsDefaultStatus)
    {
        $this->commentsDefaultStatus = $commentsDefaultStatus;
    }

    /**
     * Get comments_default_status
     *
     * @return integer $commentsDefaultStatus
     */
    public function getCommentsDefaultStatus()
    {
        return $this->commentsDefaultStatus;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return bool
     */
    public function isCommentable()
    {
        if (!$this->getCommentsEnabled() || !$this->getEnabled()) {
            return false;
        }

        if ($this->getCommentsCloseAt() instanceof \DateTime) {
            return $this->getCommentsCloseAt()->diff(new \DateTime)->invert == 1 ? true : false;
        }

        return true;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setContentFormatter($contentFormatter)
    {
        $this->contentFormatter = $contentFormatter;
    }

    public function getContentFormatter()
    {
        return $this->contentFormatter;
    }

    public function setRawContent($rawContent)
    {
        $this->rawContent = $rawContent;
    }

    public function getRawContent()
    {
        return $this->rawContent;
    }

    public function setImages($images)
    {
        $this->images = $images;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setRubric($rubric)
    {
        $this->rubric = $rubric;
    }

    public function getRubric()
    {
        return $this->rubric;
    }
}
