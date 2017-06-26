<?php
  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
use MicrosoftAzure\Storage\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Common\ServiceException;
use MicrosoftAzure\Storage\Queue\Models\CreateQueueOptions;
use MicrosoftAzure\Storage\Queue\Models\PeekMessagesOptions;
use MicrosoftAzure\Storage\Table\Models\Entity;
use MicrosoftAzure\Storage\Table\Models\EdmType;
  
class Azurestorage
{
	public function Azurestorage() {
		require 'vendor/autoload.php';
	}
	
	public function __construct()
	{
		// nothing to see here
	}
	
	public function getConnectionString()
	{
		$storageaccountkey = getenv('STORAGE_ACCOUNT_KEY');
		$storageaccountname = getenv('STORAGE_ACCOUNT_NAME');
		return "DefaultEndpointsProtocol=https;AccountName=$storageaccountname;AccountKey=$storageaccountkey";
	}
	
	public function uploadCsvFile($connectionString, $inputFile)
	{
		echo "blob client";
		$blobClient = ServicesBuilder::getInstance()->createBlobService($connectionString);
		$content = fopen($inputFile, "r");
		$container_name = "input";
		$blob_name = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)).".csv";
		echo $blob_name;
		try {
			echo "try it";
			$blobClient->createBlockBlob($container_name, $blob_name, $content);
		} catch (ServiceException $e) {
			echo "shit went wrong";
			$code = $e->getCode();
			$error_message = $e->getMessage();
			echo $code.": ".$error_message.PHP_EOL;
		}
		return $blob_name;
	}
	
}

?>  