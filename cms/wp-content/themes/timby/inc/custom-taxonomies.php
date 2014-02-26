<?php
/**
 * Custom Taxonomies
 *
 *
 * Sector for post type report
 */

add_action( 'init', 'create_custom_taxonomies', 0 );

function create_custom_taxonomies(){

  # REPORT CATEGORY
  $labels = array(
    'name'              => _x( 'R', 'taxonomy general name', 'timbyweb' ),
    'singular_name'     => _x( 'Sector', 'taxonomy singular name', 'timbyweb' ),
    'search_items'      => __( 'Search Sectors', 'timbyweb' ),
    'all_items'         => __( 'All Sectors', 'timbyweb' ),
    'parent_item'       => __( 'Parent Sector', 'timbyweb' ),
    'parent_item_colon' => __( 'Parent Sector:', 'timbyweb' ),
    'edit_item'         => __( 'Edit Sector', 'timbyweb' ),
    'update_item'       => __( 'Update Sector', 'timbyweb' ),
    'add_new_item'      => __( 'Add New Sector', 'timbyweb' ),
    'new_item_name'     => __( 'New Sector Name', 'timbyweb' ),
    'menu_name'         => __( 'Sectors', 'timbyweb' ),
  );

  $args = array(
    'labels'            => $labels,
    'public'            => true,
    'show_in_nav_menus' => true,
    'show_admin_column' => false,
    'hierarchical'      => false,
    'show_tagcloud'     => false,
    'show_ui'           => true,
    'query_var'         => true,
    'rewrite'           => true,
    'query_var'         => true,
    'capabilities'      => array(),
  );

  register_taxonomy( 'sector', 'report', $args );


  # SECTOR
  $labels = array(
    'name'              => _x( 'Sectors', 'taxonomy general name', 'timbyweb' ),
    'singular_name'     => _x( 'Sector', 'taxonomy singular name', 'timbyweb' ),
    'search_items'      => __( 'Search Sectors', 'timbyweb' ),
    'all_items'         => __( 'All Sectors', 'timbyweb' ),
    'parent_item'       => __( 'Parent Sector', 'timbyweb' ),
    'parent_item_colon' => __( 'Parent Sector:', 'timbyweb' ),
    'edit_item'         => __( 'Edit Sector', 'timbyweb' ),
    'update_item'       => __( 'Update Sector', 'timbyweb' ),
    'add_new_item'      => __( 'Add New Sector', 'timbyweb' ),
    'new_item_name'     => __( 'New Sector Name', 'timbyweb' ),
    'menu_name'         => __( 'Sectors', 'timbyweb' ),
  );

  $args = array(
    'labels'            => $labels,
    'public'            => true,
    'show_in_nav_menus' => true,
    'show_admin_column' => false,
    'hierarchical'      => false,
    'show_tagcloud'     => false,
    'show_ui'           => true,
    'query_var'         => true,
    'rewrite'           => true,
    'query_var'         => true,
    'capabilities'      => array(),
  );

  register_taxonomy( 'sector', 'report', $args );
  

}