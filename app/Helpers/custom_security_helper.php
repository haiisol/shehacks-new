<?php

function sanitize_header($str)
{
    if (!$str) return '';
    
    $str = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $str);
    $str = strip_tags($str);
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function secure_input($str)
{
    if (!$str) return '';
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}
