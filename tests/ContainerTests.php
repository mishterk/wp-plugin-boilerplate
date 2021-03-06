<?php


namespace PdkPluginBoilerplate\Tests;


use InvalidArgumentException;
use PdkPluginBoilerplate\Framework\Container\Container;
use PdkPluginBoilerplate\Tests\Mocks\ContainerBuild\Contract;
use PdkPluginBoilerplate\Tests\Mocks\ContainerBuild\DependencyFour;
use PdkPluginBoilerplate\Tests\Mocks\ContainerBuild\DependencyOne;
use PdkPluginBoilerplate\Tests\Mocks\ContainerBuild\DependencyThree;
use PdkPluginBoilerplate\Tests\Mocks\ContainerBuild\DependencyTwo;
use PdkPluginBoilerplate\Tests\Mocks\ContainerBuild\ImplementsContract;
use PdkPluginBoilerplate\Tests\Mocks\ContainerBuild\RootClass;
use PdkPluginBoilerplate\Tests\Mocks\ContainerBuild\RootClassAlt;
use PdkPluginBoilerplate\Tests\Mocks\ContainerBuild\RootClassSimple;
use PdkPluginBoilerplate\Tests\Mocks\ContainerBuild\RootClassWithContractAsDependency;
use PdkPluginBoilerplate\Tests\Mocks\Factory;
use ReflectionClass;
use RuntimeException;
use stdClass;
use WP_UnitTestCase;


class ContainerTests extends WP_UnitTestCase {


	public function test_bind_method_binds_single_instances_by_default() {
		$container = new Container();
		$container->bind( 'test.key', function () {
			$obj = new stdClass();

			return $obj;
		} );

		$obj1 = $container->make( 'test.key' );
		$obj2 = $container->make( 'test.key' );

		$this->assertTrue( $obj1 === $obj2 );
	}


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


	public function test_make_method_returns_concrete_implementation_of_contract() {
		$container = new Container();
		$container->bind( ImplementsContract::class );
		$container->alias( ImplementsContract::class, Contract::class );

		// test with fluent alias setter
		$container2 = new Container();
		$container2->bind( ImplementsContract::class )->alias( Contract::class );

		$this->assertInstanceOf( ImplementsContract::class, $container->make( Contract::class ) );
		$this->assertInstanceOf( ImplementsContract::class, $container2->make( Contract::class ) );
	}


