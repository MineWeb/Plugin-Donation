<?php

class DonationController extends DonationAppController {
    public function index() {
        $this->set('title_for_layout', $this->Lang->get('DONATION__TITLE'));
        
        $this->loadModel('Donation.DonationConfig');
        $donation_configs = $this->DonationConfig->find('first')['DonationConfig'];
        
        $this->loadModel('Donation.DonationHistory');
        $donation_history = $this->DonationHistory->find('all', ['limit' => 5, 'order' => 'id DESC']);
        

        
        $this->set(compact('donation_configs', 'donation_history')); 
    }

    public function canceled() {
        $this->set('title_for_layout', $this->Lang->get('DONATION__TITLE_CANCELED'));
        $this->loadModel('Donation.DonationConfig');
        $donation_configs = $this->DonationConfig->find('first')['DonationConfig'];
        $this->set(compact('donation_configs'));
    }

    public function return() {
        $this->set('title_for_layout', $this->Lang->get('DONATION__TITLE_RETURN'));
        $this->loadModel('Donation.DonationConfig');
        $donation_configs = $this->DonationConfig->find('first')['DonationConfig'];
        $this->set(compact('donation_configs'));
    }

    public function admin_config() {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->set('title_for_layout', $this->Lang->get('DONATION__TITLE'));
            $this->layout = 'admin';
            $this->loadModel('Donation.DonationConfig');
            $donation_configs = $this->DonationConfig->find('first')['DonationConfig'];

            if ($this->request->is('ajax')) {
                $this->response->type('json');
                $this->autoRender = null;
                
                if (empty($this->request->data['objectif']))
                    return $this->sendJSON(['statut' => false, 'msg' => $this->Lang->get('DONATION__OBJECTIF_NULL')]);
                if ($this->request->data['objectif'] <= 0)
                    return $this->sendJSON(['statut' => false, 'msg' => $this->Lang->get('DONATION_OBJECTIF_ZERO')]); 
                if (empty($this->request->data['emailDon']))
                    return $this->sendJSON(['statut' => false, 'msg' => $this->Lang->get('DONATION__EMAIL_NULL')]);
                
                $this->DonationConfig->edit(
                    $this->request->data['objectif'],
                    $this->request->data['emailDon'],
                    $this->request->data['descriptionDon'],
                    $this->request->data['color']
                );
                $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('DONATION__CONFIG_SUCCESS'))));
                
            } else {
                $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('ERROR__BAD_REQUEST'))));
            }

            $this->set(compact('donation_configs'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_history() {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->layout = 'admin';
            $this->set('title_for_layout', $this->Lang->get('DONATION__PAYMENT_HISTORY'));
            $this->loadModel('Donation.DonationHistory');
            $donation_history = $this->DonationHistory->find('all');
            $this->set(compact('donation_history'));
        } else {
            $this->redirect('/');
        }
    }

    // Inspiré du plugin Shop
    public function ipn() { // cf. https://developer.paypal.com/docs/classic/ipn/gs_IPN/
        $this->autoRender = false;
        if ($this->request->is('post')) { //On vérifie l'état de la requête

            // On assigne les variables
            $payment_status = strtoupper($this->request->data['payment_status']);
            $payment_amount = $this->request->data['mc_gross'];
            // Devise
            $payment_currency = $this->request->data['mc_currency'];
            //Payment_id
            $txn_id = $this->request->data['txn_id'];
            $payer_email = $this->request->data['payer_email'];
            $user_id = $this->request->data['custom'];

            // On vérifie que l'utilisateur contenu dans le champ custom existe bien
            $this->loadModel('User');
            if (!$this->User->exist($user_id)) {
                throw new InternalErrorException('PayPal : Unknown user');
            }

            // On prépare la requête de vérification
            $IPN = 'cmd=_notify-validate';
            foreach ($this->request->data as $key => $value) {
                $value = urlencode($value);
                $IPN .= "&$key=$value";
            }

            // On fais la requête
            $cURL = curl_init();
            curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($cURL, CURLOPT_URL, "https://www.paypal.com/cgi-bin/webscr");
            curl_setopt($cURL, CURLOPT_ENCODING, 'gzip');
            curl_setopt($cURL, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($cURL, CURLOPT_POST, true); // POST back
            curl_setopt($cURL, CURLOPT_POSTFIELDS, $IPN); // the $IPN
            curl_setopt($cURL, CURLOPT_HEADER, false);
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($cURL, CURLOPT_FORBID_REUSE, true);
            curl_setopt($cURL, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($cURL, CURLOPT_TIMEOUT, 60);
            curl_setopt($cURL, CURLINFO_HEADER_OUT, true);
            curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
                'Connection: close',
                'Expect: ',
            ));
            $Response = curl_exec($cURL);
            $Status = (int)curl_getinfo($cURL, CURLINFO_HTTP_CODE);
            curl_close($cURL);

            // On traite la réponse :

            // On vérifie que il y ai pas eu d'erreur
            if (empty($Response) || $Status != 200 || !$Status) {
                throw new InternalErrorException('PayPal : Error with PayPal Response');
            }

            // On vérifie que la paiement est vérifié
            if (!preg_match('~^(VERIFIED)$~i', trim($Response))) {
                throw new InternalErrorException('PayPal : Paiement not verified');
            }

            // On effectue les autres vérifications
            if ($payment_status == "COMPLETED") { //Le paiment est complété

                if ($payment_currency == "EUR") { //Le paiement est bien en euros

                    // On vérifie que le paiement n'est pas déjà en base de données
                    $this->loadModel('Donation.DonationHistory');
                    $this->loadModel('Donation.DonationConfig');
                    $findPayment = $this->DonationHistory->find('first', array('conditions' => array('payment_id' => $txn_id)));

                    if (empty($findPayment)) {

                        // On l'ajoute dans l'historique des paiements
                        $this->DonationHistory->add (
                            $txn_id,
                            $this->User->getUsernameByID($user_id),
                            $payer_email,
                            $payment_amount
                        );

                        $current = $this->DonationConfig->find('first')['DonationConfig']['total'];
                        $new_total = $current + $payment_amount;
                        
                        // On update le total des paiements
                        $this->DonationConfig->updateTotal($new_total);

                        //Envoie de notification
                        $this->loadModel('Notification');
                        $this->Notification->setToUser($this->Lang->get('DONATION__THANK'), $this->User->getKey('pseudo'));

                    } else
                        throw new InternalErrorException('PayPal : Payment already credited');
                    return $this->response->statusCode(200);

                } else {
                    throw new InternalErrorException('PayPal : Bad currency');
                }

            } else {
                throw new InternalErrorException('PayPal : Paiement not completed');
            }

        } else {
            throw new InternalErrorException('PayPal : Not post');
        }
    }
}