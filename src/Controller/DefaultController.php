<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public function index()
    {
        $message = null;

        if (key_exists('MONGODB_URL', $_ENV)) {
            $mongoDbUrl = $_ENV['MONGODB_URL'];

            $client = new \MongoDB\Client($mongoDbUrl);

            $collection = $client->main->starwars;
            $result = $collection->insertOne( [ 'name' => 'Luke', 'occupation' => 'Jedi' ] );

            $message = "Inserted with Object ID '{$result->getInsertedId()}'";
        }

        return new Response($message);
    }

    public function config()
    {
        $mongoDbUrl = $_ENV['MONGODB_URL'];

        return new Response('MONGODB_URL: ' . $mongoDbUrl);
    }
}