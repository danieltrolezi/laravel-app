<?php

namespace App\Models;

use App\Enums\Frequency;
use App\Enums\Period;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema()]
class UserSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'platforms',
        'genres',
        'period',
        'frequency'
    ];

    #[OA\Property(property: 'id', type: 'integer')]
    #[OA\Property(property: 'user_id', type: 'integer')]
    #[OA\Property(
        property: 'platforms',
        type: 'array',
        items: new OA\Items(
            type: 'string',
            enum: 'App\Enums\Platform'
        ),
    )]
    #[OA\Property(
        property: 'genres',
        type: 'array',
        items: new OA\Items(
            type: 'string',
            enum: 'App\Enums\RawgGenre'
        ),
    )]
    #[OA\Property(
        property: 'period',
        type: 'string',
        enum: 'App\Enums\Period'
    )]
    #[OA\Property(
        property: 'frequency',
        type: 'string',
        enum: 'App\Enums\Frequency'
    )]
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'platforms' => 'array',
            'genres'    => 'array',
            'period'    => Period::class,
            'frequency' => Frequency::class
        ];
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
