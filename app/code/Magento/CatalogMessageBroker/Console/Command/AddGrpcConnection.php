<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogMessageBroker\Console\Command;

use Magento\CatalogMessageBroker\Model\Publisher\ProductPublisher;
use Magento\Framework\App\DeploymentConfig\Writer;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to add gRPC connection
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AddGrpcConnection extends Command
{
    const GRPC_PORT = 'grpc-port';
    const GRPC_HOST = 'grpc-host';
    const GRPC_CONNECTION_NAME = 'name';

    /**
     * @var Writer
     */
    private $deploymentWriter;

    /**
     * AddGrpcConnection constructor.
     * @param Writer $deploymentWriter
     * @param string|null $name
     */
    public function __construct(
        Writer $deploymentWriter,
        string $name = null
    ) {
        parent::__construct($name);
        $this->deploymentWriter = $deploymentWriter;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('grpc:connection:add')
            ->setDescription('Add gRPC connection')
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
                self::GRPC_PORT,
                null,
                $mode,
                'gRPC port'
            ),
            new InputOption(
                self::GRPC_HOST,
                null,
                $mode,
                'gRPC host'
            ),
            new InputOption(
                self::GRPC_CONNECTION_NAME,
                null,
                $mode,
                'gRPC name'
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
                        sprintf("%s option is not specified", $option->getName())
                    );
                }
            }
        }

        if ($input->getOption('name') !== ProductPublisher::SERVICE_NAME) {
            throw new \Symfony\Component\Console\Exception\RuntimeException(
                'Service name is not correct. Please try to use "catalog"'
            );
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
        try {
            $this->validate($input);
            $this->deploymentWriter->saveConfig([
                'app_env' => [
                    'GRPC_CONNECTION_TYPE' => 'network',
                    'grpc' => [
                        'connections' => [
                            $input->getOption(self::GRPC_CONNECTION_NAME) => [
                                'hostname' => $input->getOption(self::GRPC_HOST),
                                'port' => $input->getOption(self::GRPC_PORT)
                            ]
                        ]
                    ]
                ]
            ]);
        } catch (\Throwable $exception) {
            $output->writeln('Service add failed: ' . $exception->getMessage());
            return Cli::RETURN_FAILURE;
        }
        $output->writeln('Service add complete');

        return Cli::RETURN_SUCCESS;
    }
}
