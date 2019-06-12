<?php

declare(strict_types=1);

/*
 * This file is part of the Superdesk Web Publisher Analytics Bundle.
 *
 * Copyright 2019 Sourcefabric z.ú. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2019 Sourcefabric z.ú
 * @license http://www.superdesk.org/license
 */

namespace SWP\Bundle\AnalyticsBundle\Command;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetupDatabasesCommand extends Command
{
    protected static $defaultName = 'swp:analytics:setup-databases';

    protected $dynamoDbClient;

    protected function configure()
    {
        $this
            ->setDescription('Setup required databases and tables.')
        ;
    }

    public function __construct(DynamoDbClient $dynamoDbClient)
    {
        parent::__construct();

        $this->dynamoDbClient = $dynamoDbClient;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = [
            'TableName' => 'ArticleEvents',
            'KeySchema' => [
                [
                    'AttributeName' => 'id',
                    'KeyType' => 'HASH',
                ],
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'id',
                    'AttributeType' => 'S',
                ],
            ],
        ];

        try {
            $result = $this->dynamoDbClient->createTable($params);
            $output->writeln(sprintf('Created table. Status: %s', $result['TableDescription']['TableStatus']));
        } catch (DynamoDbException $e) {
            $output->writeln(sprintf('Unable to create table: %s', $e->getMessage()));
        }
    }
}
