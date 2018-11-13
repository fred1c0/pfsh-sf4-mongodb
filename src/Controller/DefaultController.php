<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public function index()
    {
        if ($relationships = getenv('PLATFORM_RELATIONSHIPS')) {
            $relationships = json_decode(base64_decode($relationships), TRUE);

//   "documentstore" : [
//       {
//          "username" : "main",
//          "query" : {
//              "is_master" : true
//          },
//          "service" : "mydocumentstore",
//          "ip" : "169.254.167.100",
//          "cluster" : "6qopsqmoqigp4-master-7rqtwti",
//          "rel" : "mongodb",
//          "host" : "documentstore.internal",
//          "port" : 27017,
//          "hostname" : "oxwt2t4s4hj26qyzvv5blkluji.mydocumentstore.service._.eu-2.platformsh.site",
//          "path" : "main",
//          "scheme" : "mongodb",
//          "password" : "main"
//      }
//   ]

            // For a relationship named 'documentstore' referring to one endpoint.
            if (!empty($relationships['documentstore'])) {
                foreach ($relationships['documentstore'] as $endpoint) {
                    $settings = $endpoint;
                    break;
                }
            }
        }

        $server = sprintf('%s://%s:%s@%s:%d/%s',
            $settings['scheme'],
            $settings['username'],
            $settings['password'],
            $settings['host'],
            $settings['port'],
            $settings['path']
        );

        $client = new \MongoDB\Client($server);

        return new Response(
            '<html><body>Test MongoDB connection... Connection: ' . $server . '</body></html>'
        );
    }
}