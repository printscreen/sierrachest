<?php

class Model_ScreenShot extends Model_Base_Db
{
    protected $_screenShotId;
    protected $_gameId;
    protected $_gameName;
    protected $_date;
    protected $_description;
    protected $_image;
    protected $_thumbnail;
    protected $_total;

    public function __construct(array $options = array())
    {
        $settings = array_merge(array(
            'screenShotId' => null,
            'gameId' => null,
            'date' => null,
            'description' => null,
            'image' => null,
            'thumbnail' => null,
            'db' => null,
            ), $options);

        parent::__construct($settings['db']);
        $this->_screenShotId = $settings['screenShotId'];
        $this->_gameId = $settings['gameId'];
        $this->_date = $settings['date'];
        $this->_description = $settings['description'];
        $this->_image = $settings['image'];
        $this->_thumbnail = $settings['thumbnail'];
    }

    public function loadRecord($record)
    {
        $this->_screenShotId = $record->screen_shot_id;
        $this->_gameId = $record->game_id;
        $this->_gameName = $record->game_name;
        $this->_date = $record->date;
        $this->_description = $record->description;
        $this->_image = $record->image;
        $this->_thumbnail = $record->thumbnail;
        $this->_total = $record->total;
    }

    public function load()
    {
        $where = 'WHERE true';
        $binds = array();
        if(!empty($this->_screenShotId) && is_numeric($this->_screenShotId)) {
            $where .= ' AND s.screen_shot_id = :screenShotId';
            $binds[':screenShotId'] = array('value' => $this->_screenShotId, 'type' => PDO::PARAM_INT);
        } else {
            throw new Zend_Exception("No screen shot id supplied");
        }

        $sql = "
            SELECT
                s.screen_shot_id    AS screen_shot_id
              , s.game_id           AS game_id
              , g.title             AS game_name
              , s.date              AS date
              , s.description       AS description
              , s.image             AS image
              , s.thumbnail         AS thumbnail
              , 1                   AS total
            FROM screenshot s
            INNER JOIN game g ON s.game_id = g.game_id
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
    public function setScreenShotId($screenShotId){$this->_screenShotId = $screenShotId; return $this;}
    public function setGameId($gameId){$this->_gameId = $gameId; return $this;}
    public function setDate($date){$this->_date = $date; return $this;}
    public function setDescription($description){$this->_description = $description; return $this;}
    public function setImage($image){$this->_image = $image; return $this;}
    public function setThumbnail($thumbnail){$this->_thumbnail = $thumbnail; return $this;}

    //Getters
    public function getScreenShotId(){return $this->_screenShotId;}
    public function getGameId(){return $this->_gameId;}
    public function getGameName(){return $this->_gameName;}
    public function getDate(){return $this->_date;}
    public function getDescription(){return $this->_description;}
    public function getImage(){return Zend_Registry::get(APPLICATION_URL) . $this->_image;}
    public function getThumbnail(){return Zend_Registry::get(APPLICATION_URL) . $this->_thumbnail;}
    public function getTotal(){return $this->_total;}
}