	public function test_make_method_resolves_dependencies_when_a_contract_is_type_hinted() {
		$container = new Container();
		$container->bind( RootClassWithContractAsDependency::class );
		$container->bind( ImplementsContract::class )->alias( Contract::class );
		$resolved = $container->make( RootClassWithContractAsDependency::class );

		$this->assertInstanceOf( RootClassWithContractAsDependency::class, $resolved );
		$this->assertInstanceOf( ImplementsContract::class, $resolved->one );
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


	public function test_factory_method_can_bind_anonymous_functions() {
		$container = new Container();
		$container->factory( 'test.func', function () {
			return random_int( 1111, 9999 );
		} );

		$this->assertTrue( is_int( $container->make( 'test.func' ) ) );
		$this->assertNotSame( $container->make( 'test.func' ), $container->make( 'test.func' ) );
	}


	public function test_factory_method_can_bind_a_callable_class_by_class_name() {
		$container = new Container();
		$container->factory( Factory::class );

		$this->assertTrue( is_int( $container->make( Factory::class ) ) );
	}


	public function test_factory_method_returns_unique_instances_when_a_classname_is_bound() {
		$container = new Container();
		$container->factory( RootClassSimple::class );

		$this->assertNotSame(
			$container->make( RootClassSimple::class ),
			$container->make( RootClassSimple::class )
		);
	}


	public function test_factory_method_returns_unique_instances_when_a_classname_is_bound_to_a_key() {
		$container = new Container();
		$container->factory( 'test.factory', RootClassSimple::class );

		$this->assertNotSame(
			$container->make( 'test.factory' ),
			$container->make( 'test.factory' )
		);
	}


	public function test_factory_method_can_be_aliased() {
		$container = new Container();
		$container->factory( 'test.factory', function () {
			return random_int( 111, 999 );
		} )->alias( 'test.factory.alias' );

		$this->assertTrue( is_int( $container->make( 'test.factory' ) ) );
		$this->assertTrue( is_int( $container->make( 'test.factory.alias' ) ) );
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
		$container->factory( 'key5', function () {
			return 'whatever';
		} );

		$container->make( 'key1' );
		$container->make( 'key2' );
		$container->make( 'key3' );
		$container->make( 'key4' );
		$container->make( 'key5' );

		$container->unbind( 'key1' );
		$container->unbind( 'key2' );
		$container->unbind( 'key3' );
		$container->unbind( 'key4' );
		$container->unbind( 'key5' );

		$reflector = new ReflectionClass( $container );
		$props     = $reflector->getProperties();

		$merged_props = [];

		foreach ( $props as $prop ) {
			$prop->setAccessible( true );
			$value = $prop->getValue( $container );
			if ( is_array( $value ) ) {
				$merged_props = array_merge( $merged_props, $value );
			}
		}

		$this->assertEmpty( $merged_props );
	}


	public function test_make_method_builds_a_class_without_constructor_where_class_name_is_bound_as_key() {
		$container = new Container();

		$container->bind( RootClassSimple::class );

		$this->assertInstanceOf( RootClassSimple::class, $container->make( RootClassSimple::class ) );
	}


	public function test_make_method_builds_a_class_without_constructor_where_class_name_is_bound_to_key() {
		$container = new Container();

		$container->bind( 'test.build', RootClassSimple::class );

		$this->assertInstanceOf( RootClassSimple::class, $container->make( 'test.build' ) );
	}


	public function test_make_method_builds_a_class_where_class_names_are_bound_as_keys() {
		$container = new Container();

		$container->bind( RootClass::class );
		$container->bind( DependencyOne::class );
		$container->bind( DependencyTwo::class );
		$container->bind( DependencyThree::class );
		$container->bind( DependencyFour::class );

		$instance = $container->make( RootClass::class );

		$this->assertInstanceOf( RootClass::class, $instance );
	}


	public function test_make_method_builds_a_class_where_class_names_are_bound_to_aliases() {
		$container = new Container();

		$container->bind( RootClass::class );
		$container->alias( RootClass::class, 'test.root' );

		$container->bind( DependencyOne::class );
		$container->alias( DependencyOne::class, 'test.dep.1' );

		$container->bind( DependencyTwo::class );
		$container->alias( DependencyTwo::class, 'test.dep.2' );

		$container->bind( DependencyThree::class );
		$container->alias( DependencyThree::class, 'test.dep.3' );

		$container->bind( DependencyFour::class );
		$container->alias( DependencyFour::class, 'test.dep.4' );

		$instance = $container->make( 'test.root' );

		$this->assertInstanceOf( RootClass::class, $instance );
	}


	public function test_fluent_syntax_can_be_used_to_assign_aliases_when_binding() {
		$container = new Container();

		$container->bind( RootClass::class )->alias( 'test.root' );
		$container->singleton( DependencyOne::class )->alias( 'test.dep.1' );
		$container->protected( DependencyTwo::class )->alias( 'test.dep.2' );
		$container->factory( DependencyThree::class )->alias( 'test.dep.3' );
		$container->bind( DependencyFour::class )->alias( 'test.dep.4' );

		$instance = $container->make( 'test.root' );

		$this->assertInstanceOf( RootClass::class, $instance );
	}


	public function test_make_method_builds_a_class_where_class_names_are_bound_to_keys() {
		$container = new Container();

		$container->bind( 'test.root', RootClass::class );
		$container->bind( 'test.dep.1', DependencyOne::class );
		$container->bind( 'test.dep.2', DependencyTwo::class );
		$container->bind( 'test.dep.3', DependencyThree::class );
		$container->bind( 'test.dep.4', DependencyFour::class );

		$instance = $container->make( 'test.root' );

		$this->assertInstanceOf( RootClass::class, $instance );
	}


	public function test_make_method_builds_all_dependencies() {
		$container = new Container();

		$container->bind( 'test.root', RootClass::class );
		$container->bind( 'test.root.2', RootClassAlt::class );
		$container->bind( 'test.dep.1', DependencyOne::class );
		$container->bind( 'test.dep.2', DependencyTwo::class );
		$container->bind( 'test.dep.3', DependencyThree::class );
		$container->bind( 'test.dep.4', DependencyFour::class );

		$instance = $container->make( 'test.root' );

		// check dependencies are all set
		$this->assertTrue( isset( $instance->one->two->three->four ) );
		$this->assertTrue( isset( $instance->two->three->four ) );

		// check to make sure we are getting the same objects
		$this->assertSame( $instance->one, $container->make( 'test.dep.1' ) );
		$this->assertSame( $instance->one->two, $instance->two );
		$this->assertSame( $instance->one, $container->make( 'test.root.2' )->one );
	}


}