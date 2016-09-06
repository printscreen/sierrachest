<?php

class Model_StoreItem extends Model_Base_Db
{
    protected $_storeItemId;
    protected $_title;
    protected $_storeId;
    protected $_storeName;
    protected $_gameId;
    protected $_gameName;
    protected $_boxId;
    protected $_url;
    protected $_listDate;
    protected $_expiration;
    protected $_comments;
    protected $_image;
    protected $_auction;
    protected $_swap;
    protected $_fixedPrice;
    protected $_price;
    protected $_currency;
    protected $_digital;
    protected $_displayNumber;
    protected $_total;

    public function __construct(array $options = array())
    {
        $settings = array_merge(array(
            'storeItemId' => null,
            'title' => null,
            'storeId' => null,
            'gameId' => null,
            'boxId' => null,
            'url' => null,
            'listDate' => null,
            'expiration' => null,
            'comments' => null,
            'image' => null,
            'auction' => null,
            'swap' => null,
            'fixedPrice' => null,
            'price' => null,
            'currency' => null,
            'digital' => null,
            'displayNumber' => null,
            'db' => null,
            ), $options);

        parent::__construct($settings['db']);
        $this->_storeItemId = $settings['storeItemId'];
        $this->_title = $settings['title'];
        $this->_storeId = $settings['storeId'];
        $this->_gameId = $settings['gameId'];
        $this->_boxId = $settings['boxId'];
        $this->_url = $settings['url'];
        $this->_listDate = $settings['listDate'];
        $this->_expiration = $settings['expiration'];
        $this->_comments = $settings['comments'];
        $this->_image = $settings['image'];
        $this->_auction = $settings['auction'];
        $this->_swap = $settings['swap'];
        $this->_fixedPrice = $settings['fixedPrice'];
        $this->_price = $settings['price'];
        $this->_currency = $settings['currency'];
        $this->_digital = $settings['digital'];
        $this->_displayNumber = $settings['displayNumber'];
    }

    public function loadRecord($record)
    {
        $this->_storeItemId = $record->store_item_id;
        $this->_title = $record->title;
        $this->_storeId = $record->store_id;
        $this->_storeName = $record->store_name;
        $this->_gameName = $record->game_name;
        $this->_gameId = $record->game_id;
        $this->_boxId = $record->box_id;
        $this->_url = $record->url;
        $this->_listDate = $record->list_date;
        $this->_expiration = $record->expiration;
        $this->_comments = $record->comments;
        $this->_image = $record->image;
        $this->_auction = $record->auction;
        $this->_swap = $record->swap;
        $this->_fixedPrice = $record->fixed_price;
        $this->_price = $record->price;
        $this->_currency = $record->currency;
        $this->_digital = $record->digital;
        $this->_displayNumber = $record->display_number;
        $this->_total = $record->total;
    }

    public function load()
    {
        $where = 'WHERE true';
        $binds = array();
        if(!empty($this->_storeItemId) && is_numeric($this->_storeItemId)) {
            $where .= ' AND si.store_item_id = :storeItemId';
            $binds[':storeItemId'] = array('value' => $this->_storeItemId, 'type' => PDO::PARAM_INT);
        } else {
            throw new Zend_Exception("No store item id supplied");
        }

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
              , 1                   AS total
            FROM store_item si
            INNER JOIN store s ON si.store_id = s.store_id
            INNER JOIN game g ON si.game_id = g.game_id
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
    public function setStoreItemId($storeItemId){$this->_storeItemId = $storeItemId; return $this;}
    public function setTitle($title){$this->_title = $title; return $this;}
    public function setStoreId($storeId){$this->_storeId = $storeId; return $this;}
    public function setGameId($gameId){$this->_gameId = $gameId; return $this;}
    public function setBoxId($boxId){$this->_boxId = $boxId; return $this;}
    public function setUrl($url){$this->_url = $url; return $this;}
    public function setListDate($listDate){$this->_listDate = $listDate; return $this;}
    public function setExpiration($expiration){$this->_expiration = $expiration; return $this;}
    public function setComments($comments){$this->_comments = $comments; return $this;}
    public function setImage($image){$this->_image = $image; return $this;}
    public function setAuction($auction){$this->_auction = $auction; return $this;}
    public function setSwap($swap){$this->_swap = $swap; return $this;}
    public function setFixedPrice($fixedPrice){$this->_fixedPrice = $fixedPrice; return $this;}
    public function setPrice($price){$this->_price = $price; return $this;}
    public function setCurrency($currency){$this->_currency = $currency; return $this;}
    public function setDigital($digital){$this->_digital = $digital; return $this;}
    public function setDisplayNumber($displayNumber){$this->_displayNumber = $displayNumber; return $this;}

    //Getters
    public function getStoreItemId(){return $this->_storeItemId;}
    public function getTitle(){return $this->_title;}
    public function getStoreId(){return $this->_storeId;}
    public function getStoreName(){return $this->_storeName;}
    public function getGameId(){return $this->_gameId;}
    public function getGameName(){return $this->_gameName;}
    public function getBoxId(){return $this->_boxId;}
    public function getUrl(){return $this->_url;}
    public function getListDate(){return $this->_listDate;}
    public function getExpiration(){return $this->_expiration;}
    public function getComments(){return $this->_comments;}
    public function getImage(){return Zend_Registry::get(APPLICATION_URL) . $this->_image;}
    public function getAuction(){return $this->_auction;}
    public function getSwap(){return $this->_swap;}
    public function getFixedPrice(){return $this->_fixedPrice;}
    public function getPrice(){return $this->_price;}
    public function getCurrency(){return $this->_currency;}
    public function getDigital(){return $this->_digital;}
    public function getDisplayNumber(){return $this->_displayNumber;}
    public function getTotal(){return $this->_total;}
}