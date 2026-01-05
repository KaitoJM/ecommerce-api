<?php
namespace App\Dtos;

use App\Models\User;

final class AuthResultDto
{
    public function __construct(
        public readonly User $user,
        public readonly string $token
    ) {}
}
