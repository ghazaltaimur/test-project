<?php

// ./module/Application/src/Application/View/Helper/AssetUrl.php

/**
 * @namespace
 */
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Exception;

/**
* Helper for retrieving an asset URL.
*
* @package Application_View
* @subpackage Helper
*/
class AssetUrl extends AbstractHelper {  

    /**
     * Base URL for assets.
     *
     * @var string
     */
    protected $assetBaseUrl;

    /**
     * Suffix for assets.
     *
     * @var string
     */
    protected $suffix;

    /**
     * Returns the URL for an asset.
     *
     * If a suffix was set, it will be appended as query parameter to the URL.
     *
     * @param string $file
     * @parma boolean $omitSuffix
     * @return string
     */
    public function __invoke($file, $omitSuffix = false) {
      if (null === $this->assetBaseUrl) {
        throw new Exception\RuntimeException('No asset base URL provided');
      }

      $url = $this->assetBaseUrl . '/' . ltrim($file, '/');

      if (!$omitSuffix && null !== $this->suffix) {
        if (strpos($url, '?') === false) {
          $url .= '?' . $this->suffix;
        } else {
          $url .= '&' . $this->suffix;
        }
      }

      return $url;
    }

    /**
     * Set the asset base URL.
     *
     * @param string $assetBaseUrl
     * @return AssetUrl
     */
    public function setAssetBaseUrl($assetBaseUrl) {
      $this->assetBaseUrl = rtrim($assetBaseUrl, '/');
      return $this;
    }

    /**
     * Set a suffix for assets.
     *
     * @param string $suffix
     * @return AssetUrl
     */
    public function setSuffix($suffix) {
      $this->suffix = urlencode($suffix);
      return $this;
    }

  }