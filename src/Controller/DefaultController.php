<?php

namespace App\Controller;

use App\Document\Starwars;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use MongoDB\Client;

class DefaultController extends Controller
{
    public function index()
    {
        if (key_exists('MONGODB_URL', $_ENV)) {
            /** @var DocumentManager $dm */
            $dm = $this->get('doctrine_mongodb')->getManager();
            $repo = $dm->getRepository(Starwars::class);
            $starwars = $repo->findAll();
            $result = count($starwars);

//            $client = new Client($_ENV['MONGODB_URL']);
//            $collection = $client->main->starwars;
//            $result = $collection->count();

            return new Response("Number of objects in collection 'starwars': {$result}");
        } else {
            return new Response("Environment variable MONGODB_URL not found.", 404);
        }
    }

    public function insert()
    {
        if (key_exists('MONGODB_URL', $_ENV)) {
            $client = new \MongoDB\Client($_ENV['MONGODB_URL']);
            $collection = $client->main->starwars;
            $result = $collection->insertOne( [ 'name' => 'Luke', 'occupation' => 'Jedi' ] );
            return new Response("Inserted with Object ID '{$result->getInsertedId()}'");
        } else {
            return new Response("Environment variable MONGODB_URL not found.", 404);
        }
    }

    public function config()
    {
        if (key_exists('MONGODB_URL', $_ENV) && key_exists('MONGODB_DB', $_ENV)) {
            return new Response('MONGODB_URL: ' . $_ENV['MONGODB_URL'] . PHP_EOL . 'MONGODB_DB: ' . $_ENV['MONGODB_DB']);
        } else {
            return new Response("Environment variable MONGODB_URL and/or MONGODB_DBnot found.", 404);
        }
    }
}