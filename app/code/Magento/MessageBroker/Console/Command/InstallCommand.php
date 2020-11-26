<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MessageBroker\Console\Command;

use Magento\MessageBroker\Model\Installer;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to backup code base and user data
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InstallCommand extends Command
{
    /**
     * @var Installer
     */
    private $installer;

    /**
     * TopologyInstall constructor.
     * @param Installer $installer
     * @param string|null $name
     */
    public function __construct(
        Installer $installer,
        string $name = null
    ) {
        parent::__construct($name);
        $this->installer = $installer;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('message-broker:install')
            ->setDescription('Install Message Broker')
            ->setDefinition($this->getOptionsList());

        parent::configure();
    }

    /**
     * Prepare options list
     *
     * @param int $mode
     * @return InputOption[]
     */
    public function getOptionsList($mode = InputOption::VALUE_REQUIRED)
    {
        return [
            new InputOption(
                Installer::AMQP_HOST,
                null,
                $mode,
                'AMQP host'
            ),
            new InputOption(
                Installer::AMQP_PORT,
                null,
                $mode,
                'AMQP port'
            ),
            new InputOption(
                Installer::AMQP_PASSWORD,
                null,
                $mode,
                'AMQP password'
            ),
            new InputOption(
                Installer::AMQP_USER,
                null,
                $mode,
                'AMQP user'
            ),
            new InputOption(
                Installer::BASE_URL,
                null,
                $mode,
                'Base URL'
            ),
            new InputOption(
                Installer::GRPC_CONNECTON_TYPE,
                null,
                \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
                'gRPC connection type'
            ),
            new InputOption(
                Installer::CONSUMER_WAIT_FOR_MESSAGES,
                null,
                $mode,
                'Consumer wait for messages'
            )
        ];
    }

    /**
     * Validate input options
     *
     * @param InputInterface $input
     * @return void
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     */
    private function validate(InputInterface $input): void
    {
        $givenOptions = $input->getOptions();
        foreach ($this->getOptionsList() as $option) {
            if ($option->isValueRequired()) {
                if (!isset($givenOptions[$option->getName()])) {
                    throw new \Symfony\Component\Console\Exception\RuntimeException(
                        sprintf("%s option is not specified. Please, specify all required options", $option->getName())
                    );
                }
            }
        }
    }

    /**
     * Install message-broker application
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Magento\Framework\Exception\FileSystemException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->validate($input);
        $this->installer->install($input->getOptions());

        $output->writeln('Installation complete');

        return Cli::RETURN_SUCCESS;
    }
}
