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
        private $_Default;
        private $_DateCreated;
        private $_DateModified;


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

        protected function Default($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Default = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Default;
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

        public function jsonSerialize()
        {
            return [
                'id' => $this->id,
                'ParentId' => $this->ParentId,
                'customerProfileId' => $this->customerProfileId,
                'customerPaymentProfileId' => $this->customerPaymentProfileId,
                'Default' => $this->Default,
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified
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
                    'Default' => $this->Default
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
                    "UPDATE PaymentMethod 
                    SET
                    ParentId=%s, 
                    customerProfileId=%i, 
                    customerPaymentProfileId=%i,
                    Default=%i
                WHERE 
                    id=%i",
                    $this->ParentId,
                    $this->customerProfileId,
                    $this->customerPaymentProfileId,
                    $this->Default,
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

        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $PaymentMethods = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select * from PaymentMethod");

                foreach ($results as $row) {
                    $PaymentMethod = PaymentMethod::populatefromRow($row);
                    $PaymentMethods->add_item($PaymentMethod);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('PaymentMethod_GetAll_Error', $e->getMessage());
            }
            return $PaymentMethods;
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
            $PaymentMethod->Default = $row['Default'];
            $PaymentMethod->DateCreated = $row['DateCreated'];
            $PaymentMethod->DateModified = $row['DateModified'];
            return $PaymentMethod;
        }
    }
}