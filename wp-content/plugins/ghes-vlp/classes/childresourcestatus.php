<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;

    /**
     * Class Resource
     */
    class ChildResourceStatus extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Resource_id;
        private $_Child_id;
        private $_Completed;
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

        protected function Resource_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Resource_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Resource_id;
            }
        }

        protected function Child_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Child_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Child_id;
            }
        }
        protected function Completed($value = null): int
        {
            // If value was provided, set the value
            if ($value) {
                if ($value == 'true' || $value == '1') {
                    $value = 1;
                } else {
                    $value = 0;
                }
                $this->_Completed = $value;
                return $value;
            }
            // If no value was provided return the existing value
            else {
                if ($this->_Completed)
                    return $this->_Completed;
                else
                    return 0;
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
                'Resource_id' => $this->Resource_id,
                'Child_id' => $this->Child_id,
                'Completed' => $this->Completed,
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified,
            ];
        }

        public function Create()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('Child_Resource_Status', array(
                    'Resource_id' => $this->Resource_id,
                    'Child_id' => $this->Child_id,
                    'Completed' => $this->Completed,
                ));
                $this->id = VLPUtils::$db->insertId();
                // Get a reference to Lesson, update LessonStats
                //TODO Update Lesson Status from Resource Stats: ChildLessonStatus::UpdateStatusbyResourceid($this->Resource_id, $this->Child_id);

            } catch (\MeekroDBException $e) {
                if ($e->getCode() == '1062')  { // Ignore this duplicate entry error and continue
                    return false;
                } else {
                    return new \WP_Error('Child_Resource_Status_Create_Error', $e->getMessage());
                }
            }
            return true;
        }

        public function Update()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE Child_Resource_Status
                    SET
                    Resource_id=%s, 
                    Child_id=%s, 
                    Completed=%i
                WHERE 
                    id=%i",
                    $this->Resource_id,
                    $this->Child_id,
                    $this->Completed,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $childresourcestatus = ChildResourceStatus::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Child_Resource_Status_Update_Error', $e->getMessage());
            }
            return $childresourcestatus;
        }

        public function Delete()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from Child_Resource_Status WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('Resource_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Child_Resource_Status where id = %i", $thisid);
                $childresourcestatus = ChildResourceStatus::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_Get_Error', $e->getMessage());
            }
            return $childresourcestatus;
        }


        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $childresourcestatuss = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select * from Child_Resource_Status");

                foreach ($results as $row) {
                    $childresourcestatus = ChildResourceStatus::populatefromRow($row);
                    $childresourcestatuss->add_item($childresourcestatus);  // Add the resource to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_GetAll_Error', $e->getMessage());
            }
            return $childresourcestatus;
        }

        // Helper function to populate a resource from a MeekroDB Row
        public static function populatefromRow($row): ChildResourceStatus
        {
            $childresourcestatus = new ChildResourceStatus();
            $childresourcestatus->id = $row['id'];
            $childresourcestatus->Resource_id = $row['Resource_id'];
            $childresourcestatus->Child_id = $row['Child_id'];
            $childresourcestatus->Completed = $row['Completed'];
            return $childresourcestatus;
        }
    }
}
