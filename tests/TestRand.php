<?php

use Illuminate\Support\Str;
use Mtsung\JoymapCore\Facades\Rand;
use Tests\TestCase;

class TestRand extends TestCase
{
    public function testMemberNo()
    {
        foreach (range(0, 30) as $ignored) {
            $res = Rand::memberNo();
            $this->assertTrue(Str::is('J-*', $res));
            $this->assertTrue(10 === Str::length($res));
        }
    }

    public function testPhoneVerifyCode()
    {
        foreach (range(0, 30) as $ignored) {
            $res = Rand::phoneVerifyCode();
            $this->assertTrue(Str::is('JM-*', $res));
            $this->assertTrue(9 === Str::length($res));
        }
    }

    public function testInviteCode()
    {
        foreach (range(0, 30) as $ignored) {
            $res = Rand::inviteCode();
            $this->assertTrue(6 === Str::length($res));
        }
    }

    public function testDealerNo()
    {
        foreach (range(0, 30) as $ignored) {
            $res = Rand::dealerNo();
            $this->assertTrue(9 === Str::length($res));
        }
    }

    public function testCreditNo()
    {
        foreach (range(0, 30) as $ignored) {
            $res = Rand::creditNo();
            $this->assertTrue(Str::is('JMC*', $res));
            $this->assertTrue(17 === Str::length($res));
        }
    }

    public function testPayNo()
    {
        foreach (range(0, 30) as $ignored) {
            $res = Rand::payNo();
            $this->assertTrue(Str::is('JP-*', $res));
            $this->assertTrue(14 === Str::length($res));
        }
    }

    public function testOrderNo()
    {
        foreach (range(0, 30) as $ignored) {
            $res = Rand::orderNo();
            $this->assertTrue(Str::is('BK-*', $res));
            $this->assertTrue(10 === Str::length($res));
        }
    }
}
