# s3presigned-plugin
wordpress plugin to generate pre-signed urls for s3 with a shortcode

## Installation

copy s3presigned-plugin.php to `[wordpress-root]/wp-content/plugins/s3presigned/s3presigned-plugin.php`

put your bucket and region in `wp-config.php`

```
// ---below: manually configured plugin: https://github.com/twoquarterrican/s3presigned-plugin
define( 'S3PRESIGNED_BUCKET', 'your-bucket-name-here' );
define( 'S3PRESIGNED_REGION', 'us-east-1' );
// ---above: manually configured plugin: https://github.com/twoquarterrican/s3presigned-plugin
```

## Examples

Simplest example: just the object key
In your wordpress editor
```
[s3presigned href=just/the/object/key.zip]
```
This will generate a link saying "download" with the presigned url.
The bucket name and region is used to build the prefix of the full href for the link.

Example 2: customize the text in the link
Simplest example: just the object key[
In your wordpress editor
```
[s3presigned href=just/the/object/key.zip]Click here to download the zip[/s3presigned]
```
This will generate a link saying "download" with the presigned url.
The bucket name and region is used to build the prefix of the full href for the link.
