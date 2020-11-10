<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;
    use GHES\VLP\customerProfile;

    /**
     * Class Subscription
     */
    class Subscription extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_ParentID;
        private $_StartDate;
        private $_EndDate;
        private $_Status;
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
                'Status' => $this->Status,
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
                \DB::startTransaction();

                $subscriptionDefenition = SubscriptionDefinition::Get($this->SubscriptionDefinition_id);

                if ($this->PaymentFrequency == "monthly") {
                    $subscriptionTotal = $subscriptionDefenition->MonthlyAmount;
                } else if ($this->PaymentFrequency == "yearly") {
                    $subscriptionTotal = $subscriptionDefenition->YearlyAmount;
                }

                VLPUtils::$db->insert('Subscription', array(
                    'id' => $this->id,
                    'ParentID' => $this->ParentID,
                    'StartDate' => $this->StartDate,
                    'EndDate' => $this->EndDate,
                    'Status' => "Unpaid",
                    'PaymentFrequency' => $this->PaymentFrequency,
                    'SubscriptionDefinition_id' => $this->SubscriptionDefinition_id,
                    'Total' => $subscriptionDefenition->YearlyAmount
                ));
                $this->id = VLPUtils::$db->insertId();
                // Create Subsciption Payments
                try {
                    if ($this->PaymentFrequency == "monthly") {
                        for ($x = 0; $x <= 11; $x++) {

                            $subscriptionpayment = new SubscriptionPayment();

                            $subscriptionpayment->Status = "Unpaid";
                            $subscriptionpayment->Amount = $subscriptionTotal;
                            $subscriptionpayment->Subscription_id = $this->id;

                            $monthlystartdate = new \DateTime($this->StartDate);
                            $subscriptionpayment->StartDate = $monthlystartdate->add(new \DateInterval('P' . $x . 'M'));

                            $monthlyenddate = new \DateTime($this->StartDate);
                            $endDateAddMonth = $x + 1;
                            $endDateWithoutMinusDay = $monthlyenddate->add(new \DateInterval('P' . $endDateAddMonth . 'M'));
                            $subscriptionpayment->EndDate = $endDateWithoutMinusDay->sub(new \DateInterval('P1D'));

                            $subscriptionpayment->Payment_id = null;

                            $subscriptionpayment->Create();
                        }
                    } else if ($this->PaymentFrequency == "yearly") {
                        $subscriptionpayment = new SubscriptionPayment();

                        $subscriptionpayment->Status = "Unpaid";
                        $subscriptionpayment->Amount = $subscriptionTotal;
                        $subscriptionpayment->Subscription_id = $this->id;
                        $subscriptionpayment->StartDate = new \DateTime($this->StartDate);
                        $subscriptionpayment->EndDate = new \DateTime($this->EndDate);
                        $subscriptionpayment->Payment_id = null;

                        $subscriptionpayment->Create();
                    } else {
                        \DB::rollback();
                        return new \WP_Error('Subscription_Create_Error', "No Payment Frequency Selected.");
                    }
                } catch (\MeekroDBException $e) {
                    \DB::rollback();
                    return new \WP_Error('Subscription_Create_Error', $e->getMessage());
                }
            } catch (\MeekroDBException $e) {
                \DB::rollback();
                return new \WP_Error('Subscription_Create_Error', $e->getMessage());
            }
            \DB::commit();
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
                    Status=%s,
                    PaymentFrequency=%s,
                    SubscriptionDefinition_id=%i,
                    Total=%i
                WHERE 
                    id=%i",
                    $this->ParentID,
                    $this->StartDate,
                    $this->EndDate,
                    $this->Status,
                    $this->PaymentFrequency,
                    $this->SubscriptionDefinition_id,
                    $this->Total,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $Subscription = Subscription::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Subscription_Update_Error', $e->getMessage());
            }
            return $Subscription;
        }
        public static function ActivateSubscriptionByParentId($ParentId)
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE Subscription s
                        Left Join SubscriptionPayment sp on s.id = sp.Subscription_id
                    SET
                    s.Status='Active'
                WHERE 
                    s.ParentID=%i
                        and
                    sp.Status = 'Paid'
                        and
                    Date(now()) between sp.StartDate and sp.EndDate
                        and
                    s.Status <> 'Cancelled'",
                    $ParentId
                );

                $counter = VLPUtils::$db->affectedRows();

                // $Subscription = Subscription::Get($id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Subscription_Update_Error', $e->getMessage());
            }
            return true;
        }
        public function updateStatus($id)
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $Subscription = Subscription::Get($id);

            if ($this->Status == 'Cancelled' && $Subscription->PaymentFrequency == 'monthly') {
                $response = SubscriptionPayment::cancelMonthlyPayments($this->id);
            } else if ($this->Status == 'Cancelled' && $Subscription->PaymentFrequency == 'yearly') {
                $response = SubscriptionPayment::cancelYearlyPayments($this->id);
            }

            if (!is_wp_error($response)) {
                try {
                    VLPUtils::$db->query(
                        "UPDATE Subscription
                    SET
                    Status=%s
                WHERE 
                    id=%i",
                        $this->Status,
                        $id
                    );

                    $counter = VLPUtils::$db->affectedRows();

                    $Subscription = Subscription::Get($id);
                } catch (\MeekroDBException $e) {
                    return new \WP_Error('Subscription_Update_Error', $e->getMessage());
                }
                return $Subscription;
            } else {
                return $response;
            }
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
        public static function GetAllByParentId($parentid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $Subscriptions = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select * from Subscription Where ParentID = %i", $parentid);

                foreach ($results as $row) {
                    $Subscription = Subscription::populatefromRow($row);
                    $Subscriptions->add_item($Subscription);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Subscription_GetAll_Error', $e->getMessage());
            }
            return $Subscriptions;
        }
        public static function GetAllCurrentByParentId($parentid)
        // This is to get all subsctriptions with current payments, including cancelled subscriptions since they have their remaining payment time to use it.
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $Subscriptions = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query(
                    "select distinct S.* from Subscription S
                                                    Inner Join SubscriptionPayment SP
                                                        on S.id = SP.Subscription_id
                                                    Where 
                                                        S.ParentID = %i
                                                        and
                                                        Date(Now()) between Date(SP.StartDate) and Date(SP.EndDate)
                                                        and SP.Status = 'Paid'",
                    $parentid
                );
                foreach ($results as $row) {
                    $Subscription = Subscription::populatefromRow($row);
                    $Subscriptions->add_item($Subscription);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Subscription_GetAll_Error', $e->getMessage());
            }
            return $Subscriptions;
        }

        public static function GetAllActiveByParentId($parentid)
        // This is to get all active (current and not cancelled) subscriptions
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $Subscriptions = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query(
                    "select distinct S.* from Subscription S
                                                    Inner Join SubscriptionPayment SP
                                                        on S.id = SP.Subscription_id
                                                    Where 
                                                        S.ParentID = %i
                                                        and
                                                        Date(Now()) between Date(SP.StartDate) and Date(SP.EndDate)
                                                        and 
                                                        S.Status <> 'Cancelled'",
                    $parentid
                );
                foreach ($results as $row) {
                    $Subscription = Subscription::populatefromRow($row);
                    $Subscriptions->add_item($Subscription);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Subscription_GetAll_Error', $e->getMessage());
            }
            return $Subscriptions;
        }

        public static function calculateRemainingtime($subscription)
        {
            $now = time(); // or your date as well
            $subscriptionEndDate = strtotime($subscription->EndDate);
            $datediff = $subscriptionEndDate - $now;
            $daysDiffernece = ceil($datediff / (60 * 60 * 24));
            return $daysDiffernece;
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
            $Subscription->Status = $row['Status'];
            $Subscription->PaymentFrequency = $row['PaymentFrequency'];
            $Subscription->SubscriptionDefinition_id = $row['SubscriptionDefinition_id'];
            $Subscription->Total = $row['Total'];
            return $Subscription;
        }
    }
}
