<? php
namespace AppBundle\Tests;

use Symfony\Component\Asset\Exception\InvalidArgumentException;

class testTest extends \PHPUnit_Extensions_SeleniumTestCase
{
    protected function setUp(){
        var_dump('test selenium');
        $this->setBrowser("*chrome");
        $this->setBrowserUrl("http://twitter.com/");
    }

    public function testMyTestCase()
    {
        $this->assertTrue(false, 'Selenium test case');
    }


}
