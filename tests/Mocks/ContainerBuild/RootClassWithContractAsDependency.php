<?php


namespace PdkPluginBoilerplate\Tests\Mocks\ContainerBuild;


class RootClassWithContractAsDependency {


	/**
	 * @var Contract
	 */
	public $one;


	/**
	 * @param Contract $one
	 */
	public function __construct( Contract $one ) {
		$this->one = $one;
	}


}