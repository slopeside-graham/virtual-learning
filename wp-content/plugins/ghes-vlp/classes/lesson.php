<?php

namespace GHES\VLP {

    use GHES\VLP\Utils as VLPUtils;
    /**
     * Class Lesson
     */
    class Lesson extends ghes_vlp_base implements \JsonSerializable
    {
        private $_id;
        private $_Title;
        private $_Type;
        private $_MainContent;
        private $_VideoURL;
        private $_Image_id;
        private $_Theme_id;
        private $_AgeGroup_id;
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

        protected function Type($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Type = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Type;
            }
        }


        protected function MainContent($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_MainContent = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_MainContent;
            }
        }

        protected function VideoURL($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_VideoURL = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_VideoURL;
            }
        }
        protected function Image_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Image_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Image_id;
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
        protected function AgeGroup_id($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_AgeGroup_id = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_AgeGroup_id;
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
                'Type' => $this->Type,
                'MainContent' => $this->MainContent,
                'VideoURL' => $this->VideoURL,
                'Image_id' => $this->Image_id,
                'Theme_id' => $this->Theme_id,
                'AgeGroup_id' => $this->AgeGroup_id,
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

                VLPUtils::$db->insert('Lesson', array(
                    'Title' => $this->Title,
                    'Type' => $this->Type,
                    'MainContent' => $this->MainContent,
                    'VideoURL' => $this->VideoURL,
                    'Image_id' => $this->Image_id,
                    'Theme_id' => $this->Theme_id,
                    'AgeGroup_id' => $this->AgeGroup_id
                ));
                $this->id = VLPUtils::$db->insertId();

            } catch (\MeekroDBException $e) {
                return new \WP_Error('Lesson_Create_Error', $e->getMessage());
            }
            return true;
        }

        public function Update()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE Lesson 
                    SET
                    Title=%s, 
                    Type=%s, 
                    MainContent=%s, 
                    VideoURL=%s,
                    Image_id=%i,
                    Theme_id=%i,
                    AgeGroup_id=%i
                WHERE 
                    id=%i",
                    $this->Title,
                    $this->Type,
                    $this->MainContent,
                    $this->VideoURL,
                    $this->Image_id,
                    $this->Theme_id,
                    $this->AgeGroup_id,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $lesson = Lesson::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Lesson_Update_Error', $e->getMessage());
            }
            return $lesson;
        }

        public function Delete()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from Lesson WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('Lesson_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                $row = VLPUtils::$db->queryFirstRow("select * from Lesson where id = %i", $thisid);
                $lesson = Lesson::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Lesson_Get_Error', $e->getMessage());
            }
            return $lesson;
        }

        public static function GetAllbyThemeId($themeid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $lessons = new NestedSerializable();

            try {

                    if (isset($_COOKIE['VLPSelectedChild'])) {
                        $child_id = $_COOKIE['VLPSelectedChild'];
                        $results = VLPUtils::$db->query("select l.*, cls.Completed, cls.PercentComplete from Lesson l
                                                        Left Join Child_Lesson_Status cls on l.id = cls.Lesson_id
                                                        where Theme_id = %i
                                                        and (cls.Child_id = %i or isnull(cls.Child_id ))", $themeid, $child_id);
                    } else {
                        $results = VLPUtils::$db->query("select * from Lesson where Theme_id = %i", $themeid);
                    }

                foreach ($results as $row) {
                    $lesson = Lesson::populatefromRow($row);
                    $lessons->add_item($lesson);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Lesson_GetAll_Error', $e->getMessage());
            }
            return $lessons;
        }

        public static function GetAllbyThemeIdAndAgeGroup($themeid, $agegroupid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $lessons = new NestedSerializable();

            try {
                if (isset($_COOKIE['VLPSelectedChild'])) {
                    $child_id = $_COOKIE['VLPSelectedChild'];
                    $results = VLPUtils::$db->query("select l.*, cls.Completed, cls.PercentComplete from Lesson l
                                                    Left Join Child_Lesson_Status cls on l.id = cls.Lesson_id
                                                    where Theme_id = %i and AgeGroup_id = %i
                                                    and (cls.Child_id = %i or isnull(cls.Child_id ))", $themeid, $agegroupid, $child_id);
                } else {
                    $results = VLPUtils::$db->query("select * from Lesson where Theme_id = %i and AgeGroup_id = %i", $themeid, $agegroupid);
                }

                foreach ($results as $row) {
                    $lesson = Lesson::populatefromRow($row);
                    $lessons->add_item($lesson);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Lesson_GetAll_Error', $e->getMessage());
            }
            return $lessons;
        }
        

        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $lessons = new NestedSerializable();

            try {
                    $results = VLPUtils::$db->query("select * from Lesson");

                foreach ($results as $row) {
                    $lesson = Lesson::populatefromRow($row);
                    $lessons->add_item($lesson);  // Add the lesson to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Lesson_GetAll_Error', $e->getMessage());
            }
            return $lessons;
        }

        // Helper function to populate a lesson from a MeekroDB Row
        public static function populatefromRow($row): Lesson
        {
            $lesson = new Lesson();
            $lesson->id = $row['id'];
            $lesson->Title = $row['Title'];
            $lesson->Type = $row['Type'];
            $lesson->MainContent = $row['MainContent'];
            $lesson->VideoURL = $row['VideoURL'];
            $lesson->Image_id = $row['Image_id'];
            $lesson->Theme_id = $row['Theme_id'];
            $lesson->AgeGroup_id = $row['AgeGroup_id'];
            $lesson->Completed = $row['Completed'];
            $lesson->PercentComplete = $row['PercentComplete'];
            return $lesson;
        }
    }
}
