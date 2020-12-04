<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\ConfigurationImportMessageBroker\Model\MessageBus\Import;

use Magento\ConfigurationDataExporter\Event\Data\Config;
use Magento\ConfigurationImportMessageBroker\Model\Configuration\Import\MappingProviderInterface;
use Magento\MessageBroker\Model\ServiceConnector\Connector;
use Psr\Log\LoggerInterface;

/**
 * Default configuration import processor
 */
class DefaultConsumerEvent implements ConsumerEventInterface
{
    /**
     * @var MappingProviderInterface
     */
    private $mappingProvider;

    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param MappingProviderInterface $mappingProvider
     * @param Connector $connector
     * @param LoggerInterface $logger
     */
    public function __construct(
        MappingProviderInterface $mappingProvider,
        Connector $connector,
        LoggerInterface $logger
    ) {
        $this->mappingProvider = $mappingProvider;
        $this->connector = $connector;
        $this->logger = $logger;
    }

    /**
     * Execute import into mapped services of changed configuration
     *
     * @param Config[] $configurations
     *
     * @return void
     */
    public function execute(array $configurations = []): void
    {
        $services = $this->mappingProvider->getMapping();

        foreach ($services as $service) {
            if (!empty($service['name']) && !empty($service['mapping'])) {
                $request = [];

                foreach ($configurations as $configuration) {
                    if (isset($service['mapping'][$configuration->getName()])) {
                        // @TODO should request be an object of defined type?
                        $request[] = [
                            'name' => $service['mapping'][$configuration->getName()],
                            'value' => $configuration->getValue(),
                            'store' => $configuration->getStore()
                        ];
                    }
                }

                if (!empty($request)) {
                    try {
                        $importResult = $this->connector->getConnection($service['name'])->importConfig($request);

                        if ($importResult->getStatus() === false) {
                            $this->logger->error(
                                sprintf(
                                    'Configuration import into service "%s" has failed: "%s"',
                                    $service['name'],
                                    $importResult->getMessage()
                                )
                            );
                        }
                    } catch (\Throwable $e) {
                        $this->logger->critical(
                            sprintf('Exception while importing configurations: "%s"', $e->getMessage())
                        );
                    }
                }
            }
        }
    }
}
