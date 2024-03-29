<?php

namespace GHES\VLP {

    use Exception;
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
        private $_GameboardTitle;
        private $_AgeGroup_id;
        private $_AgeGroupTitle;
        private $_Completed;
        private $_PercentComplete;
        private $_Status;
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

        protected function StartDate($value = null): \DateTime
        {
            // If value was provided, set the value
            if ($value) {
                if (strlen($value) > 10)
                    $this->_StartDate = \DateTime::createFromFormat('D M d Y H:i:s e+', $value);
                else
                    $this->_StartDate = \DateTime::createFromFormat('Y-m-d', $value);
                return $this->_StartDate;
            }
            // If no value was provided return the existing value
            else {
                return $this->_StartDate;
            }
        }

        protected function EndDate($value = null): \DateTime
        {
            // If value was provided, set the value
            if ($value) {
                if (strlen($value) > 10)
                    $this->_EndDate = \DateTime::createFromFormat('D M d Y H:i:s e+', $value);
                else
                    $this->_EndDate = \DateTime::createFromFormat('Y-m-d', $value);
                return $this->_EndDate;
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
        protected function GameboardTitle($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_GameboardTitle = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_GameboardTitle;
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
        protected function AgeGroupTitle($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_AgeGroupTitle = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_AgeGroupTitle;
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

        protected function Status($value = null)
        {
            // If value was provided, set the value
            if ($value) {
                $this->_Status = $value;
            }
            // If no value was provided return the existing value
            else {
                return $this->_Status;
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
                'GameboardTitle' => $this->GameboardTitle,
                'AgeGroup_id' => $this->AgeGroup_id,
                'AgeGroupTitle' => $this->AgeGroupTitle,
                'Completed' => $this->Completed,
                'PercentComplete' => $this->PercentComplete,
                'Status' => $this->Status,
                'DateCreated' => $this->DateCreated,
                'DateModified' => $this->DateModified,
            ];
        }

        // Status Values
        public static function Draft()
        {
            return 'Draft';
        }
        public static function Published()
        {
            return 'Published';
        }
        public static function Archived()
        {
            return 'Archived';
        }

        public function Create()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->insert('Theme', array(
                    'Title' => $this->Title,
                    'StartDate' => $this->StartDate,
                    'EndDate' => $this->EndDate,
                    'Gameboard_id' => $this->Gameboard_id,
                    'AgeGroup_id' => $this->AgeGroup_id,
                    'Status' => $this->Status
                ));
                $this->id = VLPUtils::$db->insertId();
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_Create_DatabaseError', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Theme_Create_Error', $e->getMessage());
            }
            return true;
        }

        public function Update()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query(
                    "UPDATE Theme 
                    SET
                    Title=%s, 
                    StartDate=%t, 
                    EndDate=%t,
                    Gameboard_id=%i,
                    AgeGroup_id=%i,
                    Status=%s
                WHERE 
                    id=%i",
                    $this->Title,
                    $this->StartDate,
                    $this->EndDate,
                    $this->Gameboard_id,
                    $this->AgeGroup_id,
                    $this->Status,
                    $this->id
                );

                $counter = VLPUtils::$db->affectedRows();

                $theme = Theme::Get($this->id);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_Update_Database_Error', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Theme_Update_Error', $e->getMessage());
            }
            return $theme;
        }

        public function Delete()
        {

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {

                VLPUtils::$db->query("Delete from Theme WHERE id=%d", $this->id);
                $counter = VLPUtils::$db->affectedRows();
            } catch (\MeekroDBException $e) {
                echo $e->getMessage();
                return new \WP_Error('Theme_Delete_Error', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Theme_Delete_Error', $e->getMessage());
            }
            return true;
        }

        public static function Get($thisid)
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {
                if (ghes_vlp_base::UserCanEdit()) {
                    $SQL = "select t.*, 
                            gb.Title as GameboardTitle, ag.Name as AgeGroupTitle
                        from Theme t
                            Inner Join Gameboard gb on t.Gameboard_id = gb.id
                            Inner Join AgeGroup ag on t.Agegroup_id = ag.id
                        where t.id = %i";
                    $row = VLPUtils::$db->queryFirstRow($SQL, $thisid, Theme::Published());
                } else {
                    $SQL = "select t.*, 
                            gb.Title as GameboardTitle, ag.Name as AgeGroupTitle
                        from Theme t
                            Inner Join Gameboard gb on t.Gameboard_id = gb.id
                            Inner Join AgeGroup ag on t.Agegroup_id = ag.id
                        where
                            t.id = %i
                            and t.Status = %s
                        ";
                    $row = VLPUtils::$db->queryFirstRow($SQL, $thisid, Theme::Published());
                }

                $theme = Theme::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_Get_Error', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Theme_Get_Error', $e->getMessage());
            }
            return $theme;
        }

        public static function GetbyDate($date)
        {
            // This function supports published Themes only..

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {
                if (isset($_COOKIE['VLPSelectedChild'])) {
                    $child_id = $_COOKIE['VLPSelectedChild'];
                    $row = VLPUtils::$db->queryFirstRow("select t.*, cts.Completed, cts.PercentComplete from Theme t
                                                                    Left Join Child_Theme_Status cts on t.id = cts.Theme_id
                                                                where
                                                                 %? between t.StartDate and t.EndDate
                                                                 and (cts.Child_id = %i or isnull(cts.Child_id ))
                                                                 and t.Status=%s", $date, $child_id, Theme::Published());
                } else {
                    $row = VLPUtils::$db->queryFirstRow("select * from Theme where %t between StartDate and EndDate and t.Status=%s", $date, Theme::Published());
                }
                if (isset($row)) {
                    $theme = Theme::populatefromRow($row);
                } else {
                    return null;
                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_GetbyDate_Database_Error', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Theme_GetbyDate_Error', $e->getMessage());
            }
            return $theme;
        }

        public static function GetbyDateandAgeGroup($date, $agegroupid)
        {
            // This function supports published Themes only..

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;


            try {
                if (isset($_COOKIE['VLPSelectedChild'])) {
                    // Get any theme between the dates provided, UNION that with the any theme published before the date provided, order by StartDate desc so that we get the most recent
                    $SQL = 'select t.*, 
                                cts.Completed, 
                                cts.PercentComplete 
                            from Theme t
                                Left Join Child_Theme_Status cts on t.id = cts.Theme_id and cts.Child_id = %i
                            where
                                %t between t.StartDate and t.EndDate
                                and t.AgeGroup_id = %i
                                and t.Status=%s
                            UNION
                            select t2.*,
                                cts2.Completed, 
                                cts2.PercentComplete 
                            from Theme t2
                                Left Join Child_Theme_Status cts2 on t2.id = cts2.Theme_id and cts2.Child_id = %i
                            where
                                %t > t2.StartDate
                                and t2.AgeGroup_id = %i
                                and t2.Status=%s
                            Order By StartDate desc;';
                    $child_id = $_COOKIE['VLPSelectedChild'];
                    $row = VLPUtils::$db->queryFirstRow($SQL, $child_id, $date, $agegroupid, Theme::Published(), $child_id, $date, $agegroupid, Theme::Published());
                } else if (isset($_COOKIE['VLPAgeGroupId']) && !isset($_COOKIE['VLPSelectedChild'])) {
                    $SQL = "select t.* 
                            from
                                Theme t
                            where 
                                %t between t.StartDate and t.EndDate
                                and t.AgeGroup_id = %i
                                and t.Status=%s            
                            UNION
                            select t2.* 
                            from Theme t2
                            where
                                %t > t2.StartDate
                                and t2.AgeGroup_id = %i
                                and t2.Status=%s
                            Order By StartDate desc;";
                    $row = VLPUtils::$db->queryFirstRow($SQL, $date, $agegroupid, Theme::Published(), $date, $agegroupid, Theme::Published());
                } else {
                    $row = VLPUtils::$db->queryFirstRow("select * from Theme where %t between StartDate and EndDate and Status=%s", $date, Theme::Published());
                }
                if (isset($row)) {
                    $theme = Theme::populatefromRow($row);
                } else {
                    return null;
                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_GetbyDateandAgeGroup_Database_Error', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Theme_GetbyDateandAgeGroup_Error', $e->getMessage());
            }
            return $theme;
        }

        public static function GetbyDateandAgeGroupNext($themeid, $agegroupid)
        {
            // This function supports published Themes only..

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {
                if (isset($_COOKIE['VLPSelectedChild'])) {
                    $child_id = $_COOKIE['VLPSelectedChild'];
                    $SQL = 'select t.*, 
                        cts.Completed, 
                        cts.PercentComplete 
                    from Theme t
                        Left Join Child_Theme_Status cts on t.id = cts.Theme_id and cts.Child_id = %i
                    where
                        t.StartDate > (Select StartDate from Theme where id=%i)
                        and t.AgeGroup_id = %i
                        and t.Status=%s
                    Order By StartDate asc;';
                    $row = VLPUtils::$db->queryFirstRow($SQL, $child_id, $themeid, $agegroupid, Theme::Published());
                } else if (isset($agegroupid) && !isset($_COOKIE['VLPSelectedChild'])) {
                    $SQL = 'select t.* 
                    from Theme t
                    where
                        t.StartDate > (Select StartDate from Theme where id=%i)
                        and t.AgeGroup_id = %i
                        and t.Status=%s
                    Order By StartDate asc;';
                    $row = VLPUtils::$db->queryFirstRow($SQL, $themeid, $agegroupid, Theme::Published());
                } else {
                    $SQL = 'select t.* 
                    from Theme t
                    where
                        t.StartDate > (Select StartDate from Theme where id=%i)
                        and t.Status=%s
                    Order By StartDate asc;';
                    $row = VLPUtils::$db->queryFirstRow($SQL, $themeid, $agegroupid, Theme::Published());
                }

                if (isset($row)) {
                    $theme = Theme::populatefromRow($row);
                } else {
                    return null;
                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_GetbyDateandAgeGroupNext_Database_Error', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Theme_GetbyDateandAgeGroupNext_Error', $e->getMessage());
            }
            return $theme;
        }

        public static function GetbyDateandAgeGroupPrevious($themeid, $agegroupid)
        {
            // This function supports published Themes only..

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {
                if (isset($_COOKIE['VLPSelectedChild'])) {
                    $child_id = $_COOKIE['VLPSelectedChild'];
                    $SQL = 'select t.*, 
                        cts.Completed, 
                        cts.PercentComplete 
                    from Theme t
                        Left Join Child_Theme_Status cts on t.id = cts.Theme_id and cts.Child_id = %i
                    where
                        t.StartDate < (Select StartDate from Theme where id=%i)
                        and t.AgeGroup_id = %i
                        and t.Status=%s
                    Order By StartDate desc;';
                    $row = VLPUtils::$db->queryFirstRow($SQL, $child_id, $themeid, $agegroupid, Theme::Published());
                } else if (isset($agegroupid) && !isset($_COOKIE['VLPSelectedChild'])) {
                    $SQL = 'select t.* 
                    from Theme t
                    where
                        t.StartDate < (Select StartDate from Theme where id=%i)
                        and t.AgeGroup_id = %i
                        and t.Status=%s
                    Order By StartDate desc;';
                    $row = VLPUtils::$db->queryFirstRow($SQL, $themeid, $agegroupid, Theme::Published());
                } else {
                    $SQL = 'select t.* 
                    from Theme t
                    where
                        t.StartDate < (Select StartDate from Theme where id=%i)
                        and t.Status=%s
                    Order By StartDate desc;';
                    $row = VLPUtils::$db->queryFirstRow($SQL, $themeid, $agegroupid, Theme::Published());
                }

                if (isset($row)) {
                    $theme = Theme::populatefromRow($row);
                } else {
                    return null;
                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_GetbyDateandAgeGroupPrevious_Database_Error', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Theme_GetbyDateandAgeGroupPrevious_Error', $e->getMessage());
            }
            return $theme;
        }

        public static function GetbyAgeGroup($agegroupid)
        {
            // This function supports published Themes only..

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            try {
                $row = VLPUtils::$db->queryFirstRow("select * from Theme where AgeGroup_id = %i and Status=%s", $agegroupid, Theme::Published());
                $theme = Theme::populatefromRow($row);
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_GetbyAgeGroup_Database_Error', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Theme_GetbyAgeGroup_Error', $e->getMessage());
            }
            return $theme;
        }

        public static function GetAllbyAgeGroup($agegroupid)
        {
            // This function supports published Themes only..

            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $themes = new NestedSerializable();

            try {
                $child_id = $_COOKIE['VLPSelectedChild'];
                $results = VLPUtils::$db->query("
                select t.*, 
                    gb.Title as GameboardTitle, 
                    ag.Name as AgeGroupTitle, 
                    cts.Completed, 
                    cts.PercentComplete
                from Theme t
                    Left Join Child_Theme_Status cts on t.id = cts.Theme_id and cts.Child_id = %i
                    Inner Join Gameboard gb on t.Gameboard_id = gb.id
                    Inner Join AgeGroup ag on t.Agegroup_id = ag.id
                Where 
                    AgeGroup_id = %i
                    and Status=%s", $child_id, $agegroupid, Theme::Published());

                foreach ($results as $row) {
                    $theme = Theme::populatefromRow($row);
                    $themes->add_item($theme);  // Add the theme to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_GetAllbyAgeGroup_Database_Error', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Theme_GetAllbyAgeGroup_Error', $e->getMessage());
            }
            return $themes;
        }


        public static function GetAll()
        {
            VLPUtils::$db->error_handler = false; // since we're catching errors, don't need error handler
            VLPUtils::$db->throw_exception_on_error = true;

            $themes = new NestedSerializable();

            try {
                if (ghes_vlp_base::UserCanEdit()) {
                    $SQL = "select t.*, 
                                gb.Title as GameboardTitle, ag.Name as AgeGroupTitle
                            from Theme t
                                Inner Join Gameboard gb on t.Gameboard_id = gb.id
                                Inner Join AgeGroup ag on t.Agegroup_id = ag.id";
                    $results = VLPUtils::$db->query($SQL);
                } else {
                    $SQL = "select t.*, 
                            gb.Title as GameboardTitle, ag.Name as AgeGroupTitle
                        from Theme t
                            Inner Join Gameboard gb on t.Gameboard_id = gb.id
                            Inner Join AgeGroup ag on t.Agegroup_id = ag.id
                        WHERE
                            t.Status=%s";
                    $results = VLPUtils::$db->query($SQL, Theme::Published());
                }

                foreach ($results as $row) {
                    $theme = Theme::populatefromRow($row);
                    $themes->add_item($theme);  // Add the theme to the collection

                }
            } catch (\MeekroDBException $e) {
                return new \WP_Error('Theme_GetAll_Error', $e->getMessage());
            } catch (Exception $e) {
                return new \WP_Error('Theme_GetAll_Error', $e->getMessage());
            }
            return $themes;
        }

        // Helper function to populate a theme from a MeekroDB Row
        public static function populatefromRow($row): ?Theme
        {
            if ($row == null)
                return null;

            $theme = new Theme();
            $theme->id = $row['id'];
            $theme->Title = $row['Title'];
            $theme->StartDate = $row['StartDate'];
            $theme->EndDate = $row['EndDate'];
            $theme->Gameboard_id = $row['Gameboard_id'];
            $theme->GameboardTitle = $row['GameboardTitle'];
            $theme->AgeGroup_id = $row['AgeGroup_id'];
            $theme->AgeGroupTitle = $row['AgeGroupTitle'];
            $theme->Completed = $row['Completed'];
            $theme->PercentComplete = $row['PercentComplete'];
            $theme->Status = $row['Status'];
            return $theme;
        }
    }
}
