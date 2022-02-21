<?php
class ProcessCash
{
    //accept type: cash, word
    public $type;
    public $result;

    public function convert($cash, $type = 'cash')
    {
        $this->type = $type;
        switch ($this->type) {
            case 'cash':
                $this->toCash($cash);
                break;
            case 'word':
                $this->toWord($cash);
                break;
        }

        return $this->result;
    }

    public function toWord($number = 0)
    {
        $digits = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $words = ['không ', 'một ', 'hai ', 'ba ', 'bốn ', 'năm ', 'sáu ', 'bảy ', 'tám ', 'chín '];
        $result =  str_replace($digits, $words, $number);
        return $this->result = $result;
    }

    public function toCash($number = 0)
    {
        $s09 = ['', ' một', ' hai', ' ba', ' bốn', ' năm', ' sáu', ' bảy', ' tám', ' chín'];
        $lop3 = ['', ' triệu', ' nghìn', ' tỷ', ','];

        if ($number < 0) {
            $dau = 'âm ';
            $number = str_replace('-', '', $number);
        } else {
            $dau = '';
        }
        $conSo = (string)$number;
        $soChuSo = strlen($conSo) % 9;
        if ($soChuSo >= 0) {
            $sk = '0';
            $conSo = (string)str_repeat($sk, 9 - ($soChuSo % 12)) . $conSo;
            $docSo = '';
            $i = 0;
            $lop = 1;
            do {
                $n1 = substr($conSo, $i, 1);
                $n2 = substr($conSo, $i + 1, 1);
                $n3 = substr($conSo,  $i + 2, 1);
                $i += 3;
                if ($n1 . $n2 . $n3 == '000') {
                    if ($docSo != '' && $lop == 3 && (strlen($conSo) - $i) > 2) {
                        $s123 = ' tỷ';
                    } else {
                        $s123 = '';
                    };
                } else {
                    if ($n1 == 0) {
                        if ($docSo == '') {
                            $s1 = '';
                        } else {
                            $s1 = ' không trăm';
                        };
                    } else {
                        $s1 = $s09[$n1] . ' trăm';
                    };
                    if ($n2 == 0) {
                        if ($s1 == '' || $n3 == 0) {
                            $s2 = '';
                        } else {
                            $s2 = ' linh';
                        };
                    } else {
                        if ($n2 == 1) {
                            $s2 = ' mười';
                        } else {
                            $s2 = $s09[$n2] . ' mươi';
                        };
                    };
                    if ($n3 == 1) {
                        if ($n2 == 1 || $n2 == 0) {
                            $s3 = ' một';
                        } else {
                            $s3 = ' mốt';
                        };
                    } else if ($n3 == 5 && $n2 != 0) {
                        $s3 = ' lăm';
                    } else {
                        $s3 = $s09[$n3];
                    };
                    if ($i > strlen($conSo) - 1) {
                        $s123 = $s1 . $s2 . $s3;
                    } else {
                        $s123 = $s1 . $s2 . $s3 . $lop3[$lop];
                    };
                };
                $lop += 1;
                if ($lop > 3) {
                    $lop = 1;
                };
                $docSo = $docSo . $s123;
            } while ($i < strlen($conSo));
            if ($docSo == '') {
                $kq = 'không';
            } else {
                $docSo = (string)$docSo;
                $docSo = trim($docSo);
                $kq = $dau . strtoupper(substr($docSo, 0, 1)) . substr($docSo, 1, strlen($docSo) - 1)  . " đồng.";
            };
        } else {
            $kq = $conSo;
        };
        return $this->result = $kq;
    }
}

class ConvertCash
{
    public static function __callStatic($method, $props)
    {
        return (new ProcessCash)->$method(...$props);
    }
}

echo ConvertCash::convert(1996);
