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
      <a class="navbar-brand" href="<?php echo base_url(); ?>">Azure VM Chooser</a>
    </div>
  </div>
</nav>

<?php echo validation_errors('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>

<?php

if (isset($results)) { 

	?>
	
	<div class="page-header">
	  <h1 id="navbar">Results</h1>
	</div>
	
	<?php

	$CI =& get_instance();
	$CI->load->library('table');

	$template = array(
			'table_open' => '<table class="table table-striped table-hover">'
	);
	$CI->table->set_template($template);

	$first = true;
	foreach ($results as $result) {
		$data = array();
		foreach($result as $key => $value) {
			$header[] = str_replace("'", "", $key);;
			$data[] = $value;
		}
		if ($first) {
			$CI->table->set_heading($header);
		}
		$CI->table->add_row($data);
		$first = false;
	}
	echo $CI->table->generate();

}

?>

<?php 
$attributes = array('class' => 'form-horizontal', 'id' => 'vmchooser');
echo form_open(base_url(), $attributes);
?>

<fieldset>
  <div class="form-group">
    <div class="col-lg-10 col-lg-offset-2">
	  <button type="submit" class="btn btn-primary">Let's try to match a VM t-shirt size for you!</button>
    </div>
  </div>
</fieldset>

<ul class="nav nav-tabs">
  <li class="active"><a href="#basic" data-toggle="tab" aria-expanded="true">Requirements</a></li>
  <li class=""><a href="#advanced" data-toggle="tab" aria-expanded="false">Advanced</a></li>
  <li class=""><a href="#rightsizing" data-toggle="tab" aria-expanded="false">Rightsizing</a></li>
  <li class=""><a href="#sap" data-toggle="tab" aria-expanded="false">SAP Specific</a></li>
</ul>
<div id="myTabContent" class="tab-content">
  <div class="tab-pane fade active in" id="basic">
  
	<fieldset>
		<legend>Basic requirements for your virtual machine</legend>
		<div class="form-group">
		  <label class="col-lg-2 control-label">Disk Type</label>
		  <div class="col-lg-10">
			<div class="radio">
			  <label>
				<input type="radio" name="ssd" id="optionsRadios1" value="No" <?php echo  set_radio('ssd', 'No', TRUE); ?>>
				Standard disks only
			  </label>
			</div>
			<div class="radio">
			  <label>
				<input type="radio" name="ssd" id="optionsRadios2" value="Yes" <?php echo  set_radio('ssd', 'Yes', TRUE); ?>>
				I'll be needing Premiums disks (SSD)
			  </label>
			</div>
			<div class="radio">
			  <label>
				<input type="radio" name="ssd" id="optionsRadios2" value="All" <?php echo  set_radio('ssd', 'All', TRUE); ?>>
				Doesn't matter... Just gimme all options available!
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
			<input type="text" class="form-control" name="inputMemory" id="inputMemory" value="<?php echo set_value('inputMemory[]'); ?>" placeholder="What's the minimum amount of memory (in GB) this VM needs?" autocomplete="off">
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
			<input type="text" class="form-control" name="inputData" id="inputData" value="<?php echo set_value('inputData[]'); ?>" placeholder="What's the minimum disk size (in TB) needed? (excluding the OS disk)" autocomplete="off">
		  </div>
		</div>
	 </fieldset>
    
  </div>
  <div class="tab-pane fade" id="advanced">
  
	<fieldset>
		<legend>Advanced requirements for your virtual machine</legend>
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
			<input type="text" class="form-control" name="inputTemp" id="inputTemp" value="<?php echo set_value('inputTemp[]'); ?>" placeholder="What's the minimum size (in GB) for the temp disk?" autocomplete="off">
		  </div>
		</div>
	 </fieldset>
    
  </div>
  <div class="tab-pane fade" id="rightsizing">

	<fieldset>
		<legend>Rightsizing your virtual machine</legend>
		<div class="form-group">
		  <label for="inputAvgcpupeak" class="col-lg-2 control-label">Peak CPU Usage</label>
		  <div class="col-lg-10">
			<input type="text" class="form-control" name="inputAvgcpupeak" id="inputAvgcpupeak" value="<?php echo set_value('inputAvgcpupeak[]'); ?>" placeholder="What's the peak CPU usage (in %) when checking the metrics of your VM on a 95pct curve?" autocomplete="off">
		  </div>
		</div>
		<div class="form-group">
		  <label for="inputAvgmempeak" class="col-lg-2 control-label">Peak Memory Usage</label>
		  <div class="col-lg-10">
			<input type="text" class="form-control" name="inputAvgmempeak" id="inputAvgmempeak" value="<?php echo set_value('inputAvgmempeak[]'); ?>" placeholder="What's the peak Memory usage (in %) when checking the metrics of your VM on a 95pct curve?" autocomplete="off">
		  </div>
		</div>
	 </fieldset>
  
  </div>
  <div class="tab-pane fade" id="sap">

	<fieldset>
		<legend>SAP Parameters</legend>
		<div class="form-group">
		  <label for="inputSaps2tier" class="col-lg-2 control-label">SAPS 2-Tier</label>
		  <div class="col-lg-10">
			<input type="text" class="form-control" name="inputSaps2tier" id="inputSaps2tier" value="<?php echo set_value('inputSaps2tier[]'); ?>" placeholder="What's the minimum SAPS 2-tier benchmark value you need?" autocomplete="off">
		  </div>
		</div>
		<div class="form-group">
		  <label for="inputSaps3tier" class="col-lg-2 control-label">SAPS 3-Tier</label>
		  <div class="col-lg-10">
			<input type="text" class="form-control" name="inputSaps3tier" id="inputSaps3tier" value="<?php echo set_value('inputSaps3tier[]'); ?>" placeholder="What's the minimum SAPS 3-tier benchmark value you need?" autocomplete="off">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-lg-2 control-label">HANA Support</label>
		  <div class="col-lg-10">
			<div class="radio">
			  <label>
				<input type="radio" name="hana" id="optionsRadios2" value="Yes" <?php echo  set_radio('hana', 'Yes', TRUE); ?>>
				Show me only the HANA supported devices!
			  </label>
			</div>
			<div class="radio">
			  <label>
				<input type="radio" name="hana" id="optionsRadios2" value="All" <?php echo  set_radio('hana', 'All', TRUE); ?>>
				Doesn't matter... Just gimme all options available!
			  </label>
			</div>
		  </div>
		</div>
	 </fieldset>
  
  </div>
</div>




</form>

<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>

<script type="text/javascript">
  var appInsights=window.appInsights||function(config){
    function i(config){t[config]=function(){var i=arguments;t.queue.push(function(){t[config].apply(t,i)})}}var t={config:config},u=document,e=window,o="script",s="AuthenticatedUserContext",h="start",c="stop",l="Track",a=l+"Event",v=l+"Page",y=u.createElement(o),r,f;y.src=config.url||"https://az416426.vo.msecnd.net/scripts/a/ai.0.js";u.getElementsByTagName(o)[0].parentNode.appendChild(y);try{t.cookie=u.cookie}catch(p){}for(t.queue=[],t.version="1.0",r=["Event","Exception","Metric","PageView","Trace","Dependency"];r.length;)i("track"+r.pop());return i("set"+s),i("clear"+s),i(h+a),i(c+a),i(h+v),i(c+v),i("flush"),config.disableExceptionTracking||(r="onerror",i("_"+r),f=e[r],e[r]=function(config,i,u,e,o){var s=f&&f(config,i,u,e,o);return s!==!0&&t["_"+r](config,i,u,e,o),s}),t
    }({
        instrumentationKey:"<?PHP echo getenv('APPINSIGHTSKEY'); ?>"
    });
       
    window.appInsights=appInsights;
    appInsights.trackPageView();
</script>

</body>
</html>
