<?php
namespace Ecentura\Blog\Model\Category;

use Ecentura\Blog\Model\ResourceModel\Category\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $_loadedData;
    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $category) {
            $category = $category->load($category->getCategoryId());
            $this->_loadedData[$category->getCategoryId()] = $category->getData();
        }
//        echo "<pre>";
//        print_r($this->_loadedData); die();
        return $this->_loadedData;

    }

}
