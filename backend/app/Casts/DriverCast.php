<?php

namespace App\Casts;

use App\Entities\Drivers\AwsS3Driver;
use App\Entities\Drivers\FileSystemDriver;
use App\Entities\Drivers\MysqlDriver;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class DriverCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $drivers = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('The value is not a valid JSON.');
        }

        $result = [];

        foreach ($drivers as $driver) {
            switch ($driver['type']) {
                case 'files_system':
                    $result[] = new FileSystemDriver(
                        $driver['path']
                    );
                    break;
                case 'mysql':
                    $result[] = new MysqlDriver(
                        $driver['host'],
                        $driver['port'],
                        $driver['user'],
                        $driver['password'],
                        $driver['database']
                    );
                    break;
                case 'aws_s3':
                    $result[] = new AwsS3Driver(
                        $driver['bucket'],
                        $driver['region'],
                        $driver['key'],
                        $driver['secret']
                    );
                    break;
                default:
                    throw new InvalidArgumentException('Unsupported driver type.');
            }
        }

        return $result;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $drivers = [];

        foreach ($value as $driver) {
            if ($driver instanceof FileSystemDriver) {
                $drivers[] = [
                    'type' => 'files_system',
                    'path' => $driver->path,
                ];
            } elseif ($driver instanceof MysqlDriver) {
                $drivers[] = [
                    'type' => 'mysql',
                    'host' => $driver->host,
                    'port' => $driver->port,
                    'user' => $driver->user,
                    'password' => $driver->password,
                    'database' => $driver->database,
                ];
            }  elseif ($driver instanceof AwsS3Driver) {
                $drivers[] = [
                    'type' => 'aws_s3',
                    'bucket' => $driver->bucket,
                    'region' => $driver->region,
                    'key' => $driver->key,
                    'secret' => $driver->secret,
                ];
            }

            else {
                throw new InvalidArgumentException('Unsupported driver type.');
            }
        }

        return json_encode($drivers);
    }
}
