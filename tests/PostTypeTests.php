<?php


namespace PdkPluginBoilerplate\Tests;


use PdkPluginBoilerplate\Framework\PostTypes\Post;
use PdkPluginBoilerplate\Tests\Mocks\PostType\PostType;


class PostTypeTests extends \WP_UnitTestCase {


	public function test_magic_access_to_post_object_properties() {
		$mock = $this->factory->post->create_and_get( [
			'post_title'   => 'title',
			'post_content' => 'content',
		] );

		$post = Post::find( $mock->ID );

		$this->assertSame( 'title', $post->post_title );
		$this->assertSame( 'content', $post->post_content );
	}


	public function test_magic_set_capability_of_post_object_properties() {
		$mock = $this->factory->post->create_and_get( [
			'post_title'   => 'title',
			'post_content' => 'content',
		] );

		$post = Post::find( $mock->ID );

		$post->post_title   = 'title changed';
		$post->post_content = 'content changed';

		$this->assertSame( 'title changed', $post->post_title );
		$this->assertSame( 'content changed', $post->post_content );
	}


	public function test_save_method_updates_existing_post_in_the_db() {
		$mock = $this->factory->post->create_and_get( [
			'post_title'   => 'title',
			'post_content' => 'content',
		] );

		$post = Post::find( $mock->ID );

		$post->post_title   = 'title changed';
		$post->post_content = 'content changed';

		$post->save();

		wp_cache_flush();

		$post2 = Post::find( $mock->ID );

		$this->assertSame( 'title changed', $post2->post_title );
		$this->assertSame( 'content changed', $post2->post_content );
	}


	public function test_accessor_can_mutate_existing_post_properties() {
		$mock = $this->factory->post->create_and_get( [
			'post_title'   => 'title',
			'post_content' => 'content',
		] );

		$post = PostType::find( $mock->ID );

		$this->assertSame( 'custom value', $post->some_random_prop );
	}


	public function test_accessor_can_mutate_non_existent_post_properties() {
		$mock = $this->factory->post->create_and_get( [
			'post_title'   => 'title',
			'post_content' => 'content',
		] );

		$post = PostType::find( $mock->ID );

		$this->assertSame( 'title-appended', $post->post_title );
	}


}