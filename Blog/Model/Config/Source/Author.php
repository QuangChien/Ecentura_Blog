<?php

namespace Ecentura\Blog\Model\Config\Source;

/**
 * Authors list
 *
 */
class Author implements \Magento\Framework\Option\ArrayInterface
{
    protected $authorCollectionFactory;

    protected $options;

    public function __construct(
        \Ecentura\Blog\Model\ResourceModel\Author\CollectionFactory $authorCollectionFactory
    ) {
        $this->authorCollectionFactory = $authorCollectionFactory;
    }

    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [['label' => __('Please select'), 'value' => '']];
            $collection = $this->authorCollectionFactory->create();

            foreach ($collection as $item) {
                $this->options[] = [
                    'label' => $item->getName(),
                    'value' => $item->getAuthorId(),
                ];
            }
        }

        return $this->options;
    }

    public function toArray()
    {
        $array = [];
        foreach ($this->toOptionArray() as $item) {
            $array[$item['value']] = $item['label'];
        }
        return $array;
    }
}
