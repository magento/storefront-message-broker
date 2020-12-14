<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MessageBroker\Model;

use Magento\Framework\App\DeploymentConfig\Writer;
use Magento\Framework\Config\Data\ConfigData;
use Magento\Framework\Setup\Option\AbstractConfigOption;
use Magento\Framework\Stdlib\DateTime;
use Symfony\Component\Console\Input\InputDefinition;

class Installer
{
    /**
     * @var string
     */
    public const SERVICE_COMMUNICATION_CONNECTION_TYPE = 'GRPC_CONNECTION_TYPE';
    public const DEFAULT_CONNECTION_TYPE = 'in-memory';

    /**
     * Configuration for AMQP
     */
    const AMQP_HOST = 'amqp-host';
    const AMQP_PORT = 'amqp-port';
    const AMQP_USER = 'amqp-user';
    const AMQP_PASSWORD = 'amqp-password';
    const GRPC_CONNECTON_TYPE = 'connection_type';
    const CONSUMER_WAIT_FOR_MESSAGES = 'consumers_wait_for_messages';

    /**
     * Configuration for Cache Redis
     */
    const CONFIG_VALUE_CACHE_REDIS = \Magento\Framework\Cache\Backend\Redis::class;
    const INPUT_VALUE_CACHE_REDIS = 'redis';
    const INPUT_KEY_CACHE_BACKEND = 'cache-backend';
    const INPUT_KEY_CACHE_BACKEND_REDIS_SERVER = 'cache-backend-redis-server';
    const INPUT_KEY_CACHE_BACKEND_REDIS_DATABASE = 'cache-backend-redis-db';
    const INPUT_KEY_CACHE_BACKEND_REDIS_PORT = 'cache-backend-redis-port';
    const INPUT_KEY_CACHE_BACKEND_REDIS_PASSWORD = 'cache-backend-redis-password';
    const INPUT_KEY_CACHE_BACKEND_REDIS_COMPRESS_DATA = 'cache-backend-redis-compress-data';
    const INPUT_KEY_CACHE_BACKEND_REDIS_COMPRESSION_LIB = 'cache-backend-redis-compression-lib';
    const INPUT_KEY_CACHE_ID_PREFIX = 'cache-id-prefix';

    /**
     * Other settings
     */
    const BASE_URL = 'backoffice-base-url';

    /**
     * gRPC configuration client hostname.
     */
    public const GRPC_HOSTNAME = 'grpc-hostname';

    /**
     * gRPC configuration client port.
     */
    public const GRPC_PORT = 'grpc-port';

    /**
     * @var Writer
     */
    private $deploymentConfigWriter;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var ModulesCollector
     */
    private $modulesCollector;

    /**
     * Installer constructor.
     * @param Writer $deploymentConfigWriter
     * @param DateTime $dateTime
     * @param ModulesCollector $modulesCollector
     */
    public function __construct(
        Writer $deploymentConfigWriter,
        DateTime $dateTime,
        ModulesCollector $modulesCollector
    ) {
        $this->deploymentConfigWriter = $deploymentConfigWriter;
        $this->dateTime = $dateTime;
        $this->modulesCollector = $modulesCollector;
    }

    /**
     * Prepare cache list
     *
     * @return array
     */
    private function getCacheTypes(): array
    {
        return [
            'config' => 1,
            'compiled_config' => 1
        ];
    }

    private function getCacheStorage(array $givenOptions, $definitionOptions): array
    {
        $configData = new ConfigData('app_env');
        $cacheOptions = array_filter($definitionOptions->getOptions(), function($object) {
            return $object instanceof AbstractConfigOption;
        });
        foreach ($cacheOptions as $option) {
            $optionData = $givenOptions[$option->getName()];
            if ($option->getName() === self::INPUT_KEY_CACHE_BACKEND
                && $optionData == self::INPUT_VALUE_CACHE_REDIS) {
                $optionData = self::CONFIG_VALUE_CACHE_REDIS;
            }
            $configData->set($option->getConfigPath(), $optionData);
        }
        return $configData->getData();
    }

    /**
     * Create env.php file configuration
     * @param array $givenOptions
     * @param InputDefinition $definitionOptions
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function install(array $givenOptions, InputDefinition $definitionOptions): void
    {
        if (!isset($givenOptions[self::GRPC_CONNECTON_TYPE])) {
            $givenOptions[self::GRPC_CONNECTON_TYPE] = self::DEFAULT_CONNECTION_TYPE;
        }

        $config = [
            'app_env' => [
                self::SERVICE_COMMUNICATION_CONNECTION_TYPE => $givenOptions[self::GRPC_CONNECTON_TYPE],
                'cache_types' => $this->getCacheTypes(),
                'queue' => [
                    'consumers_wait_for_messages' => $givenOptions[self::CONSUMER_WAIT_FOR_MESSAGES],
                    'amqp' => [
                        'host' => $givenOptions[self::AMQP_HOST],
                        'user' => $givenOptions[self::AMQP_USER],
                        'password' => $givenOptions[self::AMQP_PASSWORD],
                        'port' => $givenOptions[self::AMQP_PORT],
                    ]
                ],
                'system' => [
                    'default' => [
                        'backoffice' => [
                            'web' => [
                                'base_url' => $givenOptions[self::BASE_URL]
                            ]
                        ]
                    ],
                ],
                'install' => [
                    'date' => $this->dateTime->formatDate(true)
                ]
            ],
            'app_config' => [
                'modules' => $this->modulesCollector->execute()
            ]
        ];

        if (!empty($givenOptions[self::INPUT_KEY_CACHE_BACKEND])) {
            $config['app_env'] = array_merge($config['app_env'], $this->getCacheStorage($givenOptions, $definitionOptions));
        }

        $this->deploymentConfigWriter->saveConfig($config);
    }
}
