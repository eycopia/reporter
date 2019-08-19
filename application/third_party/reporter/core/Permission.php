<?php

/**
 * Name: Permissions.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 * Description:
 */
class Permission
{
    static $READER    = 1;
    static $WRITER    = 2;
    static $DELETED   = 3;
    static $ADMIN     = 4;
    static $DEVELOPER = 5;

    public static function checkPermission($userPermission, $requiredPermission){
        if( !(is_numeric($userPermission) && !is_numeric($requiredPermission))) {
            throw new Exception("No se puede verificar los permisos, tipos de datos enviados son incorrectos");
        }

        $permission = self::${$requiredPermission};
        return ($userPermission >= $permission) ? TRUE : FALSE;
    }
}