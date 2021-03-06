<?php 
/**
 * Angular App Initializes in this template
 */
?>
<!DOCTYPE html>
<html lang="en" ng-app="timby">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{title}}</title>

    <!-- Bootstrap -->
    <!-- <link href="<?php echo get_template_directory_uri() ?>/css/bootstrap.css" rel="stylesheet"> -->
    <link href="<?php echo get_template_directory_uri() ?>/css/global.css" rel="stylesheet">
      <script type="text/javascript">
          window.Config = <?php echo get_timby_options_json(); ?>;
      </script>
    <?php wp_head() ?>

  
  </head>
  <body data-template-url="<?php echo get_template_directory_uri() ?>" controller="MainController">
    <toaster-container toaster-options="{'time-out': 2000, 'position-class': 'toast-top-center' }"></toaster-container>
    <div ng-view></div>
    
    <?php wp_footer(); // this loads jQuery, Angular and our Angular app, see functions.php ?>

  </body>
</html>
