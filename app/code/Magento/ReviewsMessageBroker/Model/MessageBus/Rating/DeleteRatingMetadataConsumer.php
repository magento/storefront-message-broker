<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\ReviewsMessageBroker\Model\MessageBus\Rating;

use Magento\MessageBroker\Model\ServiceConnector\Connector;
use Magento\ReviewsMessageBroker\Model\ServiceConfig;
use Magento\ReviewsStorefrontApi\Api\Data\DeleteRatingsMetadataRequestMapper;
use Magento\ReviewsMessageBroker\Model\MessageBus\ConsumerEventInterface;
use Psr\Log\LoggerInterface;

/**
 * Delete rating metadata from storage
 */
class DeleteRatingMetadataConsumer implements ConsumerEventInterface
{
    /**
     * @var DeleteRatingsMetadataRequestMapper
     */
    private $deleteRatingsMetadataRequestMapper;

    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param DeleteRatingsMetadataRequestMapper $deleteRatingsMetadataRequestMapper
     * @param Connector $connector
     * @param LoggerInterface $logger
     */
    public function __construct(
        DeleteRatingsMetadataRequestMapper $deleteRatingsMetadataRequestMapper,
        Connector $connector,
        LoggerInterface $logger
    ) {
        $this->deleteRatingsMetadataRequestMapper = $deleteRatingsMetadataRequestMapper;
        $this->connector = $connector;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute(array $entities, string $scope = null): void
    {
        $ids = [];

        foreach ($entities as $entity) {
            $ids[] = $entity->getEntityId();
        }

        $deleteRequest = $this->deleteRatingsMetadataRequestMapper->setData(
            [
                'ratingIds' => $ids,
                'store' => $scope,
            ]
        )->build();

        $result = $this->connector
            ->getConnection(ServiceConfig::SERVICE_NAME_RATINGS_METADATA)
            ->deleteRatingsMetadata($deleteRequest);

        if ($result->getStatus() === false) {
            $this->logger->error(\sprintf('Rating metadata deletion has failed: "%s"', $result->getMessage()));
        }
    }
}
