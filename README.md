Elao Admin Bundle
=================

## Installation

Require the bundle in _Composer_:

```bash
$ composer require elao/admin-bundle
```

Install the bundle in your _AppKernel_:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Elao\Bundle\AdminBundle\ElaoAdminBundle(),
    );
}
```

Import the rounting in your `routing.yml` configuration file:

```yml
// app/config/routing.yml
elao_admin_bundle:
    resource: "@ElaoAdminBundle/Resources/config/routing.yml"
    prefix:   / # You can prefix all actions here

```

Configure some actions in your `config.yml`:

```yml
// app/config/config.yml
elao_admin:
    administrations:
        # Name of the administration
        article:
            options:
                # The FQCN of the model handled by this administration
                model: Acme\DemoBundle\Entity\Article
            actions:
                list:   ~
                create: ~
                read:   ~
                update: ~
                delete: ~
```

## How it works

Generating administrations is not about magic. It's about registering available
actions, re-using existing code, and be able to easily extend everything. The
ElaoMicroAdminBundle is made with all these considerations in mind.

Administrations are pretty simple to understand: each administration has in
common a model and a way of handling it. Then each administration registers a
set of actions.

## Stack

Here is a short explanation of what each element of the stack is doing and how
it is working.

### Configuration and DIC

_This part of the stack **should not** be overriden._

The configuration is handled by the Symfony bundle configuration loader in the
method `load` of the `DependencyInjection/ElaoMicroAdminExtension` class. This
is the most common way of loading configuration in a Symfony bundle. You can see
the configuration reference above to see a complete list of what can be
configured.



### Actions

_Disclaimer_: we choose to end actions' name by Action since there may be
conflicts between short names (index, create, etc) and PHP keywords.

### Route Loading

Routes for each action are registered just after the action registration in the
dependency injection container.

### Model management

### Form handling

### Templating

## Full configuration reference:

```yml
elao_admin:
    administrations:
        # Where 'name' is the name of the administration
        name:
            options:              # Required
                model:                ~ # Required
                model_manager:        elao_admin.model_manager.doctrine
                route_resolver:       elao_admin.route_resolver
            actions:              # Required
                # Where name is the name of the action
                name:
                    type:                 null # If not set: use the name of the action
                    options:              [] # Every action has its own options
```
