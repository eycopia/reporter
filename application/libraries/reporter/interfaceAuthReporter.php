<?php
/**
 * Name: interfaceAuthReporter.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 * Description:
 */
interface interfaceAuthReporter{

    /**
     * Redirect to login page
     * @return HttpRequest
     */
    public function login();

    /**
     * @return redirect to login page
     */
    public function logout();

    /**
     * Check user is login
     * @return boolean
     */
    public function isLogin();

    /**
     * Check if current user is admin
     * @return boolean
     */
    public function isAdmin();

    /**
     * Get the id for the current user
     * @return integer
     */
    public function get_user_id();
}