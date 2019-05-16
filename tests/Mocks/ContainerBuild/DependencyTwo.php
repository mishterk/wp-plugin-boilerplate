<?php


namespace PdkPluginBoilerplate\Tests\Mocks\ContainerBuild;


class DependencyTwo {


	/**
	 * @var DependencyThree
	 */
	private $three;


	/**
	 * @var int
	 */
	private $value;


	/**
	 * @param DependencyThree $three
	 * @param int $value
	 */
	public function __construct( DependencyThree $three, $value = 1 ) {
		$this->three = $three;
		$this->value = $value;
	}


}