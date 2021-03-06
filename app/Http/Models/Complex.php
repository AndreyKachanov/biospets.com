<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
/**
 * Eloquent class to describe the complexes table
 * 
 * automatically generated by ModelGenerator.php
 *
 * @property int $id
 * @property int $is_active
 * @property string $title
 * @property string $code
 * @property float $price
 * @property float $discount
 * @property string $slug
 * @property int $term
 * @property string $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property int|null $created_by_user_id
 * @property int|null $updated_by_user_id
 * @property int $is_complex
 * @property string $first_letter
 * @property \Carbon\Carbon|null $deleted_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $rAnalyses
 * @property-read \App\Models\User|null $rUserCreated
 * @property-read \App\Models\User|null $rUserUpdated
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereCreatedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereFirstLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereIsComplex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex whereUpdatedByUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Complex findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Complex onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Complex withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Complex withoutTrashed()
 */
class Complex extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes, Sluggable;

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $table = 'complexes';

    public function getDates()
    {
        return ['deleted_at'];
    }

    protected $fillable = [
        'is_active', 'title', 'code', 'price', 'discount', 'slug', 'meta_title',
        'meta_description', 'meta_keywords', 'deleted_at', 'created_by_user_id', 'updated_by_user_id',
        'first_letter', 'term'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
            ]
        ];
    }

    public function rUserCreated()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    public function rUserUpdated()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id', 'id');
    }

    public function rAnalyses()
    {
        return $this->belongsToMany(Analyse::class, 'complexes_analyses', 'complex_id', 'analyse_id');
    }

    /**
     * @param $value
     */
    public function setFirstLetterAttribute($value)
    {
        $s = mb_substr($value, 0,1);

        if (preg_match( '/[а-яА-ЯЁё]/u', $s)) {
            $this->attributes['first_letter'] = mb_strtoupper($s);

        } elseif(preg_match( '/[a-zA-Z]/', $s)) {
            $this->attributes['first_letter'] = 'A-Z';

        } elseif(preg_match( '/[0-9]/', $s)) {
            $this->attributes['first_letter'] = '0-9';

        }
    }

    /**
     * @param $letter
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function fetchAllComplexes($letter)
    {
        $complexes = Complex::where('is_active', 1)
            ->orderBy('title')
            ->paginate(config('app.pagination_default_value'));

        if ($letter != 'all') {
            $complexes = Complex::where('is_active', 1)
                ->where('first_letter', $letter)
                ->orderBy('title')
                ->paginate(config('app.pagination_default_value'));
        }

        return $complexes;
    }
}
