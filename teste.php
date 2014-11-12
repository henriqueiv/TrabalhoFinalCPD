<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './FacebookAPI.php';
$u = new FacebookAPI();

function printr($asd) {
    echo "<pre > ";
    print_r($asd);
    echo "<pre>";
}

function printrx($asd) {
    printr($asd);
    die();
}

$page = isset($_GET['page']) ? $_GET['page'] : FacebookAPI::pageID;

//$posts = $u->getPosts($page);
$limite = isset($_GET['limite']) ? $_GET['limite'] : 1;
$posts = $u->getOrderedPosts($limite);
$posts = json_encode($posts);
printrx($posts);
?>
<html>
    <head>
        <!--
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.3/css/jquery.dataTables.css">
        -->
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/380cb78f450/integration/bootstrap/3/dataTables.bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"/>

        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.3/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/380cb78f450/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#example').dataTable();
            });
        </script>
        <style>
            body { 
                font-size: 140%; 
            }
        </style>
    </head>
    <body>
        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Autor</th>
                    <th>Data</th>
                    <th>Peso</th>
                    <th>Post</th>
                    <th>Likes</th>
                    <th>Comments</th>
                    <th>Shares</th>
                </tr>
            </thead>
            <tbody>
                <?php
                echo "<pre>";
                print_r($posts);
                die();
                foreach ($posts as $key => $p) {
                    echo "<tr>";

                    echo "<td>";
                    echo $key;
                    echo "</td>";

                    echo "<td>";
                    echo $p['post']['from']['name'];
                    echo "</td>";

                    echo "<td>";
                    echo $p['post']['created_time'];
                    echo "</td>";

                    echo "<td>";
                    echo $p['peso'];
                    echo "</td>";

                    echo "<td>";
                    echo $p['post']['message'];
                    echo "</td>";

                    echo "<td>";
                    echo $p['likes'];
                    echo "</td>";

                    echo "<td>";
                    echo $p['comments'];
                    echo "</td>";

                    echo "<td>";
                    echo $p['shares'];
                    echo "</td>";

                    echo "</tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <!--
                    <th>URL: <a href='<?php //$posts['url']   ?>'><?php //$posts['url']   ?></th>
                    -->
                </tr>
            </tfoot>
        </table>
    </body>
</html>
