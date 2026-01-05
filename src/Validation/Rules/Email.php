<?php

namespace Belur\Validation\Rules;

class Email implements ValidationRule {
    
    public function message(): string {
        return "The field must be a valid email address.";
    }

    public function isValid(string $field, array $data): bool {
        $email = strtolower(trim($data[$field]));

        $split = explode("@", $email);

        if (count($split) != 2) {
            return false;
        }   

        [$username, $domain] = $split;

        $split = explode(".", $domain);

        if (count($split) != 2) {
            return false;
        }  

        [$label, $topLevelDomain] = $split;

        return strlen($username) > 0 
        && strlen($label) > 0 
        && strlen($topLevelDomain) > 0;
    }
}