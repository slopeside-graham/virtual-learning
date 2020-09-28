<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;
    /**
     * Class SubscriptionPayment
     */
    class SubscriptionPayment extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Status;
        private $_Amount;
        private $_Subscription_id;
        private $_StartDate;
        private $_EndDate;
        private $_Payment_id;
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


        protected function Subscription_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Subscription_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Subscription_id;
            }
        }
        protected function StartDate($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_StartDate = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_StartDate;
            }
        }
        protected function EndDate($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_EndDate = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_EndDate;
            }
        }
        protected function Payment_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Payment_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Payment_id;
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
                'Status' => $this->Status,
                'Amount' => $this->Amount,
                'Subscription_id' => $this->Subscription_id,
                'StartDate' => $this->StartDate,
                'EndDate' => $this->EndDate,
                'Payment_id' => $this->Payment_id,
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified,
            ];
        }

        public function Create()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('SubscriptionPayment', array(
                    'id' => $this->id,
                    'Status' => $this->Status,
                    'Amount' => $this->Amount,
                    'Subscription_id' => $this->Subscription_id,
                    'StartDate' => $this->StartDate,
                    'EndDate' => $this->EndDate,
                    'Payment_id' => $this->Payment_id
                ));
                $this->id = VLPUtils::$db->insertId();

            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_Create_Error', $e->getMessage());
            }
            return true;
        }

        public function Update()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE SubscriptionPayment 
                    SET
                    Status=%s, 
                    Amount=%i, 
                    Subscription_id=%i,
                    StartDate=%i,
                    EndDate=%i,
                    Payment_id=%i
                WHERE 
                    id=%i",
                    $this->Status,
                    $this->Amount,
                    $this->Subscription_id,
                    $this->StartDate,
                    $this->EndDate,
                    $this->Payment_id,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $SubscriptionPayment = SubscriptionPayment::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_Update_Error', $e->getMessage());
            }
            return $SubscriptionPayment;
        }

        public function Delete()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from SubscriptionPayment WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('SubscriptionPayment_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from SubscriptionPayment where id = %i", $thisid);
                $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_Get_Error', $e->getMessage());
            }
            return $SubscriptionPayment;
        }        
        

        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                    $results = VLPUtils::$db->query("select * from SubscriptionPayment");

                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        public static function GetAllBySubscriptionId($subscriptionId)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                    $results = VLPUtils::$db->query("select * from SubscriptionPayment where Subscription_id = %i", $subscriptionId);

                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        public static function GetCurrentDueBySubscriptionId($subscriptionId)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                    $results = VLPUtils::$db->query("select * from SubscriptionPayment 
                                                        where 
                                                            Subscription_id = %i 
                                                            and Date(StartDate) <= Date(Now())", $subscriptionId);

                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        public static function GetUpcomingBySubscriptionId($subscriptionId)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                    $results = VLPUtils::$db->query("select * from SubscriptionPayment 
                                                        where 
                                                            Subscription_id = %i
                                                            Limit 999 Offset 1", $subscriptionId);

                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        // Helper function to populate a lesson from a MeekroDB Row
        public static function populatefromRow($row): ?SubscriptionPayment
        {
            if ($row == null)
            return null;
            
            $SubscriptionPayment = new SubscriptionPayment();
            $SubscriptionPayment->id = $row['id'];
            $SubscriptionPayment->Status = $row['Status'];
            $SubscriptionPayment->Amount = $row['Amount'];
            $SubscriptionPayment->Subscription_id = $row['Subscription_id'];
            $SubscriptionPayment->StartDate = $row['StartDate'];
            $SubscriptionPayment->EndDate = $row['EndDate'];
            $SubscriptionPayment->Payment_id = $row['Payment_id'];
            return $SubscriptionPayment;
        }
    }
}