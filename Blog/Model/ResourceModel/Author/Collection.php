<?php
namespace Ecentura\Blog\Model\ResourceModel\Author;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Ecentura\Blog\Model\Author', 'Ecentura\Blog\Model\ResourceModel\Author');
    }
}
