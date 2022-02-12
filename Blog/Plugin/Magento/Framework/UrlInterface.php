<?php

namespace Ecentura\Blog\Plugin\Magento\Framework;

use Magento\Framework\UrlInterface as UrlInterfaceFramework;

/**
 * Class UrlInterface
 * @package Techflarestudio\Slashes\Plugin\Magento\Framework
 */
class UrlInterface
{
    /**
     * Remove trailing slash for getUrl
     *
     * @param UrlInterfaceFramework $subject
     * @param string $result
     * @return string
     */
    public function afterGetUrl(
        UrlInterfaceFramework $subject,
                              $result
    ) {
        if (empty($result) || !is_string($result)) {
            return $result;
        }

        return rtrim($result, '/');
    }
}
