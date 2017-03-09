<?php
    date_default_timezone_set("America/Los_Angeles");
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Map.php";
    require_once __DIR__."/../src/Network.php";
    require_once __DIR__."/../src/User.php";


    $app = new Silex\Application();
    $app->register(new Silex\Provider\TwigServiceProvider(), ["twig.path" => __DIR__."/../views"]);

    $app['debug']= true;

    $server = 'mysql:host=localhost:8889;dbname=growth';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    session_start();
    if(empty($_SESSION['user'])){
        $_SESSION['user'] = [];
    }

    $app->get('/', function() use($app) {
        $network = new Network([400,100,100,400]);
        // $a = [1,0];
        // $b = [0,1];
        // $c = [1,1];
        // $array1 = [[1,2,3],[4,5,6],[7,8,9]];
        // $array2 = [[3],[4],[5]];
        // $result = Network::dot($array1,$array2);
        // $result = $network->feedforward($a);
        $_SESSION['user'] = [];//uncomment to fix pesky session bugs

        // for($i=0;$i<100;$i++){
            // $network->backprop([1,0],[0,1],.1);
            // $network->backprop([0,1],[1,0],.1);
        //     var_dump($network->feedforward($a));
        //     var_dump($network->feedforward($b));
        //     var_dump($network->feedforward($c));
        //     $network->backprop([0,1],[0,1],.1);
        //     var_dump($network->feedforward($a));
        //     var_dump($network->feedforward($b));
        //     var_dump($network->feedforward($c));
            // $network->backprop([1,1],[1,1],1);
        //     var_dump($network->feedforward($a));
        //     var_dump($network->feedforward($b));
        //     var_dump($network->feedforward($c));
        // }
        //
        // var_dump($network->feedforward($a));
        // var_dump($network->feedforward($b));
        // var_dump($network->feedforward($c));
        //
        //
        //
        //
        // var_dump($result);
        // $map = Map::find(38);
        // $coords = $map->getCoordinates();
        // $map2 = Map::find(36);
        // $coords2 = $map2->getCoordinates();
        // $map3 = Map::find(40);
        // $coords3 = $map3->getCoordinates();
        // // var_dump($coords3);
        // $loser_moves = Network::parse_training_grid($coords)[0];
        // $winner_moves = Network::parse_training_grid($coords)[1];
        // $loser_moves2 = Network::parse_training_grid($coords2)[0];
        // $winner_moves2 = Network::parse_training_grid($coords2)[1];
        // $player_moves = Network::parse_playing_grid($coords3);
        //
        // for($i=0;$i<20;$i++){
        //   $network->backprop($loser_moves, $winner_moves, 1);
        //   $network->backprop($loser_moves2, $winner_moves2, 1);
        // }
        // // var_dump($network->feedforward($player_moves));
        $result = 'no';








        return $app["twig"]->render("root.html.twig", ['result' => $result, 'user' => $_SESSION['user'], 'edit' => false]);
    });

    $app->get('/hello', function() use($app) {
        $result = 'hello';
        return $result;
    });
    $app->get('/deleteAll', function() use($app) {
        Map::deleteAll();
        return $app->redirect("/");
    });

    $app->post('/save_map', function() use ($app){
        //will save map here
        $map = new Map($_POST['title'], $_POST['type'], 1, 1, 1, 1, $_POST['map']);

        $map->save();

        return json_encode($_POST['map']);
    });

    $app->get('/load_map', function() use($app) {
        $maps = Map::getAll();
        return $app['twig']->render('all_maps.html.twig', ['maps' => $maps, 'user'=>$_SESSION['user']]);
    });

    $app->get('/play/{id}', function($id) use($app) {
        return $app['twig']->render('root.html.twig', ['user'=>$_SESSION['user'], 'edit'=>false]);
    });

    $app->post('/getMap/{id}', function($id) use($app) {
        $map = Map::find($id);
        $response = $map->getCoordinates();
        return json_encode($response);
    });

    $app->get('/create_map', function() use($app) {
        return $app['twig']->render('root.html.twig', ['edit' => true, 'user'=>$_SESSION['user']]);
    });

    $app->post('/sign_up', function() use ($app) {
        $user = new User($_POST['username'], $_POST['password']);
        $user->save();
        return $app->redirect("/");
    });

    $app->post('/log_in', function() use ($app) {
        User::logIn($_POST['username'], $_POST['password']);
        return $app->redirect("/");
    });

    $app->post('/log_out', function() use ($app) {
        $_SESSION['user']->logOut();
        return $app->redirect("/");
    });



    return $app;
?>
