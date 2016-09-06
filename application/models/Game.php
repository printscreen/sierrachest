<?php

class Model_Game extends Model_Base_Db
{
    protected $_gameId;
    protected $_slug;
    protected $_title;
    protected $_description;
    protected $_coverArt;
    protected $_releaseDate;
    protected $_systemRequirements;
    protected $_esrbId;
    protected $_banner;
    protected $_gogLink;
    protected $_ebayLink;
    protected $_completionDate;
    protected $_insertTs;
    protected $_updateTs;
    protected $_total;

    public function __construct(array $options = array())
    {
        $settings = array_merge(array(
            'gameId' => null,
            'slug' => null,
            'title' => null,
            'description' => null,
            'coverArt' => null,
            'releaseDate' => null,
            'systemRequirements' => null,
            'esrbId' => null,
            'banner' => null,
            'gogLink' => null,
            'ebayLink' => null,
            'completionDate' => null,
            'db' => null,
            ), $options);

        parent::__construct($settings['db']);
        $this->_gameId = $settings['gameId'];
        $this->_slug = $settings['slug'];
        $this->_title = $settings['title'];
        $this->_description = $settings['description'];
        $this->_coverArt = $settings['coverArt'];
        $this->_releaseDate = $settings['releaseDate'];
        $this->_systemRequirements = $settings['systemRequirements'];
        $this->_esrbId = $settings['esrbId'];
        $this->_banner = $settings['banner'];
        $this->_gogLink = $settings['gogLink'];
        $this->_ebayLink = $settings['ebayLink'];
        $this->_completionDate = $settings['completionDate'];
    }

    public function loadRecord($record)
    {
        $this->_gameId = $record->game_id;
        $this->_slug = $record->slug;
        $this->_title = $record->title;
        $this->_description = $record->description;
        $this->_coverArt = $record->cover_art;
        $this->_releaseDate = $record->release_date;
        $this->_systemRequirements = $record->system_requirements;
        $this->_esrbId = $record->esrb_id;
        $this->_banner = $record->banner;
        $this->_gogLink = $record->gog_link;
        $this->_ebayLink = $record->ebay_link;
        $this->_completionDate = $record->completion_date;
        $this->_insertTs = $record->insert_ts;
        $this->_updateTs = $record->update_ts;
        $this->_total = $record->total;
    }

    public function load()
    {
        $where = 'WHERE true';
        $binds = array();
        if(!empty($this->_gameId) && is_numeric($this->_gameId)) {
            $where .= ' AND g.game_id = :gameId';
            $binds[':gameId'] = array('value' => $this->_gameId, 'type' => PDO::PARAM_INT);
        } else if ($this->_title != '') {
            $where .= ' AND g.title = :title';
            $binds[':title'] = array('value' => $this->_title, 'type' => PDO::PARAM_STR);
        } else if ($this->_slug != '') {
            $where .= ' AND g.slug = :slug';
            $binds[':slug'] = array('value' => $this->_slug, 'type' => PDO::PARAM_STR);
        } else {
            throw new Zend_Exception("No game id supplied");
        }

        $sql = "
            SELECT
                g.game_id
              , g.slug
              , g.title
              , g.description
              , g.cover_art
              , g.release_date
              , g.system_requirements
              , g.esrb_id
              , g.banner
              , g.gog_link
              , g.ebay_link
              , g.completion_date
              , g.insert_ts
              , g.update_ts
              , 1 AS total
            FROM game g
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
    public function setCoverArt($coverArt){$this->_coverArt = $coverArt; return $this;}
    public function setReleaseDate($releaseDate){$this->_releaseDate = $releaseDate; return $this;}
    public function setSystemRequirements($systemRequirements){$this->_systemRequirements = $systemRequirements; return $this;}
    public function setEsrbId($esrbId){$this->_esrbId = $esrbId; return $this;}
    public function setBanner($banner){$this->_banner = $banner; return $this;}
    public function setGogLink($gogLink){$this->_gogLink = $gogLink; return $this;}
    public function setEbayLink($ebayLink){$this->_ebayLink = $ebayLink; return $this;}
    public function setCompletionDate($completionDate){$this->_completionDate = $completionDate; return $this;}

    //Getters
    public function getGameId(){return $this->_gameId;}
    public function getSlug(){return $this->_slug;}
    public function getTitle(){return $this->_title;}
    public function getDescription(){$this->_description;}
    public function getCoverArt(){return Zend_Registry::get(APPLICATION_URL) . $this->_coverArt;}
    public function getReleaseDate(){return $this->_releaseDate;}
    public function getSystemRequirements(){return $this->_systemRequirements;}
    public function getEsrbId(){return $this->_esrbId;}
    public function getBanner(){return $this->_banner;}
    public function getGogLink(){return $this->_gogLink;}
    public function getEbayLink(){return $this->_ebayLink;}
    public function getCompletionDate(){return $this->_completionDate;}
    public function getInsertTs(){return $this->_insertTs;}
    public function getUpdateTs(){return $this->_updateTs;}
    public function getTotal(){return $this->_total;}
}