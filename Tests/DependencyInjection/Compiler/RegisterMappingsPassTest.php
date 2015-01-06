<?php

namespace Ekino\WordpressBundle\Tests\DependencyInjection\Compiler;

use Ekino\WordpressBundle\DependencyInjection\Compiler\RegisterMappingsPass;

class RegisterMappingsPassTest extends \PHPUnit_Framework_TestCase
{
    protected $driver;
    protected $driverPattern;
    protected $namespaces;
    protected $enabledParameter;
    protected $fallbackManagerParameter;

    /**
     * @var RegisterMappingsPass
     */
    protected $compilerPass;

    protected function setUp()
    {
        $this->driver = $this->getMockBuilder('Symfony\Component\DependencyInjection\Definition')->disableOriginalConstructor()->getMock();
        $this->driverPattern = 'doctrine.orm.%s_metadata_driver';
        $this->namespaces = array(
            'Resources/config/doctrine/model' => 'Ekino\WordpressBundle\Model',
        );
        $this->enabledParameter = 'ekino_wordpress.backend_type_orm';
        $this->fallbackManagerParameter = 'doctrine.default_entity_manager';

        $this->compilerPass = new RegisterMappingsPass(
            $this->driver,
            $this->driverPattern,
            $this->namespaces,
            $this->enabledParameter,
            $this->fallbackManagerParameter
        );
    }

    public function testProcessNoParameter()
    {
        $containerBuilder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')->disableOriginalConstructor()->getMock();

        $containerBuilder->expects($this->once())
            ->method('hasParameter')
            ->with($this->equalTo($this->enabledParameter))
            ->will($this->returnValue(false));

        $containerBuilder->expects($this->never())
            ->method('getDefinition');

        $this->compilerPass->process($containerBuilder);
    }

    public function testProcessNoChainDriver()
    {
        $containerBuilder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')->disableOriginalConstructor()->getMock();

        $containerBuilder->expects($this->exactly(3))
            ->method('hasParameter')
            ->will($this->returnCallback(array($this, 'validateNoChainDriverServiceName')));

        $containerBuilder->expects($this->never())
            ->method('getDefinition');

        $this->setExpectedException('Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException');
        $this->compilerPass->process($containerBuilder);
    }

    public function testProcessEkinoChainDriver()
    {
        $containerBuilder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')->disableOriginalConstructor()->getMock();
        $driver = $this->getMockBuilder('Symfony\Component\DependencyInjection\Definition')->disableOriginalConstructor()->getMock();

        $containerBuilder->expects($this->exactly(2))
            ->method('hasParameter')
            ->will($this->returnCallback(array($this, 'validateNoChainDriverEkinoServiceName')));
        $driverTestName = 'test';
        $containerBuilder->expects($this->once())
            ->method('getParameter')
            ->with($this->equalTo('ekino_wordpress.model_manager_name'))
            ->will($this->returnValue($driverTestName));
        $containerBuilder->expects($this->once())
            ->method('getDefinition')
            ->with($this->equalTo('doctrine.orm.test_metadata_driver'))
            ->will($this->returnValue($driver));

        $driver->expects($this->once())
            ->method('addMethodCall')
            ->with($this->equalTo('addDriver'), $this->equalTo(array($this->driver, 'Ekino\WordpressBundle\Model')));

        $containerBuilder->expects($this->once())
            ->method('getDefinition')
            ->with($this->equalTo('doctrine.orm.test_metadata_driver'));

        $this->compilerPass->process($containerBuilder);
    }

    public function testProcessDefaultChainDriver()
    {
        $containerBuilder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')->disableOriginalConstructor()->getMock();
        $driver = $this->getMockBuilder('Symfony\Component\DependencyInjection\Definition')->disableOriginalConstructor()->getMock();

        $containerBuilder->expects($this->exactly(3))
            ->method('hasParameter')
            ->will($this->returnCallback(array($this, 'validateDefaultServiceName')));
        $driverTestName = 'test';
        $containerBuilder->expects($this->once())
            ->method('getParameter')
            ->with($this->equalTo('doctrine.default_entity_manager'))
            ->will($this->returnValue($driverTestName));
        $containerBuilder->expects($this->once())
            ->method('getDefinition')
            ->with($this->equalTo('doctrine.orm.test_metadata_driver'))
            ->will($this->returnValue($driver));

        $driver->expects($this->once())
            ->method('addMethodCall')
            ->with($this->equalTo('addDriver'), $this->equalTo(array($this->driver, 'Ekino\WordpressBundle\Model')));

        $containerBuilder->expects($this->once())
            ->method('getDefinition')
            ->with($this->equalTo('doctrine.orm.test_metadata_driver'));

        $this->compilerPass->process($containerBuilder);
    }

    public function testcreateOrmMappingDriver()
    {
        $result = RegisterMappingsPass::createOrmMappingDriver($this->namespaces);

        $this->assertInstanceOf('Ekino\WordpressBundle\DependencyInjection\Compiler\RegisterMappingsPass', $result);
    }

    /**
     * @param string $parameterName
     *
     * @return bool
     */
    public function validateNoChainDriverServiceName($parameterName)
    {
        if ($parameterName == $this->enabledParameter) {
            return true;
        }

        if ($parameterName == 'ekino_wordpress.model_manager_name') {
            return false;
        }

        if ($parameterName == $this->fallbackManagerParameter) {
            return false;
        }

        $this->fail(sprintf('Invalid parameter name : "%s"', $parameterName));
    }

    /**
     * @param string $parameterName
     *
     * @return bool
     */
    public function validateNoChainDriverEkinoServiceName($parameterName)
    {
        if ($parameterName == $this->enabledParameter) {
            return true;
        }

        if ($parameterName == 'ekino_wordpress.model_manager_name') {
            return true;
        }

        $this->fail(sprintf('Invalid parameter name : "%s"', $parameterName));
    }

    /**
     * @param string $parameterName
     *
     * @return bool
     */
    public function validateDefaultServiceName($parameterName)
    {
        if ($parameterName == $this->enabledParameter) {
            return true;
        }

        if ($parameterName == 'ekino_wordpress.model_manager_name') {
            return false;
        }

        if ($parameterName == $this->fallbackManagerParameter) {
            return true;
        }

        $this->fail(sprintf('Invalid parameter name : "%s"', $parameterName));
    }
}
