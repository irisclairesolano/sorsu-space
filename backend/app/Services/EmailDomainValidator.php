<?php

namespace App\Services;

class EmailDomainValidator
{
    public static function allows(string $email): bool
    {
        $domain = strtolower(substr(strrchr($email, '@') ?: '', 1));
        $allowed = strtolower(config('sorsu.allowed_email_domain'));

        return $domain === $allowed;
    }
}
