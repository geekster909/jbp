<?php
	$dealers = $this->getModel()->getDealers();
	$showMsg = isset($_GET['status']) ? $_GET['status'] : null;
	$performed = isset($_GET['performed']) ? $_GET['performed'] : null;
	// echo '<pre>'; print_r($results); echo '</pre>';die('here');
?>
<style type="text/css">
	table {
		width: 100%;
		border-collapse: collapse;
	}
	thead tr {
		height: 50px;
	}
	tbody>:nth-child(odd){
		background-color: #f9f9f9;
	}
	th, td {
		text-align: left;
		padding: 0 15px;
	}
	td {
		/*border-bottom: solid 1px #000;*/
		height: 30px;
	}
	tr:last-child td {
		border-bottom: none;
	}
</style>
<div class="jbp-wrap">
    <h1 class="jbp-block-title"><svg class="icon-title"><use xlink:href="#dealers"></use></svg>Manage Dealers</h1>
    <a class="back-to-dashboard" href="?page=jbp_dashboard" style="display: block">&larr; Back to Dashboard</a>
    <a class="back-to-dashboard" href="?page=jbp_dealer&action=add" style="display: block">Add Dealer</a>
    <?php if (!is_null($showMsg)): ?>
        <br>
    <?php $containerClasses = 'notice is-dismissible' ?>
    <?php $containerClasses .= $showMsg == 1 ? ' notice-success' : ' notice-error'; ?>
    <?php $msg = $showMsg == 1 ? 'Dealer '.$performed.' Successful' : 'Dealer '.$performed.' Failed'; ?>
        <div id="message" class="<?php echo $containerClasses; ?>"><p><?php echo $msg; ?></p></div>
        <script>
            (function($){
                var url_search = window.location.search.replace('?', '').split('&');
                var new_url = window.location.pathname + '?';

                $(url_search).each(function(key, value){
                    if (value.indexOf('status') < 0) {
                        new_url += value + '&';
                    }
                });

                window.history.pushState(null, null, new_url.slice(0, -1));
            })(jQuery)
        </script>
    <?php endif; ?>

	<?php if ($dealers) : ?>
		<table>
			<thead>
				<tr>
					<th>id</th>
					<th>Name</th>
					<th>Address 1</th>
					<th>State</th>
					<th>Zip</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($dealers as $dealer) : ?>
					<tr>
						<td><?php echo $dealer->id; ?></td>
						<td><?php echo $dealer->name; ?></td>
						<td><?php echo $dealer->address_1; ?></td>
						<td><?php echo $dealer->state; ?></td>
						<td><?php echo $dealer->zip; ?></td>
						<td><a href="?page=jbp_dealer&action=edit&recordId=<?php echo $dealer->id; ?>">Edit</a></td>
					</tr>
			    <?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		<p>There are currently no dealers</p>
	<?php endif; ?>
</div>