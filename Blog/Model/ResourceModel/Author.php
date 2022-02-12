<?php
namespace Ecentura\Blog\Model\ResourceModel;

class Author extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('blog_author', 'author_id');
    }

}
