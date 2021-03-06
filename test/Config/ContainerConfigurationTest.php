<?php
namespace Lcobucci\DependencyInjection\Config;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class ContainerConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Handler
     */
    private $handler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->handler = $this->getMock(Handler::class);
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     */
    public function constructShouldConfigureTheAttributes()
    {
        $config = new ContainerConfiguration(['services.xml'], [$this->handler], ['test']);

        $this->assertAttributeEquals(['services.xml'], 'files', $config);
        $this->assertAttributeSame([$this->handler], 'handlers', $config);
        $this->assertAttributeEquals(['test'], 'paths', $config);
        $this->assertAttributeEquals(sys_get_temp_dir(), 'dumpDir', $config);
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getFiles
     */
    public function getFilesShouldReturnTheFileList()
    {
        $config = new ContainerConfiguration(['services.xml']);

        $this->assertEquals(['services.xml'], $config->getFiles());
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::addFile
     */
    public function addFileShouldAppendANewFileToTheList()
    {
        $config = new ContainerConfiguration();
        $config->addFile('services.xml');

        $this->assertAttributeEquals(['services.xml'], 'files', $config);
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getHandlers
     */
    public function getHandlersShouldReturnTheHandlersList()
    {
        $config = new ContainerConfiguration([], [$this->handler]);

        $this->assertSame([$this->handler], $config->getHandlers());
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::addHandler
     */
    public function addHandlerShouldAppendANewHandlerToTheList()
    {
        $config = new ContainerConfiguration();
        $config->addHandler($this->handler);

        $this->assertAttributeSame([$this->handler], 'handlers', $config);
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getPaths
     */
    public function getPathsShouldReturnThePathsList()
    {
        $config = new ContainerConfiguration([], [], ['config']);

        $this->assertEquals(['config'], $config->getPaths());
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::addPath
     */
    public function addPathShouldAppendANewPathToTheList()
    {
        $config = new ContainerConfiguration();
        $config->addPath('services');

        $this->assertAttributeEquals(['services'], 'paths', $config);
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::setBaseClass
     */
    public function setBaseClassShouldChangeTheAttribute()
    {
        $config = new ContainerConfiguration();
        $config->setBaseClass('Test');

        $this->assertAttributeEquals('Test', 'baseClass', $config);

        return $config;
    }

    /**
     * @test
     *
     * @depends setBaseClassShouldChangeTheAttribute
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getBaseClass
     */
    public function getBaseClassShouldReturnTheAttributeValue(ContainerConfiguration $config)
    {
        $this->assertEquals('Test', $config->getBaseClass());
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getDumpDir
     */
    public function getDumpDirShouldReturnTheAttributeValue()
    {
        $config = new ContainerConfiguration();

        $this->assertEquals(sys_get_temp_dir(), $config->getDumpDir());
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::setDumpDir
     */
    public function setDumpDirShouldChangeTheAttribute()
    {
        $config = new ContainerConfiguration();
        $config->setDumpDir('/test/');

        $this->assertAttributeEquals('/test', 'dumpDir', $config);
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getClassName
     */
    public function getClassNameShouldCreateAHashFromFilesAndPaths()
    {
        $config = new ContainerConfiguration(['services.xml'], [], ['config']);

        $this->assertEquals(
            'Project' . md5(implode(';', ['services.xml', 'config'])) . 'ServiceContainer',
            $config->getClassName()
        );
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getClassName
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getDumpFile
     */
    public function getDumpFileShouldReturnTheFullPathOfDumpFile()
    {
        $config = new ContainerConfiguration();

        $this->assertEquals(
            sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Project' . md5('') . 'ServiceContainer.php',
            $config->getDumpFile()
        );
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getClassName
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getDumpOptions
     */
    public function getDumpOptionsShouldReturnTheDumpingInformations()
    {
        $config = new ContainerConfiguration();
        $options = ['class' => 'Project' . md5('') . 'ServiceContainer'];

        $this->assertEquals($options, $config->getDumpOptions());
    }

    /**
     * @test
     *
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::__construct
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::setBaseClass
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getClassName
     * @covers Lcobucci\DependencyInjection\Config\ContainerConfiguration::getDumpOptions
     */
    public function getDumpOptionsShouldIncludeBaseWhenWasConfigured()
    {
        $config = new ContainerConfiguration();
        $config->setBaseClass('Test');
        $options = ['class' => 'Project' . md5('') . 'ServiceContainer', 'base_class' => 'Test'];

        $this->assertEquals($options, $config->getDumpOptions());
    }
}
