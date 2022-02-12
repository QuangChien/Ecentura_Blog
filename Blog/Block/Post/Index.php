<?php

namespace Ecentura\Blog\Block\Post;

use Ecentura\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollection;

class Index extends \Magento\Framework\View\Element\Template
{

    protected $_postCollection;
    protected $_urlInterface;
    protected $_pageConfig;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        PostCollection $postCollection,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\View\Page\Config $pageConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->_postCollection = $postCollection;
        $this->_urlInterface = $urlInterface;
        $this->_pageConfig = $pageConfig;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function getId()
    {
        return $this->getRequest()->getParam('id');
    }

    public function getPostItem()
    {
        $postItem = '';
        $posts = $this->_postCollection->create();
        $posts->addFieldToFilter('post_id', array('where' => $this->getId()));
        $posts->joinWithAuthor();
        foreach ($posts as $post) {
            $postItem = $post;
        }
        return $postItem;
    }


    public function getPath()
    {
        $path = $this->getPostItem()->getPath();
        $path = explode('/', $path);
        return $path;
    }


    public function getPostTogethor()
    {
        $posts = $this->_postCollection->create();
        $posts->addFieldToFilter('path', array(
          'in' => $this->getPath()
        ));
//        $posts->addFieldToFilter('path', ['in' => $this->getPath()]);
//        $posts->addFieldToFilter('path', array(
//            array('like' => '%/' . $this->getPath() . '/%'),
//            array('like' => '%/' . $this->getPath()),
//            array('like' => $this->getPath() . '/%')
//        ));
        $posts = $posts->addFieldToFilter('post_id', array('nin' => $this->getPostItem()->getId()));
        return $posts;
    }

    //get path pub/media
    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getUrlCurrent()
    {
        return $this->_urlInterface->getCurrentUrl();
    }

    protected function _prepareLayout()
    {
        $this->_addBreadcrumbs();
        $this->pageConfig->getTitle()->set($this->getPostItem()->getMetaTitle());
        $this->pageConfig->setKeywords($this->getPostItem()->getMetaKeyword());
        $this->pageConfig->setDescription($this->getPostItem()->getMetaDescription());
        return $this;
    }
    //get Data from System Configuration
    public function getConfigValue($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    protected function _addBreadcrumbs()
    {
        $isShowBreadcrumbs = 1;
        if ($isShowBreadcrumbs && ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'blog',
                [
                    'label' => 'Blog',
                    'title' => 'Blog'

                ]
            );
            $breadcrumbsBlock->addCrumb(
                'post',
                [
                    'label' => 'Post',
                    'title' => 'Post',
                    'link' => trim($this->getUrl('blog/posts.html'), '/')
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'item',
                [
                    'label' => $this->getPostItem()->getData()['title'],
                    'title' => $this->getPostItem()->getData()['title']
                ]
            );
        }
    }


}
