<?php

class Model_Game extends Model_Base_Db
{
    protected $_gameId;
    protected $_title;
    protected $_description;
    protected $_image;
    protected $_releaseDate;
    protected $_techSupport;
    protected $_ersbId;
    protected $_banner;
    protected $_gogLink;
    protected $_total;

    public function __construct(array $options = array())
    {
        $settings = array_merge(array(
            'gameId' => null,
            'title' => null,
            'description' => null,
            'image' => null,
            'releaseDate' => null,
            'techSupport' => null,
            'ersbId' => null,
            'banner' => null,
            'gogLink' => null,
            'db' => null,
            ), $options);

        parent::__construct($settings['db']);
        $this->_gameId = $settings['gameId'];
        $this->_title = $settings['title'];
        $this->_description = $settings['description'];
        $this->_image = $settings['image'];
        $this->_releaseDate = $settings['releaseDate'];
        $this->_techSupport = $settings['techSupport'];
        $this->_ersbId = $settings['ersbId'];
        $this->_banner = $settings['banner'];
        $this->_gogLink = $settings['gogLink'];
    }

    public function loadRecord($record)
    {
        $this->_gameId = $record->id;
        $this->_title = $record->title;
        $this->_description = $record->description;
        $this->_image = $record->image;
        $this->_releaseDate = $record->release_date;
        $this->_techSupport = $record->tech_support;
        $this->_ersbId = $record->ERSB_id;
        $this->_banner = $record->banner;
        $this->_gogLink = $record->gog_link;
        $this->_total = $record->total;
    }

    public function load()
    {
        $where = 'WHERE true';
        $binds = array();
        if(!empty($this->_gameId) && is_numeric($this->_gameId)) {
            $where .= ' AND g.id = :gameId';
            $binds[':gameId'] = array('value' => $this->_gameId, 'type' => PDO::PARAM_INT);
        } else if ($this->_title != '') {
            $where .= ' AND g.title = :title';
            $binds[':title'] = array('value' => $this->_title, 'type' => PDO::PARAM_STR);
        } else {
            throw new Zend_Exception("No game id supplied");
        }

        $sql = "
            SELECT
                g.id
              , g.title
              , g.description
              , g.image
              , g.release_date
              , g.tech_support
              , g.ERSB_id
              , g.banner
              , g.gog_link
              , 1 AS total
            FROM item_image ii
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
    public function setGameId($gameId){$this->_gameId = $gameId; return $this;}
    public function setTitle($title){$this->_title = $title; return $this;}
    public function setDescription($description){$this->_description = $description; return $this;}
    public function setImage($image){$this->_image = $image; return $this;}
    public function setReleaseDate($releaseDate){$this->_releaseDate = $releaseDate; return $this;}
    public function setTechSupport($techSupport){$this->_techSupport = $techSupport; return $this;}
    public function setErsbId($ersbId){$this->_ersbId = $ersbId; return $this;}
    public function setBanner($banner){$this->_banner = $banner; return $this;}
    public function setGogLink($gogLink){$this->_gogLink = $gogLink; return $this;}

    //Getters
    public function getItemImageId(){return $this->_itemImageId;}
    public function getItemId(){return $this->_itemId;}
    public function getUserId(){return $this->_userId;}
    public function getLat(){return $this->_lat;}
    public function getLon(){return $this->_lon;}
    public function getInsertTs(){return $this->_insertTs;}
    public function getDefaultImage(){return (bool)$this->_defaultImage;}
    public function getThumbnail(){return $this->_thumbnail;}
    public function getTotal(){return $this->_total;}
}