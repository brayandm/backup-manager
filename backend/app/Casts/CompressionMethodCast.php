<?php

namespace App\Casts;

use App\Entities\CompressionMethodConfig;
use App\Entities\Methods\CompressionMethods\TarCompressionMethod;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class CompressionMethodCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $compressionMethod = json_decode($value, true);

        info($compressionMethod);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('The value is not a valid JSON.');
        }

        switch ($compressionMethod['type']) {
            case 'tar':
                $result = new TarCompressionMethod();
                break;
            default:
                throw new InvalidArgumentException('Unsupported compression method type.');
        }

        return new CompressionMethodConfig($result);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = $value->compressionMethod;

        if ($value instanceof TarCompressionMethod) {
            $compressionMethod = [
                'type' => 'tar',
            ];
        } else {
            throw new InvalidArgumentException('Unsupported compression method type.');
        }

        return json_encode($compressionMethod);
    }
}
