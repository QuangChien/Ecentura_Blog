<?php

namespace Ecentura\Blog\Controller\Post;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_post;
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Ecentura\Blog\Model\PostFactory $postFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_post = $postFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
//        echo $this->getUrlKey();
//        die();
        if ($this->hasId() == true && $this->idCorrect() == true
            && $this->getStatus() == true && $this->checkDataPublish() == true) {
            return $this->_pageFactory->create();

        } else {
            return $this->redirect();
        }
    }

    public function hasId()
    {
        return $this->getRequest()->getParam('id') ? true : 0;
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

    public function redirect()
    {
        $redirect = $this->resultRedirectFactory->create();
        return $redirect->setPath('blog/posts.html');
    }


//    public function match(\Magento\Framework\App\RequestInterface $request)
//    {
//        $identifier = trim($request->getPathInfo(), '/');
//        if(strpos($identifier, 'blog') !== false) {
//            $request->setModuleName('blog')-> //module name
//            setControllerName('post')-> //controller name
//            setActionName($this->getUrlKey());
//        } else {
//            return false;
//        }
////        return $this->actionFactory->create(
////            'Magento\Framework\App\Action\Forward',
////            ['request' => $request]
////        );
//    }
}
