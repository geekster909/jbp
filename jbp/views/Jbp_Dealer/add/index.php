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