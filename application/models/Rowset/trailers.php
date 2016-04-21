class Model_Rowset_Trailers extends Zend_Db_Table_Rowset_Abstract
{
    /**
     * @return array all trailers
     */
    public function getAsArray()
    {
        $trailers = array();

        while ($this->valid()) {
            $trailer = $this->current();
            $trailers[] = $trailer->name;  // the actual trailer name
            $this->next();
        }

        $this->rewind();

        return $trailers;
    }
}
