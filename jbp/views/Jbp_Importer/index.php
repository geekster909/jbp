<div class="jbp-wrap">
	<?php if (false): ?>
		<div id="message" class="notice notice-error is-dismissible" style="margin-top: 20px; margin-bottom: 20px;"><p>Alert - <?php echo urldecode($this->error_message); ?>. Please correct error and try again.</p></div>
	<?php endif; ?>
    <h1 class="jbp-block-title"><svg class="icon-title"><use xlink:href="#importer"></use></svg>Importer/Exporter</h1>
    <a class="back-to-dashboard" href="?page=jbp_dashboard">&larr; Back to Dashboard</a>
    <div class="jbp-admin-box">
        <div class="jbp-importer-outer-wrapper" id="js-option">
            <div class="jbp-importer-inner-wrapper">
                <h2>Select a module to import:</h2>
                <a href="#" class="js-jbp-importer-type button button-hero" data-type="1040">1040</a>
                <a href="#" class="js-jbp-importer-type button button-hero" data-type="Products">Products</a>
<!--                <a href="#" class="js-jbp-importer-type button button-hero" data-type="features">Features</a>-->
            </div>
        </div>
        <div class="hidden jbp-importer-outer-wrapper" id="js-import">
            <form id="js-csv-import" method="post" action="<?php echo admin_url('admin.php?page=jbp_importer&action=import'); ?>" enctype="multipart/form-data">
                <div class="jbp-importer-inner-wrapper">
                    <h2 style="display:inline-block;">Importer</h2><small class="js-type" style="display:inline-block;"></small>
                    <br>
                    <?php wp_nonce_field('ajax_file_nonce', 'security'); ?>
                    <input id="js-csv-import-file-input" type="file" name="file" class="hidden">
                    <input id="js-csv-import-file-type" type="hidden" name="type">
                    <a id="js-csv-import-file-trigger" class="button button-hero">Select CSV File</a>
                    <span id="js-csv-import-file-name"></span>
                    <small>Maximum file size: <strong><?php echo ini_get('upload_max_filesize'); ?></strong></small>
                    <div style="padding-top: 10px">
                        <button class="button button-hero">Import</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="hidden jbp-importer-outer-wrapper" id="js-export">

            <div class="jbp-importer-inner-wrapper">
                <h2 style="display:inline-block;">Exporter</h2><small class="js-type" style="display:inline-block;"></small>
                <br>
                <a href="admin.php?page=jbp_importer&action=import&type=all" class="js-export-all button button-hero" data-type="">Export All</a>
                <a href="admin.php?page=jbp_importer&action=import&type=template" class="js-export-template button button-hero" data-type="">Export CSV Template</a>
            </div>
        </div>
    </div>
</div>