<?php

use AspectMock\Test as test;

class TypeCheckTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider_instanceOf
     */
    public function testObjectInstanceOf_matches($object, $type)
    {
        $this->assertInstanceOf($type, $object);
    }

    public function dataProvider_instanceOf()
    {
        return [
            // type checked instance         , expected valid instanceof
            [new demo\UserModel              ,             'demo\UserModel'],
            [test::double(new demo\UserModel),  'AspectMock\Proxy\Verifier'],
            [test::double('demo\UserModel')  ,  'AspectMock\Proxy\Verifier'],
            [test::double('demo\UserModel')->construct(),  'demo\UserModel'],
            [test::double('demo\UserModel')->make()     ,  'demo\UserModel'],
        ];
    }

    /**
     * @dataProvider dataProvider_callTypeHintedMethod
     */
    public function testCallTypeHintedMethod_throwsExceptionWhenAppropriate($user, $expected)
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

    public function dataProvider_callTypeHintedMethod()
    {
        return [
            // type checked instance           , exception is expected
            [new demo\UserModel                ,                false],
            [test::double(new demo\UserModel)  ,                 true],
            [test::double('demo\UserModel')    ,                 true],
            [test::double('demo\UserModel')->construct(),       false],
            [test::double('demo\UserModel')->make(),            false],

            [new stdClass                      ,                 true],
            [test::double(new stdClass)        ,                 true],
            [test::double('stdClass')          ,                 true],
            [test::double('stdClass')->construct(),              true],
            [test::double('stdClass')->make()  ,                 true],
        ];
    }

    public function testTypeHinting_callMethodAfterMockingClass_ok()
    {
        $user = test::double('demo\UserModel', ['getName' => 'rabbit'])->construct();
        $user->setName('dog');
        $service    = new demo\UserService;
        $exception  = false;

        try {
            $name = $service->getName($user);
        } Catch(Exception $e) {
            $exception = true;
        }

        verify($exception)->equals(false);
        verify($name)->equals('rabbit');
    }
}