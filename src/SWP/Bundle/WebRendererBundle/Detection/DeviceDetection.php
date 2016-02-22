<?php

 /**
  * This file is part of the Superdesk Web Publisher Web Renderer Bundle.
  *
  * Copyright 2015 Sourcefabric z.u. and contributors.
  *
  * For the full copyright and license information, please see the
  * AUTHORS and LICENSE files distributed with this source code.
  *
  * Some parts of that file were taken from the Liip/ThemeBundle
  * (c) Liip AG
  *
  * @copyright 2015 Sourcefabric z.ú.
  * @license http://www.superdesk.org/license
  */

namespace SWP\Bundle\WebRendererBundle\Detection;

use SWP\Bundle\WebRendererBundle\Detection\DeviceDetectionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class DeviceDetection implements DeviceDetectionInterface
{
    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @var array
     */
    protected $devices = array(
        'tablet' => array(
            'androidtablet' => 'android(?!.*(?:mobile|opera mobi|opera mini))',
            'blackberrytablet' => 'rim tablet os',
            'ipad' => '(ipad)',
        ),
        'plain' => array(
            'kindle' => '(kindle)',
            'IE6' => 'MSIE 6.0',
        ),
        'phone' => array(
            'android' => 'android.*mobile|android.*opera mobi|android.*opera mini',
            'blackberry' => 'blackberry',
            'iphone' => '(iphone|ipod)',
            'palm' => '(avantgo|blazer|elaine|hiptop|palm|plucker|xiino|webOS)',
            'windows' => 'windows ce; (iemobile|ppc|smartphone)',
            'windowsphone' => 'windows phone',
            'generic' => '(mobile|mmp|midp|o2|pda|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap|opera mini|opera mobi|opera mini)',
        ),
        'desktop' => array(
            'osx' => 'Mac OS X',
            'linux' => 'Linux',
            'windows' => 'Windows',
            'generic' => '',
        ),
    );

    /**
     * @var string
     */
    protected $type = null;

    /**
     * @var string
     */
    protected $device = null;

    /**
     * @param string $userAgent
     */
    public function __construct($userAgent = null)
    {
        $this->setUserAgent($userAgent);
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    public function setUserAgentFromRequest(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        if (!is_null($request)) {
            $this->setUserAgent($request->headers->get('User-Agent'));
        }
    }

    /**
     * @param array $devices
     */
    public function setDevices($devices)
    {
        $this->devices = $devices;
    }

    protected function init()
    {
        if (null === $this->type || null === $this->device) {
            list($device, $type) = $this->determineDevice($this->userAgent);
            $this->device = $device;
            $this->type = $type;
        }
    }

    public function determineDevice($userAgent)
    {
        foreach ($this->devices as $type => $devices) {
            foreach ($devices as $device => $regexp) {
                if ((bool) preg_match('/'.$regexp.'/i', $userAgent)) {
                    return array($device, $type);
                }
            }
        }

        return array(null, null);
    }

    /**
     * @return string
     */
    public function getType()
    {
        $this->init();

        return $this->type;
    }

    /**
     * Force device type.
     *
     * @param string $type
     */
    public function setType($type)
    {
        if (in_array($type, array_keys($this->devices))) {
            $this->type = $type;
        }
    }

    /**
     * @return string
     */
    public function getDevice()
    {
        $this->init();

        return $this->device;
    }
}