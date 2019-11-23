<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/DevPostFetcher.php";

use flight\Engine as Router;

$router = new Router();

$router->route("/", function(){
    echo "hello world!";
});

$router->route("/posts", function() {
    $request = Flight::request();
    $devPosts = new DevPostFetcher("you_api_key");
    if(isset($request->query["page"])) {
        $page = (int) $request->query["page"];
        $page = $page !== 0 ? $page : 1;
        $devPosts->setPage($page);
    }
    $posts = $devPosts->fetch();

    if(count($posts) < 1) {
      Flight::render('no_posts', [], 'body_content');
    } else {
      Flight::render('posts', ['posts' => $posts], 'body_content');
    }

    Flight::render('layout', ['page_title' => "Posts"]);
});

$router->start();