<?php
namespace Ecentura\Blog\Model;

class Url
{
    private $_post;
    private $_rq;
    public function __construct(
        \Ecentura\Blog\Model\PostFactory $postFactory,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        $this->_post = $postFactory;
        $this->_rq = $postFactory;
    }
    public function hasId()
    {
        return $this->_rq()->getParam('id') ? true : 0;
    }

    public function idCorrect()
    {
        $post = $this->_post->create()->load($this->getId());
        if ($post->isEmpty()) {
            return false;
        }
        return true;
    }

//    public function getUrlKey()
//    {
//        if($this->hasId()){
//            $post = $this->_post->create()->load($this->getId());
//            return $post->getData()['url_key'];
//        }
//    }

    public function getStatus()
    {
        $post = $this->_post->create()->load($this->getId());
        if (!$post->isEmpty()) {
            $postStatus = $post->getData()['status'];
            return $postStatus;
        }
        return false;
    }

    public function checkDataPublish()
    {
        $date = date("Y-m-d", time());
        $post = $this->_post->create()->load($this->getId());
        if (!$post->isEmpty()) {
            $datePublish = $post->getData()['publish_time'];
            if (strtotime($date) >= strtotime($datePublish)) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function getId()
    {
        if ($this->hasId()) {
            return $this->getRequest()->getParam('id');
        }
        return null;
    }
}
