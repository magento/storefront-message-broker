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
class GrpcConfigurationProvider implements ConfigurationProviderInterface
{
    /**
     * gRPC configuration client hostname.
     */
    private const GRPC_CLIENT_HOSTNAME = 'grpc/connections/%s/hostname';

    /**
     * gRPC configuration client port.
     */
    private const GRPC_CLIENT_PORT = 'grpc/connections/%s/port';

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
    public function provide(string $connectionName): array
    {
        $hostname = $this->deploymentConfig->get(
            sprintf(self::GRPC_CLIENT_HOSTNAME, $connectionName)
        );
        $port = $this->deploymentConfig->get(
            sprintf(self::GRPC_CLIENT_PORT, $connectionName)
        );

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
