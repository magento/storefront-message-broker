<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogMessageBrokerMessageQueue\Stub\Json;

use Magento\Framework\Json\EncoderInterface;

/**
 * @deprecated 101.0.0 @see \Magento\Framework\Serialize\Serializer\Json::serialize
 */
class Encoder implements EncoderInterface
{
    /**
     * Encode the mixed $data into the JSON format.
     *
     * @param mixed $data
     * @return string
     */
    public function encode($data)
    {
        echo (new \Exception())->getTraceAsString();
        echo __METHOD__, "\n\n";

        return \Zend_Json::encode($data);
    }
}