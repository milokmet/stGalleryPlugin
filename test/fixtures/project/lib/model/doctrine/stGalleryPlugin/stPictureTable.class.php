<?php


class stPictureTable extends PluginstPictureTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('stPicture');
    }
}