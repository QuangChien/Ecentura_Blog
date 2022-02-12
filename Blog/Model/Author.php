<?php
namespace Ecentura\Blog\Model;
class Author extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Ecentura\Blog\Model\ResourceModel\Author');
    }
}
