<?php
// app/Enums/Identity/UserStatus.php 

namespace App\Enums\Identity;

/**
 * UserStatus
 */
enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BLOCKED = 'blocked';
    case BANNED = 'banned';
    case CLOSED = 'closed';
}
