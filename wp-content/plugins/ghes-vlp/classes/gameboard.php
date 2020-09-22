<?php

namespace GHES\VLP {

    use Exception;
    use GHES\VLP\Utils as VLPUtils;

    /**
     * Class Gameboard
     */
    class Gameboard extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Title;
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
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified,
            ];
        }

        public function Create()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('Gameboard', array(
                    'Title' => $this->Title
                ));
                $this->id = VLPUtils::$db->insertId();
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Gameboard_Create_Error', $e->getMessage());
            }
            return true;
        }

        public function Update()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE Gameboard 
                    SET
                    Title=%s
                WHERE 
                    id=%i",
                    $this->Title,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $gameboard = Gameboard::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Gameboard_Update_Error', $e->getMessage());
            }
            return $gameboard;
        }

        public function Delete()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from Gameboard WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('Gameboard_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Gameboard where id = %i", $thisid);

                $gameboard = Gameboard::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Gameboard_Get_Error', $e->getMessage());
            }
            return $gameboard;
        }

        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $gameboards = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select * from Gameboard");

                foreach ($results as $row) {
                    $gameboard = Gameboard::populatefromRow($row);
                    $gameboards->add_item($gameboard);  // Add the theme to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Gameboard_GetAll_Error', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Gameboard_GetAll_Error', $e->getMessage());
            }
            return $gameboards;
        }

        // Helper function to populate a theme from a MeekroDB Row
        public static function populatefromRow($row): ?Gameboard
        {
            if ($row == null)
            return null;
            
            $gameboard = new Gameboard();
            $gameboard->id = $row['id'];
            $gameboard->Title = $row['Title'];
            return $gameboard;
        }
    }
}
