#!/bin/sh sh
echo "Install message broker..."
bin/magento catalog:message-broker:install --amqp-host=rabbit --amqp-port=5672 --amqp-user=guest --amqp-password=guest --backoffice-base-url=${BACKOFFICE_URL} --elasticsearch_server_hostname=elastic --elasticsearch_server_port=9200
bin/magento queue:consumers:start catalog.product.export.consumer&
bin/magento queue:consumers:start catalog.category.export.consumer
