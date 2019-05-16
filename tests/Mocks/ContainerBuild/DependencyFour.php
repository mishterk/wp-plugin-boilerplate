<?php


namespace PdkPluginBoilerplate\Tests\Mocks\ContainerBuild;


class DependencyFour {


	/**
	 * @var null
	 */
	private $value;


	/**
	 * @param null $value
	 */
	public function __construct( $value = null ) {
		$this->value = $value;
	}


}