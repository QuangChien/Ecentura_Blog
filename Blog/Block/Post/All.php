<?php

namespace Ecentura\Blog\Block\Post;

use Ecentura\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollection;
use Ecentura\Blog\Model\CategoryFactory;
use Ecentura\Blog\Model\PostFactory;
use Ecentura\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;

class All extends \Magento\Framework\View\Element\Template
{

    protected $_postCollection;
    protected $_category;
    protected $_post;
    protected $_categoryCollection;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        PostCollection $postCollection,
        CategoryCollection $categoryColleciton,
        CategoryFactory $categoryFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        PostFactory $postFactory
    )
    {
        $this->_postCollection = $postCollection;
        $this->_post = $postFactory;
        $this->_categoryCollection = $categoryColleciton;
        $this->_category = $categoryFactory;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }


    public function getPosts()
    {
        $date = date("Y-m-d", time());
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : $this->getConfigValue('blog/general/total_post_per_page');
        $posts = $this->_postCollection->create();
        $posts->addFieldToFilter('publish_time', array('lteq' => $date));
        $posts->addFieldToFilter('status', array('where' => 1))->joinWithAuthor();
        $posts->setOrder('post_id','DESC');
        $posts->setPageSize($pageSize)->setCurPage($page);
        return $posts;
    }


    //get path pub/media
    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }


    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set($this->getConfigValue('blog/config_page/meta_title'));
        $this->pageConfig->setKeywords($this->getConfigValue('blog/config_page/meta_keywords'));
        $this->pageConfig->setDescription($this->getConfigValue('blog/config_page/meta_description'));
        parent::_prepareLayout();
        $page_size = $this->getPagerCount();
        $page_data = $this->getPosts();
        if ($this->getPosts()) {
            $blockName = 'conecept.request.pager';
            $pager = null;
            if ($this->getLayout()->getBlock($blockName)) {
                $pager = $this->getLayout()->getBlock($blockName)
                    ->setAvailableLimit($page_size)->setTemplate('Ecentura_Blog::html/pager.phtml')
                    ->setShowPerPage(true)->setCollection(
                        $this->getPosts()
                    );
            } else {
                $pager = $this->getLayout()->createBlock(
                    'Ecentura\Blog\Block\Html\Pager',
                    $blockName
                )->setAvailableLimit($page_size)->setTemplate('Ecentura_Blog::html/pager.phtml')
                    ->setShowPerPage(true)->setCollection(
                        $this->getPosts()
                    );
            }
//            echo "<pre>";
//            print_r($pager->getData());die();
            $this->setChild('pager', $pager);
            $this->getPosts()->load();
        }
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getPagerCount()
    {
        // get collection
        $minimum_show = $this->getConfigValue('blog/general/total_post_per_page');
        $page_array = [];
        $list_data = $this->_postCollection->create();
        $list_count = ceil(count($list_data->getData()));
        $show_count = $minimum_show + 1;
        if (count($list_data->getData()) >= $show_count) {
            $list_count = $list_count / $minimum_show;
            $page_nu = $total = $minimum_show;
            $page_array[$minimum_show] = $minimum_show;
            for ($x = 0; $x <= $list_count; $x++) {
                $total = $total + $page_nu;
                $page_array[$total] = $total;
            }
        } else {
            $page_array[$minimum_show] = $minimum_show;
            $minimum_show = $minimum_show + $minimum_show;
            $page_array[$minimum_show] = $minimum_show;
        }
        return $page_array;
    }

    //get Data from System Configuration
    public function getConfigValue($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
