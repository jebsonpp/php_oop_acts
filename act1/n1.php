<?php

class Rectangle {
    public $height = 1;
    public $width = 1;

    public function __construct($height, $width) {
        $this->height = $height;
        $this->width = $width;
    }

    public function Getarea() {
        return $this->height * $this->width;
    }

    public function Getperimeter() {
        return 2 * ($this->height + $this->width);
    }
}
// Example usage: height is 10 and width is 5
echo "Area: " . (new Rectangle(10, 5))->Getarea() . "\n";
echo "Perimeter: " . (new Rectangle(10, 5))->Getperimeter() . "\n";
?>