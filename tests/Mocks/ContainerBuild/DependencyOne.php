<?php


namespace PdkPluginBoilerplate\Tests\Mocks\ContainerBuild;


class DependencyOne {


	/**
	 * @var DependencyTwo
	 */
	public $two;


	/**
	 * @param DependencyTwo $two
	 */
	public function __construct( DependencyTwo $two ) {
		$this->two = $two;
	}


}