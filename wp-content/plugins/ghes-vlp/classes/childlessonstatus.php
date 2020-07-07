<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;

    /**
     * Class Resource
     */
    class ChildLessonStatus extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Child_id;
        private $_Lesson_id;
        private $_VideoCompleted;
        private $_VideoPercentComplete;
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
        protected function VideoCompleted($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                if ($value == 'true' || $value == '1') {
                    $value = 1;
                } else {
                    $value = 0;
                }
                $this->_VideoCompleted = $value;
                return $value;
            }
            // If no value was provided return the existing value
            else {
                if ($this->_VideoCompleted)
                    return $this->_VideoCompleted;
                else
                    return 0;
            }
        }
        protected function VideoPercentComplete($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_VideoPercentComplete = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_VideoPercentComplete;
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
                'Lesson_id' => $this->Lesson_id,
                'Child_id' => $this->Child_id,
                'VideoCompleted' => $this->VideoCompleted,
                'VideoPercentComplete' => $this->VideoPercentComplete,
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

                VLPUtils::$db->insert('Child_Lesson_Status', array(
                    'Lesson_id' => $this->Lesson_id,
                    'Child_id' => $this->Child_id,
                    'VideoCompleted' => $this->VideoCompleted,
                    'VideoPercentComplete' => $this->VideoPercentComplete,
                    'Completed' => $this->Completed,
                    'PercentComplete' => $this->PercentComplete,
                ));
                $this->id = VLPUtils::$db->insertId();
            } catch (\MeekroDBException $e) {
                if ($e->getCode() == '1062') { // Ignore this duplicate entry error and continue
                    $this->Update();
                } else {
                    return new \WP_Error('Child_Lesson_Status_Create_Error', $e->getMessage());
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
                    "UPDATE Child_Lesson_Status
                    SET
                    Lesson_id=%i, 
                    Child_id=%i, 
                    VideoCompleted=%i,
                    VideoPercentComplete=%i,
                    Completed=%i,
                    PercentComplete=%i
                WHERE 
                    Lesson_id=%i and Child_id=%i",
                    $this->Lesson_id,
                    $this->Child_id,
                    $this->VideoCompleted,
                    $this->VideoPercentComplete,
                    $this->Completed,
                    $this->PercentComplete,
                    $this->Lesson_id,
                    $this->Child_id
                );

                $counter = VLPUtils::$db->affectedRows();

                $childlessonstatus = ChildLessonStatus::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Child_Lesson_Status_Update_Error', $e->getMessage());
            }
            return $childlessonstatus;
        }

        public function Delete()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from Child_Lesson_Status WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('Child_Lesson_Status_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Child_Lesson_Status where id = %i", $thisid);
                $childlessonstatus = ChildLessonStatus::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_Get_Error', $e->getMessage());
            }
            return $childlessonstatus;
        }

        public static function GetByLessonid($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Child_Lesson_Status where Lesson_id = %i", $thisid);
                $childlessonstatus = ChildLessonStatus::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_Get_Error', $e->getMessage());
            }
            return $childlessonstatus;
        }

        public static function GetByLessonidandChildid($lessonid, $childid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Child_Lesson_Status where Lesson_id = %i and Child_id = %i", $lessonid, $childid);
                $childlessonstatus = ChildLessonStatus::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_Get_Error', $e->getMessage());
            }
            return $childlessonstatus;
        }


        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $childlessonstatuss = new NestedSerializable();

            try {
                $results = VLPUtils::$db->query("select * from Child_Lesson_Status");

                foreach ($results as $row) {
                    $childlessonstatus = ChildLessonStatus::populatefromRow($row);
                    $childlessonstatuss->add_item($childlessonstatus);  // Add the resource to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Resource_GetAll_Error', $e->getMessage());
            }
            return $childlessonstatus;
        }

        public function updateobjectfromRow($row)
        {
            if (isset($row['Lesson_id']))
                $this->Lesson_id = $row['Lesson_id'];
            if (isset($row['Child_id']))
                $this->Child_id = $row['Child_id'];
            if (isset($row['VideoCompleted']))
                $this->VideoCompleted = $row['VideoCompleted'];
            if (isset($row['VideoPercentComplete']))
                $this->VideoPercentComplete = $row['VideoPercentComplete'];
            if (isset($row['Completed']))
                $this->Completed = $row['Completed'];
            if (isset($row['PercentComplete']))
                $this->PercentComplete = $row['PercentComplete'];
            return;
        }

        // Helper function to populate a resource from a MeekroDB Row
        public static function populatefromRow($row): ?ChildLessonStatus
        {
            if ($row == null)
            return null;
            
            $childlessonstatus = new ChildLessonStatus();
            $childlessonstatus->id = $row['id'];
            $childlessonstatus->Lesson_id = $row['Lesson_id'];
            $childlessonstatus->Child_id = $row['Child_id'];
            $childlessonstatus->VideoCompleted = $row['VideoCompleted'];
            $childlessonstatus->VideoPercentComplete = $row['VideoPercentComplete'];
            $childlessonstatus->Completed = $row['Completed'];
            $childlessonstatus->PercentComplete = $row['PercentComplete'];
            return $childlessonstatus;
        }
    }
}
