<?php

namespace Ecentura\Blog\Controller\Adminhtml\Category;

use Ecentura\Blog\Model\CategoryFactory;
use Ecentura\Blog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Backend\App\Action;

class Save extends Action
{
    private $_categoryFactory;
    protected $data;
    protected $_collectionFactory;

    public function __construct(
        Action\Context $context,
        CategoryFactory $categoryFactory,
        CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context);
        $this->_categoryFactory = $categoryFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->data = $this->getRequest()->getPostValue();
    }

    public function getId()
    {
        $id = !empty($this->data['category_id']) ? $this->data['category_id'] : null;
        return $id;
    }

    /**
     * @return array
     */
    public function dataCategory()
    {
        $newData = [
            'name' => $this->data['name'],
            'status' => $this->data['status'],
            'url_key' => $this->createUrlKey(),
            'meta_title' => $this->data['meta_title'],
            'meta_keyword' => $this->data['meta_keywords'],
            'meta_description' => $this->data['meta_description'],
            'path' => $this->getPath()
        ];
        return $newData;
    }

    public function execute()
    {
        $category = $this->_categoryFactory->create();
        if ($this->getId()) {
            $category->load($this->getId());
        }
        try {
            //save data
            $category->addData($this->dataCategory());
            $category->save();
            $this->messageManager->addSuccessMessage(__('Save Success'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

        return $this->redirectToIndex();
    }

    public function createUrlKey()
    {
        if (empty($this->data['url_key'])) {
            $url_key = preg_replace('#[^0-9a-z]+#i', '-', $this->data['name']);
        }else{
            $url_key = preg_replace('#[^0-9a-z]+#i', '-', $this->data['name']);
        }

        return strtolower($url_key);
    }

    public function redirectToIndex()
    {
        return $this->resultRedirectFactory->create()->setPath('blog/category/index');
    }

    public function getPath()
    {
        if (isset($this->data['path'])) {
            return $this->data['path'];
        }else{
            return 0;
        }
    }

    //Check rule (ACL)
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ecentura_Blog::blog');
    }
}
