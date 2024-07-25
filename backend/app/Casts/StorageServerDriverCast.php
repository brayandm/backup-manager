<?php

namespace App\Casts;

use App\Entities\StorageServerDriverConfig;
use App\Entities\StorageServerDrivers\AwsS3Driver;
use App\Entities\StorageServerDrivers\FileSystemDriver;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class StorageServerDriverCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $driver = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('The value is not a valid JSON.');
        }

        switch ($driver['type']) {
            case 'files_system':
                $result = new FileSystemDriver(
                    $driver['path']
                );
                break;
            case 'aws_s3':
                $result = new AwsS3Driver(
                    $driver['bucket'],
                    $driver['region'],
                    $driver['key'],
                    $driver['secret'],
                    $driver['endpoint'],
                    $driver['path']
                );
                break;
            default:
                throw new InvalidArgumentException('Unsupported driver type.');
        }

        return new StorageServerDriverConfig($result);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = $value->driver;

        if ($value instanceof FileSystemDriver) {
            $driver = [
                'type' => 'files_system',
                'path' => $value->path,
            ];
        } elseif ($value instanceof AwsS3Driver) {
            $driver = [
                'type' => 'aws_s3',
                'bucket' => $value->bucket,
                'region' => $value->region,
                'key' => $value->key,
                'secret' => $value->secret,
                'endpoint' => $value->endpoint,
                'path' => $value->path,
            ];
        } else {
            throw new InvalidArgumentException('Unsupported driver type.');
        }

        return json_encode($driver);
    }
}
