<?php

/**
 * @namespace
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Exception;

/**
 * Helper for retrieving an website URL for use in email templates.
 *
 * @package Application_View
 * @subpackage Helper
 */
class WebUrl extends AbstractHelper {

  /**
   * Base URL for web.
   *
   * @var string
   */
  protected $webBaseUrl;

  /**
   * Returns the URL for an asset.
   *
   * If a suffix was set, it will be appended as query parameter to the URL.
   *
   * @param string $file
   * @return string
   */
  public function __invoke($file) {
    if (null === $this->webBaseUrl) {
      throw new Exception\RuntimeException('No web base URL provided');
    }

    $url = $this->webBaseUrl . '/' . ltrim($file, '/');

    return $url;
  }

  /**
   * Set the web base URL.
   *
   * @param string $webBaseUrl
   * @return AssetUrl
   */
  public function setWebBaseUrl($webBaseUrl) {
    $this->webBaseUrl = rtrim($webBaseUrl, '/');
    return $this;
  }

}