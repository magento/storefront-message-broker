# Overview
This repository is responsible for Message Broker Application.
Message Broker application is a mediator between Catalog StoreFront Application and Magento Backoffice.
The main responsibility is to listen changes to catalog entities: category and product, request the data
from Magento backoffice and bypass it to CatalogStoreFront application.

### Message Broker Application Responsibilities
- create listeners for export messages
- Pull data from Magento backoffice 
- Push data with gRPC to catalog store front

### Service repository Dependencies 
- https://github.com/magento/commerce-data-export (Provides API to Export entities from Magento to any subscribed consumer)
- https://github.com/magento/catalog-storefront (StoreFront application, which is responsible for holding all information)

### 3rd-party dependencies (composer packages)
- magento/framework
- magento/amqp
- magento/message-queue

### Contributing
Contributions are welcomed! Read the [Contributing Guide](./CONTRIBUTING.md) for more information.

### Licensing
This project is licensed under the OSL-3.0 License. See [LICENSE](./LICENSE.md) for more information.

### Adding gRPC server configuration
To add gRPC server configuration please run next command:

`bin/command grpc:connection:add --name={some_name} --grpc-port={some-port} --grpc-host={some_host}`

### Monolith Project Installation
1. Copy all files from different repos to magento2ce. You need to copy (commerce-data-export, catalog-storefront, message-broker)
2. Please be aware that `app/etc/di.xml` should not be copied.
3. Follow normal Magento installation


### Standalone Project Installation
1. Remove `.standalone` suffix  from `composer.json.standalone` and `composer.lock.standalone`
2. In order to install project run ```composer install``` command.
3. Than run ```bin/install microservice:install ``` with all required arguments.
4. Specify environment variable: ```communication-type```. It should be equal either to `network` or `in-memory`.



### Stubs
In order to make magento framework work with Magento modules, there were created few stubs
and add few preferences:

`\Magento\CatalogMessageBrokerMessageQueue\Stub\CustomAttributesDefaultTypeLocator`
On bootstrap Magento tries to load all dependencies, that are required for WebAPI. One of such depdendency is CustomAttributesDefaultTypeLocator.
`\Magento\CatalogMessageBrokerMessageQueue\Stub\Json\Encoder`
Magento framework tries not only to encode, but also to translate JSON. We dont need this functionality.
`\Magento\CatalogMessageBrokerMessageQueue\Stub\App\Request\DefaultPathInfoProcessor`
On Bootstrap Magento need to resolve default path. As we dont need default path, we created stub.
`\Magento\CatalogMessageBroker\Stub\Amqp\ResourceModel\MessageQueueLock`
Message queue lock mechanism is utilizing Magento database, in order to get rid of database, we need to get rid of MessageQueueLock
