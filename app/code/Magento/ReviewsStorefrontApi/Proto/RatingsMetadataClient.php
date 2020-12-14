<?php
// GENERATED CODE -- DO NOT EDIT!

// Original file comments:
// *
// Copyright © Magento, Inc. All rights reserved.
// See COPYING.txt for license details.
namespace Magento\ReviewsStorefrontApi\Proto;

/**
 */
class RatingsMetadataClient extends \Grpc\BaseStub
{

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null)
    {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Magento\ReviewsStorefrontApi\Proto\ImportRatingsMetadataRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function importRatingsMetadata(
        \Magento\ReviewsStorefrontApi\Proto\ImportRatingsMetadataRequest $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/magento.reviewsStorefrontApi.proto.RatingsMetadata/importRatingsMetadata',
            $argument,
            ['\Magento\ReviewsStorefrontApi\Proto\ImportRatingsMetadataResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \Magento\ReviewsStorefrontApi\Proto\DeleteRatingsMetadataRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function deleteRatingsMetadata(
        \Magento\ReviewsStorefrontApi\Proto\DeleteRatingsMetadataRequest $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/magento.reviewsStorefrontApi.proto.RatingsMetadata/deleteRatingsMetadata',
            $argument,
            ['\Magento\ReviewsStorefrontApi\Proto\DeleteRatingsMetadataResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \Magento\ReviewsStorefrontApi\Proto\RatingsMetadataRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function getRatingsMetadata(
        \Magento\ReviewsStorefrontApi\Proto\RatingsMetadataRequest $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/magento.reviewsStorefrontApi.proto.RatingsMetadata/getRatingsMetadata',
            $argument,
            ['\Magento\ReviewsStorefrontApi\Proto\RatingsMetadataResponse', 'decode'],
            $metadata,
            $options
        );
    }
}
