<?php

use AspectMock\Test as test;

class StubTest extends \PHPUnit_Framework_TestCase
{
    public function testStandardInstantiation_InstanceOfSUT_Ok()
    {
        $sut = new demo\UserModel;

        $actual = $sut instanceof 'demo\UserModel';

        verify($actual)->true();
    }

    public function testMockTestDouble_InstanceOfSUT_Ok()
    {
        $mock = test::double(new demo\UserModel);

        $actual = $mock instanceof demo\UserModel;

        verify($actual)->true();
    }

    public function testMockTestDouble_InstanceOfProxy_Ok()
    {
        $mock = test::double(new demo\UserModel);

        $actual = $mock instanceof AspectMock\Proxy\Verifier;

        verify($actual)->true();
    }

    public function testMockTestDouble_InstanceOfProxy_Wrong()
    {
        $sut = new demo\UserModel;

        $actual = $sut instanceof AspectMock\Proxy\Verifier;

        verify($actual)->false();
    }

    /**
     * @dataProvider dataProvider_typeHintingCall
     */
    public function testStandardInstantiation_TypeHintingCall_throwsExceptionWhenAppropriate($user, $expected)
    {
        $service = new demo\UserService;
        $actual  = false;

        try {
            $service->getName($user);
        } Catch(Exception $e) {
            $actual = true;
        }

        verify($actual)->equals($expected);
    }

    public function dataProvider_typeHintingCall()
    {
        return [
            // type checked instance           , exception is expected
            [new demo\UserModel                ,                false],
            [test::double(new demo\UserModel)  ,                false],
            [test::double('demo\UserModel')    ,                false],
            [test::double('demo\UserModel', []),                false],
            [new stdClass                      ,                 true],

            [test::double(new stdClass)        ,                 true],
            [test::double('stdClass')          ,                 true],
            [test::double('stdClass', [])      ,                 true],
        ];
    }
}