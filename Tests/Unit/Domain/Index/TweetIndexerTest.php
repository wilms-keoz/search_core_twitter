<?php
namespace Codappix\SearchCoreTwitter\Tests\Unit\Domain\Index;

/*
 * Copyright (C) 2018  Daniel Siepmann <coding@daniel-siepmann.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

use Codappix\SearchCoreTwitter\Domain\Index\IndexServiceInterface;
use Codappix\SearchCoreTwitter\Domain\Index\TweetIndexer;
use Codappix\SearchCoreTwitter\Tests\Unit\AbstractUnitTestCase;
use Codappix\SearchCore\Configuration\ConfigurationContainerInterface;
use Codappix\SearchCore\Connection\ConnectionInterface;

class TweetIndexerTest extends AbstractUnitTestCase
{
    /**
     * @var TweetIndexer
     */
    protected $subject;

    /**
     * @var ConnectionInterface
     */
    protected $connectionMock;

    /**
     * @var ConfigurationContainerInterface
     */
    protected $configurationMock;

    public function setUp()
    {
        $this->connectionMock = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $this->configurationMock = $this->getMockBuilder(ConfigurationContainerInterface::class)->getMock();
        $this->indexServiceMock = $this->getMockBuilder(IndexServiceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject = new TweetIndexer(
            $this->connectionMock,
            $this->configurationMock
        );
        $this->subject->setIdentifier('identifier');
        $this->inject($this->subject, 'logger', $this->getMockedLogger());
        $this->inject($this->subject, 'indexService', $this->indexServiceMock);
    }

    /**
     * @test
     */
    public function indexAllDocumentsWillCallIndexServiceAsExpected()
    {
        $this->configurationMock->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function (string $path) {
                if ($path === 'indexing.identifier.parameters') {
                    return [];
                }
                if ($path === 'indexing.identifier.twitterAccount') {
                    return 'configuredTwitterAccount';
                }
            }));

        $this->indexServiceMock->expects($this->once())
            ->method('getResult')
            ->with(
                'statuses/user_timeline',
                'configuredTwitterAccount',
                [
                    'count' => 50,
                ]
            );

        $this->subject->indexAllDocuments();
    }

    /**
     * @test
     */
    public function indexDocumentWillRaiseExpectedException()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->subject->indexDocument('10');
    }
}
