<?php
namespace Codappix\SearchCoreTwitter\Domain\Index;

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

use Codappix\SearchCore\Domain\Index\AbstractIndexer;

/**
 * Indexer for search_core to integrate Twitter indexing.
 */
class TweetIndexer extends AbstractIndexer
{
    /**
     * @var \Codappix\SearchCoreTwitter\Domain\Index\IndexServiceInterface
     * @inject
     */
    protected $indexService;

    protected function getEndpoint() : string
    {
        return 'statuses/user_timeline';
    }

    /**
     * @return array|null
     */
    protected function getRecords(int $offset, int $limit)
    {
        // We currently can not work with offset.
        // Twitter needs a tweet ID as offset.
        // We therefore only provide the first batch.
        if ($offset > 0) {
            return null;
        }

        $parameters = array_merge(
            $this->configuration->get('indexing.' . $this->identifier . '.parameters'),
            [
                'count' => $this->getLimit(),
            ]
        );

        return $this->indexService->getResult(
            $this->getEndpoint(),
            $this->configuration->get('indexing.' . $this->identifier . '.twitterAccount'),
            $parameters
        );
    }

    /**
     * @throws BadMethodCallException
     */
    protected function getRecord(int $identifier) : array
    {
        throw new \BadMethodCallException('Not supported yet.', 1520933404);
    }

    protected function getDocumentName() : string
    {
        return $this->identifier;
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function prepareRecord(array &$record)
    {
        parent::prepareRecord($record);

        if (!isset($record['id_str'])) {
            throw new \InvalidArgumentException('No "id_str" available for tweet.', 1520933234);
        }

        $record['search_identifier'] = $record['id_str'];
    }
}
