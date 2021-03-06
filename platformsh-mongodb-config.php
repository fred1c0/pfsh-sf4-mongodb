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
    if (!getenv('PLATFORM_APPLICATION')) {
        return;
    }

    // Previously declared in /app/vendor/platformsh/symfonyflex-bridge/platformsh-flex-env.php:47
    setEnvVar('MONGODB_URL', mapPlatformShDocumentStoreUrl());
    setEnvVar('MONGODB_DB', mapPlatformShDocumentStoreDbName());
}

function mapPlatformShDocumentStoreUrl() : string
{
    $mongoRelationshipName = 'documentstore';

    if ($relationships = getenv('PLATFORM_RELATIONSHIPS')) {
        $relationships = json_decode(base64_decode($relationships), TRUE);
        if (!empty($relationships[$mongoRelationshipName])) {
            foreach ($relationships[$mongoRelationshipName] as $endpoint) {
                $settings = $endpoint;
                break;
            }

            $mongoDbUrl = sprintf('%s://%s:%s@%s:%d/%s',
                $settings['scheme'],
                $settings['username'],
                $settings['password'],
                $settings['host'],
                $settings['port'],
                $settings['path']
            );

            return $mongoDbUrl;
        }
    }

    return 'EMPTY_MONGODB_URL';
}

function mapPlatformShDocumentStoreDbName() : string
{
    $mongoRelationshipName = 'documentstore';

    if ($relationships = getenv('PLATFORM_RELATIONSHIPS')) {
        $relationships = json_decode(base64_decode($relationships), TRUE);
        if (!empty($relationships[$mongoRelationshipName])) {
            foreach ($relationships[$mongoRelationshipName] as $endpoint) {
                $settings = $endpoint;
                break;
            }

            $mongoDbName = $settings['path'];

            return $mongoDbName;
        }
    }

    return 'EMPTY_MONGODB_DB';
}
