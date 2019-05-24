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

		$post2 = Post::find( $mock->ID );

		$this->assertSame( 'title changed', $post2->post_title );
		$this->assertSame( 'content changed', $post2->post_content );
	}


	public function test_get_mutator_can_mutate_existing_post_properties() {
		$mock = $this->factory->post->create_and_get( [
			'post_title'   => 'title',
			'post_content' => 'content',
		] );

		$post = PostType::find( $mock->ID );

		$this->assertSame( 'custom value', $post->some_random_prop );
	}


	public function test_get_mutator_can_mutate_non_existent_post_properties() {
		$mock = $this->factory->post->create_and_get( [
			'post_title'   => 'title',
			'post_content' => 'content',
		] );

		$post = PostType::find( $mock->ID );

		$this->assertSame( 'title-appended', $post->post_title );
	}


	public function test_set_mutator_can_mutate_existing_post_properties() {
		$mock = $this->factory->post->create_and_get();
		$post = PostType::find( $mock->ID );

		$post->post_excerpt = 'excerpt';
		$post->save();
		$post = PostType::find( $mock->ID );

		$this->assertSame( 'excerpt-appended', $post->post_excerpt );
	}


	public function test_set_mutator_can_mutate_non_existing_post_properties() {
		$mock = $this->factory->post->create_and_get();
		$post = PostType::find( $mock->ID );

		$post->overloaded = 'overloaded';

		$this->assertSame( 'overloaded-appended', $post->overloaded );
	}


	public function test_set_mutator_returns_empty_string_where_value_has_not_been_explicity_saved_and_retrieved() {
		$mock = $this->factory->post->create_and_get();
		$post = PostType::find( $mock->ID );

		$post->overloaded = 'overloaded';

		// save the post and pull it from the DB again.
		$post->save();
		$post = PostType::find( $mock->ID );

		// Our test mock doesn't store the data anywhere so WordPress won't have this property.
		$this->assertSame( '', $post->overloaded );
	}


	public function test_accessible_props_are_set_and_retrieved_from_the_decorator() {
		$mock = $this->factory->post->create_and_get();
		$post = PostType::make( $mock );

		$this->assertEquals( 'public', $post->some_public_prop );

		$post->some_public_prop = 'overridden';

		$this->assertEquals( 'overridden', $post->some_public_prop );
		$this->assertFalse( property_exists( $mock, 'some_public_prop' ),
			'The accessible property should not exist on the WP_Post object' );
	}


}