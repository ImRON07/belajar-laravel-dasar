<?php

namespace App\Services;

class HalloServiceIndonesia implements HalloServiceIndonesia
{
    public function hallo(string $name): string
    {
        return "Halo $name";
    }
}