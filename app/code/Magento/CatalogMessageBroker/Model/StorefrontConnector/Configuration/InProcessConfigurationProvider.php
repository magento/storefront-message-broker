<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\CatalogMessageBroker\Model\StorefrontConnector\Configuration;

use Grpc\ChannelCredentials;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;

/**
 * Configuration provider class for gRPC connection.
 */
class InProcessConfigurationProvider implements ConfigurationProviderInterface
{
    /**
     * gRPC configuration client hostname.
     */
    private const GRPC_CLIENT_HOSTNAME = 'system/default/grpc_client/connection/hostname';

    /**
     * gRPC configuration client port.
     */
    private const GRPC_CLIENT_PORT = 'system/default/grpc_client/connection/port';

    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * @param DeploymentConfig $deploymentConfig
     */
    public function __construct(DeploymentConfig $deploymentConfig)
    {
        $this->deploymentConfig = $deploymentConfig;
    }

    /**
     * @inheritdoc
     *
     * @throws \InvalidArgumentException
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function provide(): array
    {
        $hostname = $this->deploymentConfig->get(self::GRPC_CLIENT_HOSTNAME);
        $port = $this->deploymentConfig->get(self::GRPC_CLIENT_PORT);

        if (!$hostname || !$port) {
            throw new \InvalidArgumentException('Incorrect gRPC connection configuration.');
        }

        return [
            'hostname' => \sprintf('%s:%s', $hostname, $port),
            'options' => [
                'credentials' => ChannelCredentials::createInsecure(),
            ],
        ];
    }
}
