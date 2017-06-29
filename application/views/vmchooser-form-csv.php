<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ($message <> "") {
	
	echo $message;
	
} else {
	
	?>

	<?php echo validation_errors('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>

	<?php 
	echo form_open_multipart(base_url()."vmchooser/csv");
	?>

	<fieldset>
	  <div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
		  <input type="submit" value="Upload the csv with the list of VMs to check!" class="btn btn-primary"/>
		</div>
	  </div>
	</fieldset>
	
	<input type="file" name="csvfile" size="20" />

	</form>

	<?php echo $error;?>

<?php 
}
?>

