<?php

namespace Ecentura\Blog\Block\Author;

use Ecentura\Blog\Model\ResourceModel\Author\CollectionFactory;

class Index extends \Magento\Framework\View\Element\Template
{

    protected $_authorCollection;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CollectionFactory $collectionFactory
    )
    {
        $this->_authorCollection = $collectionFactory;
        parent::__construct($context);
    }


    public function getAuthors()
    {
        return $this->_authorCollection->create();
    }

    //get path pub/media
    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

}
