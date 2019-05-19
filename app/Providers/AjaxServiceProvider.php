<?php


namespace PdkPluginBoilerplate\Providers;


use PdkPluginBoilerplate\Ajax\ExampleOne;
use PdkPluginBoilerplate\Ajax\ExampleTwo;
use PdkPluginBoilerplate\Framework\Container\Application;
use PdkPluginBoilerplate\Framework\Providers\ServiceProviderBase;


class AjaxServiceProvider extends ServiceProviderBase {


	/**
	 * @var Application
	 */
	protected $app;


	/**
	 * @param Application $app
	 */
	public function register( Application $app ) {
		$this->app = $app;

		/*
		// Bind ajax handlers into the container
		$app->singleton( ExampleOne::class );
		$app->singleton( 'binding_name', ExampleTwo::class );
		*/
	}


	public function init() {
		/*
		// Initialise ajax handlers. e.g;
		$handler = $this->app->make( ExampleOne::class );
		$handler->init();
		add_action( 'wp_footer', function () use ( $handler ) {
			?>
            <a href="<?= $handler->get_url() ?>"><?= $handler->get_url() ?></a>
			<?php
		} );

		$this->app->make( 'binding_name' )->init();
		*/
	}


}