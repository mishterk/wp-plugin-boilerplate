<?php


namespace PdkPluginBoilerplate\Tests\Mocks\ContainerBuild;


class DependencyOne {


	/**
	 * @var DependencyTwo
	 */
	private $two;


	/**
	 * @param DependencyTwo $two
	 */
	public function __construct( DependencyTwo $two ) {
		$this->two = $two;
	}


}