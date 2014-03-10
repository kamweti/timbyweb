<!doctype html>
<html>
  <head>
    <title></title>
    <link rel="stylesheet" href="http://libs.cartocdn.com/cartodb.js/v3/themes/css/cartodb.css" />
    <!--[if lte IE 8]>
      <link rel="stylesheet" href="http://libs.cartocdn.com/cartodb.js/v3/themes/css/cartodb.ie.css" />
    <![endif]-->
    <style>
      html, body, #map {
        height: 100%;
        padding: 0;
        margin: 0;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>

    <!-- include cartodb.js library -->
    <script src="http://libs.cartocdn.com/cartodb.js/v3/cartodb.js"></script>
    <script>
      function main() {
        cartodb
          .createVis(
            'map', 
            'http://documentation.cartodb.com/api/v2/viz/2b13c956-e7c1-11e2-806b-5404a6a683d5/viz.json', 
            {
              shareable: true,
              title: true,
              description: true,
              search: true,
              tiles_loader: true,
              center_lat: 0,
              center_lon: 0,
              zoom: 2
          })
          .done(function(vis, layers) {
            // layer 0 is the base layer, layer 1 is cartodb layer
            // setInteraction is disabled by default
            layers[1].setInteraction(true);
            layers[1].on('featureOver', function(e, pos, latlng, data) {
              cartodb.log.log(e, pos, latlng, data);
            });

            // you can get the native map to work with it
            // depending if you use google maps or leaflet
            map = vis.getNativeMap();

            // now, perform any operations you need
            // map.setZoom(3)
            // map.setCenter(new google.maps.Latlng(...))
          })
          .error(function(err) {
            console.log(err);
          });
      }

      window.onload = main;
    </script>
  </body>
</html>