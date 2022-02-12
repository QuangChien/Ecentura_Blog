<?php

namespace Ecentura\Blog\Block\Category;

use Ecentura\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollection;
use Ecentura\Blog\Model\CategoryFactory;
use Ecentura\Blog\Model\PostFactory;
use Ecentura\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;

class Index extends \Magento\Framework\View\Element\Template
{

    protected $_postCollection;
    protected $_category;
    protected $_categoryCollection;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        PostCollection $postCollection,
        CategoryCollection $categoryColleciton,
        CategoryFactory $categoryFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->_postCollection = $postCollection;
        $this->_categoryCollection = $categoryColleciton;
        $this->_category = $categoryFactory;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function getCategoryItem()
    {
        $category = $this->_category->create()->load($this->getId());
        return $category;
    }

    public function getId()
    {
        return $this->getRequest()->getParam('id');
    }


    public function getPostByCate()
    {
        $date = date("Y-m-d", time());
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : $this->getConfigValue('blog/general/total_post_per_page');
        $posts = $this->_postCollection->create();
        $posts->addFieldToFilter('path', array(
            array('like' => '%/' . $this->getId() . '/%'),
            array('like' => '%/' . $this->getId()),
            array('like' => $this->getId() . '/%'),
            array('like' => $this->getId())
        ));
        $posts->addFieldToFilter('publish_time', array('lteq' => $date));
        $posts->addFieldToFilter('status', array('where' => 1))->joinWithAuthor();
        $posts->setOrder('post_id','DESC');
        $posts->setPageSize($pageSize)->setCurPage($page);
        return $posts;
    }


    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    protected function _prepareLayout()
    {
        $this->_addBreadcrumbs();
        $this->pageConfig->getTitle()->set($this->getCategoryItem()->getMetaTitle());
        $this->pageConfig->setKeywords($this->getCategoryItem()->getMetaKeyword());
        $this->pageConfig->setDescription($this->getCategoryItem()->getMetaDescription());
        parent::_prepareLayout();
        $page_size = $this->getPagerCount();
        $page_data = $this->getPostByCate();
        if ($this->getPostByCate()) {
            $blockName = 'conecept.request.pager';
            $pager = null;
            if ($this->getLayout()->getBlock($blockName)) {
                $pager = $this->getLayout()->getBlock($blockName)
                    ->setAvailableLimit($page_size)->setTemplate('Ecentura_Blog::html/pager.phtml')
                    ->setShowPerPage(true)->setCollection(
                        $this->getPostByCate()
                    );
            } else {
                $pager = $this->getLayout()->createBlock(
                    'Ecentura\Blog\Block\Html\Category\Pager',
                    $blockName
                )->setAvailableLimit($page_size)
                    ->setShowPerPage(true)->setCollection(
                        $this->getPostByCate()
                    );
            }
            $this->setChild('pager', $pager);
            $this->getPostByCate()->load();
        }
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getPagerCount()
    {
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

    protected function _addBreadcrumbs()
    {
        $isShowBreadcrumbs = 1; // You can add this value at Configuration of module.
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
                    //'link' => '#'
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'blog-category',
                [
                    'label' => 'Category',
                    'title' => 'Category'
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'category',
                [
                    'label' => $this->getCategoryItem()->getData()['name'],
                    'title' => $this->getCategoryItem()->getData()['name']
                ]
            );
        }
    }
}
