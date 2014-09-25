Invoker Bundle
==============

This bundle imitates some of the functionality of [invoker](http://invoker.codemancers.com)
to run services in development environments.

Usage
-----

Enable the bundle in your kernel.

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Bangpound\Bundle\InvokerBundle\BangpoundInvokerBundle(),
    );
}
```

Create ProcessBuilder services for each process this bundle should launch.

```yaml
    bangpound_castle.process.elasticsearch:
        class: Bangpound\Bundle\InvokerBundle\Process\ProcessBuilder
        arguments: [ ["/usr/local/bin/elasticsearch", "--config=/usr/local/opt/elasticsearch/config/elasticsearch.yml"] ]
        calls:
            - [ setTimeout, [ ~ ]]
        tags:
            - { name: bangpound_invoker.server }

    bangpound_castle.process.couchdb:
        class: Bangpound\Bundle\InvokerBundle\Process\ProcessBuilder
        arguments: [ ["couchdb" ] ]
        calls:
            - [ setTimeout, [ ~ ]]
        tags:
            - { name: bangpound_invoker.server }

    bangpound_castle.process.rabbitmq:
        class: Bangpound\Bundle\InvokerBundle\Process\ProcessBuilder
        arguments: [ ["rabbitmq-server"] ]
        calls:
            - [ setTimeout, [ ~ ]]
        tags:
            - { name: bangpound_invoker.server }
```

Then run the Symfony console comamand `invoker`.

```bash
./bin/console invoker -vv
```

Using the verbosity flags will cause process output to be logged to the console, which is usually desireable.

This is a bundle that should only be used for development environments. Do not use this bundle in production. Instead use your operating system's service initialization such as `systemd` or `init.d` and/or use [supervisord](http://supervisord.org).
