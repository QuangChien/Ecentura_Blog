<?php
namespace Ecentura\Blog\Model;
class Category extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Ecentura\Blog\Model\ResourceModel\Category');
    }

    /**
     * Retrieve parent category ids
     * @return array
     */
    public function getParentIds()
    {
        $k = 'parent_ids';
        if (!$this->hasData($k)) {
            $this->setData(
                $k,
                $this->getPath() ? explode('/', $this->getPath()) : []
            );
        }

        return $this->getData($k);
    }

    /**
     * Retrieve parent category id
     * @return array
     */
    public function getParentId()
    {
        $parentIds = $this->getParentIds();
        if ($parentIds) {
            return $parentIds[count($parentIds) - 1];
        }

        return 0;
    }

    /**
     * Retrieve parent category
     * @return self || false
     */
    public function getParentCategory()
    {
        $k = 'parent_category';
        if (null === $this->getData($k)) {
            $this->setData($k, false);
            if ($pId = $this->getParentId()) {
                $category = $this->loadFromRepository($pId);
                if ($category->getId()) {
                    if ($category->isVisibleOnStore($this->getStoreId())) {
                        $this->setData($k, $category);
                    }
                }
            }
        }

        return $this->getData($k);
    }

    /**
     * Check if current category is parent category
     * @param  self  $category
     * @return boolean
     */
    public function isParent($category)
    {
        if (is_object($category)) {
            $category = $category->getId();
        }

        return in_array($category, $this->getParentIds());
    }

    /**
     * Retrieve children category ids
     * @param  bool  $grandchildren
     * @return array
     */
    public function getChildrenIds($grandchildren = true)
    {
        $k = 'children_ids';
        if (!$this->hasData($k)) {
            $categories = \Magento\Framework\App\ObjectManager::getInstance()
                ->create($this->_collectionName);

            $allIds = $ids = [];
            foreach ($categories as $category) {
                if ($category->isParent($this)) {
                    $allIds[] = $category->getId();
                    if ($category->getLevel() == $this->getLevel() + 1) {
                        $ids[] = $category->getId();
                    }
                }
            }

            $this->setData('all_' . $k, $allIds);
            $this->setData($k, $ids);
        }

        return $this->getData(
            ($grandchildren ? 'all_' : '') . $k
        );
    }

    /**
     * Check if current category is child category
     * @param  self  $category
     * @return boolean
     */
    public function isChild($category)
    {
        return $category->isParent($this);
    }

    /**
     * Retrieve category depth level
     * @return int
     */
    public function getLevel()
    {
        return count($this->getParentIds());
    }
}
