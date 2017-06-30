<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ($message <> "") {
	
	?>
	
	
	<div class="alert alert-dismissible alert-info">
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	  <strong><?php echo $message; ?>
	</div>
	
	<?php
	
} else {
	
	?>

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="containers">Containers</h1>
            </div>
            <div class="bs-component">
              <div class="jumbotron">
                <h1>CSV Upload</h1>
                <p>

					<?php echo validation_errors('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>

					<?php 
					echo form_open_multipart(base_url()."vmchooser/csv");
					?>
				
						<fieldset>
						  <div class="form-group">
							<div class="col-lg-10 col-lg-offset-2">
							  <input type="submit" value="Upload the csv with the list of VMs to check!" class="btn btn-primary"/>
							  <input type="file" name="csvfile" size="20" />
							</div>
						  </div>
						</fieldset>
					
					</form>

					<?php echo $error;?>
				
				</p>
              </div>
            </div>
          </div>
        </div>
	


<?php 
}
?>

<div class="alert alert-dismissible alert-info">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Please note...</strong> Please use the following <a href="<?php echo base_url()."vmchooser.csv"; ?>" target="_blank" class="alert-link">CSV format</a> when uploading. The process to parse the data can take a few minutes due to the async manner. So please be patient... ;-)
</div>

