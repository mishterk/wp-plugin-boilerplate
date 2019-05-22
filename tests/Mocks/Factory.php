<?php


namespace PdkPluginBoilerplate\Tests\Mocks;


class Factory {


	public function __invoke() {
		return random_int( 111, 999 );
	}


}