--
-- Table structure for table `favorites_users`
--
-- This table indicates which sentences are favorited by which user.
--
-- favorite_id Id of the sentence.
-- user_id     Id of the user.
--

CREATE TABLE IF NOT EXISTS `favorites_users` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  UNIQUE KEY `favorite_id` (`favorite_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;