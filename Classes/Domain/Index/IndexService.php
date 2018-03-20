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

use Codappix\SearchCore\Configuration\ConfigurationContainerInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

/**
 * Concrete implementation to index twitter information
 * via "j7mbo/twitter-api-php".
 */
class IndexService implements IndexServiceInterface
{
    /**
     * @var ConfigurationContainerInterface
     */
    protected $configuration;

    /**
     * @var FrontendInterface
     */
    protected $cache;

    public function __construct(ConfigurationContainerInterface $configuration, CacheManager $cacheManager)
    {
        $this->cache = $cacheManager->getCache('searchcoretwitterApiRequests');
        $this->configuration = $configuration;
    }

    public function getResult(string $endpoint, string $accountIdentifier, array $parameters) : array
    {
        if (isset($parameters['count']) && $parameters['count'] > TwitterIndexerInterface::MAX_TWEET_COUNT) {
            $parameters['count'] = TwitterIndexerInterface::MAX_TWEET_COUNT;
        }

        $cacheIdentifier = sha1($endpoint . $accountIdentifier . implode($parameters));
        if (($records = $this->cache->get($cacheIdentifier)) === false) {
            $records = $this->getTwitterapiExchangeInstance($accountIdentifier)
                ->setGetfield('?' . http_build_query($parameters))
                ->buildOauth($this->getUrlWithEndpoint($endpoint), TwitterIndexerInterface::REQUEST_METHOD_GET)
                ->performRequest();

            $this->cache->set($cacheIdentifier, $records);
        }

        return json_decode($records, true);
    }

    protected function getTwitterapiExchangeInstance(string $accountIdentifier) : \TwitterAPIExchange
    {
        return new \TwitterAPIExchange(
            $this->configuration->get('connections.twitter.' . $accountIdentifier)
        );
    }

    protected function getUrlWithEndpoint(string $endpoint) : string
    {
        return TwitterIndexerInterface::API_BASE_URL . $endpoint . '.json';
    }
}
