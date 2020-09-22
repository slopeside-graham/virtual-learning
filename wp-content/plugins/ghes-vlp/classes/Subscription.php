<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;

    /**
     * Class Subscription
     */
    class Subscription extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_ParentID;
        private $_StartDate;
        private $_EndDate;
        private $_PaymentStatus;
        private $_PaymentFrequency;
        private $_SubscriptionDefinition_id;
        private $_Total;
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

        protected function ParentID($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_ParentID = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_ParentID;
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

        protected function PaymentStatus($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_PaymentStatus = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_PaymentStatus;
            }
        }
        protected function PaymentFrequency($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_PaymentFrequency = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_PaymentFrequency;
            }
        }
        protected function SubscriptionDefinition_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_SubscriptionDefinition_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_SubscriptionDefinition_id;
            }
        }
        protected function Total($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Total = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Total;
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
                'ParentID' => $this->ParentID,
                'StartDate' => $this->StartDate,
                'EndDate' => $this->EndDate,
                'PaymentStatus' => $this->PaymentStatus,
                'PaymentFrequency' => $this->PaymentFrequency,
                'SubscriptionDefinition_id' => $this->SubscriptionDefinition_id,
                'Total' => $this->Total,
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified,
            ];
        }

        public function Create()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('Subscription', array(
                    'id' => $this->id,
                    'ParentID' => $this->ParentID,
                    'StartDate' => $this->StartDate,
                    'EndDate' => $this->EndDate,
                    'PaymentStatus' => $this->PaymentStatus,
                    'PaymentFrequency' => $this->PaymentFrequency,
                    'SubscriptionDefinition_id' => $this->SubscriptionDefinition_id,
                    'Total' => $this->Total
                ));
                $this->id = VLPUtils::$db->insertId();
                // Create Subsciption Payments
                try {
                    //TODO Create Subsciption Payments
                    /*
                    for ($x = 0; $x <= 10; $x++) {

                        $subscriptionpayment = new SubscriptionPayment();

                        $subscriptionpayment->Status = "Unpaid";
                        $subscriptionpayment->Subscription_id = $this->id;
                        $subscriptionpayment->Create();
                    } */
                } catch (\MeekroDBException $e) {
                    return new \WP_Error('Subscription_Create_Error', $e->getMessage());
                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Subscription_Create_Error', $e->getMessage());
            }
            return true;
        }

        public function Update()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE Subscription 
                    SET
                    ParentID=%i, 
                    StartDate=%s, 
                    EndDate=%s,
                    PaymentStatus=%s,
                    PaymentFrequency=%s,
                    SubscriptionDefinition_id=%i,
                    Total=%i
                WHERE 
                    id=%i",
                    $this->ParentID,
                    $this->StartDate,
                    $this->EndDate,
                    $this->PaymentStatus,
                    $this->PaymentFrequency,
                    $this->SubscriptionDefinition_id,
                    $this->Total
                );

                $counter = VLPUtils::$db->affectedRows();

                $Subscription = Subscription::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Subscription_Update_Error', $e->getMessage());
            }
            return $Subscription;
        }

        public function Delete()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from Subscription WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('Subscription_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Subscription where id = %i", $thisid);
                $Subscription = Subscription::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Subscription_Get_Error', $e->getMessage());
            }
            return $Subscription;
        }


        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $Subscriptions = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select * from Subscription");

                foreach ($results as $row) {
                    $Subscription = Subscription::populatefromRow($row);
                    $Subscriptions->add_item($Subscription);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Subscription_GetAll_Error', $e->getMessage());
            }
            return $Subscriptions;
        }

        // Helper function to populate a lesson from a MeekroDB Row
        public static function populatefromRow($row): ?Subscription
        {
            if ($row == null)
                return null;

            $Subscription = new Subscription();
            $Subscription->id = $row['id'];
            $Subscription->ParentID = $row['ParentID'];
            $Subscription->StartDate = $row['StartDate'];
            $Subscription->EndDate = $row['EndDate'];
            $Subscription->PaymentStatus = $row['PaymentStatus'];
            $Subscription->PaymentFrequency = $row['PaymentFrequency'];
            $Subscription->SubscriptionDefinition_id = $row['SubscriptionDefinition_id'];
            $Subscription->Total = $row['Total'];
            return $Subscription;
        }
    }
}
