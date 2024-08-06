<?php

namespace App\Entities\Methods\EncryptionMethods;

use App\Helpers\CommandBuilder;
use App\Interfaces\EncryptionMethodInterface;
use Illuminate\Support\Str;

class Aes256CbcEncryptionMethod implements EncryptionMethodInterface
{
    public ?string $key;

    public function __construct(?string $key)
    {
        $this->key = $key;
    }

    public function encrypt(string $localWorkDir)
    {
        $tempDir = CommandBuilder::tmpPathGenerator();

        $command = "CONTENT=$(ls -1A \"$localWorkDir\")";
        $command .= " && find \"$localWorkDir\" -type d | sed 's|^$localWorkDir||' | awk -v tempDir=\"$tempDir\" '{print \"mkdir -p \\\"\" tempDir \$0 \"\\\"\"}' | sh";
        $command .= " && find \"$localWorkDir\" -type f | sed 's|^$localWorkDir||' | awk -v tempDir=\"$tempDir\" -v key=\"$this->key\" '{
            inputFile = \"$localWorkDir\" \$0;
            outputFile = tempDir \$0 \".enc\";
            print \"openssl enc -aes-256-cbc -salt -pbkdf2 -in \\\"\" inputFile \"\\\" -out \\\"\" outputFile \"\\\" -pass pass:\\\"\" key \"\\\"\"
        }' | sh";
        $command .= " && rm -rf \"$localWorkDir\"/\$CONTENT && mv \"$tempDir\"/* \"$localWorkDir\" && rm -rf \"$tempDir\"";

        return $command;
    }

    public function decrypt(string $localWorkDir)
    {
        $tempDir = CommandBuilder::tmpPathGenerator();

        $command = "CONTENT=$(ls -1A \"$localWorkDir\")";
        $command .= " && find \"$localWorkDir\" -type d | sed 's|^$localWorkDir||' | awk -v tempDir=\"$tempDir\" '{print \"mkdir -p \\\"\" tempDir \$0 \"\\\"\"}' | sh";
        $command .= " && find \"$localWorkDir\" -type f -name '*.enc' | sed 's|^$localWorkDir||' | awk -v tempDir=\"$tempDir\" -v key=\"$this->key\" '{
            inputFile = \"$localWorkDir\" \$0;
            outputFile = tempDir substr(\$0, 1, length(\$0) - 4);
            print \"openssl enc -d -aes-256-cbc -pbkdf2 -in \\\"\" inputFile \"\\\" -out \\\"\" outputFile \"\\\" -pass pass:\\\"\" key \"\\\"\"
        }' | sh";
        $command .= " && rm -rf \"$localWorkDir\"/\$CONTENT && mv \"$tempDir\"/* \"$localWorkDir\" && rm -rf \"$tempDir\"";

        return $command;
    }

    public function generateKey()
    {
        $this->key = base64_encode(random_bytes(32));
    }

    public function setup()
    {
        $command = 'true';

        return $command;
    }

    public function clean()
    {
        $command = 'true';

        return $command;
    }
}
