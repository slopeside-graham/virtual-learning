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
        private $_MediaURL;
        private $_ResourceLink;
        private $_Link;
        private $_Lesson_id;
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

        protected function MediaURL($value = null)
        {
            if (isset($this->_Media_id)) {
                return wp_get_attachment_url($this->_Media_id);
            } else {
                return "";
            }
        }

        protected function ResourceLink($value = null)
        {
            if (isset($this->_Media_id)) {
                return wp_get_attachment_url($this->_Media_id);
            } else if (isset($this->_Link)) {
                return $this->_Link;
            } else {
                return "";
            }
        }
        protected function Lesson_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Lesson_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Lesson_id;
            }
        }
        protected function Link($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Link = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Link;
            }
        }
        protected function Completed($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Completed = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Completed;
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
                'Title' => $this->Title,
                'Media_id' => $this->Media_id,
                'MediaURL' => $this->MediaURL,
                'ResourceLink' => $this->ResourceLink,
                'Lesson_id' => $this->Lesson_id,
                'Link' => $this->Link,
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

                VLPUtils::$db->insert('Resource', array(
                    'Title' => $this->Title,
                    'Media_id' => $this->Media_id,
                    'Lesson_id' => $this->Lesson_id,
                    'Link' => $this->Link,
                ));
                $this->id = VLPUtils::$db->insertId();
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_Create_Error', $e->getMessage());
            }
            return true;
        }

        public function Update()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE Resource 
                    SET
                    Title=%s, 
                    Media_id=%s,
                    Lesson_id=%i,
                    Link=%i
                WHERE 
                    id=%i",
                    $this->Title,
                    $this->Media_id,
                    $this->Lesson_id,
                    $this->Link,
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

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from Resource WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                if ($e->getCode() == '1451') {
                    return new \WP_Error('Resource_Delete_Error', "You Cannot delete this resource, it is in use with current lessons.");
                } else {
                    return new \WP_Error('Resource_Delete_Error', $e->getMessage());
                }
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

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
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

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
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $resources = new NestedSerializable();

            try {
                if (isset($_COOKIE['VLPSelectedChild'])) {
                    $child_id = $_COOKIE['VLPSelectedChild'];
                    $results = VLPUtils::$db->query("
                        select r.*, 
                            crs.Completed, crs.PercentComplete 
                        from Resource r
                            Left Join Child_Resource_Status crs on r.id = crs.Resource_id and crs.Child_id = %i
                        where Lesson_id = %i", $child_id, $lessonid);
                } else {
                    $results = VLPUtils::$db->query("select * from Resource where Lesson_id = %i", $lessonid);
                }

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
        public static function populatefromRow($row): ?Resource
        {
            if ($row == null)
            return null;
            
            $resource = new Resource();
            $resource->id = $row['id'];
            $resource->Title = $row['Title'];
            $resource->Media_id = $row['Media_id'];
            $resource->Lesson_id = $row['Lesson_id'];
            $resource->Link = $row['Link'];
            $resource->Completed = $row['Completed'];
            $resource->PercentComplete = $row['PercentComplete'];
            return $resource;
        }
    }
}
