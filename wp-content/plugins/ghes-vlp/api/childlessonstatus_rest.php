<?php

namespace GHES\VLP {

    /**
     * Add rest api endpoint for resources
     */

    use GHES\VLP\ChildLessonStatus;

    /**
     * Class ChildLessonStatus_Rest
     */
    class ChildLessonStatus_Rest extends \WP_REST_Controller
    {
        /**
         * The namespace.
         *
         * @var string
         */
        protected $namespace;

        /**
         * Rest base for the current object.
         *
         * @var string
         */
        protected $rest_base;

        /**
         * ChildLessonStatus_Rest constructor.
         */
        public function __construct()
        {

            $this->namespace = 'ghes-vlp/v1';
            $this->rest_base = 'childlessonstatus';
        }

        /**
         * Alias for GET transport method.
         *
         * @since 4.4.0
         * @var string
         */
        const READABLE = 'GET';

        /**
         * Alias for POST transport method.
         *
         * @since 4.4.0
         * @var string
         */
        const CREATABLE = 'POST';

        /**
         * Alias for POST, PUT, PATCH transport methods together.
         *
         * @since 4.4.0
         * @var string
         */
        const EDITABLE = 'PUT, PATCH';

        /**
         * Alias for DELETE transport method.
         *
         * @since 4.4.0
         * @var string
         */
        const DELETABLE = 'DELETE';

        /**
         * Alias for GET, POST, PUT, PATCH & DELETE transport methods together.
         *
         * @since 4.4.0
         * @var string
         */
        const ALLMETHODS = 'GET, POST, PUT, PATCH, DELETE';


        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes()
        {

            register_rest_route($this->namespace, '/' . $this->rest_base, array(

                array(
                    'methods'             => ChildLessonStatus_Rest::READABLE,
                    'callback'            => array($this, 'get_item'),
                    'permission_callback' => array($this, 'get_item_permissions_check'),
                ),
                array(
                    'methods'         => ChildLessonStatus_Rest::EDITABLE,
                    'callback'        => array($this, 'update_item'),
                    'permission_callback' => array($this, 'update_item_permissions_check'),
                    'args'            => $this->get_endpoint_args_for_item_schema(false),
                ),
                array(
                    'methods'         => ChildLessonStatus_Rest::CREATABLE,
                    'callback'        => array($this, 'create_item'),
                    'permission_callback' => array($this, 'create_item_permissions_check'),
                    'args'            => $this->get_endpoint_args_for_item_schema(true),
                ),
                array(
                    'methods'         => ChildLessonStatus_Rest::DELETABLE,
                    'callback'        => array($this, 'delete_item'),
                    'permission_callback' => array($this, 'delete_item_permissions_check'),
                    'args'            => $this->get_endpoint_args_for_item_schema(true),
                ),
                'schema' => null,
            ));
        }

        /**
         * Check permissions for the read.
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return bool|WP_Error
         */
        public function get_item_permissions_check($request)
        {
            if (is_user_logged_in()) {
                return true;
            } else
                return new \WP_Error('rest_forbidden', esc_html__('You cannot get this child resource status.'), array('status' => $this->authorization_status_code()));
        }

        /**
         * Check permissions for the update
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return bool|WP_Error
         */
        public function update_item_permissions_check($request)
        {
            if (is_user_logged_in() && current_user_can('vlp_manage_entries')) {
                return true;
            } else
                return new \WP_Error('rest_forbidden', esc_html__('You cannot update this child resource status.'), array('status' => $this->authorization_status_code()));
        }

        /**
         * Check permissions for the create
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return bool|WP_Error
         */
        public function create_item_permissions_check($request)
        {
            if (is_user_logged_in()) {
                return true;
            } else
                return new \WP_Error('rest_forbidden', esc_html__('You cannot create this child resource status.'), array('status' => $this->authorization_status_code()));
        }

        /**
         * Check permissions for the delete
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return bool|WP_Error
         */
        public function delete_item_permissions_check($request)
        {
            if (is_user_logged_in() && current_user_can('vlp_manage_entries')) {
                return true;
            } else
                return new \WP_Error('rest_forbidden', esc_html__('You cannot delete this child resource status.'), array('status' => $this->authorization_status_code()));
        }

        /**
         * Get the resource list.
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return mixed|WP_REST_Response
         */
        public function get_item($request)
        {
            if ($request['id'] != '') {
                // Call static function Get (use :: to reference static function)
                $childlessonstatus = ChildLessonStatus::Get($request['id']);
            } else {
                // Call static function Get (use :: to reference static function)
                $childlessonstatus = ChildLessonStatus::GetAll();
            }

            if (!is_wp_error($childlessonstatus))
                return rest_ensure_response($childlessonstatus);
            else {
                $error_string = $childlessonstatus->get_error_message();
                return new \WP_Error('ChildLessonStatus_Get_Error', 'An error occured: ' . $error_string, array('status' => 400));
            }
        }

        /**
         * Update ChildLessonStatus
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return mixed|WP_Error|WP_REST_Response
         */
        public function update_item($request)
        {
            $childlessonstatus = ChildLessonStatus::populatefromRow($request);
            $success = $childlessonstatus->Update();

            $childlessonstatus = ChildLessonStatus::Get($childlessonstatus->id);

            if (!is_wp_error($success))
                return rest_ensure_response($childlessonstatus);
            else {
                $error_string = $success->get_error_message();
                return new \WP_Error('ChildLessonStatus_Update_Error', 'An error occured: ' . $error_string, array('status' => 400));
            }
        }

        /**
         * Delete resource
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return mixed|WP_Error|WP_REST_Response
         */
        public function delete_item($request)
        {
            $childlessonstatus = ChildLessonStatus::Get($request['id']);
            $success = $childlessonstatus->Delete();

            if (!is_wp_error($success))
                return rest_ensure_response($childlessonstatus);
            else {
                $error_string = $success->get_error_message();
                return new \WP_Error('ChildLessonStatus_Delete_Error', 'An error occured: ' . $error_string, array('status' => 400));
            }
        }

        /**
         * Create ChildLessonStatus
         *
         * @param WP_REST_Request $request get data from request.
         *
         * @return mixed|WP_Error|WP_REST_Response
         */
        public function create_item($request)
        {

            $childlessonstatus = ChildLessonStatus::GetByLessonidandChildid($request["Lesson_id"], $request["Child_id"]);
            if ($childlessonstatus) {
                $childlessonstatus->updateobjectfromRow($request);
                $childlessonstatus->Update();
                $success = true; // if we got here, it was successful
            } else {
                $childlessonstatus = ChildLessonStatus::populatefromRow($request);
                $success = $childlessonstatus->Create();
            }
            if ($success == true) {
                $childlessonstatus = ChildLessonStatus::Get($childlessonstatus->id);
            }

            if (!is_wp_error($success))
                return rest_ensure_response($childlessonstatus);
            else {
                $error_string = $success->get_error_message();
                return new \WP_Error('ChildLessonStatus_Create_Error', 'An error occured: ' . $error_string, array('status' => 400));
            }
        }


        /**
         * Sets up the proper HTTP status code for authorization.
         *
         * @return int
         */
        public function authorization_status_code()
        {

            $status = 401;

            if (is_user_logged_in()) {
                $status = 403;
            }

            return $status;
        }
    }
}
