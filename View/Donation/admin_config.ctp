<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('DONATION__CONFIG') ?></h3>
                </div>
                <div class="card-body">
                    <form data-redirect-url="<?= $this->Html->url(array('controller' => 'donation', 'action' => 'config', 'admin' => true)) ?>" method="post" data-ajax="true">
                        <div class="ajax-msg"></div>
                        
                        <div class="form-group">
                            <label><?= $Lang->get('DONATION__ADMIN_SETTINGS') ?></label>
                            <input type="number" id="number-objectif" class="form-control" placeholder="100" value="<?= $donation_configs['objectif'] ?>" onchange="numberController('number-objectif', 'put-objectif')">
                            <input type="hidden" id="put-objectif" name="objectif" class="form-control" value="<?= $donation_configs['objectif'] ?>">
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('DONATION__ADMIN_SETTINGS_EMAIL') ?></label>
                            <input type="email" name="emailDon" class="form-control" placeholder="example@paypal.com" value="<?= $donation_configs['email'] ?>">
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('DONATION__ADMIN_SETTINGS_DESCRIPTION') ?></label>
                            <textarea id="editor input" class="form-control white white-input" name="descriptionDon" cols="30" rows="10" placeholder="<?= $Lang->get('DONATION__CONFIG_MAIN_DESCRIPTION') ?>"><?= (!empty($donation_configs['description'])) ? nl2br($donation_configs['description']) : '' ?></textarea>
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('DONATION__ADMIN_SETTINGS_COLOR') ?></label>
                            <input type="color" id="color-objectif" class="form-control" placeholder="#3377ff" value="<?= (empty($donation_configs['color'])) ? '#3377ff' : $donation_configs['color'] ?>" onchange="colorController('color-objectif', 'putcolor-objectif')">
                            <input type="hidden" id="putcolor-objectif" name="color" class="form-control" value="<?= $donation_configs['color'] ?>">
                        </div>

                        <button type="submit" class="btn btn-primary"><?= $Lang->get('DONATION__EDIT_SETTINGS') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function numberController(inputId, outputId) {
        document.getElementById(outputId).value = document.getElementById(inputId).value;
    }
    function colorController(inputColor, outputColor) {
        document.getElementById(outputColor).value = document.getElementById(inputColor).value;
    }
</script>
