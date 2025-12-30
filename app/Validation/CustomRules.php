<?php

namespace App\Validation;

class CustomRules
{
    public function password_conf(string $password, ?string &$error = null): bool
    {
        $hasUpper = preg_match('/[A-Z]/', $password);
        $hasLower = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSymbol = preg_match('/[^a-zA-Z0-9]/', $password);
        $hasInvalid = preg_match('/[!=<>\"\]]/', $password);

        if (
            strlen($password) >= 8 &&
            $hasUpper &&
            $hasLower &&
            $hasNumber &&
            $hasSymbol &&
            !$hasInvalid
        ) {
            return true;
        }

        $error = 'Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, simbol, dan tidak boleh mengandung karakter: ! = < > " ]';
        return false;
    }
}
