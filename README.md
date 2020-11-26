# Overview
Message Broker application is a mediator between Catalog StoreFront Application and Magento Backoffice.

### Message Broker Application Responsibilities
- Listen to changes from Magento Backoffice
- Pull data from Magento backoffice 
- Push data with gRPC to Storefront service

Related repositories 
- https://github.com/magento/commerce-data-export Extension to Magento. Provides API to Export entities from Magento to any subscribed consumer
- https://github.com/magento/catalog-storefront Catalog Storefront Service. gRPC server, provides API for Catalog domain area 
 
### 3rd-party dependencies (composer packages)
- magento/framework
- magento/amqp
- magento/message-queue

## Installation
Message Broker can be installed in 2 ways:
 - Monolithic installation: just copy files to your Magento root folder. This is for development purposes only, do not use in production. 
 - Standalone installation: recommended approach, install Message Broker as a standalone installation 

### Standalone Project Installation
1. Add Magento authentication keys to access the Magento Commerce repository 
* with auth.json: copy the contents of `auth.json.dist` to new `auth.json` file and replace placeholders with your credentials  
* with environment variable: specify environment variable `COMPOSER_AUTH` according to [documentation](https://getcomposer.org/doc/03-cli.md#composer-auth)
2. Run `bash ./dev/tools/make_standalone_app.sh`
3. Run `composer install`
4. Run `bin/command message-broker:install` with all required arguments.

### Adding gRPC server configuration
There are 2 modes how Message broker can talk to Storefront Service:
- "in-memory": direct call of PHP class. This will work only with Monolithic Installation.
- "network": do gRPC call to Storefront Service.

In order to change mode you can use environment variable: `export GRPC_CONNECTION_TYPE=<mode>`

To add gRPC server configuration please run next command:

`bin/command message-broker:grpc-connection:add --name={some_name} --grpc-port={some-port} --grpc-host={some_host}`
If at least one server added, communication mode will be automatically changed to "network" 


### Stubs
In order to make Magento Framework work in a standalone installation without relying on Magento modules, there were created few stubs:

- `\Magento\MessageBroker\Stub\CustomAttributesDefaultTypeLocator` On bootstrap Magento tries to load all dependencies, that are required for WebAPI. One of such dependency is CustomAttributesDefaultTypeLocator.
- `\Magento\MessageBroker\Stub\Encoder` Magento framework tries not only to encode, but also to translate JSON. We don't need this functionality.
- `\Magento\MessageBroker\Stub\Amqp\ResourceModel\MessageQueueLock` Message queue lock mechanism is utilizing Magento database, in order to get rid of database, we need to get rid of MessageQueueLock

### Contributing
Contributions are welcomed! Read the [Contributing Guide](./CONTRIBUTING.md) for more information.

### Licensing
This project is licensed under the OSL-3.0 License. See [LICENSE](./LICENSE.md) for more information.
