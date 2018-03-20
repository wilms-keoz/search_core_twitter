.. highlight:: typoscript

``Codappix\SearchCoreTwitter\Domain\Index\TweetIndexer``
========================================================

Allows to index recent tweets of a user timeline.

This indexer maps to https://developer.twitter.com/en/docs/tweets/timelines/api-reference/get-statuses-user_timeline.html

Therefore either ``user_id`` or ``screen_name`` has to be configured for the indexer.

Example::

   plugin.tx_searchcore.settings.indexing {
       tweetsFromPress {
           indexer = Codappix\SearchCoreTwitter\Domain\Index\TweetIndexer
           account = pressAccount
           parameters {
               screen_name = company-press
           }
       }
   }
