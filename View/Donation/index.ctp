<?= $this->Html->css('Donation.donation-style.css')?>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<?php
    $objectif = $donation_configs['objectif'];
    $total = $donation_configs['total'];
    $current = (round($total / $objectif * 100, 1));
?>

<style type="text/css">
    .w3-xlarge .w3-center{
        width:<?= ($total <= $objectif) ? $current : 100 ?>%;
        <?php if($total >= $objectif) echo 'border-radius: 5px;'; ?>
    }
    .donation-color {
        text-decoration-color: <?= (empty($donation_configs['color'])) ? '#3377ff' : $donation_configs['color'] ?>;
        border-color: <?= (empty($donation_configs['color'])) ? '#3377ff' : $donation_configs['color'] ?>;
    }
</style>

<div class="container-donation white">
    <div class="row">
        <div class="col-md-8">
            <h1 class="donation-title donation-color">Faire un don</h1>
            <div class="index-description">
                <p><?= (!empty($donation_configs['description'])) ? nl2br($donation_configs['description']) : '' ?></p>
            </div>
            <?php if(!empty($donation_configs['email']) || !empty($donation_configs['id']) || !empty($total) || !empty($objectif)) { ?>
                <blockquote class="bq-donation donation-color">
                    <div class="bq-index">
                        <?= $Lang->get('DONATION__INDEX_OBJECTIF') ?><span class="bold"><?= $objectif ?>€</span><br>
                        <?= $Lang->get('DONATION__INDEX_TOTAL') ?><span class="bold"><?= $total ?>€</span><br>
                        <?= $Lang->get('DONATION__INDEX_POURCENTAGE') ?><span class="bold"><?= $current ?>%</span><br>
                    </div>
                </blockquote>
                <div class="w3-light-grey w3-xlarge">
                    <div class="w3-container
                        <?php        if($current == 0)  { ?>w3-light-grey 
                        <?php } else if($current <= 20) { ?>w3-red 
                        <?php } else if($current <= 40) { ?>w3-orange 
                        <?php } else if($current <= 60) { ?>w3-yellow 
                        <?php } else if($current <= 80) { ?>w3-green 
                        <?php } else if($current < 100) { ?>w3-aqua 
                        <?php } else if($current >= 100) { ?>w3-blue 
                        <?php } ?>w3-center"><span class="bold"><?= $current ?>%</span>
                    </div>
                </div>
                <div class="donation-login">
                    <?php if($isConnected): ?>
                        <input type="number" id="input-amount-paypal" min="1" name="continuer-visible" step="1" class="form-control continue-box" placeholder="<?= $Lang->get('DONATION__VALIDE_EUR') ?>" onchange="numberController()" onkeydown="numberController()" onkeyup="numberController()">
                        <button type="button" id="button-paypal" class="fud-yes text-center btn" data-toggle="modal" data-target="#confirmation" disabled><?= $Lang->get('DONATION__MAKE') ?></button>
                    <?php else: ?>
                        <a type="button" data-toggle="modal" data-target="#login" class="fud-no"><?= $Lang->get('DONATION__MAKE_NO_CONNECTED') ?></a>
                    <?php endif; ?>
                </div>

                <!-- -- Modal de confimation du paiement de la donnation  -- -->
                <div class="modal fade" id="confirmation" tabindex="-1" role="dialog" aria-labelledby="Confirmation-of-donation" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="Confirmation-of-donation"><?= $Lang->get('DONATION__PAYMENT_CONFIRMATION') ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php $srv_name = $_SERVER['SERVER_NAME']; ?>
                            <div class="modal-body text-center">
                                <p><?= $Lang->get('DONATION__PAYMENT_BTN') ?></p>
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="form-horizontal">
                                    <input name="currency_code" type="hidden" value="EUR" />
                                    <input name="shipping" type="hidden" value="0.00" />
                                    <input name="tax" type="hidden" value="0.00" />
                                    <input name="return" type="hidden" value="https://<?= $srv_name ?>/donation/return" />
                                    <input name="cancel_return" type="hidden" value="https://<?= $srv_name ?>/donation/canceled" />
                                    <input name="notify_url" type="hidden" value="<?= $this->Html->url(array('controller' => 'donnation', 'action' => 'ipn'), true) ?>" />
                                    <input name="cmd" type="hidden" value="_xclick" />
                                    <input name="business" id="mail_paypal" type="hidden" value="<?= $donation_configs['email'] ?>" />
                                    <input name="item_name" type="hidden" id="output-value-item_name-paypal" />
                                    <input name="no_note" type="hidden" value="1" />
                                    <input name="lc" type="hidden" value="FR" />
                                    <input name="custom" type="hidden" value="<?= $user['id'] ?>" />
                                    <input name="bn" type="hidden" value="PP-BuyNowBF" />
                                    <input type="hidden" name="cbt" value="<?= $Lang->get('SHOP__PAYPAL_RETURN_MSG', array('{WEBSITE_NAME}' => $website_name)) ?>" />
                                    <input type="hidden" name="charset" value="UTF-8" />
                                    <input type="hidden" name="amount" id="output-value-amount-paypal" />
                                    <button type="submit" name="submit" value="paypal" class="btn btn-primary btn-block"><?= $Lang->get('DONATION__PAY') ?> <span id="output-amount-paypal"></span><?= $Lang->get('DONATION__PAY_WITH_PAYPAL') ?> <i class="fab fa-paypal" aria-hidden="true"></i></button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><?= $Lang->get('DONATION__CLOSE') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="donation-login d-flex">
                    <span class="alert alert-danger text-center w-100"><?= $Lang->get('DONATION__INVALID') ?></span>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-4 d-flex">
            <div class="last-dons donation-color">
                <h3 class="donation-title donation-color"><?= $Lang->get('DONATION__LAST_DONATIONS') ?></h3>
                <ul class="list-dons">
                    <?php foreach($donation_history as $history){ ?>
                        <li>
                            <p><img src="/API/get_head_skin/<?= $history['DonationHistory']['user_pseudo'] ?>/40" alt="Tête de <?= $history['DonationHistory']['user_pseudo'] ?>"> <?= $history['DonationHistory']['user_pseudo'] ?> (<span class="bold"><?= $history['DonationHistory']['payment_amount'] ?>€</span>)</p>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        
    </div>
</div>
<?php if($isConnected) { ?>
    <script>
        function numberController() {
            let get = document.getElementById('input-amount-paypal').value;
            document.getElementById('output-amount-paypal').innerHTML = get;
            document.getElementById('output-value-amount-paypal').value = get;
            document.getElementById("output-value-item_name-paypal").value = "Don de <?= $user['pseudo']?> (" + get + " €)";
            if (get >= 1) {
                document.getElementById("button-paypal").disabled = false;
            } else {
                document.getElementById("button-paypal").disabled = true;
            }
        }
    </script>
<?php } ?>