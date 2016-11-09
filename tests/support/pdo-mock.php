<?php

/** 
 * for mocking the PDO class and PDO Statement 
 */
class MockPDO extends PDO {
    public function __construct () {}
    public function prepare( $statement ) {}
}

class MockPDOStatement {
    public function bindValue( $label, $value, $optional_type = false) {}
}