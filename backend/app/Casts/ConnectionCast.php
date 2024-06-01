<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Connections\DockerConnection;
use App\Entities\Connections\SshConnection;
use InvalidArgumentException;

class ConnectionCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $connections = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('The value is not a valid JSON.');
        }

        $result = [];

        foreach ($connections as $connection) {
            switch ($connection['type']) {
                case 'ssh':
                    $result[] = new SshConnection(
                        $connection['user'],
                        $connection['host'],
                        $connection['port'],
                        $connection['private_key'],
                        $connection['passphrase'] !== null ? $connection['passphrase'] : null
                    );
                    break;
                case 'docker':
                    $result[] = new DockerConnection(
                        $connection['container_name']
                    );
                    break;
                default:
                    throw new InvalidArgumentException('Unsupported connection type.');
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
        $connections = [];

        foreach ($value as $connection) {
            if ($connection instanceof SshConnection) {
                $connections[] = [
                    'type' => 'ssh',
                    'user' => $connection->user,
                    'host' => $connection->host,
                    'port' => $connection->port,
                    'private_key' => $connection->privateKey,
                    'passphrase' => $connection->passphrase,
                ];
            } elseif ($connection instanceof DockerConnection) {
                $connections[] = [
                    'type' => 'docker',
                    'container_name' => $connection->containerName,
                ];
            } else {
                throw new InvalidArgumentException('Unsupported connection type.');
            }
        }

        return json_encode($connections);
    }
}
