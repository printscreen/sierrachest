<?php
class Model_ScreenShots extends Model_Base_Db
{
    protected $_screenShots;

    public function __construct(array $options = array())
    {
        $settings = array_merge(array(
            'db' => null,
            ), $options);

        parent::__construct($settings['db']);

        $this->_screenShots = array();
    }

    public function getScreenShots($gameId = null, $sort = null, $offset = null, $limit = null)
    {
        $where = '';
        $binds = array();
        if(!empty($gameId) && is_numeric($gameId)) {
            $where .= ' AND s.game_id = :gameId';
            $binds[':gameId'] = array('value' => $gameId, 'type' => PDO::PARAM_INT);
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
              , ( SELECT
                    count(*)
                  FROM screenshot s
                  WHERE true $where
                )                   AS total
            FROM screenshot s
            INNER JOIN game g ON s.game_id = g.game_id
            WHERE true $where
            ORDER BY :sort ".$this->getDirection($sort)."
            LIMIT :offset,:limit
        ";

        $query = $this->_db->prepare($sql);
        $this->bind($query, $binds);

        $sort = $this->getSort($sort);
        $offset = $this->getOffset($offset);
        $limit = $this->getLimit($limit);

        $query->bindParam(':sort', $sort, PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->execute();

        return $this->_loadRecords(
            $query->fetchAll()
        );
    }

    private function _loadRecords($result)
    {
        $this->_screenShots = array();
        if(!empty($result)) {
            foreach($result as $key => $value) {
                $screenShot = new Model_ScreenShot();
                $screenShot->loadRecord($value);
                $this->_screenShots[] = $screenShot;
            }
        }
        return $this->_screenShots;
    }

    public function toArray()
    {
        $screenShots = array();
        if(is_array($this->_screenShots) && count($this->_screenShots) > 0) {
            foreach($this->_screenShots as $screenShot) {
                $screenShots[] = $screenShot->toArray();
            }
        }
        return $screenShots;
    }
}