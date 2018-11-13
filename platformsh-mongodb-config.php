<?php

declare(strict_types=1);

mapPlatformShDocumentStoreEnvironment();

/**
 * Map Platform.Sh environment variables to the values Symfony Flex expects.
 *
 * This is wrapped up into a function to avoid executing code in the global
 * namespace.
 */
function mapPlatformShDocumentStoreEnvironment() : void
{
    // Previously declared in /app/vendor/platformsh/symfonyflex-bridge/platformsh-flex-env.php:47
    setEnvVar('MONGODB_URL', mapPlatformShDocumentStoreConfig());
}

function mapPlatformShDocumentStoreConfig() : string
{
    $dbRelationshipName = 'documentstore';

    if (getenv('PLATFORM_RELATIONSHIPS')) {
        $relationships = json_decode(base64_decode(getenv('PLATFORM_RELATIONSHIPS'), true), true);
        if (isset($relationships[$dbRelationshipName])) {
            foreach ($relationships[$dbRelationshipName] as $endpoint) {
                $settings = $endpoint;
                break;
            }
        }
    }

    $dbUrl = $server = sprintf('%s://%s:%s@%s:%d/%s',
        $settings['scheme'],
        $settings['username'],
        $settings['password'],
        $settings['host'],
        $settings['port'],
        $settings['path']
    );

    return $dbUrl;
}
