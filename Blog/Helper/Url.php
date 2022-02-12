<?php

namespace Ecentura\Blog\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Url extends AbstractHelper
{
    protected $_post;
    protected $_category;
    protected $_request;
    public function __construct(
        Context $context,
        \Ecentura\Blog\Model\PostFactory $postFactory,
        \Ecentura\Blog\Model\CategoryFactory $categoryFactory

    )
    {
        parent::__construct($context);
        $this->_post = $postFactory;
        $this->_category = $categoryFactory;
    }

    public function getPost($urlKeyPath)
    {
        $date = date("Y-m-d", time());
        $post = '';
        $posts = $this->_post->create()->getCollection();
        $posts->addFieldToFilter('url_key', $urlKeyPath);
        $posts->addFieldToFilter('publish_time', array('lteq' => $date));
        $posts->addFieldToFilter('status', array('where' => 1));
        if($posts->count() > 0){
            foreach ($posts as $postItem){
                $post = $postItem;
            }
            return $post;
        }
        return false;
    }

    public function getCagetory($urlKeyPath)
    {
        $category = '';
        $categories = $this->_category->create()->getCollection();
        $categories->addFieldToFilter('url_key', $urlKeyPath);
        $categories->addFieldToFilter('status', array('where' => 1));
        if($categories->count() > 0){
            foreach ($categories as $categoryItem){
                $category = $categoryItem;
            }
            return $category;
        }
        return false;
    }

}
