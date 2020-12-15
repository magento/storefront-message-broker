# Overview

Magento_ConfigurationImportMessageBroker is responsible for synchronizing system configuration data
from Magento back office to any subscribed service that registered in defined way and implements 
`rpc importConfig (ImportConfigRequest) returns (ImportConfigResponse)  {}` endpoint .

##Subscription for configuration and mapping:

Services that want to subscribe for configuration changes should:

1. Add registration for changes and mapping of fields that want to be imported in module `etc/configuration_import_mapping.xml` file with content e.g.


        <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_ConfigurationImportMessageBroker:etc/configuration_import_mapping.xsd">
            <configuration>
                <service name="catalog">
                    <path name="catalog/seo/category_canonical_tag">category_canonical</path>
                </service>
            </configuration>
        </config>


-  `service name="catalog"` - alias of the service. Connection to service with such alias should be defined in message broker connection pool.
- `<path name="catalog/seo/category_canonical_tag">category_canonical</path>` - name attribute it is full configuration path in magento system configuration
value of this node - path mapping (alias) - that will be imported into service


2. Implement `importConfig` endpoint by defining and implementing following proto schema:


        service Catalog {
          rpc importConfig (ImportConfigRequest) returns (ImportConfigResponse)  {}
        }

        message ImportConfigRequest {
          repeated Config config = 1;
        }

        message ImportConfigResponse {
          bool status = 1;
          string message = 2;
        }

        message Config {
          string name = 1;
          string value = 2;
          string store = 3;
        }