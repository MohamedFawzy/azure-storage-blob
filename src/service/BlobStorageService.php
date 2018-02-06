<?php
/**
 * enable strict typing supporting from php 7
 */
declare(strict_types=1);
namespace Azure\Storage\Blob\service;
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
        try{
            // open connection string with azure api
            $this->blobRestProxy = BlobRestProxy::createBlobService($connectionString);
        }catch (ServiceException $e){
            return $e->getCode();
        }
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

        return  $containerName;
    }

    /**
     * Upload media function
     * @param $containerName
     * @param $filePath
     * @param $blobName
     * @return string
     * @throws \Exception
     */
    public function createBlob(string $containerName, string $filePath, string $blobName): string
    {
        if(!is_readable($filePath)){
            throw new \Exception("File : ".  $filePath. " is not readable");
        }

        $content = fopen($filePath, "r");
        try{

            $this->blobRestProxy->createBlockBlob($containerName,$blobName,$content);

        }catch (ServiceException $e){
            return $e->getMessage();
        }

        return $blobName;
    }

    /**
     * download blob which can be image , videos , etc
     * @param string $containerName
     * @param string $blobName
     * @return string which is stream string need to use base 64 to return data
     */
    public function downloadBlob(string $containerName, string  $blobName): string
    {
        try{
            $blob = $this->blobRestProxy->getBlob($containerName, $blobName);
            $source = stream_get_contents($blob->getContentStream());
        }catch (ServiceException $e){
            return $e->getMessage();
        }

        return $source;
    }


    /**
     * get all blobs in azure based on container name
     * @param string $containerName
     * @return string|array
     */
    public function listBlobs(string $containerName)
    {
        try{
            $result = [];
            $blobList = $this->blobRestProxy->listBlobs($containerName);
            $blobs    =  $blobList->getBlobs();
            foreach ($blobs as $blob){
                $result[$blob->getName()] = $blob->getUrl();
            }

        }catch (ServiceException $e){

            return $e->getMessage();
        }
        return $result;
    }
}