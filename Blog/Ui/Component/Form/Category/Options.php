<?php
namespace Ecentura\Blog\Ui\Component\Form\Category;

use Magento\Framework\Data\OptionSourceInterface;
use Ecentura\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\App\RequestInterface;

class Options implements OptionSourceInterface
{

    protected $categoryCollectionFactory;

    protected $request;

    protected $categoryTree;

    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        RequestInterface $request
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->request = $request;
    }

    public function toOptionArray()
    {
        return $this->getCategoryTree();
    }

    protected function getCategoryTree()
    {
        if ($this->categoryTree === null) {
            $collection = $this->categoryCollectionFactory->create();

//            $collection->addAttributeToSelect('name');

            foreach ($collection as $category) {
                $categoryId = $category->getCategoryId();
                if (!isset($categoryById[$categoryId])) {
                    $categoryById[$categoryId] = [
                        'value' => $categoryId
                    ];
                }
                $categoryById[$categoryId]['label'] = $category->getName();
            }
            $this->categoryTree = $categoryById;
        }
        return $this->categoryTree;
    }
}
