<?php
class Model_Games extends Model_Base_Db
{
    protected $_games;

    public function __construct(array $options = array())
    {
        $settings = array_merge(array(
            'db' => null,
            ), $options);

        parent::__construct($settings['db']);

        $this->_games = array();
    }

    public function getGames($sort = null, $offset = null, $limit = null)
    {
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
              , ( SELECT
                    count(*)
                  FROM game
                ) AS total
            FROM game g
            ORDER BY :sort ".$this->getDirection($sort)."
            LIMIT :offset,:limit
        ";
        $query = $this->_db->prepare($sql);

        $sort = $this->getSort($sort);
        $offset = $this->getOffset($offset);
        $limit = $this->getLimit($limit);

        $query->bindParam(':sort', $sort, PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetchAll();

        $this->_games = array();
        if(!empty($result)) {
            foreach($result as $key => $value) {
                $game = new Model_Game();
                $game->loadRecord($value);
                $this->_games[] = $game;
            }
        }
        return $this->_games;
    }

    public function toArray()
    {
        $games = array();
        if(is_array($this->_games) && count($this->_games) > 0) {
            foreach($this->_games as $game) {
                $games[] = $game->toArray();
            }
        }
        return $games;
    }
}