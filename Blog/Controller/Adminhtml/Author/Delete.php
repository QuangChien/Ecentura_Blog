<?php

namespace Ecentura\Blog\Controller\Adminhtml\Author;

use Magento\Backend\App\Action;
use Ecentura\Blog\Model\ResourceModel\Author\CollectionFactory;
use Ecentura\Blog\Model\AuthorFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\Model\View\Result\RedirectFactory;

class Delete extends Action
{
    private $_authorFactory;
    private $_filter;
    private $_collectionFactory;
    private $_resultRedirect;

    public function __construct(
        Action\Context    $context,
        AuthorFactory   $authorFactory,
        Filter            $filter,
        CollectionFactory $collectionFactory,
        RedirectFactory   $redirectFactory
    )
    {
        parent::__construct($context);
        $this->_authorFactory = $authorFactory;
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->_resultRedirect = $redirectFactory;
    }

    public function execute()
    {
        // get all category is selected
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $total = 0;
        $err = 0;

        //foreach and delete all portfolio category selected
        foreach ($collection->getItems() as $item) {
            $deleteauthor = $this->_authorFactory->create()->load($item->getData('author_id'));
            try {
                $deleteauthor->delete();
                $total++;
            } catch (LocalizedException $exception) {
                $err++;
            }
        }

        if ($total) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $total)
            );
        }

        if ($err) {
            $this->messageManager->addErrorMessage(
                __(
                    'A total of %1 record(s) haven\'t been deleted. Please see server logs for more details.',
                    $err
                )
            );
        }

        return $this->redirectToIndex();
    }

    public function redirectToIndex()
    {
        return $this->_resultRedirect->create()->setPath('blog/author/index');
    }

    //Check rule (ACL)
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ecentura_Blog::blog');
    }
}
