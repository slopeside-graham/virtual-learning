<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;
    /**
     * Class Resource
     */
    class Resource extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Title;
        private $_Media_id;
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

        protected function Media_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Media_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Media_id;
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
                'Media_id' => $this->Media_id,
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified,
            ];
        }

        public function Create()
        {

            \DB::$error_handler = false; // since we're catching errors, don't need error handler
            \DB::$throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('Resource', array(
                    'Title' => $this->Title,
                    'Media_id' => $this->Media_id,
                ));
                $this->id = VLPUtils::$db->insertId();

            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_Create_Error', $e->getMessage());
            }
            return true;
        }

        public function Update()
        {

            \DB::$error_handler = false; // since we're catching errors, don't need error handler
            \DB::$throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE Resource 
                    SET
                    Title=%s, 
                    Media_id=%s, 
                WHERE 
                    id=%i",
                    $this->Title,
                    $this->Media_id,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $resource = Resource::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_Update_Error', $e->getMessage());
            }
            return $resource;
        }

        public function Delete()
        {

            \DB::$error_handler = false; // since we're catching errors, don't need error handler
            \DB::$throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from Resource WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('Resource_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            \DB::$error_handler = false; // since we're catching errors, don't need error handler
            \DB::$throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Resource where id = %i", $thisid);
                $resource = Resource::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_Get_Error', $e->getMessage());
            }
            return $resource;
        }
        

        public static function GetAll()
        {
            \DB::$error_handler = false; // since we're catching errors, don't need error handler
            \DB::$throw_exception_on_error = true;

            $resources = new NestedSerializable();

            try {
                    $results = VLPUtils::$db->query("select * from Resource");

                foreach ($results as $row) {
                    $resource = Resource::populatefromRow($row);
                    $resources->add_item($resource);  // Add the resource to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_GetAll_Error', $e->getMessage());
            }
            return $resources;
        }

        public static function GetAllbyLessonId($lessonid)
        {
            \DB::$error_handler = false; // since we're catching errors, don't need error handler
            \DB::$throw_exception_on_error = true;

            $resources = new NestedSerializable();

            try {
                    $results = VLPUtils::$db->query("select * from Resource where Lesson_id = %i", $lessonid);

                foreach ($results as $row) {
                    $resource = Resource::populatefromRow($row);
                    $resources->add_item($resource);  // Add the resource to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_GetAll_Error', $e->getMessage());
            }
            return $resources;
        }

        // Helper function to populate a resource from a MeekroDB Row
        public static function populatefromRow($row): Resource
        {
            $resource = new Resource();
            $resource->id = $row['id'];
            $resource->Title = $row['Title'];
            $resource->Media_id = $row['Media_id'];
            return $resource;
        }
    }
}
