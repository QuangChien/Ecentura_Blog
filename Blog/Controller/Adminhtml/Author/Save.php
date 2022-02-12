<?php

namespace Ecentura\Blog\Controller\Adminhtml\Author;

use Ecentura\Blog\Model\AuthorFactory;
use Ecentura\Blog\Model\ResourceModel\Author\CollectionFactory;
use Magento\Backend\App\Action;

class Save extends Action
{
    private $_authorFactory;
    protected $data;
    protected $_collectionFactory;

    public function __construct(
        Action\Context $context,
        AuthorFactory $authorFactory,
        CollectionFactory $collectionFactory
    )

    {
        parent::__construct($context);
        $this->_authorFactory = $authorFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->data = $this->getRequest()->getPostValue();
    }

    public function getId()
    {
        $id = !empty($this->data['author_id']) ? $this->data['author_id'] : null;
        return $id;
    }

    /**
     * @return array
     */
    public function dataAuthor()
    {
        $newData = [
            'name' => $this->data['name'],
            'information' => $this->data['information'],
            'facebook_link' => $this->data['facebook_link'],
            'instagram_link' => $this->data['instagram_link'],
            'linkedin_link' => $this->data['linkedin_link'],
            'avatar' => isset($this->data['avatar'][0]) ? $this->data['avatar'][0]['name'] : ' '
        ];
        return $newData;
    }

    public function execute()
    {
        $author = $this->_authorFactory->create();

        if ($this->getId()) {
            $author->load($this->getId());
        }

        try {
            //save data
            $author->addData($this->dataAuthor());
            $author->save();
            $this->messageManager->addSuccessMessage(__('Save Success'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

        return $this->redirectToIndex();
    }


    public function redirectToIndex()
    {
        return $this->resultRedirectFactory->create()->setPath('blog/author/index');
    }

    //Check rule (ACL)
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ecentura_Blog::blog');
    }
}
