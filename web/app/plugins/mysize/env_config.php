<?php

$env = 'production';

if ($env == 'production') {
    $GLOBALS['url_api'] = "https://api.mysz.io";
    $GLOBALS['url_widget'] = "https://widget.mysz.io";
    $GLOBALS['url_analytics'] = "https://analytics-js.mysz.io";
}
else {
    $GLOBALS['url_api'] = "https://api-staging.mysz.io";
    $GLOBALS['url_widget'] = "https://widget-staging.mysz.io";
    $GLOBALS['url_analytics'] = "https://mysize-analytics-staging.s3.eu-west-1.amazonaws.com";
}