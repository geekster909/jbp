<?php $editor = $this->getEditor();  ?>
<?php $settings = Jbp_settings::getJbpSettings();  ?>
<?php $settings = $settings[0];  ?>
<?php //echo '<pre>'; print_r($editor); echo '</pre>';die('here'); ?>
<?php $fields = [
    ['label' => 'Name', 'field' => 'name'],
    ['label' => 'Address 1', 'field' => 'address_1'],
    ['label' => 'Address 2', 'field' => 'address_2'],
    ['label' => 'City', 'field' => 'city'],
    ['label' => 'State', 'field' => 'state'],
    ['label' => 'Zip', 'field' => 'zip'],
    ['label' => 'Country', 'field' => 'country'],
    ['label' => 'Website', 'field' => 'website'],
    ['label' => 'Phone', 'field' => 'phone'],
    ['label' => 'Latitude', 'field' => 'latitude'],
    ['label' => 'Longitude', 'field' => 'longitude']
]; ?>
<div class="jbp-wrap">
    <h1 class="jbp-block-title"><svg class="icon-title"><use xlink:href="#manage"></use></svg><?php echo $editor->title; ?></h1>
    <a class="back-to-dashboard" href="?page=jbp_dealer" style="display: inline-block;">&larr; Back to Manage Dealers</a>
    <form id="jbp-delete-dealer-form" action="?page=jbp_dealer&action=deleteDealer" method="post">
		<input id="dealer_id" type="text" name="dealer_id" value="<?php echo $editor->recordId; ?>" style="display: none!important;">
		<input type="submit" value="Delete Dealer" class="button jbp-button" />
    </form>
    <?php if ($settings->map_geo_api) : ?>
    	<input type="submit" value="Get Coordinates" class="button jbp-button" id="get-coordinates" style="display: inline-block;vertical-align:inherit;margin-left:15px;" onclick="getCoordinates()">
    <?php endif; ?>
    <div class="jbp-editor-wrapper">
		<form id="jbp-dealer-form" action="?page=jbp_dealer&action=updateDealer" method="post">
			<input id="dealer_id" type="text" name="dealer_id" value="<?php echo $editor->recordId; ?>" style="display: none!important;">
			<?php foreach ($fields as $field): ?>
			    <div class="acf-field acf-field-text">
			        <div class="acf-label">
			            <label for="<?php echo $field['field']; ?>"><?php echo $field['label']; ?></label>
			        </div>
			        <div class="acf-input">
			            <div class="acf-input-wrap">
			                <input id="<?php echo $field['field']; ?>" type="text" name="<?php echo $field['field']; ?>" value="<?php echo $editor->dealer->$field['field']; ?>">
			            </div>
			        </div>
			    </div>
			<?php endforeach; ?>	
			<br />	
			<div>
				<input type="submit" value="Submit" class="button jbp-button" />
			</div>
		</form>
    </div>
</div>
<script type="text/javascript">

	var apiKey = "<?php echo $settings->map_geo_api; ?>";

	jQuery( "#jbp-delete-dealer-form" ).submit(function( event ) {
		var answer = confirm('Are you sure you want to delete this?');
		if (!answer) {
		  event.preventDefault(); 
		}
		
	});

    function getCoordinates() {

    	var dealer = {
			address_1:document.getElementById('address_1').value.split(' ').join('+'),
			city:document.getElementById('city').value.split(' ').join('+'),
			state:document.getElementById('state').value.split(' ').join('+'),
			zip:document.getElementById('zip').value.split(' ').join('+')
    	}

        fetch("https://maps.googleapis.com/maps/api/geocode/json?address="+dealer['address_1']+",+"+dealer['city']+",+"+dealer['state']+"&key="+apiKey, {
            method: "GET",
        }).then(
            res => res.json()
        )
        .catch(
            error => console.error('Error:', error)
        )
        .then(
            response => {
                console.log('Success:', response);
                if (response['results'].length > 0) {

	                let location = response['results'][0]['geometry']['location'];

	                location['lat'] = Math.round(location['lat']*1000000)/1000000
	                location['lng'] = Math.round(location['lng']*1000000)/1000000
	                
	                if (location) {
	                	document.getElementById('latitude').value = location['lat'];
	                	document.getElementById('longitude').value = location['lng'];
	                }
	            }

            }
        );
    }
</script>