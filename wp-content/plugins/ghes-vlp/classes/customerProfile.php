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

        private $APILoginId;
        private $APIKey;
        private static $ENVIRONMENT = null;
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

        public static function getEnvironment()
        {
            // ENVIRONMENT is set when class is constructed / instantiated
            if (customerProfile::$ENVIRONMENT == null) {
            customerProfile::$ENVIRONMENT = VLPUtils::getunencryptedsetting('registration-environment');
            }
            return customerProfile::$ENVIRONMENT;
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
            $response = $controller->executeWithApiResponse(customerProfile::getEnvironment());

            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                $response->getCustomerProfileId();
                $paymentProfileid = $response->getCustomerPaymentProfileIdList();
                $paymentProfileid[0];
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
         * Get Customer Profile
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return mixed|WP_Error|Object
         */
        public static function Get($customerProfileId)
        {
            $customerProfile = new customerProfile;

            $profileIdRequested = $customerProfileId;
            /* Create a merchantAuthenticationType object with authentication details */
            $merchantAuthentication = $customerProfile->setMerchantAuthentication();

            // Set the transaction's refId
            $refId = 'ref' . time();

            // Retrieve an existing customer profile along with all the associated payment profiles and shipping addresses

            $request = new AnetAPI\GetCustomerProfileRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setCustomerProfileId($profileIdRequested);
            $controller = new AnetController\GetCustomerProfileController($request);
            $response = $controller->executeWithApiResponse(customerProfile::getEnvironment());
            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                return $response;
            } else {
                $response = $response->getMessages()->getMessage();
            }
            return $response;
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
            $response = $controller->executeWithApiResponse(customerProfile::getEnvironment());

            return $response;
        }

        public function refundCustomer($profileid, $paymentprofileid, $refundAmount, $originalPayment, $voidAmount)
        {
            $originalPaymentDetails = $this->getTransactionDetails($originalPayment);
            if(!is_wp_error($originalPaymentDetails)) {
            $refTransId = $originalPayment->transId;
            if ($originalPaymentDetails) {
                $originalPaymentStatus = $originalPaymentDetails->getTransaction()->getTransactionStatus();
                if ($originalPaymentStatus == 'settledSuccessfully') {
                    $response = $this->refundCustomerProfile($profileid, $paymentprofileid, $refundAmount, $originalPayment);
                } else {
                    $response = $this->voidCustomerProfileCharge($profileid, $paymentprofileid, $voidAmount, $originalPayment);
                }
            }
        } else {
            return false;
        }
            return $response;
        }

        public function getTransactionDetails($originalPayment)
        {
            $refTransId = $originalPayment->transId;
            /* Create a merchantAuthenticationType object with authentication details */
            $merchantAuthentication = $this->setMerchantAuthentication();

            $transactionId = $originalPayment->transId;

            $request = new AnetAPI\GetTransactionDetailsRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setTransId($transactionId);

            $controller = new AnetController\GetTransactionDetailsController($request);

            $response = $controller->executeWithApiResponse(customerProfile::getEnvironment());

            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                return $response;
            } else {
                //Failed
                $errorMessages = $response->getMessages()->getMessage();
                return new \WP_Error($errorMessages[0]->getCode(), $errorMessages[0]->getText());
            }
        }

        public function refundCustomerProfile($profileid, $paymentprofileid, $refundAmount, $originalPayment)
        {
            if ($refundAmount != 0) {
                $refTransId = $originalPayment->transId;
                /* Create a merchantAuthenticationType object with authentication details */
                $merchantAuthentication = $this->setMerchantAuthentication();

                // Set the transaction's refId
                $refId = 'ref' . time();

                // set payment profile for customer

                $paymentProfile = new AnetAPI\PaymentProfileType();
                $paymentProfile->setpaymentProfileId($paymentprofileid);

                // set customer profile
                $customerProfile = new AnetAPI\CustomerProfilePaymentType();
                $customerProfile->setCustomerProfileId($profileid);
                $customerProfile->setPaymentProfile($paymentProfile);

                //create a transaction
                $transactionRequest = new AnetAPI\TransactionRequestType();
                $transactionRequest->setTransactionType("refundTransaction");
                $transactionRequest->setAmount($refundAmount);
                $transactionRequest->setRefTransId($refTransId);
                $transactionRequest->setProfile($customerProfile);


                $request = new AnetAPI\CreateTransactionRequest();
                $request->setMerchantAuthentication($merchantAuthentication);
                $request->setRefId($refId);
                $request->setTransactionRequest($transactionRequest);
                $controller = new AnetController\CreateTransactionController($request);
                $response = $controller->executeWithApiResponse(customerProfile::getEnvironment());

                return $response;
            } else {
                return false;
            }
        }

        public function voidCustomerProfileCharge($profileid, $paymentprofileid, $voidAmount, $originalPayment)
        {
            $refTransId = $originalPayment->transId;
            /* Create a merchantAuthenticationType object with authentication details */
            $merchantAuthentication = $this->setMerchantAuthentication();

            // Set the transaction's refId
            $refId = 'ref' . time();

            // set payment profile for customer

            $paymentProfile = new AnetAPI\PaymentProfileType();
            $paymentProfile->setpaymentProfileId($paymentprofileid);

            // set customer profile
            $customerProfile = new AnetAPI\CustomerProfilePaymentType();
            $customerProfile->setCustomerProfileId($profileid);
            $customerProfile->setPaymentProfile($paymentProfile);

            // Void Trasaction because it is unsettled
            //create a transaction
            $transactionRequest = new AnetAPI\TransactionRequestType();
            $transactionRequest->setTransactionType("voidTransaction");
            $transactionRequest->setAmount($voidAmount);
            $transactionRequest->setRefTransId($refTransId);
            $transactionRequest->setProfile($customerProfile);


            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setTransactionRequest($transactionRequest);
            $controller = new AnetController\CreateTransactionController($request);
            $response = $controller->executeWithApiResponse(customerProfile::getEnvironment());

            return array($response, $void = true);
        }

        public static function analyzeANresponse($response)
        {
            if ($response != null) {
                if ($response->getMessages()->getResultCode() == "Ok") {
                    $tresponse = $response->getTransactionResponse();

                    if ($tresponse != null && $tresponse->getMessages() != null) {
                        // Request Success and Transaction Success
                        return $response;
                    } else {
                        // Request Success and Transaction Failed
                        if ($tresponse->getErrors() != null) {
                            $terrorcode = $tresponse->getErrors()[0]->getErrorCode();
                            $terrormessage = $tresponse->getErrors()[0]->getErrorText();
                        }
                        $response = "Error: " . $terrorcode . ", Error Message: " . $terrormessage;
                        return new \WP_Error('AN_analyzeANresponse_Error', "Error: " . $terrorcode . ", Error Message: " . $terrormessage);
                    }
                } else {
                    // Request Failed and Transaction Failed
                    $tresponse = $response->getTransactionResponse();
                    if ($tresponse != null && $tresponse->getErrors() != null) {
                        $terrorcode = $tresponse->getErrors()[0]->getErrorCode();
                        $terrormessage = $tresponse->getErrors()[0]->getErrorText();
                    } else {
                        $terrorcode = $response->getMessages()->getMessage()[0]->getCode();
                        $terrormessage = $response->getMessages()->getMessage()[0]->getText();
                    }
                    return new \WP_Error('AN_ResponseCustomerProfile_Error', "Error: " . $terrorcode . ", Error Message: " . $terrormessage);
                }
            } else {
                return new \WP_Error('AN_ResponseCustomerProfile_Error', "No response returned");
            }
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
