<?php

namespace App\Services;

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    private UploadApi $uploadApi;

    public function __construct()
    {
        $config = config('filesystems.disks.cloudinary');

        Configuration::instance([
            'cloud' => [
                'cloud_name' => $config['cloud'],
                'api_key'    => $config['key'],
                'api_secret' => $config['secret'],
            ],
            'url' => ['secure' => true],
        ]);

        $this->uploadApi = new UploadApi();
    }

    /**
     * Upload satu file ke Cloudinary dan kembalikan secure_url-nya.
     *
     * @param  UploadedFile  $file    File yang akan diupload
     * @param  string        $folder  Folder tujuan di Cloudinary
     * @return string                 URL aman hasil upload
     */
    public function upload(UploadedFile $file, string $folder): string
    {
        $result = $this->uploadApi->upload($file->getRealPath(), [
            'folder'       => $folder,
            'quality'      => 'auto',
            'fetch_format' => 'auto',
        ]);

        return $result['secure_url'];
    }

    /**
     * Upload beberapa file sekaligus dan kembalikan array secure_url.
     *
     * @param  UploadedFile[]  $files
     * @param  string          $folder
     * @return string[]
     */
    public function uploadMany(array $files, string $folder): array
    {
        return array_map(fn(UploadedFile $file) => $this->upload($file, $folder), $files);
    }
}
