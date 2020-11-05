<?php

namespace GHES\VLP {

    use Exception;
    use net\authorize\api\contract\v1 as AnetAPI;
    use net\authorize\api\contract\v1\CustomerAddressType;
    use net\authorize\api\controller as AnetController;
    use GHES\Utils as VLPUtils;
    use GHES\VLP\ghes_vlp_base;
    use GHES\Parents;

    class customerProfile extends ghes_vlp_base implements \JsonSerializable
    {
        //const ENVIRONMENT = \net\authorize\api\constants\ANetEnvironment::SANDBOX;
        //const ENVIRONMENT = \net\authorize\api\constants\ANetEnvironment::PRODUCTION;

        private $APILoginId;
        private $APIKey;
        private $ENVIRONMENT;
        private $refId;
        private $merchantAuthentication;
        public $responseText;

        private $_id;
        private $_merchantCustomerId;
        private $_description;
        private $_Email;

        private $_anCustomerProfile;

        public function __construct()
        {

            $this->APILoginId = VLPUtils::getencryptedsetting('registration-apilogin');
            $this->APIKey = VLPUtils::getencryptedsetting('registration-apikey');
            $this->ENVIRONMENT = VLPUtils::getunencryptedsetting('registration-environment');

            $this->refId = 'ref' . time();
            $this->merchantAuthentication = $this->setMerchantAuthentication();
            $this->responseText = array("1" => "Approved", "2" => "Declined", "3" => "Error", "4" => "Held for Review");
            $this->_anCustomerProfile = new AnetAPI\CustomerProfileType();

            $this->description = "GHES VLL Parent";
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

        protected function merchantCustomerId($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anCustomerProfile->setMerchantCustomerId($value);
                $this->_merchantCustomerId = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_merchantCustomerId;
            }
        }

        protected function description($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anCustomerProfile->setDescription($value);
                $this->_description = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_description;
            }
        }

        protected function Email($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_anCustomerProfile->setEmail($value);
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
                'id' => $this->id,
                'merchantCustomerId' => $this->merchantCustomerId,
                'description' => $this->description,
                'Email' => $this->Email
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

        public function createCustomerProfile(&$paymentProfiles, $Parent_id)
        {
            $merchantAuthentication = $this->setMerchantAuthentication();

            // Set the transaction's refId
            $refId = 'ref' . time();

            // Create a Customer Profile Request
            //  3. Create a Customer Profile (or specify an existing profile)
            //  4. Submit a CreateCustomerProfile Request
            //  5. Validate Profile ID returned


            // Create a new CustomerProfileType and add the payment profile object
            $this->_anCustomerProfile->setpaymentProfiles($paymentProfiles);


            // Assemble the complete transaction request
            $request = new AnetAPI\CreateCustomerProfileRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setProfile($this->_anCustomerProfile);

            // Create the controller and get the response
            $controller = new AnetController\CreateCustomerProfileController($request);
            $response = $controller->executeWithApiResponse($this->getEnvironment());

            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                echo "Succesfully created customer profile : " . $response->getCustomerProfileId() . "\n";
                $paymentProfileid = $response->getCustomerPaymentProfileIdList();
                echo "SUCCESS: PAYMENT PROFILE ID : " . $paymentProfileid[0] . "\n";
                try {
                    $this->id = $response->getCustomerProfileId();
                    $parent = \GHES\Parents::Get($Parent_id);
                    $parent->customerProfileId = $this->id;
                    $parent->customerPaymentProfileId = $paymentProfileid[0];
                    $parent->Update();
                } catch (Exception $e) {
                    echo $e;
                }
                $this->id = $response->getCustomerProfileId();
                $paymentProfiles[0]->id = $paymentProfileid[0];

                return true;
            } else {
                $errorMessages = $response->getMessages()->getMessage();
                return new \WP_Error('AN_CreateCustomerProfile_Error', "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText());
            }
            return true;
        }
        /**
         * Charge Customer Profile
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return mixed|WP_Error|Object
         */
        public function chargeCustomerProfile($profileid, $paymentprofileid, $amount)
        {
            /* Create a merchantAuthenticationType object with authentication details */
            $merchantAuthentication = $this->setMerchantAuthentication();

            // Set the transaction's refId
            $refId = 'ref' . time();

            $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
            $profileToCharge->setCustomerProfileId($profileid);
            $paymentProfile = new AnetAPI\PaymentProfileType();
            $paymentProfile->setPaymentProfileId($paymentprofileid);
            $profileToCharge->setPaymentProfile($paymentProfile);

            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($amount);
            $transactionRequestType->setProfile($profileToCharge);

            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setTransactionRequest($transactionRequestType);
            $controller = new AnetController\CreateTransactionController($request);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

            if ($response != null) {
                return $response;
            } else {
                return new \WP_Error('AN_ChargePaymentProfile_Error', "No response returned \n");
            }
            return $response;
        }

        // Helper function to populate a lesson from a MeekroDB Row
        public static function populatefromRow($row): ?customerProfile
        {
            if ($row == null)
                return null;

            $customerProfile = new customerProfile();
            $customerProfile->id = $row['id'];
            $customerProfile->merchantCustomerId = $row['merchantCustomerId'];
            $customerProfile->description = $row['description'];
            $customerProfile->Email = $row['Email'];
            return $customerProfile;
        }
    }
}
