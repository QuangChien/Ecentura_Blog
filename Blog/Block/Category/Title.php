<?php

namespace Ecentura\Blog\Block\Category;
use Ecentura\Blog\Model\CategoryFactory;

class Title extends \Magento\Framework\View\Element\Template
{

    protected $_category;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CategoryFactory $categoryFactory
    )
    {
        $this->_category = $categoryFactory;
        parent::__construct($context);
    }

    public function getId()
    {
        return $this->getRequest()->getParam('id');
    }

    public function getCategoryItem()
    {
        $category = $this->_category->create()->load($this->getId());
        return $category;
    }
}
