<?php
/**----------------------------------------------------------------------------------
 * Microsoft Developer & Platform Evangelism
 *
 * Copyright (c) Microsoft Corporation. All rights reserved.
 *
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND,
 * EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND/OR FITNESS FOR A PARTICULAR PURPOSE.
 *----------------------------------------------------------------------------------
 * The example companies, organizations, products, domain names,
 * e-mail addresses, logos, people, places, and events depicted
 * herein are fictitious.  No association with any real company,
 * organization, product, domain name, email address, logo, person,
 * places, or events is intended or should be inferred.
 *----------------------------------------------------------------------------------
 **/

/** -------------------------------------------------------------
 * Azure Storage Blob Sample - Demonstrate how to use the Blob Storage service.
 * Blob storage stores unstructured data such as text, binary data, documents or media files.
 * Blobs can be accessed from anywhere in the world via HTTP or HTTPS.
 *
 * Documentation References:
 *  - Associated Article - https://docs.microsoft.com/en-us/azure/storage/blobs/storage-quickstart-blobs-php
 *  - What is a Storage Account - http://azure.microsoft.com/en-us/documentation/articles/storage-whatis-account/
 *  - Getting Started with Blobs - https://azure.microsoft.com/en-us/documentation/articles/storage-php-how-to-use-blobs/
 *  - Blob Service Concepts - http://msdn.microsoft.com/en-us/library/dd179376.aspx
 *  - Blob Service REST API - http://msdn.microsoft.com/en-us/library/dd135733.aspx
 *  - Blob Service PHP API - https://github.com/Azure/azure-storage-php
 *
 **/
require_once 'vendor/autoload.php';
require_once APPPATH.'core/MyException.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

// Set the connection string using enviroment variables
$connectionString = 'DefaultEndpointsProtocol=https;AccountName='.getenv('AZURE_STORAGE_ACCOUNT').';AccountKey='.getenv('AZURE_STORAGE_KEY');

/**
 * Secure Random String Generator.
 *
 * Given a length and a keyspace, using a cryptographically secure
 * pseudorandom number generator (`random_int`) generates a random
 * string each time.
 *
 * @param int    $length   Desired string size
 * @param string $keyspace All possible characters
 *
 * @return string
 */
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces[] = $keyspace[random_int(0, $max)];
    }

    return implode('', $pieces);
}

function config_container_options()
{
    // Create container options object.
    $createContainerOptions = new CreateContainerOptions();

    // Set public access policy. Possible values are
    // PublicAccessType::CONTAINER_AND_BLOBS and PublicAccessType::BLOBS_ONLY.
    // CONTAINER_AND_BLOBS:
    // Specifies full public read access for container and blob data.
    // proxys can enumerate blobs within the container via anonymous
    // request, but cannot enumerate containers within the storage account.
    //
    // BLOBS_ONLY:
    // Specifies public read access for blobs. Blob data within this
    // container can be read via anonymous request, but container data is not
    // available. proxys cannot enumerate blobs within the container via
    // anonymous request.
    // If this value is not specified in the request, container data is
    // private to the account owner.
    $createContainerOptions->setPublicAccess(PublicAccessType::BLOBS_ONLY);

    // Set container metadata.
    $createContainerOptions->addMetaData('key1', 'value1');
    $createContainerOptions->addMetaData('key2', 'value2');

    $containerName = 'blobstorage'.random_str(16);

    return [$containerName, $createContainerOptions];
}

function get_current_date()
{
    date_default_timezone_set('America/Sao_Paulo');

    return date('Y-m-d');
}

function upload_to_storage($fileToUpload)
{
    // Create blob client.
    $blobClient = BlobRestProxy::createBlobService($connectionString);
    $containerName = 'evidenciaimages';
    try {
        // Getting local file so that we can upload it to Azure
        $myfile = fopen($fileToUpload, 'w') or die('Unable to open file!');
        fclose($myfile);

        log_message('MONITORING', 'Uploading file '.$fileToUpload.' to Blob Storage...');

        // Get the file content to upload
        $content = fopen($fileToUpload, 'r');
        // Generates the name of the file
        $blobName = get_current_date().'/'.(hash(ALGORITHM_HASH, $params['id'].uniqid(rand(), true)).'.jpg');

        //Upload blob
        $blobClient->createBlockBlob($containerName, $blobName, $content);

        // List blobs.
        $listBlobsOptions = new ListBlobsOptions();
        $listBlobsOptions->setPrefix($fileToUpload);

        $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
        foreach ($result->getBlobs() as $blob) {
            $blobUrl = $blob->getUrl();
        }
    } catch (ServiceException $e) {
        $code = $e->getCode();
        $error_message = $e->getMessage();
        log_message('ERROR', 'CODE ['.$code.'] - '.$error_message);
        throw new MyException($error_message, $code);
    } catch (InvalidArgumentTypeException $e) {
        $code = $e->getCode();
        $error_message = $e->getMessage();
        log_message('ERROR', 'CODE ['.$code.'] - '.$error_message);
        throw new MyException($error_message, $code);
    }
}
