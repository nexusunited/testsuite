<?php

namespace PyzTest\_support;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SftpFileTransfer;
use Generated\Shared\Transfer\SshSettingsTransfer;
use Generated\Shared\Transfer\SshSftpTransfer;
use Pyz\Zed\Ssh\Business\SshFacade;

class SshHelper extends Unit
{
    public const TEST_DIRECTORY = 'integration test directory';
    /**
     * @var string
     */
    public $fileName = 'INTEGRATION KEY';

    /**
     * @param string $sshBody
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Shared\Zed\Ssh\Business\SshFacade
     */
    public function createSshFacadeMock(string $sshBody): SshFacade
    {
        $dhlBusinessFactoryMock = $this->getMockBuilder(SshFacade::class)
            ->setMethods([ 'listDirectory', 'readFiles', 'deleteFiles'])
            ->getMock();
        $dhlBusinessFactoryMock->method('listDirectory')->willReturn($this->createSshSftpTransfer($sshBody));
        $dhlBusinessFactoryMock->method('readFiles')->willReturn($this->createSshSftpTransfer($sshBody));
        $dhlBusinessFactoryMock->method('deleteFiles')->willReturn($this->createSshSftpTransfer($sshBody));

        return $dhlBusinessFactoryMock;
    }

    /**
     * @param string $body
     *
     * @return \Generated\Shared\Transfer\SshSftpTransfer
     */
    public function createSshSftpTransfer(string $body): SshSftpTransfer
    {
        return (new SshSftpTransfer())
                ->setDirectory(self::TEST_DIRECTORY)
                ->setRecursive(false)
                ->addSftpFile((new SftpFileTransfer())->setName($this->fileName)->setContent($body))
                ->setSshSettings(new SshSettingsTransfer());
    }
}
