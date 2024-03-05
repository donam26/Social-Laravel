<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SexStatus extends Enum
{
    const MAN = 1;
    const WOMAN = 0;
    
    /**
     * Get list of status
     * @return array<int,string>
     */
    public static function getList()
    {
        return [
            self::MAN => 'Nam',
            self::WOMAN => 'Nữ',
        ];
    }

    /**
     * Get label of status
     * @param int $status
     * @return string
     */
    public static function getLabel(int $status) : string
    {
        $list = self::getList();
        return $list[$status] ?? '';
    }
}
