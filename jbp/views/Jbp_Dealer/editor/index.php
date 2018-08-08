<?php $editor = $this->getEditor();  ?>
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
	jQuery( "#jbp-delete-dealer-form" ).submit(function( event ) {
		var answer = confirm('Are you sure you want to delete this?');
		if (!answer) {
		  event.preventDefault(); 
		}
		
	});
</script>