<?php
namespace Ecentura\Blog\Model\Post;

use Ecentura\Blog\Model\ResourceModel\Post\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $_loadedData;
    protected $collection;
    protected $_storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = [],
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->collection = $collectionFactory->create();
        $this->_storeManager = $storeManager;
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

        foreach ($items as $post) {
            $post = $post->load($post->getPostId());
            $fullData = $post->getData();

            if($post->getPostId()) {
                $arrPath = explode("/",$post->getPath());
                foreach ($arrPath as $key => $arrPathItem){
                    $path['path'][$key] = $arrPathItem;
                }

                $fullData = array_merge($fullData, $path);
            }else{
                $fullData = $post->getData();
            }

            if ($post->getFeatureImage()) {
                $thumbnail['feature_image'][0]['name'] = $post->getFeatureImage();
                $thumbnail['feature_image'][0]['url'] = $this->getMediaUrl()."ecentura/blog/".$post->getFeatureImage();
                $fullData = array_merge($fullData, $thumbnail);
            }

            $this->_loadedData[$post->getId()] = $fullData;
        }
        return $this->_loadedData;

    }

    public function getMediaUrl()
    {
        return $this ->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

}
