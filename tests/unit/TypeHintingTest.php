<?php

use AspectMock\Test as test;

class StubTest extends \PHPUnit_Framework_TestCase
{
    public function testStandardInstantiation_InstanceOf_Ok()
    {
        $sut = new demo\UserModel;

        $actual = $sut instanceof demo\UserModel;

        verify($actual)->true();
    }

    public function testMockTestDouble_InstanceOf_Ok()
    {
        $mock = test::double(new demo\UserModel);

        $actual = $mock instanceof demo\UserModel;

        verify($actual)->true();
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