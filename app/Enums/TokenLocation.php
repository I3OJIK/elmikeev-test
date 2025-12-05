<?php

namespace App\Enums;

enum TokenLocation: string
{
    case QUERY = 'query';
    case HEADER = 'header';
    case BODY = 'body';
    
    public function label(): string
    {
        return match($this) {
            self::QUERY => 'Query Parameter',
            self::HEADER => 'HTTP Header',
            self::BODY => 'Body',
        };
    }
}
