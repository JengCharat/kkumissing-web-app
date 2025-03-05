<?php

namespace App\Helpers;

class BahtText
{
    private static $numbers = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
    private static $positions = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');

    public static function convert($number)
    {
        if ($number == 0) {
            return 'ศูนย์บาทถ้วน';
        }

        // Split number into integer and decimal parts
        $number = number_format($number, 2, '.', '');
        list($integer, $decimal) = explode('.', $number);

        $bahtText = self::convertIntegerToBahtText($integer);

        if ($decimal == '00') {
            $bahtText .= 'บาทถ้วน';
        } else {
            $bahtText .= 'บาท';
            $bahtText .= self::convertDecimalToBahtText($decimal);
            $bahtText .= 'สตางค์';
        }

        return $bahtText;
    }

    private static function convertIntegerToBahtText($number)
    {
        $bahtText = '';
        $length = strlen($number);

        for ($i = 0; $i < $length; $i++) {
            $digit = (int)$number[$i];
            $position = $length - $i - 1;

            if ($digit != 0) {
                if ($position % 6 == 1 && $digit == 1) {
                    $bahtText .= ''; // ในหลัก 'สิบ', ไม่ใส่ 'หนึ่ง' เพราะไม่มีใครพูด 'หนึ่งสิบ'
                } elseif ($position % 6 == 1 && $digit == 2) {
                    $bahtText .= 'ยี่'; // ในหลัก 'สิบ', ใส่ 'ยี่' แทน 'สอง' เพราะไม่มีใครพูด 'สองสิบ'
                } elseif ($position % 6 == 0 && $digit == 1 && $length > 1) {
                    $bahtText .= 'เอ็ด'; // ในหลัก  'หน่วย', ใส่ 'เอ็ด' แทน 'หนึ่ง' [101 = ร้อยเอ็ด]
                } else {
                    $bahtText .= self::$numbers[$digit];
                }

                $bahtText .= self::$positions[$position % 6];
            }

            if ($position % 6 == 0 && $position > 0) {
                $bahtText .= 'ล้าน';
            }
        }

        return $bahtText;
    }

    private static function convertDecimalToBahtText($decimal)
    {
        $bahtText = '';
        $length = strlen($decimal);

        for ($i = 0; $i < $length; $i++) {
            $digit = (int)$decimal[$i];
            $position = $length - $i - 1;

            if ($digit != 0) {
                if ($position % 6 == 1 && $digit == 1) {
                    $bahtText .= ''; // ในหลัก 'สิบ', ไม่ใส่ 'หนึ่ง' เพราะไม่มีใครพูด 'หนึ่งสิบ'
                } elseif ($position % 6 == 1 && $digit == 2) {
                    $bahtText .= 'ยี่'; // ในหลัก 'สิบ', ใส่ 'ยี่' แทน 'สอง' เพราะไม่มีใครพูด 'สองสิบ'
                } elseif ($position % 6 == 0 && $digit == 1 && $i > 0) {
                    $bahtText .= 'เอ็ด'; // ในหลัก  'หน่วย', ใส่ 'เอ็ด' แทน 'หนึ่ง' [101 = ร้อยเอ็ด]
                } else {
                    $bahtText .= self::$numbers[$digit];
                }

                $bahtText .= self::$positions[$position % 6];
            }
        }

        return $bahtText;
    }
}
