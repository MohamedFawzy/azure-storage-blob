<?php
/**
 * enable strict typing supporting from php 7
 */
declare(strict_types=1);
namespace Azure\Storage\Blob;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

/**
 * Class BlobStorageService
 * @package Azure\Storage\Blob
 * @author mohamed fawzy
 */
final class BlobStorageService
{

    private $blobRestProxy;

    /**
     * BlobStorageService constructor.
     * @param string $accountName
     * @param string $accountKey
     */
    public function __construct(string $accountName, string $accountKey)
    {
        $connectionString = "DefaultEndpointsProtocol=http;AccountName=".$accountName.";AccountKey=".$accountKey."";
        // open connection string with azure api
        $this->blobRestProxy = BlobRestProxy::createBlobService($connectionString);
    }

    /**
     * create container inside azure storage blob
     * @param string $containerName
     * @return string
     */
    public function createContainer(string $containerName): string
    {
        // create container options
        $createContainerOptions = new CreateContainerOptions();
        // set container access
        $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
        try{
            // create container
            $this->blobRestProxy->createContainer($containerName, $createContainerOptions);
        }catch (ServiceException $e){
            return $e->getMessage();
        }

        return "Container with name ". $containerName. " created";
    }
}