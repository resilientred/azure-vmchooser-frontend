<?php
  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Appinsight {
    public function Appinsight() {
      require 'vendor/autoload.php';
      $telemetryClient = new \ApplicationInsights\Telemetry_Client();
      $telemetryClient->getContext()->setInstrumentationKey(getenv('APPINSIGHTS_INSTRUMENTATIONKEY'));
    }
  }
  
?>  
