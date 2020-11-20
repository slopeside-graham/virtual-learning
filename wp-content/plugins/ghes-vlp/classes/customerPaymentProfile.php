<?php

namespace GHES\VLP {

    use net\authorize\api\contract\v1 as AnetAPI;
    use net\authorize\api\contract\v1\CustomerAddressType;
    use net\authorize\api\controller as AnetController;
    use GHES\Utils as VLPUtils;

    class customerPaymentProfile extends ghes_vlp_base implements \JsonSerializable
    {

        private $APILoginId;
        private $APIKey;
        private $ENVIRONMENT;
        private $refId;
        private $merchantAuthentication;
        public $responseText;

        private $_id;

        private $_CardNumber;
        private $_ExpirationDate;
        private $_CardCode;

        private $_AccountType;
        private $_EcheckType;
        private $_RoutingNumber;
        private $_AccountNumber;
        private $_NameOnAccount;
        private $_BankName;

        private $_FirstName;
        private $_LastName;
        private $_Address;
        private $_City;
        private $_State;
        private $_Zip;
        private $_Country;
        private $_PhoneNumber;

        private $_ancreditCard;
        private $_anbankAccount;

        private $_anBillTo;

        public function __construct()
        {
            $this->APILoginId = VLPUtils::getencryptedsetting('registration-apilogin');
            $this->APIKey = VLPUtils::getencryptedsetting('registration-apikey');
            $this->ENVIRONMENT = VLPUtils::getunencryptedsetting('registration-environment');

            $this->refId = 'ref' . time();
            $this->merchantAuthentication = $this->setMerchantAuthentication();
            $this->responseText = array("1" => "Approved", "2" => "Declined", "3" => "Error", "4" => "Held for Review");

            $this->authorizeNetPaymentProfile = new AnetAPI\CustomerPaymentProfileType();

            // Set credit card information for payment profile
            $this->_ancreditCard = new AnetAPI\CreditCardType();
            $this->_anbankAccount = new AnetAPI\BankAccountType();

            // Create the Bill To info for new payment type
            $this->_anBillTo = new AnetAPI\CustomerAddressType();
        }
        protected function id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_id;
            }
        }

        protected function CardNumber($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_ancreditCard->setCardNumber($value);
                $this->_CardNumber = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_CardNumber;
            }
        }

        protected function ExpirationDate($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_ancreditCard->setExpirationDate($value);
                $this->_ExpirationDate = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_ExpirationDate;
            }
        }

        protected function CardCode($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_ancreditCard->setCardCode($value);
                $this->_CardCode = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_CardCode;
            }
        }

        protected function AccountType($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anbankAccount->setAccountType($value);
                $this->_AccountType = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_AccountType;
            }
        }
        protected function EcheckType($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anbankAccount->setEcheckType($value);
                $this->_EcheckType = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_EcheckType;
            }
        }
        protected function RoutingNumber($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anbankAccount->setRoutingNumber($value);
                $this->_RoutingNumber = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_RoutingNumber;
            }
        }
        protected function AccountNumber($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anbankAccount->setAccountNumber($value);
                $this->_AccountNumber = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_AccountNumber;
            }
        }
        protected function NameOnAccount($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anbankAccount->setNameOnAccount($value);
                $this->_NameOnAccount = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_NameOnAccount;
            }
        }
        protected function BankName($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anbankAccount->setBankName($value);
                $this->_BankName = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_BankName;
            }
        }

        protected function FirstName($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anBillTo->setFirstName($value);
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
                $this->_anBillTo->setLastName($value);
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
                $this->_anBillTo->setAddress($value);
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
                $this->_anBillTo->setCity($value);
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
                $this->_anBillTo->setState($value);
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
                $this->_anBillTo->setZip($value);
                $this->_Zip = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Zip;
            }
        }

        protected function Country($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anBillTo->setCountry("USA");
                $this->_Country = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Country;
            }
        }

        protected function PhoneNumber($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anBillTo->setPhoneNumber($value);
                $this->_PhoneNumber = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_PhoneNumber;
            }
        }

        public function jsonSerialize()
        {
            // Look at what in in this and make it return that
            return [
                'id' => $this->id
            ];
        }

        public function getEnvironment()
        {
            // ENVIRONMENT is set when class is constructed / instantiated
            return $this->ENVIRONMENT;
        }

        public function setMerchantAuthentication()
        {
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName($this->APILoginId);
            $merchantAuthentication->setTransactionKey($this->APIKey);

            return $merchantAuthentication;
        }

        public function createCustomerPaymentProfile($existingcustomerprofileid)
        {
            $merchantAuthentication = $this->setMerchantAuthentication();

            // Set the transaction's refId
            $refId = 'ref' . time();

            // Create a Customer Profile Request
            //  1. (Optionally) create a Payment Profile
            //  2. (Optionally) create a Shipping Profile
            //  3. Create a Customer Profile (or specify an existing profile)
            //  4. Submit a CreateCustomerProfile Request
            //  5. Validate Profile ID returned

            // Set credit card information for payment profile
            $paymentMethod = new AnetAPI\PaymentType();

            if (!is_null($this->_ancreditCard)) {
                $paymentMethod->setCreditCard($this->_ancreditCard);
            } else if (!is_null($this->_anbankAccount)) {
                $paymentMethod->setBankAccount($this->_anbankAccount);
            } else {
                return new \Exception('Something is wrong with your payment method. Please double check and try again.');
            }

            // Create a new Customer Payment Profile object
            $paymentprofile = new AnetAPI\CustomerPaymentProfileType();
            $paymentprofile->setCustomerType('individual');
            $paymentprofile->setBillTo($this->_anBillTo);
            $paymentprofile->setPayment($paymentMethod);
            $paymentprofile->setDefaultPaymentProfile(true);

            $paymentprofiles[] = $paymentprofile;

            // Assemble the complete transaction request
            $paymentprofilerequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
            $paymentprofilerequest->setMerchantAuthentication($merchantAuthentication);

            // Add an existing profile id to the request
            $paymentprofilerequest->setCustomerProfileId($existingcustomerprofileid);
            $paymentprofilerequest->setPaymentProfile($paymentprofile);
            $paymentprofilerequest->setValidationMode("liveMode");

            // Create the controller and get the response
            $controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
            $response = $controller->executeWithApiResponse($this->getEnvironment());

            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                // Ignore error below - nothig to be done about it.
                echo "Create Customer Payment Profile SUCCESS: " . $response->getCustomerPaymentProfileId() . "\n";
            } else {
                echo "Create Customer Payment Profile: ERROR Invalid response\n";
                $errorMessages = $response->getMessages()->getMessage();
                echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
            }
            return true;
        }

        public static function updateProfile($Parent, $request)
        {
            $customerProfileId = $Parent->customerProfileId;
            $customerPaymentProfileId = $Parent->customerPaymentProfileId;

            $customerProfile = CustomerProfile::populatefromrow($request);
            $customerProfile->id = $customerProfileId;

            $customerPaymentProfile = customerPaymentProfile::populatefromrow($request);
            $customerPaymentProfile->id = $customerPaymentProfileId;
            $customerPaymentProfile->updateCustomerPaymentProfile($customerProfileId, $customerPaymentProfileId);
        }


        function updateCustomerPaymentProfile($customerProfileId, $customerPaymentProfileId)
        {
            $merchantAuthentication = $this->setMerchantAuthentication();

            // Set the transaction's refId
            $refId = 'ref' . time();

            $request = new AnetAPI\GetCustomerPaymentProfileRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setCustomerProfileId($customerProfileId);
            $request->setCustomerPaymentProfileId($customerPaymentProfileId);

            $controller = new AnetController\GetCustomerPaymentProfileController($request);
            $response = $controller->executeWithApiResponse($this->getEnvironment());
            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {

                $paymentMethod = new AnetAPI\PaymentType();

                if (!is_null($this->_ancreditCard->getcardCode()) && !is_null($this->_ancreditCard->getcardNumber()) && !is_null($this->_ancreditCard->getexpirationDate())) {
                    $paymentMethod->setCreditCard($this->_ancreditCard);
                } else if (!is_null($this->_anbankAccount->getaccountType() && !is_null($this->_anbankAccount->getroutingNumber()) && !is_null($this->_anbankAccount->getAccountNumber()) && !is_null($this->_anbankAccount->getNameOnAccount()) && !is_null($this->_anbankAccount->getEcheckType()) && !is_null($this->_anbankAccount->getBankName()))) {
                    $paymentMethod->setBankAccount($this->_anbankAccount);
                } else {
                    return new \Exception('Something is wrong with your payment method. Please double check and try again.');
                }

                $paymentprofile = new AnetAPI\CustomerPaymentProfileExType();
                $paymentprofile->setBillTo($this->_anBillTo);
                $paymentprofile->setCustomerPaymentProfileId($customerPaymentProfileId);
                $paymentprofile->setPayment($paymentMethod);

                // We're updating the billing address but everything has to be passed in an update
                // For card information you can pass exactly what comes back from an GetCustomerPaymentProfile
                // if you don't need to update that info

                // Update the Customer Payment Profile object


                // Submit a UpdatePaymentProfileRequest
                $request = new AnetAPI\UpdateCustomerPaymentProfileRequest();
                $request->setMerchantAuthentication($merchantAuthentication);
                $request->setCustomerProfileId($customerProfileId);
                $request->setPaymentProfile($paymentprofile);

                $controller = new AnetController\UpdateCustomerPaymentProfileController($request);
                $response = $controller->executeWithApiResponse($this->getEnvironment());
                if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                    return $response;
                } else if ($response != null) {
                    $errorMessages = $response->getMessages()->getMessage();
                    echo "Failed to Update Customer Payment Profile :  " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
                }
                return $response;
            } else {
                $errorMessages = $response->getMessages()->getMessage();
                echo "Failed to Get Customer Payment Profile :  " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
            }

            return $response;
        }

        /**
         * Get Customer Payment Profiles
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return mixed|Object
         */
        public function getanCustomerPaymentProfile()
        {
            // Set credit card information for payment profile
            $_anPaymentType = new AnetAPI\PaymentType();
            $_anPaymentType->setCreditCard($this->_ancreditCard);

            // Create a new CustomerPaymentProfile object
            $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
            $paymentProfile->setCustomerType('individual');
            $paymentProfile->setBillTo($this->_anBillTo);
            $paymentProfile->setPayment($_anPaymentType);
            $paymentProfiles[] = $paymentProfile;

            return $paymentProfiles;
        }

        /**
         * Get Customer Payment Profiles
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return mixed|Object
         */
        public static function GetAll()
        {
            $parent = \GHES\Parents::GetByUserID(get_current_user_id());

            $customerProfileId = $parent->customerProfileId;
            $customerProfile = customerProfile::Get($customerProfileId);

            $paymentProfiles = new NestedSerializable();
            $results = $customerProfile->getProfile()->getPaymentProfiles();

            foreach ($results as $row) {
                $paymentProfile = customerPaymentProfile::populatefromANobject($row);
                $paymentProfiles->add_item($paymentProfile);  // Add the paymentprofile to the collection
            }

            return $paymentProfiles;
        }

        public static function populatefromANobject($row): ?customerPaymentProfile
        {
            if ($row == null)
                return null;

            $customerPaymentProfile = new customerPaymentProfile();
            $customerPaymentProfile->id = $row->getanCustomerPaymentProfileId();
            $customerPaymentProfile->CardNumber = $row->getPayment()->getCreditCard();
            $customerPaymentProfile->ExpirationDate = $row->getPayment()->getCreditCard();
            $customerPaymentProfile->CardCode = $row->getPayment()->getCreditCard();
            $customerPaymentProfile->AccountType = $row->getPayment()->getBankAccount()->getAccountType();
            $customerPaymentProfile->EcheckType = $row->getPayment()->getBankAccount()->getECheckType();
            $customerPaymentProfile->RoutingNumber = $row->getPayment()->getBankAccount()->getRoutingNumber();
            $customerPaymentProfile->AccountNumber = $row->getPayment()->getBankAccount()->getAccountNumber();
            $customerPaymentProfile->NameOnAccount = $row->getPayment()->getBankAccount()->getNameOnAccount();
            $customerPaymentProfile->BankName = $row->getPayment()->getBankAccount()->getBankName();
            $customerPaymentProfile->FirstName = $row->getBillto()->getFirstName();
            $customerPaymentProfile->LastName = $row->getBillto()->getLastName();
            $customerPaymentProfile->Address = $row->getBillto()->getAddress();
            $customerPaymentProfile->City = $row->getBillto()->getCity();
            $customerPaymentProfile->State = $row->getBillto()->getState();
            $customerPaymentProfile->Zip = $row->getBillto()->getZip();
            $customerPaymentProfile->Country = 'USA';
            $customerPaymentProfile->PhoneNumber = $row->getBillto()->getPhoneNumber();
            return $customerPaymentProfile;
        }

        public static function populatefromRow($row): ?customerPaymentProfile
        {
            if ($row == null)
                return null;

            $customerPaymentProfile = new customerPaymentProfile();
            $customerPaymentProfile->id = $row['id'];
            $customerPaymentProfile->CardNumber = $row['CardNumber'];
            $customerPaymentProfile->ExpirationDate = $row['ExpirationDate'];
            $customerPaymentProfile->CardCode = $row['CardCode'];
            $customerPaymentProfile->AccountType = $row['AccountType'];
            $customerPaymentProfile->EcheckType = $row['EcheckType'];
            $customerPaymentProfile->RoutingNumber = $row['RoutingNumber'];
            $customerPaymentProfile->AccountNumber = $row['AccountNumber'];
            $customerPaymentProfile->NameOnAccount = $row['NameOnAccount'];
            $customerPaymentProfile->BankName = $row['BankName'];
            $customerPaymentProfile->FirstName = $row['FirstName'];
            $customerPaymentProfile->LastName = $row['LastName'];
            $customerPaymentProfile->Address = $row['Address'];
            $customerPaymentProfile->City = $row['City'];
            $customerPaymentProfile->State = $row['State'];
            $customerPaymentProfile->Zip = $row['Zip'];
            $customerPaymentProfile->Country = $row['Country'];
            $customerPaymentProfile->PhoneNumber = $row['PhoneNumber'];
            return $customerPaymentProfile;
        }
    }
}
