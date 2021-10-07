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
    class PaymentMethod extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_ParentId;
        private $_customerProfileId;
        private $_customerPaymentProfileId;
        private $_DefaultPayment;
        private $_DateCreated;
        private $_DateModified;

        private $_CardNumber;
        private $_ExpirationDate;
        private $_CardCode;
        private $_CardType;

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

        protected function ParentId($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_ParentId = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_ParentId;
            }
        }

        protected function customerProfileId($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_customerProfileId = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_customerProfileId;
            }
        }

        protected function customerPaymentProfileId($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_customerPaymentProfileId = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_customerPaymentProfileId;
            }
        }

        protected function DefaultPayment($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_DefaultPayment = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_DefaultPayment;
            }
        }

        protected function DateCreated($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_DateCreated = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_DateCreated;
            }
        }

        protected function DateModified($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_DateModified = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_DateModified;
            }
        }

        protected function CardNumber($value = null)
        {
            // If value was provided, set the value
            if ($value) {
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
                $this->_CardCode = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_CardCode;
            }
        }

        protected function CardType($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_CardType = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_CardType;
            }
        }

        protected function AccountType($value = null)
        {
            // If value was provided, set the value
            if ($value) {
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

        protected function Country($value = null)
        {
            // If value was provided, set the value
            if ($value) {
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
                $this->_PhoneNumber = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_PhoneNumber;
            }
        }

        public function jsonSerialize()
        {
            return [
                'id' => $this->id,
                'ParentId' => $this->ParentId,
                'customerProfileId' => $this->customerProfileId,
                'customerPaymentProfileId' => $this->customerPaymentProfileId,
                'DefaultPayment' => $this->DefaultPayment,
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified,

                'CardNumber' => $this->CardNumber,
                'CardCode' => $this->CardCode,
                'ExpirationDate' => $this->ExpirationDate,
                'CardType' => $this->CardType,

                'AccountType' => $this->AccountType,
                'EcheckType' => $this->EcheckType,
                'RoutingNumber' => $this->RoutingNumber,
                'AccountNumber' => $this->AccountNumber,
                'NameOnAccount' => $this->NameOnAccount,
                'BankName' => $this->BankName,

                'FirstName' => $this->FirstName,
                'LastName' => $this->LastName,
                'Address' => $this->Address,
                'City' => $this->City,
                'State' => $this->State,
                'Zip' => $this->Zip,
                'Country' => $this->Country,
                'PhoneNumber' => $this->PhoneNumber
            ];
        }

        public function Create()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('PaymentMethod', array(
                    'ParentId' => $this->ParentId,
                    'customerProfileId' => $this->customerProfileId,
                    'customerPaymentProfileId' => $this->customerPaymentProfileId,
                    'DefaultPayment' => $this->DefaultPayment
                ));
                $this->id = VLPUtils::$db->insertId();
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Payments_Create_Error', $e->getMessage());
            }
            return true;
        }

        public static function CreateFromAN($response)
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            //Build each field since this is coming from another class
            $ParentId = \GHES\Parents::GetByUserID(get_current_user_id())->id;
            $customerProfileId = $response->customerProfileId;
            $customerPaymentProfileId = $response->customerPaymentProfileId;
            $DefaultPayment = $response->DefaultPayment;

            try {

                VLPUtils::$db->insert('PaymentMethod', array(
                    'ParentId' => $ParentId,
                    'customerProfileId' => $customerProfileId,
                    'customerPaymentProfileId' => $customerPaymentProfileId,
                    'DefaultPayment' => $DefaultPayment
                ));
                $paymentMethodid = VLPUtils::$db->insertId();
                $paymentMethod = PaymentMethod::Get($paymentMethodid);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Payments_Create_Error', $e->getMessage());
            }
            return $paymentMethod;
        }

        // We are not using any function besides Create at the moment. So I am not updating these.
        public function Update()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE PaymentMethod 
                    SET
                    ParentId=%s, 
                    customerProfileId=%i, 
                    customerPaymentProfileId=%i,
                    DefaultPayment=%i
                WHERE 
                    id=%i",
                    $this->ParentId,
                    $this->customerProfileId,
                    $this->customerPaymentProfileId,
                    $this->DefaultPayment,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $PaymentMethod = PaymentMethod::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('PaymentMethod_Update_Error', $e->getMessage());
            }
            return $PaymentMethod;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from PaymentMethod where id = %i", $thisid);
                $PaymentMethod = PaymentMethod::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('PaymentMethod_Get_Error', $e->getMessage());
            }
            return $PaymentMethod;
        }

        public static function GetByPaymentProfileId($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $parentId = \GHES\Parents::GetByUserID(get_current_user_id())->id;

            try {
                $row = VLPUtils::$db->queryFirstRow("select * from PaymentMethod where customerPaymentProfileId = %i and ParentId = %i", $thisid, $parentId);
                $PaymentMethod = PaymentMethod::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('PaymentMethod_Get_Error', $e->getMessage());
            }
            return $PaymentMethod;
        }

        public static function GetByParentId()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $parentId = \GHES\Parents::GetByUserID(get_current_user_id())->id;

            try {
                $row = VLPUtils::$db->queryFirstRow("select * from PaymentMethod where ParentId = %i", $parentId);
                $PaymentMethod = PaymentMethod::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('PaymentMethod_Get_Error', $e->getMessage());
            }
            return $PaymentMethod;
        }

        public static function GetAll()
        {
            //Only allow this function to get payment methods fro the current Parent
            if (is_user_logged_in() && \GHES\ghes_base::UserIsParent()) {

                VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
                VLPUtils::$db->throw_exception_on_error = true;

                $parentId = \GHES\Parents::GetByUserID(get_current_user_id())->id;

                $PaymentMethods = new NestedSerializable();

                try {
                    $results = VLPUtils::$db->query("select * from PaymentMethod where ParentId = %i", $parentId);

                    foreach ($results as $row) {
                        $PaymentMethod = PaymentMethod::populatefromRow($row);

                        // Fill in all the other details from Authorize.Net for this payment Method
                        $PaymentMethod->populatefromAN();

                        $PaymentMethods->add_item($PaymentMethod);  // Add the lesson to the collection

                    }
                } catch (\MeekroDBException $e) {
                    return new \WP_Error('PaymentMethod_GetAll_Error', $e->getMessage());
                }
                return $PaymentMethods;
            } else {
                return new \WP_Error('PaymentMethod_GetAll_Error', 'Current User is not a Parent');
            }
        }

        private function populatefromAN()
        {
            $customerPaymentProfile = new customerPaymentProfile;
            $success = $customerPaymentProfile->GetByPaymentMethod($this);

            if ($success != false) {
                $this->CardNumber = $customerPaymentProfile->CardNumber;
                $this->CardCode = $customerPaymentProfile->CardCode;
                $this->ExpirationDate = $customerPaymentProfile->ExpirationDate;
                $this->CardType = $customerPaymentProfile->CardType;

                $this->AccountType = $customerPaymentProfile->AccountType;
                $this->EcheckType = $customerPaymentProfile->EcheckType;
                $this->RoutingNumber = $customerPaymentProfile->RoutingNumber;
                $this->AccountNumber = $customerPaymentProfile->AccountNumber;
                $this->NameOnAccount = $customerPaymentProfile->NameOnAccount;
                $this->BankName = $customerPaymentProfile->BankName;

                $this->FirstName = $customerPaymentProfile->FirstName;
                $this->LastName = $customerPaymentProfile->LastName;
                $this->Address = $customerPaymentProfile->Address;
                $this->City = $customerPaymentProfile->City;
                $this->State = $customerPaymentProfile->State;
                $this->Zip = $customerPaymentProfile->Zip;
                $this->Country = $customerPaymentProfile->Country;
                $this->PhoneNumber = $customerPaymentProfile->PhoneNumber;
            }
        }

        // Helper function to populate a lesson from a MeekroDB Row
        public static function populatefromRow($row): ?PaymentMethod
        {
            if ($row == null)
                return null;

            $PaymentMethod = new PaymentMethod();
            $PaymentMethod->id = $row['id'];
            $PaymentMethod->ParentId = $row['ParentId'];
            $PaymentMethod->customerProfileId = $row['customerProfileId'];
            $PaymentMethod->customerPaymentProfileId = $row['customerPaymentProfileId'];
            $PaymentMethod->DefaultPayment = $row['DefaultPayment'];
            $PaymentMethod->DateCreated = $row['DateCreated'];
            $PaymentMethod->DateModified = $row['DateModified'];
            return $PaymentMethod;
        }
    }
}
