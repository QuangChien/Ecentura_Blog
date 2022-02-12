<?php
namespace Ecentura\Blog\Model\Author;

use Ecentura\Blog\Model\ResourceModel\Author\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $_loadedData;
    protected $collection;
    protected $_storeManager;
    protected $moduleAssetDir;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = [],
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Asset\Repository $moduleAssetDir
    ) {
        $this->collection = $collectionFactory->create();
        $this->_storeManager = $storeManager;
        $this->moduleAssetDir = $moduleAssetDir;
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

        foreach ($items as $author) {
            $fullData = $author->getData();
            $author = $author->load($author->getId());
            if ($author->getAvatar() !=='') {
                $avatar['avatar'][0]['name'] = $author->getAvatar();
                $avatar['avatar'][0]['url'] = $this->getMediaUrl()."ecentura/blog/".$author->getAvatar();
                $fullData = array_merge($fullData, $avatar);
            }else{
                $avatar['avatar'][0]['name'] = 'avatar-default.png';
                $avatar['avatar'][0]['url'] = $this->moduleAssetDir->getUrl("Ecentura_Blog::images/avatar-default.png");
                $fullData = array_merge($fullData, $avatar);
            }
            $this->_loadedData[$author->getId()] = $fullData;
        }
//        echo "<pre>";
//        print_r($this->_loadedData); die();
        return $this->_loadedData;

    }

    public function getMediaUrl()
    {
        return $this ->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

}
