<?php
namespace Ecentura\Blog\Controller\Category;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_category;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Ecentura\Blog\Model\CategoryFactory $categoryFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_category = $categoryFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        if($this->hasId() == true && $this->idCorrect() == true){
            return $this->_pageFactory->create();

        }else{
            return $this->redirect();
        }
    }

    public function idCorrect()
    {
        $category = $this->_category->create()->load($this->getId());
        if ($category->isEmpty()){
            return false;
        }
        return true;
    }

    public function getStatus()
    {

        $category = $this->_category->create()->load($this->getId());
        if (!$category->isEmpty()){
            $categoryStatus = $category->getData()['status'];
            return $categoryStatus;
        }
        return false;
    }

    public function getId()
    {
        if($this->hasId()){
            return $this->getRequest()->getParam('id');
        }
        return null;
    }

    public function hasId(){
        return $this->getRequest()->getParam('id') ? true : 0;
    }

    public function redirect()
    {
        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('blog/posts.html');
        return $redirect;
    }
}
