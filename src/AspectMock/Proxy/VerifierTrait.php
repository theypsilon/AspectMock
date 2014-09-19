<?php
namespace AspectMock\Proxy;
use \PHPUnit_Framework_ExpectationFailedException as fail;
use AspectMock\Util\ArgumentsFormatter;

/**
 * Interface `Verifiable` defines methods to verify method calls.
 * Implementation may differ for class methods and instance methods.
 *
 */

trait VerifierTrait {

    use ClassHandlerTrait;

    protected $invokedFail = "Expected %s to be invoked but it never occur.";
    protected $notInvokedMultipleTimesFail = "Expected %s to be invoked %s times but it never occur.";
    protected $invokedMultipleTimesFail = "Expected %s to be invoked but called %s times but called %s.";

    protected $neverInvoked = "Expected %s not to be invoked but it was.";

    abstract public function getCallsForMethod($method);

    protected function callSyntax($method)
    {
        return method_exists($this->getClassName(),$method)
            ? '::'
            : '->';
    }

    protected function onlyExpectedArguments($expectedParams, $passedArgs)
    {
        return empty($expectedParams) ?
            $passedArgs :
            array_slice($passedArgs, 0, count($expectedParams));
    }

    /**
     * @see Verifier::verifyInvoked
     */
    public function verifyInvoked($name, $params = null)
    {
        $calls = $this->getCallsForMethod($name);
        $separator = $this->callSyntax($name);

        if (empty($calls)) throw new fail(sprintf($this->invokedFail, $this->getClassName().$separator.$name));

        if (is_array($params)) {
            foreach ($calls as $args) {
                if ($this->onlyExpectedArguments($params, $args) === $params) return;
            }
            $params = ArgumentsFormatter::toString($params);
            throw new fail(sprintf($this->invokedFail, $this->getClassName().$separator.$name."($params)"));
        } else if(is_callable($params)) {
            $params($calls);
        }
    }

    /**
     * @see Verifier::verifyInvokedOnce
     */
    public function verifyInvokedOnce($name, $params = null)
    {
        $this->verifyInvokedMultipleTimes($name, 1, $params);
    }

    /**
     * @see Verifier::verifyInvokedMultipleTimes
     */
    public function verifyInvokedMultipleTimes($name, $times, $params = null)
    {
        if ($times == 0) return $this->verifyNeverInvoked($name, $params);

        $calls = $this->getCallsForMethod($name);
        $separator = $this->callSyntax($name);

        if (empty($calls)) throw new fail(sprintf($this->notInvokedMultipleTimesFail, $this->getClassName().$separator.$name, $times));
        if (is_array($params)) {
            $equals = 0;
            foreach ($calls as $args) {
                if ($this->onlyExpectedArguments($params, $args) == $params) $equals++;
            }
            if ($equals == $times) return;
            $params = ArgumentsFormatter::toString($params);
            throw new fail(sprintf($this->invokedMultipleTimesFail, $this->getClassName().$separator.$name."($params)", $times, $equals));
        } else if(is_callable($params)) {
            $params($calls);
        }
        $num_calls = count($calls);
        if ($num_calls != $times) throw new fail(sprintf($this->invokedMultipleTimesFail, $this->getClassName().$separator.$name, $times, $num_calls));
    }

    /**
     * @see Verifier::verifyNeverInvoked
     */
    public function verifyNeverInvoked($name, $params = null)
    {
        $calls = $this->getCallsForMethod($name);
        $separator = $this->callSyntax($name);

        if (is_array($params)) {
            if (empty($calls)) return;
            $params = ArgumentsFormatter::toString($params);
            foreach ($calls as $args) {
                if ($this->onlyExpectedArguments($params, $args) == $params) throw new fail(sprintf($this->neverInvoked, $this->getClassName()));
            }
            return;
        }
        if (count($calls)) throw new fail(sprintf($this->neverInvoked, $this->getClassName().$separator.$name));
    }

}
