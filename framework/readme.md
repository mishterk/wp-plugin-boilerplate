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