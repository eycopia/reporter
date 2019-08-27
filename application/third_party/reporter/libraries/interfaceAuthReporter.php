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
     * @return array [success=>BOOLEAN, msg=>STRING, userid => INT]
     */
    public function login();

    /**
     * @return HttpRequest to login page
     */
    public function logout();


}
