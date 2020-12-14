<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\ReviewsMessageBroker\Model;

/**
 * Provide Reviews Service configuration required to setup communication between services
 */
class ServiceConfig
{
    /**
     * Catalog service name
     */
    public const SERVICE_NAME_REVIEWS = 'reviews';

    /**
     * Catalog service name
     */
    public const SERVICE_NAME_RATINGS_METADATA = 'ratings_metadata';
}
