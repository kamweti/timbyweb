Latitude:
<input type="text" name="_latitude" id="_latitude" value="<?php if(get_post_meta( get_the_id(), '_latitude', true)): echo get_post_meta( get_the_id(), '_latitude', true); endif; ?>" />
Longitude:
<input type="text" name="_longitude" id="_longitude" value="<?php if(get_post_meta( get_the_id(), '_longitude', true)): echo get_post_meta( get_the_id(), '_longitude', true); endif; ?>" />
Type a location to navigate:
<input type="text" name="_location_address" id="_location_address" placeholder="e.g monrovia" value="<?php echo get_post_meta( get_the_id(), '_location_address', true); ?>" />
<button type='button' onclick="getlocation();">Go</button>
<br />
<br />
Select the desired location below:
<div id="map-canvas" style="min-height:300px;min-width:900px;width:100%;height:100%"></div>