<?php

class ManipleUser_Filter_FullNameTest extends PHPUnit_Framework_TestCase
{
    public function testFilter()
    {
        $user = new ManipleUser_Entity_User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setMiddleName('F.');

        $filter = new ManipleUser_Filter_FullName();
        $this->assertEquals(array('middleName' => false, 'reverse' => false), $filter->getOptions());
        $this->assertEquals('John Doe', $filter->filter($user));

        $filter->setOptions(array('middleName' => false, 'reverse' => true));
        $this->assertEquals(array('middleName' => false, 'reverse' => true), $filter->getOptions());
        $this->assertEquals('Doe John', $filter->filter($user));

        $filter->setOptions(array('middleName' => true, 'reverse' => true));
        $this->assertEquals(array('middleName' => true, 'reverse' => true), $filter->getOptions());
        $this->assertEquals('Doe John F.', $filter->filter($user));

        $filter->setOptions(array('middleName' => true, 'reverse' => false));
        $this->assertEquals(array('middleName' => true, 'reverse' => false), $filter->getOptions());
        $this->assertEquals('John F. Doe', $filter->filter($user));
    }

    public function testFilterStatic()
    {
        $user = new ManipleUser_Entity_User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setMiddleName('F.');

        $this->assertEquals(
            'John Doe',
            ManipleUser_Filter_FullName::filterStatic($user)
        );

        $this->assertEquals(
            'Doe John',
            ManipleUser_Filter_FullName::filterStatic($user, array('reverse' => true))
        );

        $this->assertEquals(
            'Doe John F.',
            ManipleUser_Filter_FullName::filterStatic($user, array('middleName' => true, 'reverse' => true))
        );

        $this->assertEquals(
            'John F. Doe',
            ManipleUser_Filter_FullName::filterStatic($user, array('middleName' => true))
        );
    }
}
