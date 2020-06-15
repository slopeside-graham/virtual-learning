<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;
    /**
     * Class AgeGroup
     */
    class AgeGroup extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Name;
        private $_AgeStart;
        private $_AgeEnd;
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

        protected function AgeStart($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_AgeStart = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_AgeStart;
            }
        }


        protected function AgeEnd($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_AgeEnd = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_AgeEnd;
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
                'Name' => $this->Name,
                'AgeStart' => $this->AgeStart,
                'AgeEnd' => $this->AgeEnd,
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified,
            ];
        }

        public function Create()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('AgeGroup', array(
                    'Name' => $this->Name,
                    'AgeStart' => $this->AgeStart,
                    'AgeEnd' => $this->AgeEnd,
                ));
                $this->id = VLPUtils::$db->insertId();

            } catch (\MeekroDBException $e) {
                return new \WP_Error('AgeGroup_Create_Error', $e->getMessage());
            }
            return true;
        }

        public function Update()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE AgeGroup 
                    SET
                    Name=%s, 
                    AgeStart=%s, 
                    AgeEnd=%s, 
                WHERE 
                    id=%i",
                    $this->Name,
                    $this->AgeStart,
                    $this->AgeEnd,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $ageGroup = AgeGroup::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('AgeGroup_Update_Error', $e->getMessage());
            }
            return $ageGroup;
        }

        public function Delete()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from AgeGroup WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('AgeGroup_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from AgeGroup where id = %i", $thisid);
                $ageGroup = AgeGroup::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('AgeGroup_Get_Error', $e->getMessage());
            }
            return $ageGroup;
        }

        public static function GetByAgeMonths($agemonths)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from AgeGroup where %i between AgeStart and AgeEnd", $agemonths);
                $ageGroup = AgeGroup::populatefromRow($row);

            } catch (\MeekroDBException $e) {
                return new \WP_Error('AgeGroup_Get_Error', $e->getMessage());
            }
            return $ageGroup;
        }
        
        

        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $ageGroups = new NestedSerializable();

            try {
                    $results = VLPUtils::$db->query("select * from AgeGroup");

                foreach ($results as $row) {
                    $ageGroup = AgeGroup::populatefromRow($row);
                    $ageGroups->add_item($ageGroup);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('AgeGroup_GetAll_Error', $e->getMessage());
            }
            return $ageGroups;
        }

        // Helper function to populate a lesson from a MeekroDB Row
        public static function populatefromRow($row): AgeGroup
        {
            $ageGroup = new AgeGroup();
            $ageGroup->id = $row['id'];
            $ageGroup->Name = $row['Name'];
            $ageGroup->AgeStart = $row['AgeStart'];
            $ageGroup->AgeEnd = $row['AgeEnd'];
            return $ageGroup;
        }
    }
}
