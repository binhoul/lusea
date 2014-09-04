<?php
class Card{

    public static function find($arr = array()){
        global $db;

        if(empty($arr)){
            // random
            $st = $db->prepare("");

        }
        else if($arr['cn_name']){
            $st = $db->prepare("select * from cards where cn_name=:cn_name");
        }
        else {
            throw new Exception("no card!");
        }
        $st->execute($arr);
        // Returns an array of Category objects:
        return $st->fetchAll();

    }

}
