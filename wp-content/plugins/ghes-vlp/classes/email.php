<?php

namespace GHES\VLP {

    use GHES\Parents;
    use GHES\VLP\Utils as VLPUtils;

    /**
     * Class AgeGroup
     */
    class Email extends ghes_vlp_base implements \JsonSerializable
    {

        private $Amount;
        private $FirstName;
        private $LastName;
        private $Address;
        private $City;
        private $State;
        private $Zip;
        private $PhoneNumber;
        private $Email;

        protected function Amount($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Amount = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Amount;
            }
        }
        protected function FirstName($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_FirstName = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_FirstName;
            }
        }
        protected function LastName($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_LastName = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_LastName;
            }
        }
        protected function Address($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Address = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Address;
            }
        }
        protected function City($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_City = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_City;
            }
        }
        protected function State($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_State = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_State;
            }
        }
        protected function Zip($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Zip = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Zip;
            }
        }
        protected function PhoneNumber($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_PhoneNumber = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_PhoneNumber;
            }
        }
        protected function Email($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Email = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Email;
            }
        }

        public function jsonSerialize()
        {
            return [
                'Amount' => $this->Amount,
                'FirstName' => $this->FirstName,
                'LastName' => $this->LastName,
                'Address' => $this->Address,
                'City' => $this->City,
                'State' => $this->State,
                'Zip' => $this->Zip,
                'PhoneNumber' => $this->PhoneNumber,
                'Email' => $this->Email
            ];
        }


        public static function SendChargeEmail($payment)
        {
            $filepath = plugin_dir_path(__FILE__) . '../emails/successfulCharge.html';
            $emailbody = file_get_contents($filepath);

            $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);

            $output = '';
            $parent = Parents::GetByUserID(get_current_user_id());
            $sendtoemail = $parent->Email;
            $FirstName = $parent->FirstName;
            $paymentid = $payment->id;

            if ($payment->jsonSerialize()) {
                $PaymentAmount = $formatter->formatCurrency($payment->Amount, 'USD');
                $PaymentDate = date('m/d/Y', strtotime($payment->DateCreated)); // This is null
                $PaymentAccount = $payment->accountNumber;
                $PaymentAccountType = $payment->accountType;
            }

            $subscriptions = Subscription::GetAllByPaymentId($paymentid);
            $SubscriptionPaymentsList = '';
            if ($subscriptions->jsonSerialize()) {
                $SubscriptionPaymentsList .= '<ul>';
                foreach ($subscriptions->jsonSerialize() as $k => $subscription) {
                    $subscriptiondefenition = SubscriptionDefinition::Get($subscription->SubscriptionDefinition_id);
                    $subscriptionpayments = SubscriptionPayment::GetAllPaidBySubscriptionIdandPaymentId($subscription->id, $paymentid);
                    $SubscriptionPaymentsList .= '<li>Subscription Type: ' . $subscriptiondefenition->Name . ' - ' . $subscription->Status . '</li>';
                    $SubscriptionPaymentsList .= '<ul>';
                    if ($subscriptionpayments->jsonSerialize()) {
                        foreach ($subscriptionpayments->jsonSerialize() as $k => $subscriptionpayment) {
                            $SubscriptionPaymentsList .= '<li>Paid for: ' . date('m/d/Y', strtotime($subscriptionpayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionpayment->EndDate)) . ' - ' . $formatter->formatCurrency($subscriptionpayment->Amount, 'USD') .  '</li>';
                        }
                    }
                    $SubscriptionPaymentsList .= '</ul>';
                }
                $SubscriptionPaymentsList .= '</ul>';
            }

            $ManageSubscriptionLink = get_permalink(esc_attr(get_option('vlp-manage')));
            $LaunchGameboardLink = get_permalink(esc_attr(get_option('vlp-agetree'))) . '?destination=Gameboard';
            $MyProfileLink = get_permalink(esc_attr(get_option('registration_welcome_url')));

            $emailbody = str_replace('~FirstName~', $FirstName, $emailbody);
            $emailbody = str_replace('~PaymentAmount~', $PaymentAmount, $emailbody);
            $emailbody = str_replace('~PaymentDate~', $PaymentDate, $emailbody);
            $emailbody = str_replace('~PaymentAccountType~', $PaymentAccountType, $emailbody);
            $emailbody = str_replace('~PaymentAccount~', $PaymentAccount, $emailbody);
            $emailbody = str_replace('~SubscriptionPaymentsList~', $SubscriptionPaymentsList, $emailbody);
            $emailbody = str_replace('~ManageSubscriptionLink~', $ManageSubscriptionLink, $emailbody);
            $emailbody = str_replace('~LaunchGameboardLink~', $LaunchGameboardLink, $emailbody);
            $emailbody = str_replace('~MyProfileLink~', $MyProfileLink, $emailbody);
            $emailbody = str_replace('~taxid~', get_option('registration-taxid'), $emailbody);

            wp_mail($sendtoemail, 'GHES Virtual Learning Payment Succesful', $emailbody, array('Content-Type: text/html; charset=UTF-8'));
            return true;
        }

        public static function SendRefundEmail($payment, $subscriptionId)
        {
            $filepath = plugin_dir_path(__FILE__) . '../emails/successfulCancel.html';
            $emailbody = file_get_contents($filepath);

            $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);

            $parent = Parents::GetByUserID(get_current_user_id());
            $sendtoemail = $parent->Email;
            $FirstName = $parent->FirstName;
            $paymentid = $payment->id;

            $RefundAmount = 0;
            $payments = Payment::GetAllCancelledBySubscriptionId($subscriptionId);
            foreach ($payments->jsonSerialize() as $k => $payment) {
                $RefundAmount = +$payment->Amount;
            }


            $cancelledSubscription = Subscription::Get($subscriptionId);
            $subscriptionDefinition = SubscriptionDefinition::Get($cancelledSubscription->SubscriptionDefinition_id);
            $SubscriptionName = $subscriptionDefinition->Name;
            $SubscriptionStartDate = date('m/d/Y', strtotime($cancelledSubscription->StartDate));
            $SubscriptionEndDate = date('m/d/Y', strtotime($cancelledSubscription->EndDate));

            $payments = Payment::GetAllCancelledBySubscriptionId($subscriptionId);
            $SubscriptionRefundsList = '';
            $SubscriptionRefundsList .= '<ul>';
            foreach ($payments->jsonSerialize() as $k => $payment) {
                $SubscriptionRefundsList .= '<li>Payment Type: ' . $payment->Type . '</li>';
                $SubscriptionRefundsList .= '<ul>';
                $SubscriptionRefundsList .= '<li>' . $formatter->formatCurrency($payment->Amount, 'USD') . '</li>';
                $SubscriptionRefundsList .= '<li>' . $payment->accountType . ': ' . $payment->accountNumber . '</li>';
                $SubscriptionRefundsList .= '</ul>';
            }
            $SubscriptionRefundsList .= '</ul>';

            $SubscriptionPaymentsList = '';
            $subscriptionPayments = SubscriptionPayment::GetAllBySubscriptionId($subscriptionId);

            $SubscriptionPaymentsList .= '<ul>';
            foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
                $SubscriptionPaymentsList .= '<li>' . $subscriptionPayment->Status . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</li>';
            }
            $SubscriptionPaymentsList .= '</ul>';

            $emailbody = str_replace('~FirstName~', $FirstName, $emailbody);
            $emailbody = str_replace('~RefundAmount~', $formatter->formatCurrency($RefundAmount, 'USD'), $emailbody);
            $emailbody = str_replace('~SubscriptionName~', $SubscriptionName, $emailbody);
            $emailbody = str_replace('~SubscriptionStartDate~', $SubscriptionStartDate, $emailbody);
            $emailbody = str_replace('~SubscriptionEndDate~', $SubscriptionEndDate, $emailbody);
            $emailbody = str_replace('~SubscriptionRefundsList~', $SubscriptionRefundsList, $emailbody);
            $emailbody = str_replace('~SubscriptionPaymentsList~', $SubscriptionPaymentsList, $emailbody);
            $emailbody = str_replace('~taxid~', get_option('registration-taxid'), $emailbody);

            wp_mail($sendtoemail, 'GHES Virtual Learning Cancelled', $emailbody, array('Content-Type: text/html; charset=UTF-8'));
            return true;
        }

        public static function BuildSubscriptionList($uniquesubscriptionIds)
        {
            $subscriptionlist = '';
            $subscriptionlist .= '<ul>';
            foreach ($uniquesubscriptionIds as $subscriptionId) {
                $subscription = Subscription::Get($subscriptionId);
                $subscriptiondefenition = SubscriptionDefinition::Get($subscription->SubscriptionDefenition_id);
                $subscriptionlist .=  '<li>' . $subscriptiondefenition->Name . ' - ' . date('m/d/Y', strtotime($subscription->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscription->EndDate)) . '</li>';
                $subscriptionlist .= '<ul>';
                $subscriptionPayments = SubscriptionPayment::GetAllBySubscriptionId($subscriptionId);
                foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
                    $subscriptionlist .= '<li>' . $subscriptionPayment->Status . ' - $' . $subscriptionPayment->Amount . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->StartDate)) . ' - ' . date('m/d/Y', strtotime($subscriptionPayment->EndDate)) . '</li>';
                }
                $subscriptionlist .= '<ul>';
            }
            $subscriptionlist .= '</ul>';
            return $subscriptionlist;
        }

        public static function ProcessReplacements($emailbody, $replacements)
        {
            $emailbody = str_replace('~Amount~', $replacements->Amount, $emailbody);
            $emailbody = str_replace('~FirstName~', $replacements->FirstName, $emailbody);
            $emailbody = str_replace('~LastName~', $replacements->LastName, $emailbody);
            $emailbody = str_replace('~Address~', $replacements->Address, $emailbody);
            $emailbody = str_replace('~City~', $replacements->City, $emailbody);
            $emailbody = str_replace('~State~', $replacements->State, $emailbody);
            $emailbody = str_replace('~Zip~', $replacements->Zip, $emailbody);
            $emailbody = str_replace('~PhoneNumber~', $replacements->PhoneNumber, $emailbody);
            $emailbody = str_replace('~Email~', $replacements->Email, $emailbody);
            $emailbody = str_replace('~taxid~', get_option('registration-taxid'), $emailbody);

            return $emailbody;
        }

        public static function ProcessSubscriptionListReplacement($emailbody, $replacements)
        {
            $emailbody = str_replace('~SubscriptionList~', $replacements, $emailbody);

            return $emailbody;
        }

        // Helper function to populate a lesson from a MeekroDB Row
        public static function populatefromRow($row)
        {
            if ($row == null)
                return null;
            $billinginfo = new Email();
            $billinginfo->Amount = $row['Amount'];
            $billinginfo->FirstName = $row['FirstName'];
            $billinginfo->LastName = $row['LastName'];
            $billinginfo->Address = $row['Address'];
            $billinginfo->City = $row['City'];
            $billinginfo->State = $row['State'];
            $billinginfo->Zip = $row['Zip'];
            $billinginfo->PhoneNumber = $row['PhoneNumber'];
            $billinginfo->Email = $row['Email'];

            return $billinginfo;
        }
    }
}
