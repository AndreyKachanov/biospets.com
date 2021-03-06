<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

/**
 * Eloquent class to describe the analyses_categories table
 * automatically generated by ModelGenerator.php
 *
 * @property int $id
 * @property string $title
 * @property int $is_active
 * @property int|null $parent_id
 * @property string $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property int|null $created_by_user_id
 * @property int|null $updated_by_user_id
 * @property \Carbon\Carbon|null $deleted_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Analyse[] $rAnalyses
 * @property-read \App\Models\User|null $rUserCreated
 * @property-read \App\Models\User|null $rUserUpdated
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AnalyseCategory onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereCreatedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereUpdatedByUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AnalyseCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AnalyseCategory withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnalyseCategory[] $children
 * @property-read \App\Models\AnalyseCategory|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyseCategory whereDescription($value)
 */
class AnalyseCategory extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes, Sluggable;

    /**
     * @var string
     */
    protected $table = 'analyses_categories';

    /**
     * @return array
     */

    public function getDates()
    {
        return ['deleted_at'];
    }

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'is_active', 'parent_id', 'slug', 'meta_title', 'meta_description',
        'meta_keywords', 'deleted_at', 'created_by_user_id', 'updated_by_user_id', 'description'
    ];

    /**
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
            ]
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rUserCreated()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rUserUpdated()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rAnalyses()
    {
        return $this->hasMany(Analyse::class, 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
