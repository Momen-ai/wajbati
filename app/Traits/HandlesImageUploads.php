<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait HandlesImageUploads
{
    /**
     * Allowed image extensions.
     */
    protected array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * Upload an image to the given folder on the public disk.
     * Validates binary headers, uses UUID naming, and enforces allowed extension whitelist.
     *
     * @throws ValidationException
     */
    protected function uploadImage(UploadedFile $file, string $folder): string
    {
        $this->verifyImageFile($file);

        $ext = strtolower($file->getClientOriginalExtension());
        if (! in_array($ext, $this->allowedExtensions, true)) {
            $ext = strtolower($file->extension() ?: 'jpg');
        }

        if (! in_array($ext, $this->allowedExtensions, true)) {
            throw ValidationException::withMessages([
                'image' => __('The file must be a valid image with an allowed extension (jpg, jpeg, png, webp).'),
            ]);
        }

        $filename = Str::uuid()->toString().'.'.$ext;
        $path = $folder.'/'.$filename;

        Storage::disk('public')->putFileAs($folder, $file, $filename);

        return $path;
    }

    /**
     * Replace an existing image on a model.
     * Deletes the old file from storage, then stores the new one.
     * Creates the image relation record if it doesn't exist yet.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model  Model with a morphOne `image()` relation
     */
    protected function replaceImage($model, UploadedFile $file, string $folder): void
    {
        $newPath = $this->uploadImage($file, $folder);

        if ($model->image) {
            Storage::disk('public')->delete($model->image->image_path);
            $model->image->update(['image_path' => $newPath]);
        } else {
            $model->image()->create(['image_path' => $newPath]);
        }
    }

    /**
     * Delete a model's image from storage and remove the image relation record.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    protected function deleteImage($model): void
    {
        if ($model->image) {
            Storage::disk('public')->delete($model->image->image_path);
            $model->image->delete();
        }
    }

    /**
     * Perform strict binary image verification.
     *
     * @throws ValidationException
     */
    protected function verifyImageFile(UploadedFile $file): void
    {
        $realPath = $file->getRealPath();
        if (! $realPath || ! file_exists($realPath)) {
            throw ValidationException::withMessages([
                'image' => __('The uploaded image file is invalid.'),
            ]);
        }

        $imageInfo = @getimagesize($realPath);
        if ($imageInfo === false) {
            throw ValidationException::withMessages([
                'image' => __('The uploaded file is not a valid image.'),
            ]);
        }

        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        if (! in_array($imageInfo['mime'], $allowedMimes, true)) {
            throw ValidationException::withMessages([
                'image' => __('Invalid image MIME type detected.'),
            ]);
        }
    }
}
