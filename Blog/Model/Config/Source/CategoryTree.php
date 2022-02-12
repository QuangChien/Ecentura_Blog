<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Ecentura\Blog\Model\Config\Source;

/**
 * Used in edit post form
 *
 */
class CategoryTree implements \Magento\Framework\Option\ArrayInterface
{
    protected $_categoryCollectionFactory;

    protected $_options;

    protected $_childs;
    protected $_request;
    public function __construct(
        \Ecentura\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\App\RequestInterface $request

    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_request = $request;
    }

    public function toOptionArray()
    {
        if ($this->_options === null) {
            $this->_options = $this->_getOptions();
        }
//        echo "<pre>";
//        print_r($this->_options);die();
        return $this->_options;
    }

    protected function _getOptions($itemId = 0)
    {
        $childs =  $this->_getChilds();
//        echo "<pre>";
//        print_r($this->_request->getParam('id'));die();
        $options = [];
        if (!$itemId) {
            $options[] = [
                'label' => 'Select...',
                'value' => 0,
            ];
        }
        if (isset($childs[$itemId])) {
            foreach ($childs[$itemId] as $item) {
                if($item->getId() !== $this->_request->getParam('id') ||  $item->getStatus() == false){
                    $data = [
                        'label' => $item->getName() .
                            ($item->getStatus() ? '' : ' ('.__('Disabled').')'),
                        'value' => $item->getId(),
                    ];
                    if (isset($childs[$item->getId()])) {
                        $data['optgroup'] = $this->_getOptions($item->getId());
                    }

                    $options[] = $data;
                }
            }
        }

        return $options;
    }

    protected function _getChilds()
    {
        if ($this->_childs === null) {
            $this->_childs =  $this->_categoryCollectionFactory->create()
                ->getGroupedChilds();
        }
        return $this->_childs;
    }
}
