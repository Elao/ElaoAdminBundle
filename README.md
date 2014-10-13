Elao Micro Admin Bundle
=======================


## Configuration reference

Here is an exemple of configuration for this bundle:

```
elao_micro_admin:
    # Actions used in all administrations when the actions key is not overwritten
    default_actions: ['index', 'read', 'create', 'update', 'delete']
    administrations:
        article:
            # The FQCN of the model handled by this administration
            model: Article
            # A manager responsible for retrieving and persisting the model
            manager: ArticleManager
            # Actions available for this administration
            actions:
                # The alias of the service used as the Action
                index:
                    # The template used to render the response
                    view: MicroAdminBundle:Action:create.html.twig
                    # The route definition of this action
                    route:
                        name: article_create
                        pattern: /article/create
                create:
                    # Parameters here are dependent of the Action
                    form: form_name
                    view: ~
                    route: ~
                delete:
                    redirect_route: article_index
        articleLolilol:
            model: Article
            manager: ArticleLolilolManager
            actions:
                read: ~
                update: ~
        category:
            model: Category
            manager: ~
            actions: ~
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
