<?php

namespace Ecentura\Blog\Plugin\Magento\Framework;

use Magento\Framework\Url as UrlFramework;

/**
 * Class Url
 * @package Techflarestudio\Slashes\Plugin\Magento\Framework
 */
class Url
{
    /**
     * @param UrlFramework $subject
     * @param $result
     * @return string
     */
    public function afterGetRouteUrl(
        UrlFramework $subject,
                     $result
    ) {
        if (empty($result) || !is_string($result)) {
            return $result;
        }

        return rtrim($result, '/');
    }
}
