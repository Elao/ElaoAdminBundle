Elao Micro Admin Bundle
=======================

## Stacks

### Configuration

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
            manager: CategoryManager
            actions: ~
```

### Route Loading

### Actions

_Disclaimer_: we choose to end actions' name by Action since there may be
conflicts between short names (index, create, etc) and PHP keywords.

### Model management

### Form handling

### Templating
