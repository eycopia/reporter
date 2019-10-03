<?php
namespace application\third_party\reporter\models;

/**
 *
 * @author JorgeCopia
 *        
 */
class Menu_m
{

    /**
     */
    public function __construct()
    {
        
    }
    
    
    public function getTypes(){
        return ['sql' => 'SQL', 'json' => 'JSON'];
    }
    
    
}

