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
    $bundles = [
        // ...
        new Elao\Bundle\AdminBundle\ElaoAdminBundle(),
    ];
}
```

Import the rounting in your `routing.yml` configuration file:

```yml
// app/config/routing.yml
elao_admin_bundle:
    resource: "@ElaoAdminBundle/Resources/config/routing.yml"
    prefix:   / # You can prefix all actions here

```

## Usage

Use a set of Actions or create your own.



Configure some actions in your `config.yml`:

```yml
// app/config/config.yml
elao_admin:
    administrations:
        post:
            repository: app.repository.post
            actions:
                list:
                    html_list: ~
                create:
                    html_create:
                        form: BlogBundle\Form\PostType
                update:
                    html_update:
                        form: BlogBundle\Form\PostType
                read:
                    html_read: ~
                delete:
                    html_delete:
                        security: has_role('ROLE_ADMIN')
```

This config will generate the following routes:

|-------------|----------|--------|------|--------------------|
| Name        | Method   | Scheme | Host | Path               |
|-------------|----------|--------|------|--------------------|
| post_list   | GET      | ANY    | ANY  | /posts             |
| post_create | GET|POST | ANY    | ANY  | /posts/new         |
| post_update | GET|POST | ANY    | ANY  | /posts/{id}/edit   |
| post_read   | GET      | ANY    | ANY  | /posts/{id}        |
| post_delete | GET|POST | ANY    | ANY  | /posts/{id}/delete |
|-------------|----------|--------|------|--------------------|

## How it works

Generating administrations is not about magic. It's about registering available
actions, re-using existing code, and be able to easily extend everything. The
ElaoAdminBundle is made with all these considerations in mind.

Administrations are pretty simple to understand: each administration has in
common a model and a way of handling it. Then each administration registers a
set of actions.

## Stack

Here is a short explanation of what each element of the stack is doing and how
it is working.

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
            # Administration-level options (optional)
            foo: true
            # (required)
            actions:
                # Where name is the name of the action
                name:
                    # Where action_type is a registered action type.
                    action_type:
                        # Every action has its own options
```

## Register actions



```php
<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use AppBundle\DependencyInjection\Action\Factory as ActionFactory;

class AppBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('elao_admin');
        $extension->addActionFactory(new ActionFactory\ListActionFactory());
        $extension->addActionFactory(new ActionFactory\UpdateActionFactory());
        $extension->addActionFactory(new ActionFactory\CreateActionFactory());
        $extension->addActionFactory(new ActionFactory\DeleteActionFactory());
        $extension->addActionFactory(new ActionFactory\ReadActionFactory());
    }
}
```
