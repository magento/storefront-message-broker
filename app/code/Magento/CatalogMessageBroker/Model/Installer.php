<?php

namespace Magento\CatalogMessageBroker\Model;

use Magento\Framework\App\DeploymentConfig\Writer;
use Magento\Framework\Stdlib\DateTime;

class Installer
{
    /**
     * Configuration for AMQP
     */
    const AMQP_HOST = 'host';
    const AMQP_PORT = 'port';
    const AMQP_USER = 'user';
    const AMQP_PASSWORD = 'password';
    /**
     * Configuration for Elasticsea
     */
    const ELASTICSEARCH_HOST = 'elasticsearch_server_hostname';
    const ELASTICSEARCH_ENGINE = 'engine';
    const ELASTICSEARCH_PORT = 'elasticsearch_server_port';
    const ELASTICSEARCH_INDEX_PREFIX = 'elasticsearch_index_prefix';
    /**
     * Other settings
     */
    const BASE_URL = 'backoffice-base-url';

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

    /**
     * Create env.php file configuration
     *
     * @param array $parameters
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function install(array $parameters): void
    {
        $config = [
            'app_env' => [
                'cache_types' => $this->getCacheTypes(),
                'queue' => [
                    'consumers_wait_for_messages' => 0,
                    'amqp' => [
                        self::AMQP_HOST => $parameters[self::AMQP_HOST],
                        self::AMQP_USER => $parameters[self::AMQP_USER],
                        self::AMQP_PASSWORD => $parameters[self::AMQP_PASSWORD],
                        self::AMQP_PORT => $parameters[self::AMQP_PORT],
                    ]
                ],
                'system' => [
                    'default' => [
                        'backoffice' => [
                            'web' => [
                                'base_url' => $parameters[self::BASE_URL]
                            ]
                        ]
                    ]
                ],
                'catalog-store-front' => [
                    'connections' => [
                        'default' => [
                            'protocol' => 'http',
                            'hostname' => $parameters[self::ELASTICSEARCH_HOST],
                            'port' => $parameters[self::ELASTICSEARCH_PORT],
                            'username' => '',
                            'password' => '',
                            'timeout' => 3
                        ]
                    ]
                ],
                'install' => [
                    'date' => $this->dateTime->formatDate(true)
                ]
            ],
            'app_config' => $this->modulesCollector->execute()
        ];

        $this->deploymentConfigWriter->saveConfig($config);
    }
}
