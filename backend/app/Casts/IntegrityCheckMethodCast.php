<?php

namespace App\Casts;

use App\Entities\IntegrityCheckMethodConfig;
use App\Entities\Methods\IntegrityCheckMethods\NoIntegrityCheckMethod;
use App\Entities\Methods\IntegrityCheckMethods\Sha256SumIntegrityCheckMethod;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class IntegrityCheckMethodCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $integrityCheckMethod = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('The value is not a valid JSON.');
        }

        switch ($integrityCheckMethod['type']) {
            case 'sha-256-sum':
                $result = new Sha256SumIntegrityCheckMethod($integrityCheckMethod['hash']);
                break;
            case 'none':
                $result = new NoIntegrityCheckMethod();
                break;
            default:
                throw new InvalidArgumentException('Unsupported integrity check method type.');
        }

        return new IntegrityCheckMethodConfig($result);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = $value->integrityCheckMethod;

        if ($value instanceof Sha256SumIntegrityCheckMethod) {
            $integrityCheckMethod = [
                'type' => 'sha-256-sum',
                'hash' => $value->hash,
            ];
        } elseif ($value instanceof NoIntegrityCheckMethod) {
            $integrityCheckMethod = [
                'type' => 'none',
            ];
        } else {
            throw new InvalidArgumentException('Unsupported integrity check method type.');
        }

        return json_encode($integrityCheckMethod);
    }
}
