<?php

namespace Ecentura\Blog\Block\Sidebar;

use Ecentura\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollection;
use Ecentura\Blog\Model\CategoryFactory;
use Ecentura\Blog\Model\PostFactory;
use Ecentura\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_post;
    protected $_categoryCollection;
    protected $_scopeConfig;
    protected $moduleAssetDir;
    protected $urlBuilder;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CategoryCollection $categoryColleciton,
        PostFactory $postFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Asset\Repository $moduleAssetDir,
        \Magento\Framework\UrlInterface $urlBuilder
    )
    {
        $this->_post = $postFactory;
        $this->_categoryCollection = $categoryColleciton;
        $this->_scopeConfig = $scopeConfig;
        $this->moduleAssetDir = $moduleAssetDir;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context);
    }

    public function getCurrentUrl() {
        return $this->urlBuilder->getCurrentUrl();
    }

    public function checkPathCategory()
    {
        if(strpos($this->getCurrentUrl(), 'category/') == true){
            return true;
        } else{
            return false;
        }
    }

    public function hasId()
    {
        return $this->getRequest()->getParam('id') ? true : 'false';
    }

    public function getId()
    {
        if($this->hasId()){
            return $this->getRequest()->getParam('id');
        }
    }

    //get post news
    public function getPostNews()
    {
        $date = date("Y-m-d", time());
        $posts = $this->_post->create()->getCollection();
        $posts->addFieldToFilter('publish_time', array('lteq' => $date));
        $posts->addFieldToFilter('status', array('where' => 1));
        $posts->setOrder('post_id','DESC');
        if($this->hasId() && !$this->checkPathCategory()){
            $posts = $posts->addFieldToFilter('post_id', array('nin' => $this->getId()));
        }
        $posts->setPageSize($this->getConfigValue('blog/general/total_post_news'));
        return $posts;
    }

    public function checkChild($path)
    {
        $categories = $this->_categoryCollection->create()->addFieldToFilter('path', array('like' => $path))
            ->addFieldToFilter('status', array('where' => 1));
        if($categories->count()>0){
            return ' ';
        }
        return 'none';
    }

    public function generateSubMenu($path, $a = 0){
        $string = '';
        $i = 0;
        $categories = $this->_categoryCollection->create()->addFieldToFilter('path', array('like' => $path))
            ->addFieldToFilter('status', array('where' => 1));
        foreach ($categories as $category){
            if($category->getPath() == $path){
                $i++;
                $a += 4;
                $string .= '<div style="margin-left: '.$a.'%" class="sub-menu-category">
                            <div class="sub-menu-item">
                                <div class="relative">
                                    <a class="sub-menu-item-link decoration-none" href="'. $this->getUrl('blog/category') . $category->getUrlKey() . '.html'.'">'.$category->getName().'</a>
                                    <img class="category-item-icon absolute '.$this->checkChild($category->getCategoryId()).'" src="'.$this->moduleAssetDir->getUrl('Ecentura_Blog::images/line-angle-down.png').'"
                                         width="100%" alt="">

                                </div>'.$this->generateSubMenu($category->getCategoryId()).'
                            </div>
                        </div>';
                return $string;
            }
        }
    }

    public function generateMenu($path = 0)
    {
        $string = '';
        $categories = $this->_categoryCollection->create()->addFieldToFilter('path', array('like' => $path))
            ->addFieldToFilter('status', array('where' => 1));
        foreach ($categories as $category){
            if($category->getPath() == $path){
                $string .= '
                <div class="category-item">
                        <div class="category-item-title">
                            <a href="'. $this->getUrl('blog/category') . $category->getUrlKey() . '.html'.'" class="category-item-link decoration-none">'.$category->getName().'</a>
                            <img class="category-item-icon '.$this->checkChild($category->getCategoryId()).'" src="'.$this->moduleAssetDir->getUrl('Ecentura_Blog::images/line-angle-down.png').'"
                                 width="100%" alt="">
                        </div>'.$this->generateSubMenu($category->getCategoryId()).'
                    </div>
                ';
            }
        }
        echo $string;
    }

    public function getAllCateogry()
    {
        $categories = $this->_categoryCollection->create()
            ->addFieldToFilter('status', array('where' => 1));
        return $categories;
    }

    public function getConfigValue($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
