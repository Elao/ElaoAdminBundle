# Create your own actions

## Create the class

You action is a class that implements `Elao\Bundle\AdminBundle\Behaviour\ActionInterface`, recieve a Request and returns a Response. Yes, just like any controller.

You might, for example, define a "MyListAction" class that hold the logic for displaying a list of models.

```php
class MyListAction implements ActionInterface
{
    public function __construct(EngineInterface $templating, RepositoryInterface $repository, array $parameters) {
        $this->templating = $templating;
        $this->repository = $repository;
        $this->parameters = $parameters;
    }

    /**
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getResponse(Request $request)
    {
        $models = $this->repository->findAll();

        return new Response(
            $this->templating->render(
                $this->parameters['template'],
                [ $this->parameters['template_variable'] => $models ]
            )
        );
    }
}
```

When used for a `Post` entity, this action will fetch all `Post` entities, then pass them to the template `:post:list.html.twig` (`template` parameter) in a variable `posts` (`template_variable` parameter).

_Keep in mind that the action is designed to handle not one specific model by any model._

## Define actions as service

To be used by administrations, actions must be defined as abstract services.
It also allow you to inject any service your action needs to perform its logic.

```xml
<service id="app.action.my_list" class="AppBundle\Action\MyListAction" abstract="true">
    <argument type="service" id="templating" /><!-- Twig engine -->
</service>
```

## Create the factory

The action factory is responsible for registering the Action in the AdminBundle and can define its configuration, making it available in the administrations configuration.

An ActionFactory must implement the `Elao\Bundle\AdminBundle\Behaviour\ActionFactoryInterface` it's recommanded that you extends the `Elao\Bundle\AdminBundle\DependencyInjection\Action\Factory\ActionFactory` that does most of the work for you.

There's some ActionFactory abstract methods that __must__ be implemented:

- `getKey`: define a key for the administration configuration. e.g. "my_list"
- `getServiceId`: the action's abstract service id as defined above. e.g. "app.action.my_list"
- `getRouteName`: define the route name dynamically. e.g. "post_list"
- `getRoutePattern`: define the route pattern dynamically.  e.g. "/posts"

There's a few other things you _can_ do in the factory:

- `addConfiguration`: define a configuration specific to this type of action.
- `configureAction`: Modify the action service definition (to inject service based on configuration).
- `getRouteMethods`: specify accepted method for the route.

See `ActionFactory` and `ActionFactoryInterface` and for more details.

Full working example:

```php
<?php

namespace AppBundle\DependencyInjection\Action\Factory;

use Elao\Bundle\AdminBundle\DependencyInjection\Action\Factory\ActionFactory;

class MyListActionFactory extends ActionFactory
{
    /**
     * Define key for configuration file
     */
    public function getKey()
    {
        return 'my_list';
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceId()
    {
        return 'app.action.my_list';
    }

    /**
     * Define configuration for the action
     */
    public function addConfiguration(NodeDefinition $node)
    {
        parent::addConfiguration($node);

        $node
            ->children()
                ->scalarNode('repository')
                    ->defaultValue('app.repository.%name%') // e.g. 'app.repository.post'
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('template')
                    ->defaultValue(':%name%:%alias%.html.twig') // e.g. 'post:list.html.twig'
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('template_variable')
                    ->defaultValue('%names%') // e.g. 'posts'
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;
    }

    /**
     * Configure action service definition
     */
    public function configureAction(Definition $definition)
    {
        // One processed, the config defined in `addConfiguration` will be available in the attribute `config` of the factory.

        // We extract the repository service id from the config and it to the service arguments.
        $definition->addArgument(new Reference($this->config['repository']));
        unset($this->config['repository']);

        // Here we inject the remaining config parameters in the action service as an additional argument.
        $definition->addArgument($this->config);
    }

    /**
     * Define route name
     */
    protected function getRouteName()
    {
        return '%name%_%alias%'; // e.g. 'post_list'
    }

    /**
     * Define route pattern
     */
    protected function getRoutePattern()
    {
        return '/%-names-%'; // e.g. '/posts'
    }

    /**
     * Define route accepted methods
     */
    protected function getRouteMethods()
    {
        return ['GET'];
    }
}
```

#### Tokens

If you extends the `ActionFactory`, you benefit from the Tokens feature.
The `getTokens` method define a set of tokens that will be dynamically replaced when parsing the configuration.
This allow you to make your Action configuration vary over the administration name (usualy the model name. e.g. "user") and the action alias (alias chosen by the user. e.g. "list").

Default tokens (example for the administration "user" and the action "list"):

- `%name%`: "user"
- `%names%`: "users"
- `%Name%`: "User"
- `%Names%`: "Users"
- `%-name-%`: "user" (url safe, meant for route pattern)
- `%-names-%`: "users" (url safe, meant for route pattern)
- `%alias%`: "list"
- `%Alias%`: "List"
- `%-alias-%`: "list" (url safe, meant for route pattern)

## Register actions

Now we need to make our `my_list` action available by registering it in the `elao_admin` extension in your Bundle file.

```php
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AppBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('elao_admin');

        $extension->addActionFactory(new AppBundle\DependencyInjection\Action\Factory\MyListActionFactory());
    }
}
```

## Using your new action:

Now the `my_list` action is available:

```yml
# app/config/config.yml
elao_admin:
    administrations:
        user:
            actions:
                list:
                    my_list: ~ # Use default configuration values defined in `addConfiguration`.
```
