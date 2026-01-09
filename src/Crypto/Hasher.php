<?php

namespace Belur\Crypto;

interface Hasher {
    public function hash(string $value): string;
    public function verify(string $input, string $hash): bool;
}
