<?php


namespace PdkPluginBoilerplate\Tests;


use PdkPluginBoilerplate\Framework\Utils\Config;


class ConfigTests extends \WP_UnitTestCase {


	public function test_set_method_sets_a_value() {
		$config = new Config();
		$config->set( 'test', 'value' );
		$this->assertSame( 'value', $config->get( 'test' ) );
	}


	public function test_set_method_sets_multiple_values_when_passed_an_array() {
		$config = new Config();
		$config->set( [
			'one' => 'value 1',
			'two' => 'value 2',
		] );
		$this->assertSame( 'value 1', $config->get( 'one' ) );
		$this->assertSame( 'value 2', $config->get( 'two' ) );
	}


	public function test_set_method_can_set_nested_values_using_dot_notation() {
		$config = new Config();
		$config->set( 'nested.array.keys', 'value' );

		$all = $config->all();

		$this->assertTrue( isset( $all['nested']['array']['keys'] ) );
	}


	public function test_get_method_returns_value() {
		$config = new Config();
		$config->set( 'test', 'value' );
		$this->assertSame( 'value', $config->get( 'test' ) );
	}


	public function test_get_method_returns_null_if_key_does_not_exist() {
		$config = new Config();
		$this->assertNull( $config->get( 'not_set' ) );
	}


	public function test_get_method_returns_specified_default_if_key_does_not_exist() {
		$config = new Config();
		$this->assertSame( 'default value', $config->get( 'not_set', 'default value' ) );
	}


	public function test_get_method_can_get_nested_values_using_dot_notation() {
		$config = new Config();
		$config->set( 'nested.array.keys', 'value' );
		$this->assertSame( 'value', $config->get( 'nested.array.keys' ) );
	}


	public function test_has_method_can_check_for_key_existence() {
		$config = new Config();
		$config->set( 'key', 'value' );
		$this->assertTrue( $config->has( 'key' ) );
	}


	public function test_has_method_can_check_for_key_presence_using_dot_notation() {
		$config = new Config();
		$config->set( 'nested.array.key', 'value' );
		$this->assertTrue( $config->has( 'nested.array.key' ) );
	}


	public function test_all_method_returns_all_items() {
		$config = new Config();
		$config->set( [
			'one' => 'value 1',
			'two' => 'value 2',
		] );

		$all = $config->all();

		$this->assertCount( 2, $all );
		$this->assertSame( 'value 1', $all['one'] );
		$this->assertSame( 'value 2', $all['two'] );
	}


	public function test_unset_method_unsets_an_item() {
		$config = new Config();
		$config->set( [
			'one' => 'value 1',
			'two' => 'value 2',
		] );

		$config->unset( 'two' );

		$this->assertSame( 'value 1', $config->get( 'one' ) );
		$this->assertNull( $config->get( 'two' ) );
	}


	public function test_array_accessibility() {
		$config          = new Config();
		$config['prop1'] = 'value 1';

		$this->assertTrue( isset( $config['prop1'] ) );
		$this->assertSame( 'value 1', $config['prop1'] );

		unset( $config['prop1'] );

		$this->assertFalse( isset( $config['prop1'] ) );
	}


}