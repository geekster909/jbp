<?php $settings = Jbp_settings::getJbpSettings();  ?>
<?php $settings = $settings[0];  ?>
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
	<h1 class="jbp-block-title"><svg class="icon-title"><use xlink:href="#manage"></use></svg>Add Dealer</h1>
	<a class="back-to-dashboard" href="?page=jbp_dealer">&larr; Back to Manage Dealers</a>
	<input type="submit" value="Get Coordinates" class="button jbp-button" id="get-coordinates" style="display: inline-block;vertical-align:inherit;margin-left:15px;" onclick="getCoordinates()" disabled="true">
	<div class="jbp-editor-wrapper">
		<form id="jbp-dealer-form" action="?page=jbp_dealer&action=addDealer" method="post">
			<?php foreach ($fields as $field): ?>
				<div class="acf-field acf-field-text">
					<div class="acf-label">
						<label for="<?php echo $field['field']; ?>"><?php echo $field['label']; ?></label>
					</div>
					<div class="acf-input">
						<div class="acf-input-wrap">
							<input id="<?php echo $field['field']; ?>" type="text" name="<?php echo $field['field']; ?>" value="">
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
	var dealer = {};

	jQuery('#jbp-dealer-form').on("change", ":input", function() {
		dealer = {
			address_1:document.getElementById('address_1').value.split(' ').join('+'),
			city:document.getElementById('city').value.split(' ').join('+'),
			state:document.getElementById('state').value.split(' ').join('+'),
			zip:document.getElementById('zip').value.split(' ').join('+')
		}

		if (dealer['address_1'] && dealer['city'] && dealer['state'] && dealer['zip']) {
			document.getElementById("get-coordinates").disabled = false;
		} else {
			document.getElementById("get-coordinates").disabled = true;
		}
	});

	function getCoordinates() {

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