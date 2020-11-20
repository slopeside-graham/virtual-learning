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

            wp_mail($sendtoemail, 'GHES Virtual Learning Payment Succesful', $emailbody, array('Content-Type: text/html; charset=UTF-8'));
            return true;
        }

        public static function SendRefundEmail($payment)
        {
            /*
            $subscriptionlist = '';
            //Gather All subscription ID's
            $subscriptionPayments = SubscriptionPayment::GetAllByPaymentId($payment->id);
            $subscriptionIds = array();
            foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
                $subscriptionIds[] = $subscriptionPayment['Subscription_id'];
            }
            $uniquesubscriptionIds = array_unique($subscriptionIds);

            //Build a grid of Subscriptions Information
            $subscriptionlist = Email::BuildSubscriptionList($uniquesubscriptionIds);

            $parent = Parents::GetByUserID(get_current_user_id());
           // $billinginfo = Email::populatefromRow($parent);

            $filepath = plugin_dir_path(__FILE__) . '../emails/successfulCharge.html';
            $emailbody = file_get_contents($filepath);

            //$billinginfo->ProcessReplacements($emailbody, $billinginfo);
            //Email::ProcessReplacements($emailbody, $subscriptionlist);

            //$sendtoemail = $billinginfo->Email;

           // wp_mail($sendtoemail, 'Your Virtual Learning Subscription has been cancelled.', $emailbody, array('Content-Type: text/html; charset=UTF-8'));
           */

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
