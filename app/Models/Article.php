<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'rubric_id',
        'created_by'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(Rubric::class);
    }

    // Récupérer les objets média complets
    public function getMediaObjects()
    {
        if (empty($this->media)) {
            return collect();
        }
        
        return Media::whereIn('id', $this->media)->get();
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function creator()
{
    return $this->belongsTo(Admin::class, 'created_by'); // created_by = id de l'admin
}

    protected $casts = [
        'created_at' => 'datetime:c',
        'updated_at' => 'datetime:c',
    ];
}
