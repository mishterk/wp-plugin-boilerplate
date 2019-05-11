<?php


use PdkPluginBoilerplate\Framework\Foundation\Container;


class ContainerTests extends WP_UnitTestCase {


	public function test_bind_method_binds_closures_to_keys() {
		$container = new Container();
		$container->bind( 'test.key', function () {
			$obj       = new stdClass();
			$obj->data = 'some data';

			return $obj;
		} );

		$obj = $container->make( 'test.key' );

		$this->assertEquals( 'some data', $obj->data );
	}


	public function test_bind_method_binds_scalar_values_to_keys() {
		$container = new Container();
		$container->bind( 'test.string', 'some value' );
		$container->bind( 'test.bool', true );
		$container->bind( 'test.boolfalse', false );

		$this->assertEquals( 'some value', $container->make( 'test.string' ) );
		$this->assertTrue( $container->make( 'test.bool' ) );
		$this->assertFalse( $container->make( 'test.boolfalse' ) );
	}


	public function test_bind_method_throws_exception_when_key_not_found() {
		$container = new Container();
		$this->expectException( InvalidArgumentException::class );
		$container->make( 'something' );
	}


	public function test_singleton_method_binds_shared_instance() {
		$container = new Container();
		$container->singleton( 'test.singleton', function () {
			return new stdClass();
		} );

		$bound1 = $container->make( 'test.singleton' );
		$bound2 = $container->make( 'test.singleton' );

		$this->assertTrue( $bound1 === $bound2 );
	}


	//public function test_protected_method_prevents_rebinding_of_same_key() {
	//	$container = new Container();
	//	$container->protected( 'test.protected', 'some value' );
	//}


}