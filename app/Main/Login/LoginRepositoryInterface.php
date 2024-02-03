<?php
namespace App\Main\Login;

interface LoginRepositoryInterface
{
    public function login($request);
    public function logout($request): void;
}
