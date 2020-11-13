<?php

namespace GHES\VLP {

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


        public static function SendChargeEmail($request, $payment)
        {
            $billinginfo = Email::populatefromRow($request);
            //$billinginfo = Email::populatefromRow($payment);

            $filepath = plugin_dir_path(__FILE__) . '../emails/successfulCharge.html';
            $emailbody = file_get_contents($filepath);

            $billinginfo->ProcessReplacements($emailbody, $billinginfo);

            $sendtoemail = $billinginfo->Email;

            wp_mail($sendtoemail, 'Thank you for your Virtual Learning Purchase', $emailbody, array('Content-Type: text/html; charset=UTF-8'));
        }
        public static function ProcessReplacements($emailbody, $billinginfo)
        {
            $emailbody = str_replace('~Amount~', $billinginfo->Amount, $emailbody);
            $emailbody = str_replace('~FirstName~', $billinginfo->FirstName, $emailbody);
            $emailbody = str_replace('~LastName~', $billinginfo->LastName, $emailbody);
            $emailbody = str_replace('~Address~', $billinginfo->Address, $emailbody);
            $emailbody = str_replace('~City~', $billinginfo->City, $emailbody);
            $emailbody = str_replace('~State~', $billinginfo->State, $emailbody);
            $emailbody = str_replace('~Zip~', $billinginfo->Zip, $emailbody);
            $emailbody = str_replace('~PhoneNumber~', $billinginfo->PhoneNumber, $emailbody);
            $emailbody = str_replace('~Email~', $billinginfo->Email, $emailbody);

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
