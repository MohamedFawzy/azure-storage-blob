# Azure Storage Blobs
- Microsoft azure blob storage service gateway .


# Description
- Api gateway service for using microsoft azure blobs storage account .

# License
- GNU (GENERAL PUBLIC LICENSE) .
- Version 3, 29 June 2007 .

# Requirements
- PHP >= 7 .
- Composer .
 
# Install
- `composer require azure/storage-blob`

# Testing
- `cp .env.example cp.env`  
- Update the following keys:

        1- ACCOUNT_NAME = "YOUR STORAGE ACCOUNT NAME"
        
        2- ACCOUNT_KEY = "ACCOUNT KEY CAN BE ACCESSED FROM AZURE DASHBOARD"
        
- Run the following command .
`./vendor/bin/phpunit`
        
