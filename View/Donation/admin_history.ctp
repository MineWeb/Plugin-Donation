<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('DONATION__PAYMENT_HISTORY') ?></h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered dataTable" style="table-layout: fixed;word-wrap: break-word;">
                        <thead>
                            <tr>
                                <th><?= $Lang->get('DONATION__HISTORY_PAYMENT_ID') ?></th>
                                <th><?= $Lang->get('DONATION__HISTORY_USER_PSEUDO') ?></th>
                                <th><?= $Lang->get('DONATION__HISTORY_USER_EMAIL') ?></th>
                                <th><?= $Lang->get('DONATION__HISTORY_PAYMENT_AMOUNT') ?></th>
                                <th><?= $Lang->get('DONATION__HISTORY_CREATED') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($donation_history as $history){ ?>
                                <tr>
                                    <td><?= $history['DonationHistory']['payment_id'] ?></td>
                                    <td><?= $history['DonationHistory']['user_pseudo'] ?></td>
                                    <td><?= $history['DonationHistory']['email'] ?></td>
                                    <td><?= $history['DonationHistory']['payment_amount'] ?>€</td>
                                    <td><?= $history['DonationHistory']['created'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>