<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;
    use GHES\VLP\Subscription as VLPSubscription;

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
        private $_CancelledPayment_id;
        private $_PaymentDate;
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
        protected function CancelledPayment_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_CancelledPayment_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_CancelledPayment_id;
            }
        }
        protected function PaymentDate($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_PaymentDate = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_PaymentDate;
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
                'Payment_id' => $this->CancelledPayment_id,
                'PaymentDate' => $this->PaymentDate,
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
                    Status=%s
                WHERE 
                    id=%i",
                    $this->Status,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $SubscriptionPayment = SubscriptionPayment::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_Update_Error', $e->getMessage());
            }
            return $SubscriptionPayment;
        }

        public static function cancelMonthlyPayments($subscriptionId)
        {
            // TODO: Refund all months that are paid in advance. Do not refund current month.

            $subscriptionPayments = SubscriptionPayment::GetAllBySubscriptionId($subscriptionId);
            if ($subscriptionPayments->jsonSerialize()) {

                // First cancel anything that is unpaid for this subscription
                $UnpaidSubscriptionPayments = SubscriptionPayment::GetAllUnpaidBySubscriptionId($subscriptionId);
                if ($UnpaidSubscriptionPayments->jsonSerialize()) {
                    foreach ($UnpaidSubscriptionPayments->jsonSerialize() as $k => $UnpaidSubscriptionPayment) {
                        SubscriptionPayment::cancelUnpaidSubscriptionPaymentbyId($UnpaidSubscriptionPayment->id);
                    }
                }

                //Then refund/void anything that is paid for this subscription
                $PaidSubscriptionPayments = SubscriptionPayment::GetAllPaidBySubscriptionId($subscriptionId);

                $chargepaymentIds = array();  //Create the variable for the chargepaymentids as an array.

                foreach ($PaidSubscriptionPayments->jsonSerialize() as $k => $PaidSubscriptionPayment) {
                    $chargepaymentIds[] = $PaidSubscriptionPayment->Payment_id;
                }
                $uniquechargepaymentIds = array_unique($chargepaymentIds);

                $cancelledpaymentResults = new NestedSerializable();

                //Run the refund 1 time per chargepaymentid, that why we need the unique array first. Otherwise it would refund the same payment as many time as there are SubscriptionPayments with that payment id.
                foreach ($uniquechargepaymentIds as $chargepaymentId) {
                    if ($chargepaymentId != null) {
                        $chargePayment = Payment::Get($chargepaymentId);
                        // Run the cancellation for each charge payment
                        $cancelledpaymentResult = Payment::refund($chargePayment->Amount, $chargePayment);

                        // Get all SubscriptionPayments for charge payment id
                        $cancelledSubscriptionPayments = SubscriptionPayment::GetAllByPaymentId($chargepaymentId);

                        // Update each $cancelledSubscriptionPayments to Refunded, and insert the $cancelledpaymentResult->id
                        if (!is_wp_error($cancelledpaymentResult)) {
                            foreach ($cancelledSubscriptionPayments->jsonSerialize() as $k => $cancelledSubscriptionPayment) {
                                if ($cancelledpaymentResult->Type = 'Void') {
                                    SubscriptionPayment::refundPaidMonthlySubscriptionPaymentbyId($cancelledSubscriptionPayment->id, $cancelledpaymentResult->id);
                                } else {
                                    SubscriptionPayment::refundPaidFutureMonthlySubscriptionPaymentbyId($cancelledSubscriptionPayment->id, $cancelledpaymentResult->id);
                                }
                            }
                        } else {
                            return $cancelledpaymentResults;
                        }
                        $cancelledpaymentResults->add_item($cancelledpaymentResult);
                    }
                }
                Email::SendRefundEmail($cancelledpaymentResults);
                return $cancelledpaymentResults;
            } else {
                return new \Exception("Nothig was cancelled, something went wrong.");
            }


            return $subscriptionPayments;
        }

        public static function cancelYearlyPayments($subscriptionId)
        {
            $subscriptionPayments = SubscriptionPayment::GetAllBySubscriptionId($subscriptionId);
            if ($subscriptionPayments->jsonSerialize()) {
                foreach ($subscriptionPayments->jsonSerialize() as $k => $subscriptionPayment) {
                    if ($subscriptionPayment->Status == "Paid") {
                        $subscription = Subscription::Get($subscriptionId);
                        // Refund the reamining time
                        //Calculate reamining days
                        $refundDays = Subscription::calculateRemainingtime($subscription);
                        //Divide by 365
                        $refundPercent = $refundDays / 365;
                        //Multiply that number by the amount paid
                        $refundAmount = round($refundPercent * $subscription->Total, 2);

                        $paymentResult = Payment::refund($refundAmount, $subscriptionPayment);

                        if (!is_wp_error($paymentResult)) {
                            SubscriptionPayment::refundPaidYearlySubscriptionPayment($subscriptionId, $paymentResult->id);
                            return $paymentResult;
                        } else {
                            return $paymentResult;
                        }
                    }
                }
            }
        }

        public static function cancelUnpaidSubscriptionPaymentbyId($id)
        {
            // Cancel the SubscriptionPayment
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query(
                    "UPDATE SubscriptionPayment 
                        SET
                        Status='Cancelled'
                    WHERE 
                        id=%i
                            and
                        Status = 'Unpaid'",
                    $id
                );
                $counter = VLPUtils::$db->affectedRows();
                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection
                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        public static function refundPaidYearlySubscriptionPayment($subscriptionId, $paymentId)
        {
            // Cancel the SubscriptionPayment
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query(
                    "UPDATE SubscriptionPayment 
                        SET
                        Status='Refunded',
                        CancelledPayment_id = %i
                    WHERE 
                        Subscription_id=%i
                            and
                        Status = 'Paid'",
                    $subscriptionId,
                    $paymentId
                );
                $counter = VLPUtils::$db->affectedRows();
                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection
                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        public static function refundPaidMonthlySubscriptionPaymentbyId($id, $cancelledpaymentId)
        {
            // Cancel the SubscriptionPayment
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query(
                    "UPDATE SubscriptionPayment 
                        SET
                        Status='Refunded',
                        CancelledPayment_id = %i
                    WHERE 
                        id=%i
                            and
                        Status = 'Paid'",
                    $cancelledpaymentId,
                    $id
                );
                $counter = VLPUtils::$db->affectedRows();
                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection
                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        public static function refundPaidFutureMonthlySubscriptionPaymentbyId($id, $cancelledpaymentId)
        {
            // Cancel the SubscriptionPayment
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query(
                    "UPDATE SubscriptionPayment 
                        SET
                        Status='Refunded',
                        CancelledPayment_id = %i
                    WHERE 
                        id=%i
                            and
                        Status = 'Paid'
                            and 
                        Date(StartDate) > Date(Now())
                        ",
                    $cancelledpaymentId,
                    $id
                );
                $counter = VLPUtils::$db->affectedRows();
                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection
                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }


        public static function UpdateAllPendingByParentId($Parent_id, $paymentResultid)
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE SubscriptionPayment sp
                        Left Join Subscription s on sp.Subscription_id = s.id
                    SET
                    sp.Status='Paid',
                    sp.Payment_id=%i
                WHERE 
                    sp.Status='Pending'
                        and
                    s.ParentId=%i",
                    $paymentResultid,
                    $Parent_id
                );

                $counter = VLPUtils::$db->affectedRows();

                $SubscriptionPayment = SubscriptionPayment::GetAllByPaymentId($paymentResultid);
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

        public static function GetAllExceptUnpaidandCancelledBySubscriptionId($subscriptionId)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select sp.*, 
                p.DateCreated as PaymentDate
            from SubscriptionPayment sp
                Left Join Payment p on sp.Payment_id = p.id
            where 
            sp.Subscription_id = %i 
                and 
            sp.Status Not In ('Unpaid', 'Cancelled')", $subscriptionId);

                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        public static function GetAllPaidBySubscriptionId($subscriptionId)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select sp.*, 
                                                        p.DateCreated as PaymentDate
                                                    from SubscriptionPayment sp
                                                        Left Join Payment p on sp.Payment_id = p.id
                                                    where 
                                                    sp.Subscription_id = %i 
                                                        and 
                                                    sp.Status = 'Paid'", $subscriptionId);

                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        public static function GetAllUnpaidBySubscriptionId($subscriptionId)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select sp.*, 
                                                        p.DateCreated as PaymentDate
                                                    from SubscriptionPayment sp
                                                        Left Join Payment p on sp.Payment_id = p.id
                                                    where 
                                                    sp.Subscription_id = %i 
                                                        and 
                                                    sp.Status = 'Unpaid'", $subscriptionId);

                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        public static function GetAllPaidBySubscriptionIdandPaymentId($subscriptionId, $paymentid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query(
                    "SELECT sp.*, 
                                                        p.DateCreated as PaymentDate
                                                    from SubscriptionPayment sp
                                                        Left Join Payment p on sp.Payment_id = p.id
                                                    where 
                                                    sp.Subscription_id = %i 
                                                        and 
                                                    sp.Status = 'Paid' and sp.Payment_id =%i",
                    $subscriptionId,
                    $paymentid
                );

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
                                                            and Date(StartDate) <= Date(Now())
                                                            and Status Not In ('Paid', 'Cancelled')", $subscriptionId);

                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }
        public static function GetAllByPaymentId($paymentResultid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select * from SubscriptionPayment where Payment_id = %i", $paymentResultid);

                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        /**
         * Get all pending by parent id
         *
         * @param int $parentId is the id of a parent
         *
         * @return mixed|WP_Error|NestedSerializable
         */
        public static function GetAllPendingByParentId($parentId)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $PendingPayments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select sp.*,
                                                        s.ParentID as ParentId 
                                                    from SubscriptionPayment sp
                                                        Left Join Subscription s on sp.Subscription_id = s.id
                                                    where 
                                                        ParentId = %i 
                                                        and sp.Status = 'Pending'", $parentId);

                foreach ($results as $row) {
                    $PendingPayment = SubscriptionPayment::populatefromRow($row);
                    $PendingPayments->add_item($PendingPayment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAllPending_Error', $e->getMessage());
            }
            return $PendingPayments;
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
                                                            and Date(StartDate) > Date(Now())
                                                            and Status Not In ('Paid', 'Cancelled')", $subscriptionId);

                foreach ($results as $row) {
                    $SubscriptionPayment = SubscriptionPayment::populatefromRow($row);
                    $SubscriptionPayments->add_item($SubscriptionPayment);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionPayment_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionPayments;
        }

        public static function GetUpcomingPaidBySubscriptionId($subscriptionId)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionPayments = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select * from SubscriptionPayment 
                                                        where 
                                                            Subscription_id = %i
                                                            and Date(StartDate) > Date(Now())
                                                            and Status = 'Paid'", $subscriptionId);

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
            $SubscriptionPayment->PaymentDate = $row['PaymentDate'];
            return $SubscriptionPayment;
        }
    }
}
