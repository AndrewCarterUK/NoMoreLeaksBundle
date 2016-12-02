<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\Tests\Resetter;

use AndrewCarterUK\NoMoreLeaksBundle\Resetter\DoctrineEntityManagerResetter;

class DoctrineEntityManagerResetterTest extends \PHPUnit_Framework_TestCase
{
    public function testEntityManagerClear()
    {
        $configurationMock = $this
            ->getMockBuilder('Doctrine\\ORM\\Configuration')
            ->disableOriginalConstructor()
            ->setMethods(array('getSQLLogger'))
            ->getMock();

        $configurationMock
            ->expects($this->once())
            ->method('getSQLLogger')
            ->willReturn(null);
             
        $connectionMock = $this
            ->getMockBuilder('Doctrine\\DBAL\\Connection')
            ->disableOriginalConstructor()
            ->setMethods(array('getConfiguration'))
            ->getMock();

        $connectionMock
            ->expects($this->once())
            ->method('getConfiguration')
            ->willReturn($configurationMock);

        $entityManagerMock = $this
            ->getMockBuilder('Doctrine\\ORM\\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getConnection', 'clear'))
            ->getMock();

        $entityManagerMock
            ->expects($this->once())
            ->method('getConnection')
            ->willReturn($connectionMock);

        $entityManagerMock
            ->expects($this->once())
            ->method('clear');

        $resetter = new DoctrineEntityManagerResetter($entityManagerMock);
        $resetter->reset();
    }
}
