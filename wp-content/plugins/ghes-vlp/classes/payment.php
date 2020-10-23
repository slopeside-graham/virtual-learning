<?php

namespace GHES\VLP {

    use Exception;
    use GHES\VLP\Utils as VLPUtils;
    use GHES\VLP\customerProfile;
    use GHES\VLP\customerPaymentProfile;

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
                'accountNumber' => $this->accountNumber,
                'accountType' => $this->accountType,
                'prePaidCard' => $this->prePaidCard,
                'errors' => $this->errors,
                'DateCreated' => $this->DateCreated
            ];
        }

        public function Create($request)
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
                    $customerProfileId = $Parent->customerProfileId;
                    $customerPaymentProfileId = $Parent->customerPaymentProfileId;

                    $customerProfile = new CustomerProfile();
                    $customerProfile = CustomerProfile::populatefromrow($request);
                    $customerProfile->id = $customerProfileId;

                    $customerPaymentProfile = new CustomerPaymentProfile();
                    $customerPaymentProfile = customerPaymentProfile::populatefromrow($request);
                    $customerPaymentProfile->id = $customerPaymentProfileId;
                    $customerPaymentProfile->updateCustomerPaymentProfile($customerProfileId, $customerPaymentProfileId);
                    // $customerProfile->customerPaymentProfile = $customerPaymentProfile;
                } else {
                    $customerProfile = new customerProfile();
                    $customerProfile = customerProfile::populatefromrow($request);
                    $customerProfile->merchantCustomerId = $Parent_id;
                    $customerPaymentProfile = new customerPaymentProfile();
                    $customerPaymentProfile = customerPaymentProfile::populatefromrow($request);
                    $anCustomerPaymentProfile = $customerPaymentProfile->getanCustomerPaymentProfile();
                    $result = $customerProfile->createCustomerProfile($anCustomerPaymentProfile, $Parent_id);
                    if ($result == TRUE) {
                        $customerPaymentProfileId = $customerProfile->id;
                        $customerPaymentProfileId = $anCustomerPaymentProfile->id;
                    } else {
                        return $result;
                    }
                }
            } catch (Exception $e) {
                return new \WP_Error('Payment_Create_Error', $e->getMessage());
            }
            // Charge customerProfile
            try {
                $customerProfile->chargeCustomerProfile($customerProfile->id, $customerPaymentProfileId, $this->Amount);
            } catch (Exception $e) {
                return new \WP_Error('CharedPaymentProfile_Error', $e->getMessage());
            }
            // Get the response

            // Create the payment record from the response.
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('Payment', array(
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
                    'accountNumber' => $this->accountNumber,
                    'accountType' => $this->accountType,
                    'prePaidCard' => $this->prePaidCard,
                    'errors' => $this->errors
                ));
                $this->id = VLPUtils::$db->insertId();
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Payment_Create_Error', $e->getMessage());
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
            $Payment->accountNumber = $row['accountNumber'];
            $Payment->accountType = $row['accountType'];
            $Payment->prePaidCard = $row['prePaidCard'];
            $Payment->errors = $row['errors'];
            $Payment->DateCreated = $row['DateCreated'];
            return $Payment;
        }
    }
}
