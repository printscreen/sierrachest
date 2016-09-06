<?php

class Model_News extends Model_Base_Db
{
    protected $_newsId;
    protected $_date;
    protected $_title;
    protected $_content;
    protected $_blurb;
    protected $_image;
    protected $_externalUrl;
    protected $_active;
    protected $_total;

    public function __construct(array $options = array())
    {
        $settings = array_merge(array(
            'newsId' => null,
            'date' => null,
            'title' => null,
            'content' => null,
            'blurb' => null,
            'image' => null,
            'externalUrl' => null,
            'active' => null,
            'db' => null,
            ), $options);

        parent::__construct($settings['db']);
        $this->_newsId = $settings['newsId'];
        $this->_date = $settings['date'];
        $this->_title = $settings['title'];
        $this->_content = $settings['content'];
        $this->_blurb = $settings['blurb'];
        $this->_image = $settings['image'];
        $this->_externalUrl = $settings['externalUrl'];
        $this->_active = $settings['active'];
    }
    public function loadRecord($record)
    {
        $this->_newsId = $record->news_id;
        $this->_date = $record->date;
        $this->_title = $record->title;
        $this->_content = $record->content;
        $this->_blurb = $record->blurb;
        $this->_image = $record->image;
        $this->_externalUrl = $record->external_url;
        $this->_active = $record->active;
        $this->_total = $record->total;
    }

    public function load()
    {
        $where = 'WHERE true';
        $binds = array();
        if(!empty($this->_newsId) && is_numeric($this->_newsId)) {
            $where .= ' AND n.news_id = :newsId';
            $binds[':newsId'] = array('value' => $this->_newsId, 'type' => PDO::PARAM_INT);
        } else {
            throw new Zend_Exception("No news id supplied");
        }

        $sql = "
            SELECT
                n.news_id       AS news_id
              , n.date          AS date
              , n.title         AS title
              , n.content       AS content
              , n.blurb         AS blurb
              , n.image         AS image
              , n.external_url  AS external_url
              , n.active        AS active
              , 1               AS total
            FROM news n
             $where LIMIT 1
        ";

        $query = $this->_db->prepare($sql);
        $this->bind($query, $binds);

        $query->execute();
        $result = $query->fetchAll();
        if(!$result || count($result) != 1) {
            return false;
        }

        $this->loadRecord($result[0]);
        return true;
    }

    //Setters
    public function setNewsId($newsId){$this->_newsId = $newsId; return $this;}
    public function setDate($date){$this->_date = $date; return $this;}
    public function setTitle($title){$this->_title = $title; return $this;}
    public function setContent($content){$this->_content = $content; return $this;}
    public function setBlurb($blurb){$this->_blurb = $blurb; return $this;}
    public function setImage($image){$this->_image = $image; return $this;}
    public function setExternalUrl($externalUrl){$this->_externalUrl = $externalUrl; return $this;}
    public function setActive($active){$this->_active = $active; return $this;}

    //Getters
    public function getNewsId(){return $this->_newsId;}
    public function getDate(){return $this->_date;}
    public function getTitle(){return $this->_title;}
    public function getContent(){return $this->_content;}
    public function getBlurb(){return $this->_blurb;}
    public function getImage(){return Zend_Registry::get(APPLICATION_URL) . $this->_image;}
    public function getExternalUrl(){return $this->_externalUrl;}
    public function getActive(){return $this->_active;}
    public function getTotal(){return $this->_total;}
}