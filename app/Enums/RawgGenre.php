<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'string')]
enum RawgGenre: string
{
    use BaseEnum;

    case Racing = 'racing';
    case Shooter = 'shooter';
    case Adventure = 'adventure';
    case Action = 'action';
    case RPG = 'rpg';
    case Fighting = 'fighting';
    case Puzzle = 'puzzle';
    case Strategy = 'strategy';
    case Arcade = 'arcade';
    case Simulation = 'simulation';
    case Sports = 'sports';
    case Card = 'card';
    case Family = 'family';
    case BoardGames = 'board-games';
    case Educational = 'educational';
    case Casual = 'casual';
    case Indie = 'indie';
    case MassivelyMultiplayer = 'massively-multiplayer';
    case Platformer = 'platformer';
}
