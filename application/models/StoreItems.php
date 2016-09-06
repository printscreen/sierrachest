<?php
class Model_StoreItems extends Model_Base_Db
{
    protected $_storeItems;

    public function __construct(array $options = array())
    {
        $settings = array_merge(array(
            'db' => null,
            ), $options);

        parent::__construct($settings['db']);

        $this->_storeItems = array();
    }

    public function getStoreItems($sort = null, $offset = null, $limit = null)
    {
        $sql = "
            SELECT
                si.store_item_id    AS store_item_id
              , si.title            AS title
              , si.store_id         AS store_id
              , s.name              AS store_name
              , si.game_id          AS game_id
              , g.title             AS game_name
              , si.box_id           AS box_id
              , si.url              AS url
              , si.list_date        AS list_date
              , si.expiration       AS expiration
              , si.comments         AS comments
              , si.image            AS image
              , si.auction          AS auction
              , si.swap             AS swap
              , si.fixed_price      AS fixed_price
              , si.price            AS price
              , si.currency         AS currency
              , si.digital          AS digital
              , si.display_number   AS display_number
              , ( SELECT
                    count(*)
                  FROM store_item si
                )                   AS total
            FROM store_item si
            INNER JOIN store s ON si.store_id = s.store_id
            INNER JOIN game g ON si.game_id = g.game_id
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

        return $this->_loadRecords(
            $query->fetchAll()
        );
    }

    public function getDisplayItems()
    {
        $sql = "
            SELECT
                si.store_item_id    AS store_item_id
              , si.title            AS title
              , si.store_id         AS store_id
              , s.name              AS store_name
              , si.game_id          AS game_id
              , g.title             AS game_name
              , si.box_id           AS box_id
              , si.url              AS url
              , si.list_date        AS list_date
              , si.expiration       AS expiration
              , si.comments         AS comments
              , si.image            AS image
              , si.auction          AS auction
              , si.swap             AS swap
              , si.fixed_price      AS fixed_price
              , si.price            AS price
              , si.currency         AS currency
              , si.digital          AS digital
              , si.display_number   AS display_number
              , ( SELECT
                    count(*)
                  FROM store_item si
                  WHERE display_number IS NOT NULL
                )                   AS total
            FROM store_item si
            INNER JOIN store s ON si.store_id = s.store_id
            INNER JOIN game g ON si.game_id = g.game_id
            WHERE display_number IS NOT NULL
            ORDER BY display_number ASC
            LIMIT 8
        ";
        $query = $this->_db->prepare($sql);
        $query->execute();

        return $this->_loadRecords(
            $query->fetchAll()
        );
    }

    private function _loadRecords($result)
    {
        $this->_storeItems = array();
        if(!empty($result)) {
            foreach($result as $key => $value) {
                $storeItem = new Model_StoreItem();
                $storeItem->loadRecord($value);
                $this->_storeItems[] = $storeItem;
            }
        }
        return $this->_storeItems;
    }

    public function toArray()
    {
        $storeItems = array();
        if(is_array($this->_storeItems) && count($this->_storeItems) > 0) {
            foreach($this->_storeItems as $storeItem) {
                $storeItems[] = $storeItem->toArray();
            }
        }
        return $storeItems;
    }
}