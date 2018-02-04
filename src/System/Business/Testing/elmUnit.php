<?php

include_once("System/Business/Login/login.php");
include_once("System/Business/Testing/roleManagementTester.php");

class elmUnit_Test{
    function __construct(string $nameToSet, string $functionToSet){
        $this->name = $nameToSet;
        $this->function = $functionToSet;
    }

    public $name;
    public $function;
}

class elmUnit_TestExecutor{

    /**
     * Executes all given unit tests
     * @return string a HTML construct showing the result of the tests
     */
    public static function executeTests() : string{
        $returnValue = '';
        $errorCount = 0;

        self::setErrorHandler();

        foreach (self::getAllTests() as $test){
            try {
                eval($test->function . "; return true;");
                $returnValue = $returnValue . '<p class="successful"><b>Test:</b> "' . $test->name . '" was successful!</p>';
            } catch (Exception $ex) {
                $returnValue = $returnValue . '<p class="failure"><b>Test:</b> "' . $test->name . '" failed. <br>Exception:<br>' . $ex->getMessage() . '</p>';
                $errorCount++;
            }
        }
        return '<div class="elmUnitTesting"><h1>Tests executed</h1><p>'. $errorCount .' error(s) occurred</p>' . $returnValue . '</div>';
    }

    /**
     * List of all tests to execute
     * @return array An array of elmUnit_Test objects
     */
    public static function getAllTests() : array {
        $tests = array();

        //Add all tests to execute here
        array_push($tests, new elmUnit_Test("Is a role name unique functionality", "elm_roleManagementTester::isRoleUnique();"));
        array_push($tests, new elmUnit_Test("Add Role and delete created role", "elm_roleManagementTester::addRoleAndDeleteRole();"));

        return $tests;
    }

    /**
     * Used so Warnings also get caught in try catch
     */
    private static function setErrorHandler(){
        set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
            // error was suppressed with the @-operator
            if (0 === error_reporting()) {
                return false;
            }

            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
    }
}

?>