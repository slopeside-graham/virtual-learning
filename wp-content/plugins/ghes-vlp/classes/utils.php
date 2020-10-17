<?php

namespace GHES\VLP {
    class Utils
    {
        public static $db;

        public static function AddVLPRole($UserId)
        {
            $userid = $UserId;
            if (isset($userid)) {
                $user = get_user_by('id', $userid);

                $vlprole = "VLP Parent";

                $roles = (array) $user->roles;
                if (!in_array("VLP Parent", $roles)) {
                    $user->add_role($vlprole);
                }
            } else {
                throw new \Exception("The parent doesn't have an ID");
            }
        }
    }
}
