<?php


use PdkPluginBoilerplate\Framework\Container\Container;


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


	public function test_bind_method_can_override_scalar_values() {
		$container = new Container();
		$container->bind( 'test.override', 'some value' );
		$container->bind( 'test.override', 'some new value' );

		$this->assertEquals( 'some new value', $container->make( 'test.override' ) );
	}


	public function test_bind_method_throws_exception_when_key_not_found() {
		$container = new Container();
		$this->expectException( InvalidArgumentException::class );
		$container->make( 'something' );
	}


	public function test_bind_method_binds_shared_instances_by_default() {
		$container = new Container();
		$container->singleton( 'test.shared', function () {
			return new stdClass();
		} );

		$bound1 = $container->make( 'test.shared' );
		$bound2 = $container->make( 'test.shared' );

		$this->assertTrue( $bound1 === $bound2 );
	}


	public function test_make_method_throws_exception_when_a_binding_does_not_exist() {
		$container = new Container();
		$this->expectException( InvalidArgumentException::class );
		$container->make( 'test.nonexistent' );
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


	public function test_container_is_array_accessible() {
		$container                 = new Container();
		$container['test.var']     = 'some value';
		$container['test.closure'] = function () {
			$obj       = new stdClass();
			$obj->data = 'some data here';

			return $obj;
		};

		$this->assertSame( 'some value', $container['test.var'] );
		$this->assertSame( 'some data here', $container['test.closure']->data );
	}


	public function test_protected_method_prevents_rebinding_of_same_key() {
		$container = new Container();
		$container->protected( 'test.protected', 'some value' );
		$this->expectException( RuntimeException::class );
		$container->protected( 'test.protected', 'some other value' );
	}


	public function test_factory_method_bindings_make_unique_instances() {
		$container = new Container();
		$container->factory( 'test.factory', function () {
			return new stdClass();
		} );

		$obj1 = $container->make( 'test.factory' );
		$obj2 = $container->make( 'test.factory' );

		$this->assertFalse( $obj1 === $obj2 );
	}


	public function test_extend_method_extends_an_existing_binding() {
		$container = new Container();
		$container->bind( 'test.extend', function () {
			$obj       = new stdClass();
			$obj->data = 'initial';

			return $obj;
		} );

		$container->extend( 'test.extend', function ( $instance, $container ) {
			$instance->data = 'changed';

			return $instance;
		} );

		$obj = $container->make( 'test.extend' );

		$this->assertEquals( 'changed', $obj->data );
	}


	public function test_extend_method_throws_exception_where_a_non_existent_binding_is_extended() {
		$container = new Container();
		$this->expectException( InvalidArgumentException::class );
		$container->extend( 'test.extendfail', function ( $instance, $container ) {
			return $instance;
		} );
	}


	public function test_unbind_method_unbinds_a_key() {
		$container = new Container();
		$container->bind( 'test.unbind', 'value' );
		$container->unbind( 'test.unbind' );

		$this->assertFalse( $container->is_bound( 'test.unbind' ) );
	}


	public function test_unbind_method_clears_all_class_properties() {
		$container = new Container();

		$container->bind( 'key1', 'value1' );
		$container->singleton( 'key2', 'value2' );
		$container->protected( 'key3', 'value3' );
		$container->factory( 'key4', 'value4' );

		$container->unbind( 'key1' );
		$container->unbind( 'key2' );
		$container->unbind( 'key3' );
		$container->unbind( 'key4' );

		$reflector = new ReflectionClass( $container );
		$props     = $reflector->getProperties();

		$merged_props = [];

		foreach ( $props as $prop ) {
			$prop->setAccessible( true );
			$merged_props = array_merge( $merged_props, $prop->getValue( $container ) );
		}

		$this->assertEmpty( $merged_props );
	}


}