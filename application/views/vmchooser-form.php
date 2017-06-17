<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Help me find a VM size in Azure!</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cerulean/bootstrap.min.css" rel="stylesheet" integrity="sha384-zF4BRsG/fLiTGfR9QL82DrilZxrwgY/+du4p/c7J72zZj+FLYq4zY00RylP9ZjiT" crossorigin="anonymous">
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
      <a class="navbar-brand" href="#">Azure VM Chooser</a>
    </div>
  </div>
</nav>

<?php echo validation_errors('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>

<?php 
$attributes = array('class' => 'form-horizontal', 'id' => 'vmchooser');
echo form_open(site_url('/'), $attributes);
?>
  <fieldset>
    <legend>Requirements for the virtual machine</legend>
    <div class="form-group">
      <label class="col-lg-2 control-label">Disk Type</label>
      <div class="col-lg-10">
        <div class="radio">
          <label>
            <input type="radio" name="ssd" id="optionsRadios1" value="no" checked="">
            Standard disks only
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="ssd" id="optionsRadios2" value="yes">
            I'll be needing Premiums disks (SSD)
          </label>
        </div>
      </div>
    </div>
	<div class="form-group">
      <label for="inputCores" class="col-lg-2 control-label">Number of Cores</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" name="inputCores" id="inputCores"  value="<?php echo set_value('inputCores[]'); ?>" placeholder="What's the minimum of cores this VM needs?" autocomplete="off">
      </div>
    </div>
	<div class="form-group">
      <label for="inputMemory" class="col-lg-2 control-label">Amount of Memory</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" name="inputMemory" id="inputMemory" value="<?php echo set_value('inputMemory[]'); ?>" placeholder="What's the minimum amount of memory (in MB) this VM needs?" autocomplete="off">
      </div>
    </div>
		<div class="form-group">
      <label for="inputNics" class="col-lg-2 control-label">Number of NICs</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" name="inputNics" id="inputNics" value="<?php echo set_value('inputNics[]'); ?>" placeholder="What's the minimum number of network interfaces this this VM needs?" autocomplete="off">
      </div>
    </div>
	<div class="form-group">
      <label for="inputData" class="col-lg-2 control-label">Minimum disk size</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" name="inputData" id="inputData" value="<?php echo set_value('inputData[]'); ?>" placeholder="What's the minimum disk size needed? (excluding the OS disk)" autocomplete="off">
      </div>
    </div>
	<div class="form-group">
      <label for="inputIops" class="col-lg-2 control-label">IOPS</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" name="inputIops" id="inputIops" value="<?php echo set_value('inputIops[]'); ?>" placeholder="What's the minimum IOPS, for the non-OS disk(s), this VM needs?" autocomplete="off">
      </div>
    </div>
	<div class="form-group">
      <label for="inputThroughput" class="col-lg-2 control-label">Throughput</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" name="inputThroughput" id="inputThroughput" value="<?php echo set_value('inputThroughput[]'); ?>" placeholder="What's the minimum throughput (in MB), for the non-OS disk(s), this VM needs?" autocomplete="off">
      </div>
    </div>
	<div class="form-group">
      <label for="inputTemp" class="col-lg-2 control-label">Minimum temp disk size</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" name="inputTemp" id="inputTemp" value="<?php echo set_value('inputTemp[]'); ?>" placeholder="What's the minimum size for the temp disk?" autocomplete="off">
      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
	    <button type="submit" class="btn btn-primary">Let's try to match a VM t-shirt size for you!</button>
        <button type="reset" class="btn btn-default">I messed up! Please clean this form for me...</button>
      </div>
    </div>
  </fieldset>
</form>

<?php 

$this->load->library('table');

print_r($this->table);

$this->table->set_heading('Name', 'Color', 'Size');

$this->table->add_row('Fred', 'Blue', 'Small');
$this->table->add_row('Mary', 'Red', 'Large');
$this->table->add_row('John', 'Green', 'Medium');

echo $this->table->generate();

foreach ($results as $result) {
	$data = array();
	$first = true;
	foreach($result as $key => $value) {
		$header[] = $key;
		$data[] = $value;
	}
	print_r($header);
	print_r($data);
	/*if ($first) {
		$this->table->set_heading($header);
	}*/
	$this->table->add_row($data);
	$first = false;
}
echo $this->table->generate();

?>

<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>

</body>
</html>