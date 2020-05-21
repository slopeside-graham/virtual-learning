<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;
    /**
     * Class Theme
     */
    class Theme extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Title;
        private $_StartDate;
        private $_EndDate;
        private $_Gameboard_id;
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

        protected function Title($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Title = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Title;
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
        protected function Gameboard_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Gameboard_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Gameboard_id;
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
                'Title' => $this->Title,
                'StartDate' => $this->StartDate,
                'EndDate' => $this->EndDate,
                'Gameboard_id' => $this->Gameboard_id,
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified,
            ];
        }

        public function Create()
        {

            \DB::$error_handler = false; // since we're catching errors, don't need error handler
            \DB::$throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('Theme', array(
                    'Title' => $this->Title,
                    'StartDate' => $this->StartDate,
                    'EndDate' => $this->EndDate,
                    'Gameboard_id' => $this->Gameboard_id,
                ));
                $this->id = VLPUtils::$db->insertId();

            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_Create_Error', $e->getMessage());
            }
            return true;
        }

        public function Update()
        {

            \DB::$error_handler = false; // since we're catching errors, don't need error handler
            \DB::$throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE Theme 
                    SET
                    Title=%s, 
                    StartDate=%s, 
                    EndDate=%s,
                    Gameboard_id=%s,
                WHERE 
                    id=%i",
                    $this->Title,
                    $this->StartDate,
                    $this->EndDate,
                    $this->Gameboard_id,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $theme = Theme::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_Update_Error', $e->getMessage());
            }
            return $theme;
        }

        public function Delete()
        {

            \DB::$error_handler = false; // since we're catching errors, don't need error handler
            \DB::$throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from Theme WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('Theme_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            \DB::$error_handler = false; // since we're catching errors, don't need error handler
            \DB::$throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Theme where id = %i", $thisid);
                $theme = Theme::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_Get_Error', $e->getMessage());
            }
            return $theme;
        }
        

        public static function GetAll()
        {
            \DB::$error_handler = false; // since we're catching errors, don't need error handler
            \DB::$throw_exception_on_error = true;

            $themes = new NestedSerializable();

            try {
                    $results = VLPUtils::$db->query("select * from Theme"); //Need to chage out all database refences to this.
                    
                foreach ($results as $row) {
                    $theme = Theme::populatefromRow($row);
                    $themes->add_item($theme);  // Add the theme to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_GetAll_Error', $e->getMessage());
            }
            return $themes;
        }

        // Helper function to populate a theme from a MeekroDB Row
        public static function populatefromRow($row): Theme
        {
            $theme = new Theme();
            $theme->id = $row['id'];
            $theme->Title = $row['Title'];
            $theme->StartDate = $row['StartDate'];
            $theme->EndDate = $row['EndDate'];
            $theme->Gameboard_id = $row['Gameboard_id'];
            return $theme;
        }
    }
}
