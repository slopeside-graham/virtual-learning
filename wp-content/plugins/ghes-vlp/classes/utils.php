<?php

namespace GHES\VLP {

    use GHES\VLP\ghes_vlp_base as VLPBase;
    use GHES\VLP\Subscription;
    use GHES\Parents;

    class Utils extends ghes_vlp_base
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

        public static function RemoveVLPRole($UserId)
        {
            $userid = $UserId;
            if (isset($userid)) {
                $user = get_user_by('id', $userid);

                $vlprole = "VLP Parent";

                $roles = (array) $user->roles;
                if (in_array("VLP Parent", $roles)) {
                    $user->remove_role($vlprole);
                }
            } else {
                throw new \Exception("The parent doesn't have an ID");
            }
        }

        public static function CheckLoggedInParent()
        {
            $user = wp_get_current_user();
            if (!ghes_vlp_base::UserIsParent()) {
                $registrationpage = get_permalink(esc_attr(get_option('registration_welcome_url')));
                header("Location: $registrationpage");
            }
        }
        public static function CheckLoggedInVLPParent()
        {
            if (!ghes_vlp_base::UserIsVLPParent()) {
                $profilepage = get_permalink(esc_attr(get_option('registration_welcome_url')));
                header("Location: $profilepage");
            }
        }
        public static function CheckSubscriptionStatus() {
            $userid = \GHES\Parents::UserID();
            $parentid = \GHES\Parents::GetByUserID(get_current_user_id())->id;
            $currentSubscriptions = Subscription::GetAllCurrentByParentId($parentid);

            if($currentSubscriptions->jsonSerialize()) {
                Utils::AddVLPRole($userid);
            } else {
                Utils::RemoveVLPRole($userid);
            }
        }

    }
}
