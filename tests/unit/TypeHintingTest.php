<?php

use AspectMock\Test as test;

class StubTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider_typeHinting_instanceOf
     */
    public function testTypeHinting_objectInstanceOf_matches($object, $type)
    {
        $this->assertInstanceOf($type, $object);
    }

    public function dataProvider_typeHinting_instanceOf()
    {
        return [
            // type checked instance         , expected valid instanceof
            [new demo\UserModel              ,             'demo\UserModel'],
            [test::double(new demo\UserModel),             'demo\UserModel'],
            [test::double('demo\UserModel')  ,             'demo\UserModel'],
            [test::double(new demo\UserModel),  'AspectMock\Proxy\Verifier'],
        ];
    }

    /**
     * @dataProvider dataProvider_typeHinting_methodCall
     */
    public function testTypeHinting_callMethod_throwsExceptionWhenAppropriate($user, $expected)
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

    public function dataProvider_typeHinting_methodCall()
    {
        return [
            // type checked instance           , exception is expected
            [new demo\UserModel                ,                false],
            [test::double(new demo\UserModel)  ,                false],
            [test::double('demo\UserModel')    ,                false],
            [test::double(new demo\UserModel)  ,                false],
            [test::double('demo\UserModel')    ,                false],

            [new stdClass                      ,                 true],
            [test::double(new stdClass)        ,                 true],
            [test::double('stdClass')          ,                 true],
        ];
    }
}