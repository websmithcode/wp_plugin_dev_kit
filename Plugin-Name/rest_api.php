<?php
add_action('rest_api_init', function () {
  register_rest_route('plugin-name/v1', '/endpoint', array(
    'methods'  => 'GET',
    'callback' => 'callback',
    'permission_callback' => '__return_true',
  ));
});

function callback()
{
	retutn "Data from rest_api.php";
}
