<?php
/**
 * Configuration file for routes.
 */
return [
    // Load these routefiles in order specified and optionally mount them
    // onto a base route.
    "routeFiles" => [
        [
            // Routes for the REM server
            "mount" => "api",
            "file" => __DIR__ . "/route/remserver.php",
        ],
    ],
];
