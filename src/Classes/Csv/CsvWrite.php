<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Classes/Csv/CsvWrite.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 30/07/2020 14:02

/**
 * Created by PhpStorm.
 * User: davidannebicque
 * Date: 18/05/2018
 * Time: 12:52
 */

namespace App\Classes\Csv;

use function get_class;
use function is_object;
use function is_string;

/**
 * Class CsvWrite
 * @package App\Classes\Csv
 */
abstract class CsvWrite
{
    public const FORMAT_DATETIME = 'DateTime';
    public const FORMAT_STRING = 'string';
    public const ECHAPPEMENT = '"';

    /**
     * @param        $value
     * @param string $key
     *
     * @return string
     */
    public static function writeField($value, $key = ''): string
    {
        $field = '';
        $field .= $key;


        if (is_string($value)) {
            $field .= self::ECHAPPEMENT . $value . self::ECHAPPEMENT;
        }

        elseif (is_object($value)) {
            if (get_class($value) === self::FORMAT_DATETIME) {
                $field .= $value->format('d-m-Y');
            }
        } else {
            $field .= $value;
        }


        return $field;
    }
}
