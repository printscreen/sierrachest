<?php
class Model_Newss extends Model_Base_Db
{
    protected $_news;

    public function __construct(array $options = array())
    {
        $settings = array_merge(array(
            'db' => null,
            ), $options);

        parent::__construct($settings['db']);

        $this->_news = array();
    }

    public function getNews($sort = null, $offset = null, $limit = null)
    {
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
              , ( SELECT
                    count(*)
                  FROM news n
                )               AS total
            FROM news n
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

        $this->_news = array();
        if(!empty($result)) {
            foreach($result as $key => $value) {
                $news = new Model_News();
                $news->loadRecord($value);
                $this->_news[] = $news;
            }
        }
        return $this->_news;
    }

    public function toArray()
    {
        $news = array();
        if(is_array($this->_news) && count($this->_news) > 0) {
            foreach($this->_news as $news) {
                $news[] = $news->toArray();
            }
        }
        return $news;
    }
}