<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_Hook_Posts extends AAL_Hook_Base {
	
	protected function _draft_or_post_title( $post = 0 ) {
		$title = get_the_title( $post );
		if ( empty( $title ) )
			$title = __( '(no title)', 'aryo-aal' );
		return $title;
	}

	public function hooks_transition_post_status( $new_status, $old_status, $post ) {
		$action = '';

		if ( 'auto-draft' === $old_status && ( 'auto-draft' !== $new_status && 'inherit' !== $new_status ) ) {
			// page created
			$action = 'created';
		}
		elseif ( 'auto-draft' === $new_status || ( 'new' === $old_status && 'inherit' === $new_status ) ) {
			// nvm.. ignore it.
			return;
		}
		elseif ( 'trash' === $new_status ) {
			// page was deleted.
			$action = 'deleted';
		}
		else {
			// page updated. i guess.
			$action = 'updated';
		}

		if ( wp_is_post_revision( $post->ID ) )
			return;

		// Skip for menu items.
		if ( 'nav_menu_item' === get_post_type( $post->ID ) )
			return;

		aal_insert_log( array(
			'action'         => $action,
			'object_type'    => 'Post',
			'object_subtype' => $post->post_type,
			'object_id'      => $post->ID,
			'object_name'    => $this->_draft_or_post_title( $post->ID ),
		) );
	}

	public function hooks_updated_post_meta($meta_id, $object_id, $meta_key, $meta_value){
		$post = get_post($object_id);

		aal_insert_log( array(
			'action'         => 'updated',
			'object_type'    => $post->post_type,
			'object_subtype' => $post->post_type,
			'object_id'      => $post->ID,
			'object_name'    => $this->_draft_or_post_title( $post->ID ),
		) );

	}

	public function hooks_delete_post( $post_id ) {
		if ( wp_is_post_revision( $post_id ) )
			return;

		$post = get_post( $post_id );

		if ( in_array( $post->post_status, array( 'auto-draft', 'inherit' ) ) )
			return;

		// Skip for menu items.
		if ( 'nav_menu_item' === get_post_type( $post->ID ) )
			return;

		aal_insert_log( array(
			'action'         => 'deleted',
			'object_type'    => 'Post',
			'object_subtype' => $post->post_type,
			'object_id'      => $post->ID,
			'object_name'    => $this->_draft_or_post_title( $post->ID ),
		) );
	}
	
	public function hooks_story_transition($action, $story){
		aal_insert_log( array(
			'action'         => $action,
			'object_type'    => 'Post',
			'object_subtype' => 'Story',
			'object_id'      => $story->id,
			'object_name'    => $story->title,
		) );
	}

	public function __construct() {
		add_action( 'transition_post_status', array( &$this, 'hooks_transition_post_status' ), 10, 3 );
		add_action( 'delete_post', array( &$this, 'hooks_delete_post' ) );
		add_action( 'updated_postmeta', array( &$this, 'hooks_updated_post_meta' ), 10, 4 );

		add_action( 'story_transition', array( &$this, 'hooks_story_transition' ), 10, 4 );
		
		parent::__construct();
	}

}