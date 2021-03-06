INSERT INTO `pages` (`id`, `title`, `type`, `url`, `description`, `keywords`, `layout`, `controller`, `roles`, `view_count`) VALUES
(4,	'Blog',	NULL,	'blogView',	NULL,	NULL,	'right_middle',	NULL,	NULL,	0);

INSERT INTO `menu_items` (`title`, `menu_id`, `parent_id`, `page_id`, `url`, `onclick`, `target`, `tooltip`, `tooltip_position`, `icon`, `icon_position`, `languages`, `roles`, `is_enabled`, `item_order`) VALUES
('Blog',	1,	NULL,	NULL,	'blog',	NULL,	NULL,	NULL,	'top',	NULL,	'left',	NULL,	NULL,	1,	1);

INSERT INTO `content` (`page_id`, `widget_id`, `widget_order`, `layout`, `params`) VALUES
(4,	1,	1,	'right',	'{\"title\":\"Recent Posts\",\"html_en\":\"<p>{{recentPosts}}<\\/p>\\r\\n\",\"roles\":null,\"content_id\":\"12\"}'),
(4,	1,	1,	'middle',	'{\"title\":\"\",\"html_en\":\"<p>{{content}}<\\/p>\\r\\n\",\"html_de\":null,\"roles\":null,\"content_id\":\"13\"}'),
(4,	1,	2,	'right',	'{\"title\":\"Recent Comments\",\"html_en\":\"<p>{{recentComments}}<\\/p>\\r\\n\",\"html_de\":null,\"roles\":null,\"content_id\":\"15\"}'),
(4,	1,	3,	'right',	'{\"title\":\"Categories\",\"html_en\":\"<p>{{categories}}<\\/p>\\r\\n\",\"html_de\":null,\"roles\":null,\"content_id\":\"17\"}'),
(4,	1,	4,	'right',	'{\"title\":\"Archives\",\"html_en\":\"<p>{{archives}}<\\/p>\\r\\n\",\"html_de\":null,\"roles\":null,\"content_id\":\"16\"}'),
(4,	1,	5,	'right',	'{\"title\":\"Tags\",\"html_en\":\"<p>{{tags}}<\\/p>\\r\\n\",\"html_de\":null,\"roles\":null,\"content_id\":\"18\"}');


INSERT INTO `language_translations` (`id`, `language_id`, `scope`, `original`, `translated`, `checked`) VALUES
(91,	1,	'core',	'Blog',	'Blog',	0),
(224,	1,	'core',	'Blog Setting',	'Blog Einstellungen',	0),
(246,	1,	'blog',	'Categories',	'Kategorien',	0),
(247,	1,	'blog',	'Recent Comments',	'Letzte Kommentare',	0),
(248,	1,	'blog',	'Archives',	'Archive',	0),
(256,	1,	'blog',	'Tags',	'Tags',	0),
(258,	1,	'blog',	'Post',	'Eintragen',	0),
(263,	1,	'blog',	'List categories',	'Kategorien Auflisten',	0),
(265,	1,	'blog',	'List tags',	'Tags auflisten',	0),
(266,	1,	'blog',	'List comments',	'Kommentare auflisten',	0),
(270,	1,	'blog',	'Comments',	'Kommentare',	0),
(271,	1,	'blog',	'Create new post',	'Neuen Eintrag erstellen',	0),
(277,	1,	'blog',	'Are you really want to delete this post?',	'Möchten Sie wirklich diesen Eintrag löschen?',	0),
(278,	1,	'blog',	'Create new categorie',	'Neue Kategorie erstellen',	0),
(279,	1,	'blog',	'Create new tags',	'Neuen Tag erstellen',	0);


DROP TABLE IF EXISTS `blog_tags`;
DROP TABLE IF EXISTS `blog_categories`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `blog`;
DROP TABLE IF EXISTS `tags`;
DROP TABLE IF EXISTS `categorie_items`;
DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `categorie_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `tooltip` varchar(255) DEFAULT NULL,
  `tooltip_position` varchar(10) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `icon_position` varchar(10) NOT NULL,
  `languages` varchar(150) DEFAULT NULL,
  `roles` varchar(150) DEFAULT NULL,
  `is_enabled` tinyint(1) DEFAULT NULL,
  `item_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fki-categorie_items-categories-categorie_id-id` (`categorie_id`),
  KEY `fki-categorie_items-categorie_items-parent_id-id` (`parent_id`),
  CONSTRAINT `fk-categorie_items-categories-categorie_id-id` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `fk-categorie_items-categorie_items-parent_id-id` FOREIGN KEY (`parent_id`) REFERENCES `categorie_items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` text,
  `creation_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,

  `user_id` int(11) NOT NULL,

  PRIMARY KEY (`id`),
  KEY `fk_blog_users` (`user_id`),
  CONSTRAINT `fk_blog_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/** Relations to tables blog, categories, tags, comments, archives **/

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `body` text NOT NULL,
  `email` varchar(20) NOT NULL,
  `is_published` tinyint(1) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,

  `blog_id` int(11) NOT NULL,

  PRIMARY KEY (`id`),
  KEY `fk_comments_blog` (`blog_id`),
  CONSTRAINT `fk_comments_blog` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `categories_id` int(11),
  `categorie_items_id` int(11),

  PRIMARY KEY (`id`),
  KEY `fk_blog_categories_categories` (`categories_id`),
  KEY `fk_blog_categories_categorie_items` (`categorie_items_id`),
  KEY `fk_blog_categories_blog` (`blog_id`),
  CONSTRAINT `fk_blog_categories_categories` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_blog_categories_categorie_items` FOREIGN KEY (`categorie_items_id`) REFERENCES `categorie_items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_blog_categories_blog` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `blog_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `tags_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_blog_tags_tags` (`tags_id`),
  KEY `fk_blog_tags_blog` (`blog_id`),
  CONSTRAINT `fk_blog_tags_tags` FOREIGN KEY (`tags_id`) REFERENCES `tags` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_blog_tags_blog` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
