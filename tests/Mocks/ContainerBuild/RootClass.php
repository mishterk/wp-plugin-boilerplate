<?php


namespace PdkPluginBoilerplate\Tests\Mocks\ContainerBuild;


class RootClass {


	/**
	 * @var DependencyOne
	 */
	public $one;


	/**
	 * @var DependencyTwo
	 */
	public $two;


	/**
	 * @param DependencyOne $one
	 * @param DependencyTwo $two
	 */
	public function __construct( DependencyOne $one, DependencyTwo $two ) {
		$this->one = $one;
		$this->two = $two;
	}


}