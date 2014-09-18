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

    public function testStandardInstantiation_TypeHintingCall_Ok()
    {
        $sut      = new demo\UserService;
        $user     = new demo\UserModel;
        $actual   = null;

        try {
            $sut->getName($user);
        } Catch(Exception $e) {
            $actual = $e;
        }

        verify($actual)->null();
    }

    public function testStandardInstantiation_TypeHintingCall_Wrong()
    {
        $sut      = new demo\UserService;
        $actual   = null;

        try {
            $sut->getName(new stdClass);
        } Catch(Exception $e) {
            $actual = $e;
        }

        verify($actual)->notNull();
    }

    public function testMockTestDouble_TypeHintingCall_Ok()
    {
        $mock     = test::double(new demo\UserService);
        $user     = new demo\UserModel;
        $actual   = null;

        try {
            $mock->getName($user);
        } Catch(Exception $e) {
            $actual = $e;
        }

        verify($actual)->null();
    }

    public function testMockTestDouble_TypeHintingCall_Wrong()
    {
        $mock     = test::double(new demo\UserService);
        $actual   = null;

        try {
            $mock->getName(new stdClass);
        } Catch(Exception $e) {
            $actual = $e;
        }

        verify($actual)->notNull();
    }
}