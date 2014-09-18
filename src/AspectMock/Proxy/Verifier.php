<?php
namespace AspectMock\Proxy;
use \PHPUnit_Framework_ExpectationFailedException as fail;
use AspectMock\Util\ArgumentsFormatter;

/**
 * Interface `Verifiable` defines methods to verify method calls.
 * Implementation may differ for class methods and instance methods.
 *   
 */

interface Verifier {

    /**
     * Verifies a method was invoked at least once.
     * In second argument you can specify with which params method expected to be invoked;
     *
     * ``` php
     * <?php
     * $user->verifyInvoked('save');
     * $user->verifyInvoked('setName',['davert']);
     *
     * ?>
     * ```
     *
     * @param $name
     * @param null $params
     * @throws \PHPUnit_Framework_ExpectationFailedException
     * @param array $params
     * @throws fail
     */
    public function verifyInvoked($name, $params = null);

    /**
     * Verifies that method was invoked only once.
     *
     * @param $name
     * @param array $params
     */
    public function verifyInvokedOnce($name, $params = null);

    /**
     * Verifies that method was called exactly $times times.
     *
     * ``` php
     * <?php
     * $user->verifyInvokedMultipleTimes('save',2);
     * $user->verifyInvokedMultipleTimes('dispatchEvent',3,['before_validate']);
     * $user->verifyInvokedMultipleTimes('dispatchEvent',4,['after_save']);
     * ?>
     * ```
     *
     * @param $name
     * @param $times
     * @param array $params
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function verifyInvokedMultipleTimes($name, $times, $params = null);

    /**
     * Verifies that method was not called.
     * In second argument with which arguments is not expected to be called.
     *
     * ``` php
     * <?php
     * $user->setName('davert');
     * $user->verifyNeverInvoked('setName'); // fail
     * $user->verifyNeverInvoked('setName',['davert']); // fail
     * $user->verifyNeverInvoked('setName',['bob']); // success
     * $user->verifyNeverInvoked('setName',[]); // success
     * ?>
     * ```
     *
     * @param $name
     * @param null $params
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function verifyNeverInvoked($name, $params = null);

}
