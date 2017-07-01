<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h2>About</h2>
<p>"VMchooser" was created by <a href="https://about.kvaes.be/" target="_blank">Karim Vaes</a> to aid anyone in a quest to find the right VM size in Azure.</p>
<p>The <a href="https://github.com/kvaes/azure-vmchooser-frontend" target="_blank">frontend</a>, <a href="https://github.com/kvaes/azure-vmchooser-backend" target="_blank">backend</a> & <a href="https://github.com/kvaes/azure-vmchooser-database" target="_blank">database</a> are hosted on Github for the curious people out there. ;-)</p>
<p>The architecture behind the web page heavily relies on Azure App Service (Web App), Azure Functions, & Azure Blob Storage. The frontend serves the pages and will query the API (Azure Functions) directly for the search, or store the CSV file onto a storage account. The CSV file will be picked up on the storage by another Azure Function.</p>
<p>"VMchooser" is by no means endorsed by Microsoft as an organisation and it comes without any warranties. See this as a pure community project by an individual who wanted to invest personal time into helping others.</p>
<p>The csv data uploaded is not mined and will be solely used as input for the parser. Please do not upload any sensitive information as the output data is to be considered public.</p>