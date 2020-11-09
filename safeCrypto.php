<?php
class shiftCipher
{
    private $key;
    private $text;
    private $data;


    function __construct($text, $key)
    {
        $this->text = $text;
        if ($key < 50) {
            $this->key = $key;
        } else {
            $this->key = 10;
        }
        $this->data = [];
    }
    private function generate_data()
    {
        for ($i = 0; $i < 126; $i++) {
            array_push($this->data, chr($i));
        }
    }
    public function get_encryted()
    {
        $this->generate_data();
        $limit = count($this->data);
        $encoding = "";
        for ($i = 0; $i < strlen($this->text); $i++) {
            $index = (ord($this->text[$i]) + $this->key) % $limit;
            $encoding = $encoding . $this->data[$index];
        }
        return  $encoding;
    }
    function get_decrypted($decrypt, $isChecked)
    {
        if ($isChecked == true) {
            $this->generate_data();
        }
        $limit = count($this->data);
        $decoding = "";
        for ($i = 0; $i < strlen($decrypt); $i++) {
            $index = (ord($decrypt[$i]) - $this->key) % $limit;
            $index = ($limit + $index) % $limit;
            $decoding = $decoding . $this->data[$index];
        }
        return  $decoding;
    }
}
