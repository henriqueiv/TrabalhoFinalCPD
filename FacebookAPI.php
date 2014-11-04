<?php

/**
 * Description of FacebookAPI
 *
 * @author henriquevalcanaia
 */
class FacebookAPI {
    /*
     * Página para obter token
     * https://developers.facebook.com/tools/explorer/383021825180155/?method=GET&path=me%2Fhome&version=v2.1
     */

    const appSecret = "e9467392ad1384c5ecda1296df6923f5";
    const appID = "383021825180155";
    const appToken = "383021825180155|9PzaQU21GbeRVTAO9pvO1jspgZQ"; // Valid access token, I used app token here but you might want to use a user token .. up to you
    const feedToken = "CAAFcWzfUwfsBABTvpMC0FckobvFV1GJ9ERkgTi2Ucl7uUMuNrxrS30aFrlSEuBfwmaiu5OZAMXu3Cy4jnTZBXzNKXv9bwTuaVjueWlV6O8Xk31RhCzZA9nncD5AtuuKbZA0j3lh5Fi1elHZBXCYfaZCWYQMwPOEK8igcxV9nWrTlGKuSnLj572SgQLrZCL3ZCFIuMk1drEZCqDTZABf1lt9HQK15MjOy2ldtgZD";
    const pageID = "BandaTurnOff";
    const pagesBaseURL = "https://graph.facebook.com/%s/posts?access_token=%s&limit=%d&since=%s"; //(id da pg, token)
    const feedBaseURL = "https://graph.facebook.com/%s/home?access_token=%s"; //(id do perfil, token)
    const profileBaseURL = "https://graph.facebook.com/%s/feed?access_token=%s"; // (id do perfil, token)
    const profileID = "me";

    private $pesoLike = 1;
    private $pesoComment = 2;
    private $pesoShare = 3;
    private $u;

    function __construct() {
        require_once './Utils.php';
        $this->u = new Utils();
    }

    public function getPesoLike() {
        return $this->pesoLike;
    }

    public function getPesoComment() {
        return $this->pesoComment;
    }

    public function getPesoShare() {
        return $this->pesoShare;
    }

    public function setPesoLike($pesoLike) {
        $this->pesoLike = $pesoLike;
    }

    public function setPesoComment($pesoComment) {
        $this->pesoComment = $pesoComment;
    }

    public function setPesoShare($pesoShare) {
        $this->pesoShare = $pesoShare;
    }

    public function getPosts($page, $limit = 25, $since = null) {
        // > fields=message < since you want to get only 'message' property (make your call faster in milliseconds) you can remove it
        $requestURL = sprintf(FacebookAPI::baseURL, $page, FacebookAPI::appToken, $limit, $since);
        $pagePosts = file_get_contents($requestURL);
        $pagePosts = json_decode($pagePosts);
        $posts = array();
        foreach ($pagePosts->data as $post) {
            $posts[] = $post;
        }
        return $posts;
    }

    private function getDataFromURL($url, $assoc = true) {
        $data = file_get_contents($url);
        $data = json_decode($data, $assoc);
        return $data;
    }

    private function getFeedPosts($posts = array(), $requestURL = null) {
        if ($requestURL == null) {
            $requestURL = sprintf(FacebookAPI::feedBaseURL, FacebookAPI::profileID, FacebookAPI::feedToken);
        } else {
            echo $requestURL . "<br>";
        }

        $pagePosts = $this->getDataFromURL($requestURL);
        foreach ($pagePosts['data'] as $post) {
            $posts[] = $post;
        }
        return $posts;
    }

    public function getOrderedPosts($limiteChamadas = 1) {
        $posts = array();
        $chamadas = 0;
        $posts = $this->getFeedPosts($posts, $posts['pagging']['next']);
        /*
          while ((isset($posts['pagging']['next']) && $chamadas < $limiteChamadas) || (!isset($posts['pagging']['next']) || $chamadas == 0)) {
          $posts = $this->getFeedPosts($posts, $posts['pagging']['next']);
          $chamadas++;
          }
         * 
         */
        //die("getOrderedPosts");
        printrx($posts);

        $posts = $this->calcRank($posts);
        $orderedPosts = $this->u->array_sort($posts, "peso", SORT_DESC);
        return $orderedPosts;
    }

    private function calcRank($posts) {
        /*
          array de posts no formato
          posts{
          1: {
          likes: {
          1:{array com dados da pessoa},
          2:{array com dados da pessoa},
          3:{array com dados da pessoa}
          },
          comments: {
          1:{array com dados da pessoa},
          2:{array com dados da pessoa},
          3:{array com dados da pessoa}
          },
          shares: {
          1:{array com dados da pessoa},
          2:{array com dados da pessoa},
          3:{array com dados da pessoa}
          }
          }
          2: {
          likes: {
          1:{array com dados da pessoa},
          2:{array com dados da pessoa},
          3:{array com dados da pessoa}
          }
          }
          }
         */
        $p = array();
        foreach ($posts as $post) {
            $likes = $post['likes'];
            $numLikes += count($likes);

            $comments = $post['comments'];
            $numComments += count($comments);

            $shares = $post['shares'];
            $numShares += count($shares);

            $p[] = array(
                'post' => $post,
                'likes' => $numLikes,
                'comments' => $numComments,
                'shares' => $numShares,
                'peso' => ($numLikes * $this->getPesoLike()) + ($numComments * $this->getPesoComment()) + ($numShares * $this->getPesoShare())
            );
        }
        return $p;
    }

}
