<?php
/**
 * interfaceAuthReporter
 * Define los métodos de autenticación que utiliza reporter
 * @package Reporter\Libraries
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
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
     * Get the id for the current user
     * @return integer
     */
    public function get_user_id();
}
