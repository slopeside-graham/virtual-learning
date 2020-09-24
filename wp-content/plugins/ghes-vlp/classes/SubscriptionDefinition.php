<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;
    /**
     * Class SubscriptionDefinition
     */
    class SubscriptionDefinition extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Name;
        private $_MonthlyAmount;
        private $_YearlyAmount;
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

                VLPUtils::$db->insert('SubscriptionDefinition', array(
                    'id' => $this->id,
                    'Name' => $this->Name,
                    'MonthlyAmount' => $this->MonthlyAmount,
                    'YearlyAmount' => $this->YearlyAmount,
                    'AllowAutoRenew' => $this->AllowAutoRenew,
                    'StartDate' => $this->StartDate,
                    'EndDate' => $this->EndDate,
                    'Hidden' => $this->Hidden
                ));
                $this->id = VLPUtils::$db->insertId();

            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionDefinition_Create_Error', $e->getMessage());
            }
            return true;
        }

        public function Update()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE SubscriptionDefinition 
                    SET
                    Name=%s, 
                    MonthlyAmount=%i, 
                    YearlyAmount=%i,
                    AllowAutoRenew=%i,
                    StartDate=%i,
                    EndDate=%i,
                    Hidden=%i
                WHERE 
                    id=%i",
                    $this->MonthlyAmount,
                    $this->YearlyAmount,
                    $this->AllowAutoRenew,
                    $this->StartDate,
                    $this->EndDate,
                    $this->Hidden,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $SubscriptionDefinition = SubscriptionDefinition::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionDefinition_Update_Error', $e->getMessage());
            }
            return $SubscriptionDefinition;
        }

        public function Delete()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from SubscriptionDefinition WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('SubscriptionDefinition_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from SubscriptionDefinition where id = %i", $thisid);
                $SubscriptionDefinition = SubscriptionDefinition::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionDefinition_Get_Error', $e->getMessage());
            }
            return $SubscriptionDefinition;
        }        
        

        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $SubscriptionDefinitions = new NestedSerializable();

            try {
                    $results = VLPUtils::$db->query("select * from SubscriptionDefinition order by Name ASC");

                foreach ($results as $row) {
                    $SubscriptionDefinition = SubscriptionDefinition::populatefromRow($row);
                    $SubscriptionDefinitions->add_item($SubscriptionDefinition);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('SubscriptionDefinition_GetAll_Error', $e->getMessage());
            }
            return $SubscriptionDefinitions;
        }

        // Helper function to populate a lesson from a MeekroDB Row
        public static function populatefromRow($row): ?SubscriptionDefinition
        {
            if ($row == null)
            return null;
            
            $SubscriptionDefinition = new SubscriptionDefinition();
            $SubscriptionDefinition->id = $row['id'];
            $SubscriptionDefinition->Name = $row['Name'];
            $SubscriptionDefinition->MonthlyAmount = $row['MonthlyAmount'];
            $SubscriptionDefinition->YearlyAmount = $row['YearlyAmount'];
            $SubscriptionDefinition->AllowAutoRenew = $row['AllowAutoRenew'];
            $SubscriptionDefinition->StartDate = $row['StartDate'];
            $SubscriptionDefinition->EndDate = $row['EndDate'];
            $SubscriptionDefinition->Hidden = $row['Hidden'];
            return $SubscriptionDefinition;
        }
    }
}
