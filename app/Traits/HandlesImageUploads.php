<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesImageUploads
{
    /**
     * Upload an image to the given folder on the public disk.
     * Returns the stored path (e.g. "meals/abc123.jpg").
     */
    protected function uploadImage(UploadedFile $file, string $folder): string
    {
        return $file->store($folder, 'public');
    }

    /**
     * Replace an existing image on a model.
     * Deletes the old file from storage, then stores the new one.
     * Creates the image relation record if it doesn't exist yet.
     *
     * @param \Illuminate\Database\Eloquent\Model $model  Model with a morphOne `image()` relation
     */
    protected function replaceImage($model, UploadedFile $file, string $folder): void
    {
        $newPath = $this->uploadImage($file, $folder);

        if ($model->image) {
            // Always use Storage::disk('public') — NOT public_path() which resolves incorrectly
            Storage::disk('public')->delete($model->image->image_path);
            $model->image->update(['image_path' => $newPath]);
        } else {
            $model->image()->create(['image_path' => $newPath]);
        }
    }

    /**
     * Delete a model's image from storage and remove the image relation record.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    protected function deleteImage($model): void
    {
        if ($model->image) {
            Storage::disk('public')->delete($model->image->image_path);
            $model->image->delete();
        }
    }
}
