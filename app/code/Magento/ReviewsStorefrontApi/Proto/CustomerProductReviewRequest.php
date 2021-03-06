<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: reviews/reviews.proto

namespace Magento\ReviewsStorefrontApi\Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>magento.reviewsStorefrontApi.proto.CustomerProductReviewRequest</code>
 */
class CustomerProductReviewRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string customer_id = 1;</code>
     */
    protected $customer_id = '';
    /**
     * Generated from protobuf field <code>string store = 2;</code>
     */
    protected $store = '';
    /**
     * Generated from protobuf field <code>.magento.reviewsStorefrontApi.proto.PaginationRequest pagination = 3;</code>
     */
    protected $pagination = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $customer_id
     *     @type string $store
     *     @type \Magento\ReviewsStorefrontApi\Proto\PaginationRequest $pagination
     * }
     */
    public function __construct($data = null)
    {
        \Magento\ReviewsStorefrontApi\Metadata\Reviews::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string customer_id = 1;</code>
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Generated from protobuf field <code>string customer_id = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setCustomerId($var)
    {
        GPBUtil::checkString($var, true);
        $this->customer_id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string store = 2;</code>
     * @return string
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Generated from protobuf field <code>string store = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setStore($var)
    {
        GPBUtil::checkString($var, true);
        $this->store = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.magento.reviewsStorefrontApi.proto.PaginationRequest pagination = 3;</code>
     * @return \Magento\ReviewsStorefrontApi\Proto\PaginationRequest
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * Generated from protobuf field <code>.magento.reviewsStorefrontApi.proto.PaginationRequest pagination = 3;</code>
     * @param \Magento\ReviewsStorefrontApi\Proto\PaginationRequest $var
     * @return $this
     */
    public function setPagination($var)
    {
        GPBUtil::checkMessage($var, \Magento\ReviewsStorefrontApi\Proto\PaginationRequest::class);
        $this->pagination = $var;

        return $this;
    }
}
