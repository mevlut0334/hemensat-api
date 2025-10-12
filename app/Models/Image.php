<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageable_type',
        'imageable_id',
        'path',
        'filename',
        'original_name',
        'size',
        'mime_type',
        'width',
        'height',
        'order',
        'is_primary',
        'status'
    ];

    protected $casts = [
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'order' => 'integer',
        'is_primary' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // === RELATIONSHIPS ===

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    // === SCOPES ===

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }

    public function scopeForListing($query, $listingId, $listingType)
    {
        return $query->where('imageable_id', $listingId)
            ->where('imageable_type', $listingType)
            ->active()
            ->ordered();
    }

    // === ACCESSORS ===

    /**
     * Full URL for the image
     */
    public function getUrlAttribute(): ?string
    {
        if (!$this->path) {
            return null;
        }

        return asset('storage/' . $this->path);
    }

    /**
     * Absolute full URL (for mobile apps)
     */
    public function getFullUrlAttribute(): ?string
    {
        if (!$this->path) {
            return null;
        }

        return url('storage/' . $this->path);
    }

    /**
     * File extension
     */
    public function getExtensionAttribute(): string
    {
        return strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));
    }

    /**
     * File type based on MIME
     */
    public function getFileTypeAttribute(): ?string
    {
        return $this->mime_type ? strtok($this->mime_type, '/') : null;
    }

    /**
     * Check if file is an image
     */
    public function getIsImageAttribute(): bool
    {
        return $this->file_type === 'image';
    }

    /**
     * Human readable file size
     */
    public function getFormattedSizeAttribute(): string
    {
        if ($this->size < 1024) {
            return $this->size . ' B';
        } elseif ($this->size < 1048576) {
            return round($this->size / 1024, 2) . ' KB';
        } else {
            return round($this->size / 1048576, 2) . ' MB';
        }
    }

    /**
     * Get thumbnail URL (for future implementation)
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->path) {
            return null;
        }

        // Thumbnail path pattern: listings/1/thumbs/image.jpg
        $thumbnailPath = str_replace(
            basename($this->path),
            'thumbs/' . basename($this->path),
            $this->path
        );

        if (Storage::disk('public')->exists($thumbnailPath)) {
            return asset('storage/' . $thumbnailPath);
        }

        return $this->url; // Fallback to original
    }

    // === METHODS ===

    /**
     * Check if image file exists on disk
     */
    public function exists(): bool
    {
        return $this->path && Storage::disk('public')->exists($this->path);
    }

    /**
     * Delete image file from disk
     */
    public function deleteFile(): bool
    {
        if ($this->exists()) {
            return Storage::disk('public')->delete($this->path);
        }
        return true;
    }

    /**
     * Mark as primary image
     */
    public function markAsPrimary(): void
    {
        // Önce diğer resimleri primary değil olarak işaretle
        static::where('imageable_id', $this->imageable_id)
            ->where('imageable_type', $this->imageable_type)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Bu resmi primary yap
        $this->update(['is_primary' => true]);
    }

    // === EVENTS ===

    protected static function boot()
    {
        parent::boot();

        // Image silindiğinde dosyayı da sil
        static::deleted(function ($image) {
            $image->deleteFile();

            // Thumbnail'ı da sil
            $thumbnailPath = str_replace(
                basename($image->path),
                'thumbs/' . basename($image->path),
                $image->path
            );

            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
        });
    }

    // /**
    //  * DEBUG METODU - Geliştirme aşamasında kullanılır
    //  */
    // public static function debugFillable()
    // {
    //     $instance = new static;
    //     \Log::info('Image model fillable fields:', [
    //         'fillable' => $instance->getFillable(),
    //         'has_imageable_type' => in_array('imageable_type', $instance->getFillable()),
    //         'has_imageable_id' => in_array('imageable_id', $instance->getFillable())
    //     ]);
    //     return $instance->getFillable();
    // }
}
