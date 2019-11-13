<?php

namespace BusinessLogic\Caching;


class KeyBuilder {
    static function buildKey(array $parts) {
        return 'hitman2maps:' . implode(':', $parts);
    }
}