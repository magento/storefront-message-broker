<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MessageBroker\Model;

use Magento\Framework\App\DeploymentConfig\Writer;
use Magento\Framework\Stdlib\DateTime;

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
     * Create env.php file configuration
     *
     * @param array $parameters
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function install(array $parameters): void
    {
        if (!isset($parameters[self::GRPC_CONNECTON_TYPE])) {
            $parameters[self::GRPC_CONNECTON_TYPE] = self::DEFAULT_CONNECTION_TYPE;
        }

        $config = [
            'app_env' => [
                self::SERVICE_COMMUNICATION_CONNECTION_TYPE => $parameters[self::GRPC_CONNECTON_TYPE],
                'queue' => [
                    'consumers_wait_for_messages' => $parameters[self::CONSUMER_WAIT_FOR_MESSAGES],
                    'amqp' => [
                        'host' => $parameters[self::AMQP_HOST],
                        'user' => $parameters[self::AMQP_USER],
                        'password' => $parameters[self::AMQP_PASSWORD],
                        'port' => $parameters[self::AMQP_PORT],
                    ]
                ],
                'system' => [
                    'default' => [
                        'backoffice' => [
                            'web' => [
                                'base_url' => $parameters[self::BASE_URL]
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

        $this->deploymentConfigWriter->saveConfig($config);
    }
}
