<?php

// get the database config details from wordpress
if( ! file_exists( __DIR__ . '/../cms/wp-load.php') ){
  die('unable to find a wordpress installation, please install wordpress in the cms directory');
}

require_once __DIR__ . '/../cms/wp-load.php';

# include vimeo and soundcloud libs
require_once __DIR__ . '/../server/app/vendor/soundcloud/Services/Soundcloud.php';
require_once __DIR__ . '/../server/app/vendor/vimeo/vimeo.php';

require 'config.php';


// fetch public reports
$public_reports = get_posts(
  array(
    'post_type'   => 'report',
    'post_status' => 'publish',
    'meta_query' => array(
      array(
        'key'   => '_report_status',
        'value' => 'public'
      ),
    )
  )
);

$media_to_publisize = array();
foreach($public_reports as $post){
  // fetch image media that hasn't yet been uploaded to s3
  $args = array(
    'post_type'   => 'attachment',
    'numberposts' => null,
    'post_status' => null,
    'post_parent' => $post->ID,
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key'   => '_media_type',
        'value' => array('audio', 'video'),
        'compare' => 'IN',
      )
    )
  );

  $media = get_posts($args);
  if( count($media) > 0 ) {
    $media_to_publisize = array_merge($media_to_publisize, $media);
  }

}

try {
  foreach ($media_to_publisize as $media ) {
    if( get_post_meta($media->ID, '_uploaded', true ) == 'true' ){
      
      if( get_post_meta($media->ID, '_media_type', true ) == 'video'){
        // publisize vimeo video
        $video_id = get_post_meta($media->ID, '_vimeo_video_id', true );

        // intialize vimeo
        $vimeo = new Vimeo(
          $vimeoconfig['client_key'], 
          $vimeoconfig['client_secret'], 
          $vimeoconfig['access_token'],
          $vimeoconfig['access_token_secret']
        );

        $vimeo->call(
          'vimeo.videos.setPrivacy', 
          array(
            'video_id' => $video_id,
            'privacy'  => 'nobody'
          )
        );

      }

      if( get_post_meta($media->ID, '_media_type', true ) == 'audio'){
        // publisize soundcloud audio
        $trackdata = json_decode(get_post_meta($media->ID, '_soundcloud_track_data', true ));
        // update the track's metadata
        $client->put('tracks/' . $trackdata->id, array(
          'track[sharing]'    => 'public'
        ));
      }

    }
  }
} catch(Error $e){
  
}
