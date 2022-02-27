<?php
/*
Plugin Name: s3presigned
Plugin URI: 
Description: Generate pre-signed url with shortcode
Author: jthomas
Author URI: https://github.com/twoquarterrican
Version: 0.1
*/

add_shortcode('s3presigned', 's3presigned_shortcode');
function s3presigned_shortcode( $atts = [], $content = null) {
    if ( 
        defined( 'S3_UPLOADS_BUCKET' )
        && defined( 'S3_UPLOADS_REGION' )
        && defined( 'S3_UPLOADS_KEY' )
        && defined( 'S3_UPLOADS_SECRET' )
    ) {
        return 'setup present';
    } else {
        return 'missing setup';
    }
}