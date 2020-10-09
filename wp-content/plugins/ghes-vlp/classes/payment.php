<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;
    /**
     * Class Payment
     */
    class Payment extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Name;
        private $_MonthlyAmount;
        private $_YearlyAmount;
        private $_PaymentFrequency;
        private $_AllowAutoRenew;
        private $_StartDate;
        private $_EndDate;
        private $_Hidden;
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

        protected function Name($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Name = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Name;
            }
        }

        protected function MonthlyAmount($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_MonthlyAmount = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_MonthlyAmount;
            }
        }


        protected function YearlyAmount($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_YearlyAmount = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_YearlyAmount;
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
        protected function AllowAutoRenew($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_AllowAutoRenew = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_AllowAutoRenew;
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
        protected function Hidden($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Hidden = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Hidden;
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
                'Name' => $this->Name,
                'MonthlyAmount' => $this->MonthlyAmount,
                'YearlyAmount' => $this->YearlyAmount,
                'PaymentFrequency' => $this->PaymentFrequency,
                'AllowAutoRenew' => $this->AllowAutoRenew,
                'StartDate' => $this->StartDate,
                'EndDate' => $this->EndDate,
                'Hidden' => $this->Hidden,
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified,
            ];
        }

        public function Create()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('Payment', array(
                    'id' => $this->id,
                    'Name' => $this->Name,
                    'MonthlyAmount' => $this->MonthlyAmount,
                    'YearlyAmount' => $this->YearlyAmount,
                    'PaymentFrequency' => $this->PaymentFrequency,
                    'AllowAutoRenew' => $this->AllowAutoRenew,
                    'StartDate' => $this->StartDate,
                    'EndDate' => $this->EndDate,
                    'Hidden' => $this->Hidden
                ));
                $this->id = VLPUtils::$db->insertId();

            } catch (\MeekroDBException $e) {
                return new \WP_Error('Payment_Create_Error', $e->getMessage());
            }
            return true;
        }

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
            $Payment->Name = $row['Name'];
            $Payment->MonthlyAmount = $row['MonthlyAmount'];
            $Payment->YearlyAmount = $row['YearlyAmount'];
            $Payment->PaymentFrequency = $row['PaymentFrequency'];
            $Payment->AllowAutoRenew = $row['AllowAutoRenew'];
            $Payment->StartDate = $row['StartDate'];
            $Payment->EndDate = $row['EndDate'];
            $Payment->Hidden = $row['Hidden'];
            return $Payment;
        }
    }
}
