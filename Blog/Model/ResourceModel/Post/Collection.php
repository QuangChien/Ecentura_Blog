<?php
namespace Ecentura\Blog\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_postTable;
    protected $_authorTable;

    protected function _construct()
    {
        $this->_init('Ecentura\Blog\Model\Post', 'Ecentura\Blog\Model\ResourceModel\Post');
    }

    public function joinWithAuthor()
    {
        $this->_postTable = "main_table";
        $this->_authorTable = $this->getTable("blog_author");
        $this->getSelect()
            ->join(array('author' => $this->_authorTable),
                $this->_postTable . '.author_id = author.author_id',
                array('*')
            );
        return $this;
    }
}
