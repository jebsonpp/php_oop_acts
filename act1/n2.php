<?php

class QuadraticEquation {
    private $a;
    private $b;
    private $c;

    public function __construct($a, $b, $c) {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }
    public function getDiscriminant() {
        return ($this->b ** 2) - (4 * $this->a * $this->c);
    }

    public function getRoot1() {
        $discriminant = $this->getDiscriminant();
        if ($discriminant < 0) {
            return null; // No real roots
        }
        return (-$this->b + sqrt($discriminant)) / (2 * $this->a);
    }

    public function getRoot2() {
        $discriminant = $this->getDiscriminant();
        if ($discriminant < 0) {
            return null; // No real roots
        }
        return (-$this->b - sqrt($discriminant)) / (2 * $this->a);
    }

    public function quadraticFormula() {
        $discriminant = $this->getDiscriminant();
        if ($discriminant < 0) {
            return "No real roots";
        } elseif ($discriminant == 0) {
            $root = -$this->b / (2 * $this->a);
            return "One real root: x = " . $root;
        } else {
            $root1 = $this->getRoot1();
            $root2 = $this->getRoot2();
            return "Two real roots: r1 = " . $root1 . ", r2 = " . $root2;
        }
    }

} 
// Example usage: a=2, b=-6, c=4
 echo "X is equal to " . (new QuadraticEquation(2, -6, 4))->getRoot1();
 echo " or " . (new QuadraticEquation(2, -6, 4))->getRoot2() . "\n";
 echo (new QuadraticEquation(2, -6, 4))->quadraticFormula() . "\n";
?>