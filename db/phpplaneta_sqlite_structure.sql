CREATE TABLE IF NOT EXISTS `ppn_news` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `fk_news_category_id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL UNIQUE,
  `text` text NOT NULL,
  `datetime_added` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `comments_enabled` tinyint(1) NOT NULL
);

CREATE TABLE IF NOT EXISTS `ppn_news_categories` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `title` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS `ppn_news_comments` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `fk_news_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `url` varchar(100) DEFAULT NULL,
  `comment` text NOT NULL,
  `datetime_added` datetime NOT NULL,
  `active` tinyint(1) NOT NULL
);

CREATE TABLE IF NOT EXISTS `ppn_news_tags` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `title` varchar(25) NOT NULL,
  `slug` varchar(25) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS `ppn_news_tags_relations` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `fk_news_id` int(11) NOT NULL,
  `fk_news_tag_id` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `ppn_users` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(100) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `datetime_added` datetime NOT NULL,
  `role` varchar(25) NOT NULL,
  `active` tinyint(1) NOT NULL
);