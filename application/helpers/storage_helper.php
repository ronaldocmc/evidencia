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
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

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
/**
 * Utility function to set container options.
 *
 * This function creates some container setup valçues, like the name and metadata.
 * Returns an array with the container name and creation options;
 *
 * @return array
 */
function config_container_options()
{
    // Create container options object.
    $createContainerOptions = new CreateContainerOptions();
    $createContainerOptions->setPublicAccess(PublicAccessType::BLOBS_ONLY); // Anonimous Read-Only for Blobs

    // Set container metadata.
    $createContainerOptions->addMetaData('key1', 'value1');
    $createContainerOptions->addMetaData('key2', 'value2');

    $containerName = 'blobstorage'.random_str(16); // random container name

    return ['name' => $containerName, 'options' => $createContainerOptions];
}

/**
 * Function to upload files to storage.
 *
 * This functions uploads a local file to the Azure Blob Storage.
 * To upload, we must provide a container name and connect to the
 * Azure Blob Service using the RESTClient, this is done using
 * the connection string.
 *
 * After the upload is complete we must get the public url of that blob
 *
 * @param string $content  File content in string form
 * @param string $blobName Name/Path of the blob on Azure Storage
 *
 * @return string Public URL for the uploaded blob
 */
function upload_to_storage(string $content, string $blobName)
{
    // Set the connection string using enviroment variables
    $connectionString = 'DefaultEndpointsProtocol=https;AccountName='.getenv('AZURE_STORAGE_ACCOUNT').';AccountKey='.getenv('AZURE_STORAGE_KEY');

    try {
        // Create blob client.
        $blobClient = BlobRestProxy::createBlobService($connectionString);
        $containerName = 'evidenciaimages';

        log_message('MONITORING', 'Uploading file to Blob Storage...');
        // Upload blob
        $blobClient->createBlockBlob($containerName, $blobName, $content);
        log_message('MONITORING', 'Success uploading file');

        // public URLS are [http|https]://[account-name].[endpoint-suffix(usually blob.core.windows.net]/[container-name]/[blob-name]
        $blobUrl = 'https://'.getenv('AZURE_STORAGE_ACCOUNT').'.blob.core.windows.net/'.$containerName.'/'.$blobName;

        return $blobUrl;
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

function remove_from_storage($blobName)
{
    $connectionString = 'DefaultEndpointsProtocol=https;AccountName='.getenv('AZURE_STORAGE_ACCOUNT').';AccountKey='.getenv('AZURE_STORAGE_KEY');

    try {
        // Create blob client.
        $blobClient = BlobRestProxy::createBlobService($connectionString);
        $containerName = 'evidenciaimages';

        log_message('MONITORING', 'Removing file from Blob Storage...');
        // Delete blob
        $blobClient->deleteBlob($containerName, $blobName);
        log_message('MONITORING', 'Success deleting file');

        return true; // File has been deleted
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
