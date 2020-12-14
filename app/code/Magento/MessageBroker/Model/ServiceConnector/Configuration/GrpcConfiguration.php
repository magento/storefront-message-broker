<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\MessageBroker\Model\ServiceConnector\Configuration;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;

/**
 * Configuration provider class for gRPC connection.
 */
class GrpcConfiguration implements ConfigurationInterface
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
    public function retrieve(string $connectionName): array
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
                'credentials' => null, // \Grpc\ChannelCredentials::createInsecure()
            ],
        ];
    }
}
