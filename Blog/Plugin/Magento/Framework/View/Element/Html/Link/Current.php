<?php

namespace Ecentura\Blog\Plugin\Magento\Framework\View\Element\Html\Link;

/**
 * Class Current
 * @package Techflarestudio\Slashes\Plugin\Magento\Framework\View\Element\Html\Link
 */
class Current
{
    /**
     * Remove trailing slashes from hrefs
     *
     * @param $subject
     * @param $result
     * @return string
     */
    public function afterGetHref($subject, $result)
    {
        if (empty($result) || !is_string($result)) {
            return $result;
        }

        return rtrim($result, '/');
    }
}
