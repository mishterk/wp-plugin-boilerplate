<?php


namespace PdkPluginBoilerplate\Tests\Mocks\ContainerBuild;


class RootClassAlt {


	/**
	 * @var DependencyOne
	 */
	public $one;


	/**
	 * @param DependencyOne $one
	 */
	public function __construct( DependencyOne $one ) {
		$this->one = $one;
	}


}