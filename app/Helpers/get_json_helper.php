<?php
function get_json(){
    $data = json_decode(file_get_contents('php://input'), true);
    return $data;
}