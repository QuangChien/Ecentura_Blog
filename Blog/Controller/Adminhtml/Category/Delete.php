<?php

namespace Ecentura\Blog\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Ecentura\Blog\Model\ResourceModel\Category\CollectionFactory;
use Ecentura\Blog\Model\CategoryFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\Model\View\Result\RedirectFactory;

class Delete extends Action
{
    private $_categoryFactory;
    private $_filter;
    private $_collectionFactory;
    private $_resultRedirect;

    public function __construct(
        Action\Context    $context,
        CategoryFactory   $categoryFactory,
        Filter            $filter,
        CollectionFactory $collectionFactory,
        RedirectFactory   $redirectFactory
    )
    {
        parent::__construct($context);
        $this->_categoryFactory = $categoryFactory;
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->_resultRedirect = $redirectFactory;
    }

    public function allCategory()
    {
        return $this->_collectionFactory->create();
    }

    public function execute()
    {
        // get all category is selected
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $total = 0;
        $err = 0;

        //foreach and delete all portfolio category selected
        foreach ($collection->getItems() as $item) {
            $deleteCategory = $this->_categoryFactory->create()->load($item->getData('category_id'));
            foreach ($this->allCategory() as $category){
                if($category->getPath() == $deleteCategory->getId()){
                    $category->addData(['path' => $deleteCategory->getPath()])->save();
                }
            }
            try {
                $deleteCategory->delete();
                // $total + 1 => get total portfolio category show for user
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

        // $total + 1 => get total portfolio category show for user
        return $this->redirectToIndex();
    }

    public function redirectToIndex()
    {
        return $this->_resultRedirect->create()->setPath('blog/category/index');
    }

    //Check rule (ACL)
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ecentura_Blog::blog');
    }
}
