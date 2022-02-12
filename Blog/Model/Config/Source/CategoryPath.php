<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Ecentura\Blog\Model\Config\Source;

/**
 * Used in recent post widget
 *
 */
class CategoryPath extends CategoryTree
{
    protected function _getOptions($itemId = 0)
    {
        $childs =  $this->_getChilds();

        $options = [];

//        if (!$itemId) {
//            $options[] = [
//                'label' => 'Empty',
//                'value' => 0,
//                'parent' => 0,
//                'is_active' => 1
//            ];
//        }

        if (isset($childs[$itemId])) {
            foreach ($childs[$itemId] as $item) {
                $data = [
                    'label' => $item->getName() .
                        ($item->getStatus() ? '' : ' ('.__('Disabled').')'),
                    'value' => $item->getId(),
                    'parent' => ($item->getParentIds() ? $item->getPath() : 0),
                    'status' => $item->getStatus()
                ];
                if (isset($childs[$item->getId()])) {
                    $data['optgroup'] = $this->_getOptions($item->getId());
                }

                $options[] = $data;
            }
        }
        return $options;
    }
}
