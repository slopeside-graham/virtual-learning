<?php

namespace GHES\VLP {

    use Exception;
    use GHES\VLP\Utils as VLPUtils;
    use GHES\VLP\customerProfile;
    use GHES\VLP\customerPaymentProfile;
    use GHES\VLP\SubscriptionPayment;
    use GHES\VLP\Subscription;

    /**
     * Class Payment
     */
    class Payment extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Type;
        private $_User_id;
        private $_Amount;
        private $_Status;
        private $_Recurring;
        private $_Description;
        private $_ResponseCode;
        private $_authCode;
        private $_avsResultCode;
        private $_CvvResultCode;
        private $_CavvResultCode;
        private $_transId;
        private $_refTransId;
        private $_accountNumber;
        private $_accountType;
        private $_prePaidCard;
        private $_errors;
        private $_DateCreated;


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

        protected function Type($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Type = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Type;
            }
        }

        protected function Applications_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Applications_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Applications_id;
            }
        }

        protected function User_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_User_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_User_id;
            }
        }

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

        protected function Status($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Status = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Status;
            }
        }

        protected function Recurring($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Recurring = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Recurring;
            }
        }

        protected function Description($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Description = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Description;
            }
        }

        protected function ResponseCode($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_ResponseCode = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_ResponseCode;
            }
        }

        protected function authCode($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_authCode = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_authCode;
            }
        }

        protected function avsResultCode($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_avsResultCode = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_avsResultCode;
            }
        }

        protected function CvvResultCode($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_CvvResultCode = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_CvvResultCode;
            }
        }

        protected function CavvResultCode($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_CavvResultCode = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_CavvResultCode;
            }
        }

        protected function transId($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_transId = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_transId;
            }
        }

        protected function refTransId($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_refTransId = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_refTransId;
            }
        }

        protected function accountNumber($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_accountNumber = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_accountNumber;
            }
        }

        protected function accountType($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_accountType = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_accountType;
            }
        }

        protected function prePaidCard($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_prePaidCard = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_prePaidCard;
            }
        }

        protected function errors($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_errors = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_errors;
            }
        }
        /*
        protected function DateCreated($value = null): \DateTime
        {
            // If value was provided, set the value
            if ($value) {
                if (strlen($value) > 10) {
                    $theDate = \DateTime::createFromFormat('D M d Y H:i:s e+', $value);
                    if (!$theDate)
                        $theDate = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
                    if (!$theDate)
                        return null;
                    else
                        $this->_DateCreated = $theDate;
                } else
                    $this->_DateCreated = \DateTime::createFromFormat('Y-m-d', $value);
                return $this->_DateCreated;
            }
            // If no value was provided return the existing value
            else {
                return $this->_DateCreated;
            }
        }
        */

        public function jsonSerialize()
        {
            return [
                'id' => $this->id,
                'Type' => $this->Type,
                'User_id' => $this->User_id,
                'Amount' => $this->Amount,
                'Status' => $this->Status,
                'Recurring' => $this->Recurring,
                'Description' => $this->Description,
                'ResponseCode' => $this->ResponseCode,
                'authCode' => $this->authCode,
                'avsResultCode' => $this->avsResultCode,
                'CvvResultCode' => $this->CvvResultCode,
                'CavvResultCode' => $this->CavvResultCode,
                'transId' => $this->transId,
                'refTransId' => $this->refTransId,
                'accountNumber' => $this->accountNumber,
                'accountType' => $this->accountType,
                'prePaidCard' => $this->prePaidCard,
                'errors' => $this->errors,
                'DateCreated' => $this->DateCreated
            ];
        }

        private function GetErrorMessage($response)
        {
            $tresponse = $response->getTransactionResponse();
            if ($tresponse != null && $tresponse->getErrors() != null) {
                $errorMessages =  "Transaction Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n" + " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
            } else {
                $errorMessages =  "Response Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n" + " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
            }
            return $errorMessages;
        }

        public function charge($request)
        {
            // Get the User ID
            $User_id = get_current_user_id();
            // Get the Parent Objuct
            $Parent = \GHES\Parents::GetByUserID($User_id);
            // Get the Parent ID
            $Parent_id = $Parent->id;
            // Get the Customer Profile ID
            try {
                if ($Parent->customerProfileId != NULL && $Parent->customerPaymentProfileId != NULL) {
                    customerPaymentProfile::updateProfile($Parent, $request);
                } else {
                    $customerProfile = customerProfile::populatefromrow($request);
                    $customerProfile->merchantCustomerId = $Parent_id;

                    $customerPaymentProfile = customerPaymentProfile::populatefromrow($request);
                    $anCustomerPaymentProfile = $customerPaymentProfile->getanCustomerPaymentProfile();
                    $result = $customerProfile->createCustomerProfile($anCustomerPaymentProfile, $Parent_id);
                    if (!is_wp_error($result)) {
                        $Parent = \GHES\Parents::GetByUserID($User_id); // This is refreshuing the parent to get the new anet profile ids'.
                    } else {
                        return $result;
                    }
                }
            } catch (Exception $e) {
                return new \WP_Error('ChargePayment_Error', $e->getMessage());
            }
            // Charge customerProfile
            try {
                $pendingPayments = SubscriptionPayment::GetAllPendingByParentId($Parent_id);
                if (!empty($pendingPayments->jsonSerialize())) {
                    if (!is_wp_error($pendingPayments)) {
                        $backendAmount = 0;
                        foreach ($pendingPayments->jsonSerialize() as $i => $pendingPayment) {
                            $backendAmount += $pendingPayment->Amount;
                        }
                    } else {
                        return new \WP_Error('ChargePayment_Error', $pendingPayments->getMessage());
                    }
                } else {
                    return new \WP_Error('ChargePayment_Error', 'No Pending Payments. Please refresh the page and try again.');
                }
                if ($this->Amount == $backendAmount) {
                    $Amount = $backendAmount;
                } else {
                    return new \WP_Error('ChargePayment_Error', 'Something went wrong with your total. Please refresh the page and try again.');
                }
                $customerProfile = new customerProfile();
                //TODO: Populate this object with customer info first, rather than passing variables directly
                $chargeResult = $customerProfile->chargeCustomerProfile($Parent->customerProfileId, $Parent->customerPaymentProfileId, $Amount);

                // Create the payment record from the response.
                $paymentResult = $this->CreatePaymentFromResponse($chargeResult);
                $paymentResultid = $paymentResult->id;
                $chargepayment = Payment::Get($paymentResultid);

                if ($chargeResult->getMessages()->getResultCode() == "Ok") {
                    $tresponse = $chargeResult->getTransactionResponse();

                    if ($tresponse != null && $tresponse->getMessages() != null) {
                        // Everything worked.. 
                        $pendingPaymentResult = SubscriptionPayment::UpdateAllPendingByParentId($Parent_id, $paymentResultid);
                        if (!is_wp_error($pendingPaymentResult)) {
                            $subscriptionresult = Subscription::ActivateSubscriptionByParentId($Parent->id);
                            //
                            if (!is_wp_error($subscriptionresult)) {
                                $emailresult = Email::SendChargeEmail($chargepayment);
                                return true;
                            } else {
                                return $subscriptionresult;
                            }
                        } else {
                            // response is ok, but tresponse is failed (Fail)
                            return new \WP_Error('ChargePayment_Error', $this->GetErrorMessage($chargeResult));
                        }
                    } else {
                        // tresponse is null and has messages  (Fail)
                        return new \WP_Error('ChargePayment_Error', $this->GetErrorMessage($chargeResult));
                    }
                } else {
                    //Fail
                    return new \WP_Error('ChargePayment_Error', $chargeResult->getTransactionResponse()->getErrors()[0]->getErrorText()); //TODO: Get the proper message for this
                }
            } catch (Exception $e) {
                return new \WP_Error('ChargePayment_Error', $e->getMessage());
            }
            // Get the response
            return true;
        }

        public static function refund($refundAmount, $chargepayment, $voidAmount)
        {
            $refundPayment = new Payment;
            // Get the User ID
            $User_id = get_current_user_id();
            // Get the Parent Objuct
            $Parent = \GHES\Parents::GetByUserID($User_id);
            // Get the Parent ID
            $Parent_id = $Parent->id;

            $customerProfile = new customerProfile();
            $chargeResult = $customerProfile->refundCustomer($Parent->customerProfileId, $Parent->customerPaymentProfileId, $refundAmount, $chargepayment, $voidAmount);
            if ($chargeResult != false) {
                // Create the payment record from the response.
                $refundPaymentResult = $refundPayment->CreateRefundFromResponse($chargeResult, $refundAmount);

                if (!is_array($chargeResult)) {
                    $result = customerProfile::analyzeANresponse($chargeResult);
                } else {
                    $result = customerProfile::analyzeANresponse($chargeResult[0]);
                }

                if (!is_wp_error($result)) {
                    return $refundPaymentResult;
                } else {
                    $error_string = $result->get_error_message();
                    return new \WP_Error('Refund_Error', 'An error occured: ' . $error_string, array('status' => 400));
                }
            }
            return new \WP_Error('Refund_Error', 'An error occured: The transaction could not be refunded.');
        }

        public function CreatePaymentFromResponse($chargeResult)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $response = $chargeResult;
            $tresponse = $chargeResult->getTransactionResponse();

            if ($response->getMessages()->getResultCode() == "Ok" && $tresponse->getErrors() == null) {  // Transaction response was OK.
                $chargeStatus = "Successful";
                $paymentErrors = null;
            } else {
                $chargeStatus = "Failed";
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $paymentErrors = "Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    $paymentErrors .= "Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                } else {
                    if ($response != null && $response->getMessages() != null) {
                        $paymentErrors = "Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                        $paymentErrors .= "Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                    }
                }
            }
            $this->Type = 'Charge';
            $this->User_id = get_current_user_id();
            $this->Status = $chargeStatus;
            $this->Description = 'VLL Subscription';
            $this->ResponseCode = $tresponse->getResponseCode();
            $this->authCode = $tresponse->getAuthCode();
            $this->avsResultCode = $tresponse->getAvsResultCode();
            $this->CvvResultCode = $tresponse->getCvvResultCode();
            $this->CavvResultCode = $tresponse->getCavvResultCode();
            $this->transId = $tresponse->getTransId();
            $this->refTransId = $tresponse->getRefTransId();
            $this->accountNumber = $tresponse->getAccountNumber();
            $this->accountType = $tresponse->getAccountType();
            $this->prePaidCard = $tresponse->getPrePaidCard();
            $this->errors = $paymentErrors;

            $this->Create();
            return $this;
        }

        public function CreateRefundFromResponse($chargeResult, $refundAmount)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            if (!is_array($chargeResult)) {
                $type = 'Refund';
                $response = $chargeResult;
            } else {
                $type = 'Void';
                //If this is a void, we need to void the original amount, so we get the original payment, then get the amount from that.
                $response = $chargeResult[0];
                $transId = $chargeResult[0]->getTransactionResponse()->getTransId();
                $originalPayment = Payment::GetBytransId($transId);
                $voidedAmount = $originalPayment->Amount;

                $refundAmount = $voidedAmount;
            }
            $tresponse = $response->getTransactionResponse();

            if ($response->getMessages()->getResultCode() == "Ok" && $tresponse->getErrors() == null) {  // Transaction response was OK.
                $chargeStatus = "Successful";
                $paymentErrors = null;
            } else {
                $chargeStatus = "Failed";
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $paymentErrors = "Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    $paymentErrors .= "Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                } else {
                    if ($response != null && $response->getMessages() != null) {
                        $paymentErrors = "Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                        $paymentErrors .= "Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                    }
                }
            }

            if ($tresponse->getAccountType() == 'eCheck') {
                $accountNumber = $tresponse->getBankAccount();
            } else {
                $accountNumber = $tresponse->getAccountNumber();
            }

            $this->Type = $type;
            $this->User_id = get_current_user_id();
            $this->Amount = $refundAmount;
            $this->Status = $chargeStatus;
            $this->Description = 'VLL Subscription';
            $this->ResponseCode = $tresponse->getResponseCode();
            $this->authCode = $tresponse->getAuthCode();
            $this->avsResultCode = $tresponse->getAvsResultCode();
            $this->CvvResultCode = $tresponse->getCvvResultCode();
            $this->CavvResultCode = $tresponse->getCavvResultCode();
            $this->transId = $tresponse->getTransId();
            $this->refTransId = $tresponse->getRefTransId();
            $this->accountNumber = $accountNumber;
            $this->accountType = $tresponse->getAccountType();
            $this->prePaidCard = $tresponse->getPrePaidCard();
            $this->errors = $paymentErrors;

            $this->Create();
            return $this;
        }

        public function Create()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('Payment', array(
                    'Type' => $this->Type,
                    'User_id' => $this->User_id,
                    'Amount' => $this->Amount,
                    'Status' => $this->Status,
                    'Description' => $this->Description,
                    'ResponseCode' => $this->ResponseCode,
                    'authCode' => $this->authCode,
                    'avsResultCode' => $this->avsResultCode,
                    'CvvResultCode' => $this->CvvResultCode,
                    'CavvResultCode' => $this->CavvResultCode,
                    'transId' => $this->transId,
                    'refTransId' => $this->refTransId,
                    'accountNumber' => $this->accountNumber,
                    'accountType' => $this->accountType,
                    'prePaidCard' => $this->prePaidCard,
                    'errors' => $this->errors
                ));
                $this->id = VLPUtils::$db->insertId();
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Payments_Create_Error', $e->getMessage());
            }
            return true;
        }

        // We are not using any function besides Create at the moment. So I am not updating these.
        public function Update()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE Payment 
                    SET
                    Name=%s, 
                    MonthlyAmount=%i, 
                    YearlyAmount=%i,
                    PaymentFrequency=%i,
                    AllowAutoRenew=%i,
                    StartDate=%i,
                    EndDate=%i,
                    Hidden=%i
                WHERE 
                    id=%i",
                    $this->MonthlyAmount,
                    $this->YearlyAmount,
                    $this->PaymentFrequency,
                    $this->AllowAutoRenew,
                    $this->StartDate,
                    $this->EndDate,
                    $this->Hidden,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $Payment = Payment::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Payment_Update_Error', $e->getMessage());
            }
            return $Payment;
        }

        public function Delete()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from Payment WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('Payment_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Payment where id = %i", $thisid);
                $Payment = Payment::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Payment_Get_Error', $e->getMessage());
            }
            return $Payment;
        }

        public static function GetBytransId($transId)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Payment where transId = %i", $transId);
                $Payment = Payment::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Payment_Get_Error', $e->getMessage());
            }
            return $Payment;
        }


        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $Payments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select * from Payment");

                foreach ($results as $row) {
                    $Payment = Payment::populatefromRow($row);
                    $Payments->add_item($Payment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Payment_GetAll_Error', $e->getMessage());
            }
            return $Payments;
        }

        public static function GetAllBySubscriptionId($subscriptionid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $Payments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query(
                    "SELECT DISTINCT p.* 
                        FROM Payment p
                        Inner Join SubscriptionPayment sp on sp.Payment_id = p.id
                        Where sp.Subscription_id= %i
                        ",
                    $subscriptionid
                );

                foreach ($results as $row) {
                    $Payment = Payment::populatefromRow($row);
                    $Payments->add_item($Payment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Payment_GetAll_Error', $e->getMessage());
            }
            return $Payments;
        }

        public static function GetAllCancelledBySubscriptionId($subscriptionid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $Payments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query(
                    "SELECT DISTINCT p.* 
                        FROM Payment p
                        Inner Join SubscriptionPayment sp on sp.CancelledPayment_id = p.id
                        Where sp.Subscription_id= %i
                        ",
                    $subscriptionid
                );

                foreach ($results as $row) {
                    $Payment = Payment::populatefromRow($row);
                    $Payments->add_item($Payment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Payment_GetAll_Error', $e->getMessage());
            }
            return $Payments;
        }

        // Helper function to populate a lesson from a MeekroDB Row
        public static function populatefromRow($row): ?Payment
        {
            if ($row == null)
                return null;

            $Payment = new Payment();
            $Payment->id = $row['id'];
            $Payment->Type = $row['Type'];
            $Payment->User_id = $row['User_id'];
            $Payment->Amount = $row['Amount'];
            $Payment->Status = $row['Status'];
            $Payment->Recurring = $row['Recurring'];
            $Payment->Description = $row['Description'];
            $Payment->ResponseCode = $row['ResponseCode'];
            $Payment->authCode = $row['authCode'];
            $Payment->avsResultCode = $row['avsResultCode'];
            $Payment->CvvResultCode = $row['CvvResultCode'];
            $Payment->CavvResultCode = $row['CavvResultCode'];
            $Payment->transId = $row['transId'];
            $Payment->refTransId = $row['refTransId'];
            $Payment->accountNumber = $row['accountNumber'];
            $Payment->accountType = $row['accountType'];
            $Payment->prePaidCard = $row['prePaidCard'];
            $Payment->errors = $row['errors'];
            $Payment->DateCreated = $row['DateCreated'];
            return $Payment;
        }
    }
}
