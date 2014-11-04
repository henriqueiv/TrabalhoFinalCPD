<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './FacebookAPI.php';
$u = new FacebookAPI();

function printr($asd) {
    echo "<pre>";
    print_r($asd);
    echo "<pre>";
}

function printrx($asd) {
    printr($asd);
    die();
}

$page = isset($_GET['page']) ? $_GET['page'] : FacebookAPI::pageID;

//$posts = $u->getPosts($page);
$posts = $u->getOrderedPosts();
printrx($posts);
