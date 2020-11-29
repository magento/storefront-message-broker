<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogMessageBroker\Model\MessageBus\Product;

use Magento\CatalogStorefrontApi\Api\Data\DeleteProductsRequestInterfaceFactory;
use Magento\CatalogMessageBroker\Model\MessageBus\ConsumerEventInterface;
use Magento\MessageBroker\Model\ServiceConnector\Connector;
use Psr\Log\LoggerInterface;

/**
 * Delete products from storage
 */
class DeleteProductsConsumer implements ConsumerEventInterface
{
    /**
     * @var DeleteProductsRequestInterfaceFactory
     */
    private $deleteProductsRequestInterfaceFactory;

    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param DeleteProductsRequestInterfaceFactory $deleteProductsRequestInterfaceFactory
     * @param Connector $connector
     * @param LoggerInterface $logger
     */
    public function __construct(
        DeleteProductsRequestInterfaceFactory $deleteProductsRequestInterfaceFactory,
        Connector $connector,
        LoggerInterface $logger
    ) {
        $this->deleteProductsRequestInterfaceFactory = $deleteProductsRequestInterfaceFactory;
        $this->connector = $connector;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute(array $entities, ?string $scope = null): void
    {
        $ids = [];

        foreach ($entities as $entity) {
            $ids[] = $entity->getEntityId();
        }

        $deleteProductRequest = $this->deleteProductsRequestInterfaceFactory->create();
        $deleteProductRequest->setProductIds($ids);
        $deleteProductRequest->setStore($scope);

        try {
            $importResult = $this->connector
                ->getConnection(\Magento\CatalogMessageBroker\Model\ServiceConfig::SERVICE_NAME_CATALOG)
                ->deleteProducts($deleteProductRequest);

            if ($importResult->getStatus() === false) {
                $this->logger->error(sprintf('Products deletion has failed: "%s"', $importResult->getMessage()));
            }
        } catch (\Throwable $e) {
            $this->logger->critical(sprintf('Exception while deleting products: "%s"', $e));
        }
    }
}
