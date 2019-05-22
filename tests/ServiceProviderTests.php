<?php


namespace PdkPluginBoilerplate\Tests;


use PdkPluginBoilerplate\Tests\Mocks\Application;
use PdkPluginBoilerplate\Tests\Mocks\ServiceProvider;


class ServiceProviderTests extends \WP_UnitTestCase {


	public function test_register_method_can_bind_to_the_application_container() {
		$app                    = new Application( dirname( __FILE__ ) );
		$provider               = new ServiceProvider( $app );
		$provider->test_key     = 'test.key';
		$provider->test_binding = 'test.binding.value';

		$app->register_provider( $provider );

		$this->assertSame( 'test.binding.value', $app->make( 'test.key' ) );
	}


}