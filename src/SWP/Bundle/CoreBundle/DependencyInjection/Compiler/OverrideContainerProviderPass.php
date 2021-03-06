<?php

/*
 * This file is part of the Superdesk Web Publisher Core Bundle.
 *
 * Copyright 2016 Sourcefabric z.ú. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2016 Sourcefabric z.ú
 * @license http://www.superdesk.org/license
 */

namespace SWP\Bundle\CoreBundle\DependencyInjection\Compiler;

use SWP\Bundle\CoreBundle\Provider\ContainerProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class OverrideContainerProviderPass extends AbstractOverridePass
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $containerProvider = $this->getDefinitionIfExists($container, 'swp.provider.container');
        $containerProvider
            ->setClass(ContainerProvider::class)
            ->addMethodCall('setRevisionContext', [new Reference('swp_revision.context.revision')])
        ;
    }
}
