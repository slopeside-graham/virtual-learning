<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;

    /**
     * Class Resource
     */
    class ChildThemeStatus extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Child_id;
        private $_Theme_id;
        private $_Completed;
        private $_PercentComplete;
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

        protected function Theme_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Theme_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Theme_id;
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
        protected function PercentComplete($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_PercentComplete = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_PercentComplete;
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
                'Theme_id' => $this->Theme_id,
                'Child_id' => $this->Child_id,
                'Completed' => $this->Completed,
                'PercentComplete' => $this->PercentComplete,
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified,
            ];
        }

        public function Create()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('Child_Theme_Status', array(
                    'Theme_id' => $this->Theme_id,
                    'Child_id' => $this->Child_id,
                    'Completed' => $this->Completed,
                    'PercentComplete' => $this->PercentComplete,
                ));
                $this->id = VLPUtils::$db->insertId();

            } catch (\MeekroDBException $e) {
                if ($e->getCode() == '1062')  { // Ignore this duplicate entry error and continue
                    $this->Update();
                } else {
                    return new \WP_Error('Child_Theme_Status_Create_Error', $e->getMessage());
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
                    "UPDATE Child_Theme_Status
                    SET
                    Theme_id=%i, 
                    Child_id=%i, 
                    Completed=%i,
                    PercentComplete=%i
                WHERE 
                    Theme_id=%i and Child_id=%i",
                    $this->Theme_id,
                    $this->Child_id,
                    $this->Completed,
                    $this->PercentComplete,
                    $this->Theme_id,
                    $this->Child_id
                );

                $counter = VLPUtils::$db->affectedRows();

                $childthemestatus = ChildThemeStatus::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Child_Theme_Status_Update_Error', $e->getMessage());
            }
            return $childthemestatus;
        }

        public function Delete()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from Child_Theme_Status WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('Child_Theme_Status_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Child_Theme_Status where id = %i", $thisid);
                $childthemestatus = ChildThemeStatus::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_Get_Error', $e->getMessage());
            }
            return $childthemestatus;
        }


        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $childthemestatuss = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select * from Child_Theme_Status");

                foreach ($results as $row) {
                    $childthemestatus = ChildThemeStatus::populatefromRow($row);
                    $childthemestatuss->add_item($childthemestatus);  // Add the resource to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_GetAll_Error', $e->getMessage());
            }
            return $childthemestatus;
        }

        // Helper function to populate a resource from a MeekroDB Row
        public static function populatefromRow($row): ?ChildThemeStatus
        {
            if ($row == null)
            return null;
            
            $childthemestatus = new ChildThemeStatus();
            $childthemestatus->id = $row['id'];
            $childthemestatus->Theme_id = $row['Theme_id'];
            $childthemestatus->Child_id = $row['Child_id'];
            $childthemestatus->Completed = $row['Completed'];
            $childthemestatus->PercentComplete = $row['PercentComplete'];
            return $childthemestatus;
        }
    }
}
