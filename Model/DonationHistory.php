<?php

class DonationHistory extends DonationAppModel
{
    public $useTable = "history";

    public function add($payment_id, $user_pseudo, $email, $payment_amount)
    {
        $this->create();
        $this->set(array(
            'payment_id' => $payment_id,
            'user_pseudo' => $user_pseudo,
            'email' => $email,
            'payment_amount,' => $payment_amount,
        ));
        $this->save();
    }
}