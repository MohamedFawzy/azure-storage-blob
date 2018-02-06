<?php

use PHPUnit\Framework\TestCase;
use Azure\Storage\Blob\service\BlobStorageService;

/**
 * Class TestBlobStorageService
 */
class TestBlobStorageService extends TestCase
{


    private $account_name;
    private $account_key;

    private $service;
    private $faker;
    private $filePath;
    private $containerName;


    const ENV_DIR_PATH= __DIR__.'/../../';

    public function __construct($name = null, array $data = [], $dataName = '')
    {

        $dotenv = new Dotenv\Dotenv(self::ENV_DIR_PATH);
        $dotenv->load();

        $this->account_name = getenv("ACCOUNT_NAME");
        $this->account_key  = getenv("ACCOUNT_KEY");
        $this->service = new BlobStorageService($this->account_name, $this->account_key);
        $this->faker  = \Faker\Factory::create();
        $this->filePath = __DIR__."/test.png";
        $this->containerName = $this->faker->word(20);


        parent::__construct($name, $data, $dataName);

    }

    public function testCreateContainer()
    {
        $containerName = $this->faker->word(20);
        $result = $this->service->createContainer($containerName);
        $this->assertSame($containerName,$result);
    }


    public function testUploadBlob()
    {

        // create container first
        $containerName = $this->faker->word(20);
        $result = $this->service->createContainer($containerName);
        $this->assertSame($containerName, $result);
        //$test->createBlob("campaigns", $filePath, "test1.jpg");
        // upload image to blob storage
        $filePath = "test.png";
        $blobName = $this->faker->word(20);
        $result = $this->service->createBlob($containerName, $filePath,$blobName);
        $this->assertSame($blobName,$result);
    }


    public function testListBlobsInsideContainer()
    {
        $containerName = $this->faker->word(20);
        $result = $this->service->createContainer($containerName);
        $this->assertSame($containerName, $result);
        //$test->createBlob("campaigns", $filePath, "test1.jpg");
        // upload image to blob storage
        $blobName = $this->faker->word(20);
        $result = $this->service->createBlob($containerName, $this->filePath,$blobName);
        $this->assertSame($blobName,$result);
        $response = $this->service->listBlobs($containerName);
        $this->assertArrayHasKey($blobName, $response);
    }
}