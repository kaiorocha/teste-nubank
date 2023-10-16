<?php

namespace App\Command;

use App\Entity\Operation;

/**
 * Process Command
 */
class Process
{
    /**
     * @return void
     */
    public static function run(): void
    {
        echo "Por favor, informe o caminho do arquivo que deseja processar: ";

        $input = trim(fgets(STDIN, 1024));

        if (!file_exists($input)) {
            echo "Arquivo não encontrado, por favor verifique!\n";
            return;
        }

        $file = file_get_contents($input);
        $json = json_decode($file);

        $operation = new Operation($json);

        $output = json_encode($operation->process());

        echo "\nTaxas das operações: ";

        fwrite(STDOUT, $output);

        echo "\n";
    }
}