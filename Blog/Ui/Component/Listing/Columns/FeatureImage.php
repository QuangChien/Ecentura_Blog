<?php

namespace Ecentura\Blog\Ui\Component\Listing\Columns;

use Magento\Catalog\Helper\Image;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;


class FeatureImage extends Column
{
    protected $storeManager;
    protected $moduleAssetDir;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Image $imageHelper,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = [],
        \Magento\Framework\View\Asset\Repository $moduleAssetDir
    ) {
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
        $this->moduleAssetDir = $moduleAssetDir;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach($dataSource['data']['items'] as & $item) {
                $url = '';
                if($item[$fieldName] != ' ') {
                    /* Set your image physical path here */
                    $url = $this->storeManager->getStore()->getBaseUrl(
                            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                        ). "ecentura/blog/" . $item[$fieldName];
                }else{
                    $url = $this->moduleAssetDir->getUrl("Ecentura_Blog::images/avatar-default.png");
                }
                $item[$fieldName . '_src'] = $url;
                $item[$fieldName . '_alt'] = $item[$fieldName];

                $item[$fieldName . '_orig_src'] = $url;
            }
        }

//        echo "<pre>";
//        print_r($dataSource['data']['items']);
//        echo $url; die();
        return $dataSource;
    }
}
