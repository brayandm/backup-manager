<?php

namespace App\Casts;

use App\Entities\EncryptionMethodConfig;
use App\Entities\Methods\EncryptionMethods\Aes256CbcEncryptionMethod;
use App\Entities\Methods\EncryptionMethods\NoEncryptionMethod;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class EncryptionMethodCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $encryptionMethod = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('The value is not a valid JSON.');
        }

        switch ($encryptionMethod['type']) {
            case 'aes-256-cbc':
                $result = new Aes256CbcEncryptionMethod($encryptionMethod['key']);
                break;
            case 'none':
                $result = new NoEncryptionMethod();
                break;
            default:
                throw new InvalidArgumentException('Unsupported encryption method type.');
        }

        return new EncryptionMethodConfig($result);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = $value->encryptionMethod;

        if ($value instanceof Aes256CbcEncryptionMethod) {
            $encryptionMethod = [
                'type' => 'aes-256-cbc',
                'key' => $value->key,
            ];
        } elseif ($value instanceof NoEncryptionMethod) {
            $encryptionMethod = [
                'type' => 'none',
            ];
        } else {
            throw new InvalidArgumentException('Unsupported encryption method type.');
        }

        return json_encode($encryptionMethod);
    }
}
