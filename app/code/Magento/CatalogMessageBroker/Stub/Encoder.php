<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\CatalogMessageBroker\Stub;

use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Stub to allow instantiation of \Magento\Framework\MessageQueue\MessageEncoder
 */
class Encoder implements EncoderInterface
{
    /**
     * @var Json
     */
    private Json $json;

    /**
     * Encoder constructor.
     * @param Json $json
     */
    public function __construct(
        Json $json
    ) {
        $this->json = $json;
    }

    /**
     * Encode the mixed $data into the JSON format.
     *
     * @param mixed $data
     * @return string
     */
    public function encode($data)
    {
        return $this->json->serialize($data);
    }
}
