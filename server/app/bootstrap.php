<?php
/** 
 * Prepares the application
 *
 * booting up slim and 
 * @type {[type]}
 */
$app = new \Slim\Slim(
    array(
      'templates.path' => __DIR__ . '/../templates'
    )
);