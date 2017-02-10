Anodet
===========
Pre-Requisites
--------------
MySQL database, configured in app/config/parameters.yml

Slack notifier config, in app/config/parameters.yml

Log path, in app/config/parameters.yml

Modules set up, in modules.yml

Deploy
--------------

composer install

migrate database, in app/migrations/migration1.sql

How to create a new module
===========
First step: Implement the interfaces of the module.
--------------

It includes one transporter, one or more analyzers, one or more deciders and one config class (the config class must extend class Anodet\Core\Value\Config).

It is recommended that each class be in

```php
src/Implementation/$interface/$nameofClass
```

*(i.e.: src/Implementation/Analyzer/HttpErrorsAnalyzer).*

Second step: Create a new register on the database.
--------------

The manager needs to know which modules are implemented and how many times needs to run it (for each configuration), so the register will contain:

| Field            |           Description                                                            |  
| ---------------- | ---------------------------------------------------------------------------------|
| instance_code    |   custom string to easily identify each instance. It is not used by the manager. |
| module_code      |   code associated to the module(i.e: HttpAccessLog)                              |
| config_class     |   Class which contains the configuration attributes                              |
| config           |   json-encoded config values                                                     |
| is_active        |   manager will skip instances with is_active==0                                  |

**Example**

| Field            |           Description                                                            |  
| ---------------- | ---------------------------------------------------------------------------------|
| instance_code | diskspace_tsp-web2-pmi                                                              |
| module_code   | disk_space                                                                          |
| config_class  | Anodet\Implementation\Config\DiskSpace\DiskSpaceConfig                              |
| config	    | ```{"url": "http://192.168.201.107:12900/search/universal/absolute\?query\=","server": "tsp-web2-pmi","stream": "57b70aa5680f83428aea943b"}```  |
| is_active	    |     1                                                                               |

 
Third step: Add interfaces as a services in app/config/modules.yml
--------------

**Example**
```yaml
-----------------------Transporter-----------------------
uptime.transporter:                                                         # Name doesn't matter, but it need to be unique!
      class:      Anodet\Implementation\Transporter\UptimeTransporter
      arguments:  ["@database"]                                             # You can inject a service, like the database, if you want
      tags:
                  - { name: transporter, code: cpu }                        # 'name' contains the interface of module {transporter, analyzer, decider}, 'code' is used forr know the configuration class associated
```

Fourth step
--------------
Run it!

You can use next command:

    php app/main.php boot

Note that if any interface fails, the manager will skip the instance without saving config changes and run the next one.


More info
--------------
<https://knowledge.trivago.com/display/~jtrivino/Anodet>
