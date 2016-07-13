<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014-2016 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZFTest\ContentValidation\InputFilter;

use Zend\Mvc\MvcEvent;
use ZF\ContentValidation\InputFilter\InputFilterPlugin;

class InputFilterPluginTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->event = $event = new MvcEvent();

        $controller = $this->createMock('Zend\Mvc\Controller\AbstractController');
        $controller->expects($this->any())
            ->method('getEvent')
            ->will($this->returnValue($event));

        $this->plugin = new InputFilterPlugin();
        $this->plugin->setController($controller);
    }

    public function testMissingInputFilterParamInEventCausesPluginToYieldNull()
    {
        $this->assertNull($this->plugin->__invoke());
    }

    public function testInvalidTypeInEventInputFilterParamCausesPluginToYieldNull()
    {
        $this->event->setParam('ZF\ContentValidation\InputFilter', (object) ['foo' => 'bar']);
        $this->assertNull($this->plugin->__invoke());
    }

    public function testValidInputFilterInEventIsReturnedByPlugin()
    {
        $inputFilter = $this->createMock('Zend\InputFilter\InputFilterInterface');
        $this->event->setParam('ZF\ContentValidation\InputFilter', $inputFilter);
        $this->assertSame($inputFilter, $this->plugin->__invoke());
    }
}
