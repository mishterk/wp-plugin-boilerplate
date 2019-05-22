## Container

The main plugin class is an IoC container that attempts to automatically resolve dependencies where possible using PHP's 
reflection API. There system comprises of the following classes:

1. `Container`
1. `Application` (extends `Container`)
1. `Plugin` (extends `Application`)
1. `Theme` (extends `Application`) **(COMING SOON)**

If building a plugin, you should instantiate or extend the `Plugin` class. 

If building a theme, use the `Theme` class. 

By default, anything bound to the container is considered shared and is treated more or less as a singleton. That is, 
each time the `$container->make()` method is called for that binding, the same instance is returned. 

### Container Usage Examples

#### Binding simple values

```php
<?php
$container = new \PdkPluginBoilerplate\Framework\Container\Container();
$container->bind( 'my_key', 'some value' );

// to retrieve value
$value = $container->make('my_key');
```

#### Binding factories

Factories can be used to generate new instances of objects/data each time you call the `$container->make()` method. e.g;

##### Binding a lambda function

```php
<?php
$container = new \PdkPluginBoilerplate\Framework\Container\Container();
$container->factory('my_key', function(){
	return random_int(1,9999);
});

$instance_1 = $container->make('my_key');
$instance_2 = $container->make('my_key');

// $instance_1 !== $instance_2
```

In the above example, the lambda is called each time the `make` method is invoked for that particular key.

##### Binding a class

```php
<?php
$container = new \PdkPluginBoilerplate\Framework\Container\Container();
$container->factory('my_key', ClassName::class );

$instance_1 = $container->make('my_key');
$instance_2 = $container->make('my_key');

// $instance_1 !== $instance_2
```

In the above example, a new instance of `ClassName` will be returned on each call to `make()`. You could also omit keys
as follows: 

```php
<?php
$container = new \PdkPluginBoilerplate\Framework\Container\Container();
$container->factory( ClassName::class );

$instance_1 = $container->make( ClassName::class );
$instance_2 = $container->make( ClassName::class );

// $instance_1 !== $instance_2
```

## View System

The ViewRenderer can be used on its own:

```php
<?php 
$view = new \PdkPluginBoilerplate\Framework\View\ViewRenderer();
$view->set_view_base_dir('/some/dir');

$view->render('some/template', ['var' => 'data']);
```

For static access, define one or more custom view objects at the app level and extend the View class as follows:

```php
<?php 
class View extends \PdkPluginBoilerplate\Framework\View\ViewBase {

	protected function setup() {
		// this must be set for the views to resolve
		$this->set_view_base_dir( PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'templates' );
		
		// optionally enable view overrides by specifying a view base directory. This example sets a directory within 
		// the current theme.
		$this->set_view_override_base_dir( get_stylesheet_directory() . '/pdk-plugin-boilerplate' );
		
		// make all templates overridable
        $this->make_all_templates_overridable();

        // OR, specify which directories, relative to the view base dir, contain overridable templates
        $this->set_overridable_template_dirs( [ 'dir', 'some/dir' ] );
        $this->add_overridable_template_dir( 'dir' );
        $this->add_overridable_template_dir( 'some/dir' );
        
        // AND/OR, mark specific templates as overridable
        $this->set_overridable_templates( [ 'dir/post', 'some/dir/title' ] );
        $this->add_overridable_template( 'dir/post' );
        $this->add_overridable_template( 'some/dir/title' );
	}

}

// print the markup
View::echo('dir/post', ['var' => 'data', 'var2' => 'data2']);

// get the markup
$markup = View::get('dir/post', ['var' => 'data', 'var2' => 'data2']);

```

## Admin Notices

There are five classes within the framework that can be instantiated and used directly or extended for further 
customisation: 

1. `\PdkPluginBoilerplate\Framework\AdminNotices\AdminNotice`
1. `\PdkPluginBoilerplate\Framework\AdminNotices\AdminSuccessNotice`
1. `\PdkPluginBoilerplate\Framework\AdminNotices\AdminWarningNotice`
1. `\PdkPluginBoilerplate\Framework\AdminNotices\AdminErrorNotice`
1. `\PdkPluginBoilerplate\Framework\AdminNotices\AdminInfoNotice`

### Direct use example

You can simply instantiate the base class and configure as necessary to be any type of error notice. e.g;

```php
<?php

// The absolute minimum required
$notice = new \PdkPluginBoilerplate\Framework\AdminNotices\AdminNotice();
$notice->set_message( 'This is a message' );
$notice->init();

// Optional extras
$notice->set_is_dismissible( true ); // FALSE by default
$notice->set_is_alt( true ); // sets 'alt' style as built into WordPress core

// Use these to control the type of notice. The default is 'info'
$notice->set_success_type();
$notice->set_error_type();
$notice->set_warning_type();
$notice->set_info_type();
```

If you wish, you can use any of the remaining four classes to control the type for you. e.g;

```php
<?php

$error = new \PdkPluginBoilerplate\Framework\AdminNotices\AdminErrorNotice();
$warning = new \PdkPluginBoilerplate\Framework\AdminNotices\AdminWarningNotice();
$info = new \PdkPluginBoilerplate\Framework\AdminNotices\AdminInfoNotice();
$success = new \PdkPluginBoilerplate\Framework\AdminNotices\AdminSuccessNotice();
```

### Extending classes for additional control

If you need further control over your admin notices, you can extend any one of the five base classes in your 
application. By doing so, you can extend any of the methods or properties to customise as necessary. The possibilities 
available to you include but are not limited to:

1. The ability to implement the `is_visible()` method to conditionally control whether a notice will appear
1. The ability to add your own CSS classes to a notice
1. The ability to disable or customize sanitization on the notice's message

#### A very basic example

```php
<?php 

class MyErrorNotice extends \PdkPluginBoilerplate\Framework\AdminNotices\AdminErrorNotice {
	
    protected $message = 'This is a my error message.';

}

$notice = new MyErrorNotice();
$notice->init();
```

#### Implement a display condition

```php
<?php

class MyConditionalErrorNotice extends \PdkPluginBoilerplate\Framework\AdminNotices\AdminErrorNotice {

	protected $message = 'This is my error message.';

    // this method is passed the results of get_current_screen() which can be either a WP_Screen object or NULL
	protected function is_visible( $current_screen ) {
		if(current_user_can('manage_options')){
			return $current_screen and $current_screen->id === 'plugins'; 
		}
		return false;
	}

}

$class = new MyConditionalErrorNotice();
$class->init();
```

#### Add custom CSS classes to the notice

```php
<?php

class MyCustomErrorNotice extends \PdkPluginBoilerplate\Framework\AdminNotices\AdminErrorNotice {
    
	protected $message = 'This is my error message.';

	protected function get_additional_classes( $as_array = false ) {
    	
        // fetch base classes as array
        $classes = parent::get_additional_classes(true);
        
        // add custom classes
        $classes[] = 'some-class';
        $classes[] = 'some-other-class';
        
        return $as_array
            ? $classes
            : implode( ' ', $classes );
        }

}

$class = new MyCustomErrorNotice();
$class->init();
```