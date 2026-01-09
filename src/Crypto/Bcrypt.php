<?php

namespace Belur\Crypto;

class Bcrypt implements Hasher {
    public function hash(string $value): string {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    public function verify(string $input, string $hash): bool {
        return password_verify($input, $hash);
    }
}
