<?php

declare(strict_types=1);

/*
 * This file is part of the Superdesk Web Publisher Analytics Bundle.
 *
 * Copyright 2017 Sourcefabric z.ú. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2017 Sourcefabric z.ú
 * @license http://www.superdesk.org/license
 */

namespace SWP\Bundle\AnalyticsBundle\EventListener;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class AnalyticsEventListener
{
    public const TERMINATE_IMMEDIATELY = 'terminate-immediately';

    public const EVENT_ENDPOINT = '_swp_analytics';

    protected $producer;

    public function __construct(ProducerInterface $producer)
    {
        $this->producer = $producer;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (strpos($request->getPathInfo(), self::EVENT_ENDPOINT) &&
            in_array($request->getMethod(), ['POST', 'GET'])
        ) {
            if (null !== ($json = file_get_contents('php://input')) && '' !== $json) {
                $request->attributes->set('data', \json_decode($json, true));
            } elseif (null !== $json = $request->getContent()) {
                $request->attributes->set('data', \json_decode($json, true));
            }

            $this->producer->publish(serialize($request));

            $response = new Response();
            $response->headers->add([self::TERMINATE_IMMEDIATELY => true]);
            $event->setResponse($response);
            $event->stopPropagation();
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if ($response->headers->has(self::TERMINATE_IMMEDIATELY)) {
            $event->stopPropagation();
        }
    }

    public function onKernelFinishRequest(FinishRequestEvent $event): void
    {
        $request = $event->getRequest();
        if (strpos($request->getPathInfo(), self::EVENT_ENDPOINT)) {
            $event->stopPropagation();
        }
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        $response = $event->getResponse();
        if ($response->headers->has(self::TERMINATE_IMMEDIATELY)) {
            $event->stopPropagation();
        }
    }
}
