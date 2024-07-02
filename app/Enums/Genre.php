<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;

enum Genre: int
{
    use BaseEnum;

    case Racing = 1;
    case Shooter = 2;
    case Adventure = 3;
    case Action = 4;
    case RPG = 5;
    case Fighting = 6;
    case Puzzle = 7;
    case Strategy = 10;
    case Arcade = 11;
    case Simulation = 14;
    case Sports = 15;
    case Card = 17;
    case Family = 19;
    case BoardGames = 28;
    case Educational = 34;
    case Casual = 40;
    case Indie = 51;
    case MassivelyMultiplayer = 59;
    case Platformer = 83;
}
