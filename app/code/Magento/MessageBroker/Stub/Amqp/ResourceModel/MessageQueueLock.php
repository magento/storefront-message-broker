<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MessageBroker\Stub\Amqp\ResourceModel;

use Magento\Framework\App\ObjectManager;
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
     * Class in Magento monolith for instantiation for case with monolithic installation
     */
    private const MONOLITH_CLASS = '\Magento' . '\MessageQueue\Model\ResourceModel\Lock';

    /**
     * @var bool
     */
    private $isMonolithicInstallation;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function read(\Magento\Framework\MessageQueue\LockInterface $lock, $code)
    {
        if ($this->isMonolithicInstallation()) {
            return ObjectManager::getInstance()->get(self::MONOLITH_CLASS)->read($lock, $code);
        }
    }

    /**
     * @inheritDoc
     */
    public function saveLock(\Magento\Framework\MessageQueue\LockInterface $lock)
    {
        if ($this->isMonolithicInstallation()) {
            return ObjectManager::getInstance()->get(self::MONOLITH_CLASS)->saveLock($lock);
        }
    }

    /**
     * @inheritDoc
     */
    public function releaseOutdatedLocks()
    {
        if ($this->isMonolithicInstallation()) {
            return ObjectManager::getInstance()->get(self::MONOLITH_CLASS)->releaseOutdatedLocks();
        }
    }


    /**
     * Ad-hoc solution for monolithic installation, used to run tests on CI.
     * No actual dependency for standalone installation
     *
     * @return bool
     */
    private function isMonolithicInstallation(): bool
    {
        if ($this->isMonolithicInstallation === null) {
            $this->isMonolithicInstallation = \class_exists('\Magento' . '\Config\Model\Config');
        }

        return $this->isMonolithicInstallation;
    }
}
