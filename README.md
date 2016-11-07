Elao Admin Bundle
=================

> Write your controller once, use it for all your models.

## What?

The AdminBundle helps you define reusable __Actions__ that can be defined as __route controllers__ for _any_ model.

## Why?

The AdminBundle improves your productivity when implementing similar controller behavior for several models, such as CRUD back-end.

## How?

The AdminBundle declares Actions as abstract services, instanciates and configures one instance for each model and register the corresponding route in the Symfony router.

You just need to say "I want a `list` of `user`." and the AdminBundle register a `/users` route that run a `ListAction` instance configured to handle the `User` model.

## Design goals

The AdminBundle is meant to improve your productivity and remain flexible and extendable.

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

Use a set of Actions:

- [HTML Actions](https://github.com/Elao/ElaoHtmlActionBundle): For easily performing CRUD operations using Symfony forms.
- [REST Actions](https://github.com/Elao/ElaoRestActionBundle): For building an Api through REST actions.

Or [create your own set of actions](Resources/doc/actions.md)!

### Configuration

Configure some actions in your `config.yml`:

```yml
# app/config/config.yml
elao_admin:
    administrations:
        # Where 'name' is the name of the administration
        name:
            # Administration-level options (optional)
            foo: true
            # (required)
            actions:
                # Where 'alias' is the alias of the action
                alias:
                    # Where 'action_type' is a registered action type.
                    action_type:
                        # Every action has its own options
```

Here's an example with some action provided by the ElaoHtmlActionBundle.

```yml
# app/config/config.yml
elao_admin:
    administrations:
        post: # The name of the administration (usualy, the model name)
            repository: app.repository.post # The repository to use to access the model
            actions:
                list:               # A "list" action,
                    html_list: ~    # that use default configuration for "html_list".

                create:             # A "create" action,
                    html_create:    # that use "html_create" and specify the form to use.
                        form: BlogBundle\Form\PostType

                update:             # A "update" action,
                    html_update:    # that use "html_update" and specify the form to use.
                        form: BlogBundle\Form\PostType

                read:               # A "read" action,
                    html_read: ~    # that use default configuration for "html_read".

                delete:             # A "delete" action,
                    html_delete:    # that use "html_delete" and adds a security restriction.
                        security: has_role('ROLE_ADMIN')
```

This config will generate the following routes:

| Name        | Method   | Scheme | Host | Path               |
| ----------- | -------- | ------ | ---- | ------------------ |
| post_list   | GET      | ANY    | ANY  | /posts             |
| post_create | GET/POST | ANY    | ANY  | /posts/new         |
| post_update | GET/POST | ANY    | ANY  | /posts/{id}/edit   |
| post_read   | GET      | ANY    | ANY  | /posts/{id}        |
| post_delete | GET/POST | ANY    | ANY  | /posts/{id}/delete |
