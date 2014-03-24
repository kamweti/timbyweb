<?php 

/**
 * Custom Post types
 */
require get_template_directory() . '/inc/custom-post-types.php';

/**
 * Custom User Roles
 */
require get_template_directory() . '/inc/custom-user-roles.php';

/**
 * Custom Taxonomies
 */
require get_template_directory() . '/inc/custom-taxonomies.php';


/**
 * Custom API logs page
 */
require get_template_directory() . '/inc/custom-api-logs-page/page.php';


if ( ! function_exists( 'timbyweb_setup' ) ) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which runs
   * before the init hook. The init hook is too late for some features, such as indicating
   * support post thumbnails.
   */
  function timbyweb_setup() {

    /**
     * Make theme available for translation
     * Translations can be filed in the /languages/ directory
     * If you're building a theme based on timbyweb, use a find and replace
     * to change 'timbyweb' to the name of your theme in all the template files
     */
    load_theme_textdomain( 'timbyweb', get_template_directory() . '/languages' );

    /**
     * Add default posts and comments RSS feed links to head
     * Add post thumbnails
     */
    add_theme_support( 'automatic-feed-links', 'post-thumbnails' );


    /**
     * Create starter pages
     * if they don't already exist
     *
     * @todo: move this to its own function call, check if this page already exists, remove it once theme is switched
     */
    wp_insert_post(
      array(
        'post_name' => 'signin'
      )
    );      

  }
endif; // timbyweb_setup
add_action( 'after_setup_theme', 'timbyweb_setup' );

/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {
  if ( ! class_exists( 'cmb_Meta_Box' ) )
    require_once 'lib/metabox/init.php';
}
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );

/**
 * Custom Meta boxes
 */
require get_template_directory() . '/inc/custom-meta-boxes.php';

/**
 * Add a custom schedule 'every 10mins'
 */
add_filter( 'cron_schedules', 'cron_add_every_ten_minutes' );
function cron_add_every_ten_minutes( $schedules ) {
  // Adds once weekly to the existing schedules.
  $schedules['every_ten_minutes'] = array(
    'interval' => 600,
    'display' => __( 'Every ten minutes' )
  );
  return $schedules;
}


function fetch_new_reports( $args = array()){
  $args = array_merge(
    array(
      'post_type'      => 'report',
      'posts_per_page' => -1,
      'orderby'        => 'meta_key = _date_reported',
      'order'          => 'DESC',
    ),
    $args
  );

  $reports = get_posts($args);

  foreach($reports as $key => $report){
    $report->{$key} = get_report_data($report);
  }

  return $reports;
}

function get_report_data($report){
    //reporter
    $report->reporter = get_the_author_meta( 'display_name', $report->post_author );

    // report date
    $report->date_reported = date('l jS F, Y - g:ia', strtotime(get_post_meta($report->ID, '_date_reported', true)) );

    // media count
    $report->media = new StdClass;

    $report->media->audio = fetch_attachments('audio', $report->ID);
    $report->media->video = fetch_attachments('video', $report->ID);
    $report->media->photos = fetch_attachments('image', $report->ID);
    
    //verification status
    $report->verified = (get_post_meta($report->ID, '_cmb_verified', true ) == 'on');


    //geo data
    $report->lng = get_post_meta( $report->ID, '_longitude', true);
    $report->lat = get_post_meta( $report->ID, '_latitude', true);

    return $report;
}


function fetch_attachments($type = '', $post_parent = '')
{
  $args = array(
    'post_type'   => 'attachment',
    'numberposts' => null,
    'post_status' => null,
    'post_parent' => $post_parent,
    'meta_query' => array(
      array(
        'key'   => '_media_type',
        'value' => $type
      )
    )
  );
  $attachments = get_posts($args);

  foreach($attachments as $key=>$attachment) {
    if( $type == 'audio' && get_post_meta($attachment->ID, '_uploaded', true ) == 'true'){
      $attachment->soundcloud = json_decode(get_post_meta($attachment->ID, '_soundcloud_track_data', true ));        
    }
    if( $type == 'video' && get_post_meta($attachment->ID, '_uploaded', true ) == 'true'){
      $attachment->vimeo = array( 'video_id' => get_post_meta($attachment->ID, '_vimeo_video_id', true ) );        
    }
    $attachments[$key] = $attachment;
  }
  return $attachments;
}

/**
 * A cron job running every 10 mins checking 
 * if there are any new posts, new posts are essentially
 * posts with no _cartodb_id custom field
 */
// require get_template_directory().'/lib/cartodb/cartodb.class.php';
// wp_schedule_event(time(), 'every_ten_minutes', 'sync_reports_to_carto');
// function sync_reports_to_carto(){
//   // initialize cartodb
//   $cartodb =  new CartoDBClient(
//     array(
//       'key'       => 'jTIOqWUcpsQyfvQP46s09pcGcDXEn877qhgaN44C',
//       'secret'    => 'VUX82GTIzm10o9NoptjJ5ksl73eO7miUbFi3M2t9',
//       'email'     => 'kamweti@circle.co.ke',
//       'password'  => 'P%)>zV:M&{f2K74',
//       'subdomain' => 'kaam'
//     )
//   );

//   // query all posts without a cartodb id field
//   $args = array(
//     'post_type'   => 'report',
//     'post_status' => 'publish',
//     'meta_query' => array(
//       array(
//         'key'     => '_cartodb_id',
//         'compare' => 'NOT EXISTS'
//       )
//     )
//   );
//   $reports = get_posts($args);

//   if( count($reports) > 0){
//     //syncs these new reports with carto
//     foreach($reports as $post){
//       $lnglat = array(
//         'lng' => get_post_meta($post->ID, '_longitude', true ) ?: '0', //ternary operator here assigning a default value http://cn2.php.net/ternary#language.operators.comparison.ternary
//         'lat' => get_post_meta($post->ID, '_latitude', true ) ?: '0',
//       );

//       // try inserting data into table
//       $cartoreport = $cartodb->insertRow(
//         'reports', 
//         array(
//           'post_id' => "'".$post->ID."'",
//           'the_geom' => "ST_SetSRID(ST_Point(".$lnglat['lng'].", ".$lnglat['lat']."),4326)"
//         )
//       );

//       //save the cartodb id as a custom meta field
//       if( isset($cartoreport['return']['rows'][0]->id) ) {
//         update_post_meta($post->ID, '_cartodb_id', $cartoreport->rows->id );
//       }
//     }    
//   }

// }

