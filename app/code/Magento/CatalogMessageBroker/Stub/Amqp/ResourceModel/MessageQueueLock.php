<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogMessageBroker\Stub\Amqp\ResourceModel;

use \Magento\Framework\MessageQueue\Lock\ReaderInterface;
use \Magento\Framework\MessageQueue\Lock\WriterInterface;

/**
 * Stub class for Magento\MessageQueue\Model\ResourceModel\Lock to prevent error during message processing.
 * Lock was implemented for "db" connection type
 */
class MessageQueueLock extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb implements
    ReaderInterface,
    WriterInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        //Stub cont
    }

    /**
     * @inheritDoc
     */
    public function read(\Magento\Framework\MessageQueue\LockInterface $lock, $code)
    {
        //Stub content
    }

    /**
     * @inheritDoc
     */
    public function saveLock(\Magento\Framework\MessageQueue\LockInterface $lock)
    {
        //Stub content
    }

    /**
     * @inheritDoc
     */
    public function releaseOutdatedLocks()
    {
        //Stub content
    }
}
