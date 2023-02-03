<?php
namespace Diol\Fileclip\Eloquent;

use \Mockery as m;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;

class AttachmentTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Mockery\MockInterface */
    private $eloquentInstance;
    /** @var  \Mockery\MockInterface */
    private $field;
    /** @var  \Mockery\MockInterface */
    private $uploader;
    /** @var Attachment */
    private $attachment;
    /** @var  \Mockery\MockInterface */
    private $queueContainer;
    /** @var  \Mockery\MockInterface */
    private $wrapperFactory;
    /** @var  \Mockery\MockInterface */
    private $fileWrapper;
    /** @var  \Mockery\MockInterface */
    private $storedFile;
    /** @var  AttachmentHandlerList */
    private $attachmentHandlerList;
    /** @var vfsStreamDirectory */
    private $tempDirectoryMock;

    public function setUp()
    {
        $this->eloquentInstance = m::mock('Illuminate\Database\Eloquent\Model');
        $this->field = 'field';
        $this->uploader = m::mock('Diol\Fileclip\Uploader\Uploader');
        $this->queueContainer = m::mock('Diol\Fileclip\Eloquent\AttachmentQueueContainer');
        $this->wrapperFactory = m::mock('Diol\Fileclip\InputFileWrapper\WrapperFactoryCollector');
        $this->fileWrapper = m::mock('Diol\Fileclip\InputFileWrapper\IWrapper');

        $this->storedFile = m::mock('Diol\Fileclip\Uploader\Stored');
        $this->storedFile->shouldReceive('getName')->andReturn('name');

        $this->attachmentHandlerList = new AttachmentHandlerList();

        $this->attachment = new Attachment(
            $this->wrapperFactory,
            $this->eloquentInstance,
            $this->field,
            $this->uploader,
            $this->queueContainer,
            $this->attachmentHandlerList
        );

        // default stubs to manage version queue
        $this->queueContainer->shouldReceive('getVersionQueue')->andReturn([])->byDefault();
        $this->queueContainer->shouldReceive('getDeleteQueue')->andReturn([])->byDefault();
        $this->queueContainer->shouldReceive('clearVersionQueue')->byDefault();
        $this->queueContainer->shouldReceive('clearDeleteQueue')->byDefault();

        // default stub for eloquent instance
        $this->eloquentInstance->shouldReceive('getAttribute')->with($this->field)->andReturn(null)->byDefault();

        vfsStreamWrapper::register();
        $this->tempDirectoryMock = vfsStream::newDirectory('temp');
        vfsStreamWrapper::setRoot($this->tempDirectoryMock);
    }

    public function tearDown()
    {
        m::close();
    }


    /**
     * File input exists
     * @param $result
     * @return m\Expectation
     */
    private function existsFileInput($result)
    {
        return $this->eloquentInstance->shouldReceive('offsetExists')->with($this->field . '_file')
            ->andReturn($result);
    }

    /**
     * Get file input value
     * @param $returnValue - file input value
     * @return m\Expectation
     */
    private function getFileInput($returnValue)
    {
        return $this->eloquentInstance->shouldReceive('offsetGet')->with($this->field . '_file')
            ->andReturn($returnValue);
    }

    /**
     * Delete input exists
     * @param $result
     * @return m\Expectation
     */
    public function existsDeleteInput($result)
    {
        return $this->eloquentInstance->shouldReceive('offsetExists')->with($this->field . '_remove')
            ->andReturn($result);
    }

    /**
     * Unset file and delete input
     * @return m\Expectation
     */
    private function unsetInput()
    {
        $this->eloquentInstance->shouldReceive('offsetUnset')->with($this->field . '_file')->once()
            ->ordered('unset_group');
        $this->eloquentInstance->shouldReceive('offsetUnset')->with($this->field . '_remove')->once()
            ->ordered('unset_group');
    }


    public function testSavingWith_NoDeleteInput_FileInput_NoOldData_Stored()
    {
        $this->existsFileInput(true)->ordered('input_existence');
        $this->existsDeleteInput(false)->ordered('input_existence');

        $this->getFileInput('file')->once()->ordered();

        $this->wrapperFactory->shouldReceive('getWrapper')->with('file')->andReturn($this->fileWrapper);
        $this->uploader->shouldReceive('store')->with($this->fileWrapper)->once()->andReturn($this->storedFile);
        $this->eloquentInstance->shouldReceive('offsetSet')->with('field', 'name')->once();

        $this->eloquentInstance->shouldReceive('getOriginal')->with($this->field)->andReturn(null);
        $this->queueContainer->shouldReceive('addToVersionQueue')->with($this->storedFile)->once();


        $this->unsetInput();
        $this->attachment->saving();
    }

    public function testSavingWith_NoDeleteInput_FileInput_NoOldData_NotStored()
    {
        $this->existsFileInput(true)->ordered('input_existence');
        $this->existsDeleteInput(false)->ordered('input_existence');

        $this->getFileInput('file')->once()->ordered();

        $this->wrapperFactory->shouldReceive('getWrapper')->with('file')->andReturn($this->fileWrapper);
        $this->uploader->shouldReceive('store')->with($this->fileWrapper)->once()->andReturn(null);

        $this->unsetInput();

        $this->attachment->saving();
    }

    public function testSavingWith_NoDeleteInput_FileInput_OldData_Stored()
    {
        $this->existsFileInput(true)->ordered('input_existence');
        $this->existsDeleteInput(false)->ordered('input_existence');

        $this->getFileInput('file')->once()->ordered();

        $this->wrapperFactory->shouldReceive('getWrapper')->with('file')->andReturn($this->fileWrapper);
        $this->uploader->shouldReceive('store')->with($this->fileWrapper)->once()->andReturn($this->storedFile);
        $this->eloquentInstance->shouldReceive('offsetSet')->with('field', 'name')->once();

        $this->eloquentInstance->shouldReceive('getOriginal')->with($this->field)->andReturn('original');
        $this->queueContainer->shouldReceive('addToDeleteQueue')->with('original')->once();
        $this->queueContainer->shouldReceive('addToVersionQueue')->with($this->storedFile)->once();

        $this->unsetInput();

        $this->attachment->saving();
    }

    public function testSavingWith_NoDeleteInput_FileInput_OldData_NotStored()
    {
        $this->existsFileInput(true)->ordered('input_existence');
        $this->existsDeleteInput(false)->ordered('input_existence');

        $this->getFileInput('file')->once()->ordered();

        $this->wrapperFactory->shouldReceive('getWrapper')->with('file')->andReturn($this->fileWrapper);
        $this->uploader->shouldReceive('store')->with($this->fileWrapper)->once()->andReturn(null);

        $this->unsetInput();

        $this->attachment->saving();
    }

    public function testSavingWith_NoDeleteInput_NoFileInput()
    {
        $this->existsFileInput(false);
        $this->existsDeleteInput(false);
        $this->unsetInput();
        $this->attachment->saving();
    }

    public function testSavingWhenOldAndCurrentFileNamesEquals()
    {
        $this->existsFileInput(true)->ordered('input_existence');
        $this->existsDeleteInput(false)->ordered('input_existence');

        $this->getFileInput('file')->once()->ordered();

        $this->wrapperFactory->shouldReceive('getWrapper')->with('file')->andReturn($this->fileWrapper);
        $this->uploader->shouldReceive('store')->with($this->fileWrapper)->once()->andReturn($this->storedFile);
        $this->eloquentInstance->shouldReceive('offsetSet')->with('field', 'name')->once();

        $this->eloquentInstance->shouldReceive('getAttribute')->with('field')->andReturn('name');
        $this->eloquentInstance->shouldReceive('getOriginal')->with($this->field)->andReturn('name');

        $this->queueContainer->shouldReceive('addToDeleteQueue')->never();
        $this->queueContainer->shouldReceive('addToVersionQueue')->with($this->storedFile)->once();

        $this->unsetInput();

        $this->attachment->saving();
    }




    public function testSavingWith_DeleteInput_FileInput_OldData()
    {
        $this->existsDeleteInput(true)->ordered('input_existence');
        $this->existsFileInput(true)->ordered('input_existence');

        $this->eloquentInstance->shouldReceive('offsetSet')->with($this->field, null)->once();
        $this->eloquentInstance->shouldReceive('getOriginal')->with($this->field)->andReturn('original');
        $this->queueContainer->shouldReceive('addToDeleteQueue')->with('original')->once();

        $this->unsetInput();

        $this->attachment->saving();
    }

    public function testSavingWith_DeleteInput_NoFileInput_OldData()
    {
        $this->existsDeleteInput(true)->ordered('file_existence');
        $this->existsFileInput(false)->ordered('file_existence');

        $this->eloquentInstance->shouldReceive('offsetSet')->with($this->field, null)->once();
        $this->eloquentInstance->shouldReceive('getOriginal')->with($this->field)->andReturn('original');
        $this->queueContainer->shouldReceive('addToDeleteQueue')->with('original')->once();

        $this->unsetInput();

        $this->attachment->saving();
    }

    public function testSavingWith_DeleteInput_FileInput_NoOldData()
    {
        $this->existsDeleteInput(true)->ordered('file_existence');
        $this->existsFileInput(true)->ordered('file_existence');

        $this->eloquentInstance->shouldReceive('offsetSet')->with($this->field, null)->once();
        $this->eloquentInstance->shouldReceive('getOriginal')->with($this->field)->andReturn(null);

        $this->unsetInput();

        $this->attachment->saving();
    }

    public function testSavingWith_DeleteInput_NoFileInput_NoOldData()
    {
        $this->existsDeleteInput(true)->ordered('file_existence');
        $this->existsFileInput(false)->ordered('file_existence');

        $this->eloquentInstance->shouldReceive('offsetSet')->with($this->field, null)->once();
        $this->eloquentInstance->shouldReceive('getOriginal')->with($this->field)->andReturn(null);

        $this->unsetInput();

        $this->attachment->saving();
    }



    public function testDeletingWithOldData()
    {
        $this->eloquentInstance->shouldReceive('getOriginal')->with($this->field)->andReturn('original');
        $this->queueContainer->shouldReceive('addToDeleteQueue')->with('original')->once();
        $this->attachment->deleting();
    }

    public function testDeletingWithNoOldData()
    {
        $this->eloquentInstance->shouldReceive('getOriginal')->with($this->field)->andReturn(null);
        $this->attachment->deleting();
    }

    public function testDeletingWhenOldAndCurrentFileNamesEquals()
    {
        $this->eloquentInstance->shouldReceive('getAttribute')->with($this->field)->andReturn('original');
        $this->eloquentInstance->shouldReceive('getOriginal')->with($this->field)->andReturn('original');
        $this->queueContainer->shouldReceive('addToDeleteQueue')->with('original')->once();
        $this->attachment->deleting();
    }



    public function testFinishedDeleteQueue()
    {
        $this->queueContainer->shouldReceive('getDeleteQueue')->andReturn(['1', '2', '3'])->ordered();

        $this->uploader->shouldReceive('retrieve')->with('1')
            ->andReturn($s1 = m::mock('Diol\Fileclip\Uploader\Stored'));
        $this->uploader->shouldReceive('retrieve')->with('2')
            ->andReturn($s2 = m::mock('Diol\Fileclip\Uploader\Stored'));
        $this->uploader->shouldReceive('retrieve')->with('3')
            ->andReturn(null);

        $s1->shouldReceive('remove')->once();
        $s2->shouldReceive('remove')->once();

        $this->queueContainer->shouldReceive('clearDeleteQueue')->once()->ordered();

        $this->attachment->finished();
    }

    public function testFinishedVersionQueue()
    {
        $versionQueue = [
            m::mock(),
            m::mock(),
        ];

        $this->queueContainer->shouldReceive('getVersionQueue')->andReturn($versionQueue)->ordered();

        foreach ($versionQueue as $mock) {
            $mock->shouldReceive('createVersions')->once();
        }

        $this->queueContainer->shouldReceive('clearVersionQueue')->once()->ordered();

        $this->attachment->finished();
    }


    public function testRetrieveCurrent()
    {
        $this->eloquentInstance->shouldReceive('getAttribute')->with($this->field)->andReturn('field_value');
        $this->uploader->shouldReceive('retrieve')->with('field_value')->andReturn($this->storedFile);
        $this->assertEquals($this->storedFile, $this->attachment->retrieveCurrent());
    }

    public function testRetrieveCurrentIncorrect()
    {
        $this->eloquentInstance->shouldReceive('getAttribute')->with($this->field)->andReturn('field_value');
        $this->uploader->shouldReceive('retrieve')->with('field_value')->andReturn(null);
        $this->assertNull($this->attachment->retrieveCurrent());
    }


    public function testGetAbsoluteStoragePath()
    {
        $this->tempDirectoryMock->addChild(vfsStream::newFile('absolute_storage_path'));

        $this->eloquentInstance->shouldReceive('getAttribute')->with($this->field)->andReturn('field_value');
        $this->uploader->shouldReceive('retrieve')->with('field_value')->andReturn($this->storedFile);

        $this->storedFile->shouldReceive('getAbsoluteStoragePath')->withNoArgs()->andReturn(vfsStream::url('temp/absolute_storage_path'));
        $this->assertEquals('vfs://temp/absolute_storage_path', $this->attachment->getAbsoluteStoragePath());
    }


    public function testGetAbsoluteStoragePathWithoutStoredFile()
    {
        $this->eloquentInstance->shouldReceive('getAttribute')->with($this->field)->andReturn('field_value');
        $this->uploader->shouldReceive('retrieve')->with('field_value')->andReturn(null);

        $this->assertEquals(null, $this->attachment->getAbsoluteStoragePath());
    }


    public function testGetAbsolutePath()
    {
        $this->tempDirectoryMock->addChild(vfsStream::newFile('absolute_path'));
        $this->tempDirectoryMock->addChild(vfsStream::newFile('version_absolute_path'));

        $this->eloquentInstance->shouldReceive('getAttribute')->with($this->field)->andReturn('field_value');
        $this->uploader->shouldReceive('retrieve')->with('field_value')->andReturn($this->storedFile);

        $this->storedFile->shouldReceive('getAbsolutePath')->with(null)->andReturn(vfsStream::url('temp/absolute_path'));
        $this->assertEquals('vfs://temp/absolute_path', $this->attachment->getAbsolutePath());

        $this->storedFile->shouldReceive('getAbsolutePath')->with('version')->andReturn(vfsStream::url('temp/version_absolute_path'));
        $this->assertEquals('vfs://temp/version_absolute_path', $this->attachment->getAbsolutePath('version'));
    }

    public function testGetAbsolutePathWithoutStoredFile()
    {
        $this->eloquentInstance->shouldReceive('getAttribute')->with($this->field)->andReturn('field_value');
        $this->uploader->shouldReceive('retrieve')->with('field_value')->andReturn(null);

        $this->assertEquals(null, $this->attachment->getAbsolutePath());
    }

    public function testGetRelativePath()
    {
        $this->tempDirectoryMock->addChild(vfsStream::newFile('absolute_path'));
        $this->tempDirectoryMock->addChild(vfsStream::newFile('version_absolute_path'));

        $this->eloquentInstance->shouldReceive('getAttribute')->with($this->field)->andReturn('field_value');
        $this->uploader->shouldReceive('retrieve')->with('field_value')->andReturn($this->storedFile);

        $this->storedFile->shouldReceive('getAbsolutePath')->with(null)->andReturn(vfsStream::url('temp/absolute_path'));
        $this->storedFile->shouldReceive('getAbsolutePath')->with('version')->andReturn(vfsStream::url('temp/version_absolute_path'));

        $this->storedFile->shouldReceive('getRelativePath')->with(null)->andReturn('relative_path');
        $this->assertEquals('relative_path', $this->attachment->getRelativePath());

        $this->storedFile->shouldReceive('getRelativePath')->with('version')->andReturn('version_relative_path');
        $this->assertEquals('version_relative_path', $this->attachment->getRelativePath('version'));
    }

    public function testGetRelativePathWithoutStoredFile()
    {
        $this->eloquentInstance->shouldReceive('getAttribute')->with($this->field)->andReturn('field_value');
        $this->uploader->shouldReceive('retrieve')->with('field_value')->andReturn(null);

        $this->assertEquals(null, $this->attachment->getRelativePath());
    }



    public function testAttachmentHandlerOnFinished()
    {
        $this->queueContainer->shouldReceive('getDeleteQueue')->andReturn([])->ordered();

        $this->uploader->shouldReceive('retrieve')->with('1')
            ->andReturn($s1 = m::mock('Diol\Fileclip\Uploader\Stored'));
        $this->uploader->shouldReceive('retrieve')->with('2')
            ->andReturn($s2 = m::mock('Diol\Fileclip\Uploader\Stored'));

        $s1->shouldReceive('createVersions');
        $s2->shouldReceive('createVersions');

        $this->queueContainer->shouldReceive('getVersionQueue')->andReturn([$s1, $s2])->ordered();

        $h1 = m::mock('Diol\Fileclip\Eloquent\AttachmentHandlerInterface');
        $h1->shouldReceive('getEvent')->andReturn('finished');
        $h1->shouldReceive('handle')->with($this->attachment)->once();

        $h2 = m::mock('Diol\Fileclip\Eloquent\AttachmentHandlerInterface');
        $h2->shouldReceive('getEvent')->andReturn('finished');
        $h2->shouldReceive('handle')->with($this->attachment)->once();

        $h3 = m::mock('Diol\Fileclip\Eloquent\AttachmentHandlerInterface');
        $h3->shouldReceive('getEvent')->andReturn('somethingElse');

        $this->attachmentHandlerList->addAttachmentHandler($h1);
        $this->attachmentHandlerList->addAttachmentHandler($h2);
        $this->attachmentHandlerList->addAttachmentHandler($h3);

        $this->attachment->finished();
    }

    public function testAttachmentHandlerOnUpdateVersions()
    {
        $this->eloquentInstance->shouldReceive('getAttribute')->andReturn('hello');
        $this->uploader->shouldReceive('retrieve')->andReturn($s = m::mock('Diol\Fileclip\Uploader\Stored'));
        $s->shouldReceive('getAbsoluteStoragePath')->andReturn(__FILE__);
        $s->shouldReceive('removeVersions');
        $s->shouldReceive('createVersions');


        $h1 = m::mock('Diol\Fileclip\Eloquent\AttachmentHandlerInterface');
        $h1->shouldReceive('getEvent')->andReturn('versionsUpdated');
        $h1->shouldReceive('handle')->with($this->attachment)->once();

        $h2 = m::mock('Diol\Fileclip\Eloquent\AttachmentHandlerInterface');
        $h2->shouldReceive('getEvent')->andReturn('versionsUpdated');
        $h2->shouldReceive('handle')->with($this->attachment)->once();

        $h3 = m::mock('Diol\Fileclip\Eloquent\AttachmentHandlerInterface');
        $h3->shouldReceive('getEvent')->andReturn('somethingElse');

        $this->attachmentHandlerList->addAttachmentHandler($h1);
        $this->attachmentHandlerList->addAttachmentHandler($h2);
        $this->attachmentHandlerList->addAttachmentHandler($h3);

        $this->attachment->updateVersions();
    }
}
