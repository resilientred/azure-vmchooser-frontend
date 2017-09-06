<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Help me find a VM size in Azure!</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cerulean/bootstrap.min.css" rel="stylesheet" integrity="sha384-zF4BRsG/fLiTGfR9QL82DrilZxrwgY/+du4p/c7J72zZj+FLYq4zY00RylP9ZjiT" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo base_url(); ?>">Azure VM Chooser <?php echo getenv("VMCHOOSERTITLESUFFIX"); ?></a>
    </div>
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo base_url()."vmchooser/"; ?>">Search</a></li>
      </ul>
	  <ul class="nav navbar-nav">
        <li><a href="<?php echo base_url()."vmchooser/csv/"; ?>">CSV Upload</a></li>
      </ul>
    <ul class="nav navbar-nav navbar-right">
        <li><a href="https://aka.ms/vmchooserdev">Preview Edition</a></li>
    </ul>
	  <ul class="nav navbar-nav navbar-right">
        <li><a href="<?php echo base_url()."vmchooser/about/"; ?>">About</a></li>
      </ul>
    </div>
  </div>
</nav>


        