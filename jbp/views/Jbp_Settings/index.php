<?php $settings = $this->getModel()->getJbpSettings();  ?>
<?php $settings = $settings[0];  ?>
<?php 
    $status = isset($_GET['status']) ? $_GET['status'] : null;
?>
<div class="jbp-wrap">
    <h1 class="jbp-block-title"><svg class="icon-title"><use xlink:href="#settings"></use></svg>General Settings</h1>
    <a class="back-to-dashboard" href="?page=jbp_dashboard">&larr; Back to Dashboard</a>
    <?php if (!is_null($status)): ?>
        <br>
        <?php $containerClasses = 'notice is-dismissible' ?>
        <?php $containerClasses .= $status == 'success' ? ' notice-success' : ' notice-error'; ?>
        <?php $msg = $status == 'success' ? 'Settings Saved' : 'Error Saving Settings'; ?>
        <div id="message" class="<?php echo $containerClasses; ?>"><p><?php echo $msg; ?></p></div>
    <?php endif; ?>
    <div class="jbp-admin-box">
        <form id="jbp-dealer-form" action="?page=jbp_settings&action=saveJbpSettings" method="post">
            <div class="jbp-importer-inner-wrapper" style="text-align: left;">
                <h2>Dealers</h2>
                <div class="acf-field acf-field-text">
                    <div class="acf-label">
                        <label for="geo-map-api">Mapping/Geocoding API Key</label>
                    </div>
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="geo-map-api" type="text" name="geo-map-api" value="<?php echo $settings->map_geo_api; ?>">
                        </div>
                    </div>
                </div>
                <br />  
                <div>
                    <input type="submit" value="Save" class="button jbp-button button-hero" />
                </div>
            </div>
        </form>
    </div>
</div>