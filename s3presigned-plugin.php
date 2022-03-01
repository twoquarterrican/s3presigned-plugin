<?php
/*
Plugin Name: s3presigned
Plugin URI:
Description: Generate pre-signed url with shortcode
Author: jthomas
Author URI: https://github.com/twoquarterrican
Version: 0.1
 */

// require_once 'vendor/autoload.php';

use Aws\S3\S3Client;

add_shortcode('s3presigned', 's3presigned_shortcode');

/**
 * The [s3presigned] shortcode.
 *
 * Accepts an href title, downloadName and will display a link
 * whose href is a presigned s3 url of the given href.
 *
 * @param array  $atts    Shortcode attributes. Default empty.
 * @param string $content Shortcode content. Default 'download'.
 * @param string $tag     Shortcode tag (name). Default empty.
 * @return string Shortcode output.
 */
function s3presigned_shortcode($atts = [], $content = 'download', $tag = '')
{
    // sanity check #1
    if (
        !defined('S3PRESIGNED_BUCKET')
        || !defined('S3PRESIGNED_REGION')
    ) {
        $result = '<div hidden>s3presigned plugin not set up. Check if you put';
        $result .= " define( 'S3PRESIGNED_BUCKET', '...') ";
        $result .= " and define ( 'S3PRESIGNED_REGION', '...') ";
        $result .= " in wp-config</div>";
        $result .= $content;
        return result;
    }

    // normalize attribute keys, lowercase
    $atts = array_change_key_case((array) $atts, CASE_LOWER);

    // override default attributes with user attributes
    $wporg_atts = shortcode_atts(
        array(
            'title' => 'Temporary link',
            'downloadName' => '',
        ), $atts, $tag
    );

    // sanity check #2
    if (empty($atts['href'])) {
        return '<div hidden>s3presigned shortcode found no href</div>' . $content;
    }

    try {
        $s3Client = new Aws\S3\S3Client([
            'region' => S3PRESIGNED_REGION,
            'version' => '2006-03-01',
            // using default credentials
        ]);

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => S3PRESIGNED_BUCKET,
            'Key' => $atts['href'],
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');
        $presignedUrl = (string) $request->getUri();

        $a = '<a href="' . $presignedUrl . '"';
        $a .= ' title="' . $atts['title'] . '"';
        if (empty($atts['downloadName'])) {
            $a .= ' download>';
        } else {
            $a .= ' download="' . $atts['downloadName'] . '">';
        }
        if (empty($content)) {
            $a .= 'download</a>';
        } else {
            $a .= $content . '</a>';
        }
        $a .= '</a>';
        return $a;
    } catch (Exception $ex) {
        $result = '<div hidden>s3presigned shortcode failed to generate presigned url';
        $result .= $ex . '</div>' . $content;
        return $result;
    }
}
