INSERT INTO `pages` (`id`, `title`, `type`, `url`, `description`, `keywords`, `layout`, `controller`, `roles`, `view_count`) VALUES
(4,	'Blog',	NULL,	'blogView',	NULL,	NULL,	'right_middle',	NULL,	NULL,	0);

INSERT INTO `menu_items` (`title`, `menu_id`, `parent_id`, `page_id`, `url`, `onclick`, `target`, `tooltip`, `tooltip_position`, `icon`, `icon_position`, `languages`, `roles`, `is_enabled`, `item_order`) VALUES
('Blog',	1,	NULL,	NULL,	'blog',	NULL,	NULL,	NULL,	'top',	NULL,	'left',	NULL,	NULL,	1,	1);

INSERT INTO `content` (`page_id`, `widget_id`, `widget_order`, `layout`, `params`) VALUES
(4,	1,	1,	'right',	'{\"title\":\"Recent Posts\",\"html_en\":\"<p>{{blog_recent_posts}}<\\/p>\\r\\n\",\"roles\":null,\"content_id\":\"12\"}'),
(4,	1,	1,	'middle',	'{\"title\":\"Blog\",\"html_en\":\"<p>{{blog_content}}<\\/p>\\r\\n\",\"html_de\":null,\"roles\":null,\"content_id\":\"13\"}'),
(4,	1,	2,	'right',	'{\"title\":\"Recent Comments\",\"html_en\":\"<p>{{blog_recent_comments}}<\\/p>\\r\\n\",\"html_de\":null,\"roles\":null,\"content_id\":\"15\"}'),
(4,	1,	3,	'right',	'{\"title\":\"Archives\",\"html_en\":\"<p>{{blog_archives}}<\\/p>\\r\\n\",\"html_de\":null,\"roles\":null,\"content_id\":\"16\"}'),
(4,	1,	4,	'right',	'{\"title\":\"Categories\",\"html_en\":\"<p>{{blog_categories}}<\\/p>\\r\\n\",\"html_de\":null,\"roles\":null,\"content_id\":\"17\"}'),
(4,	1,	5,	'right',	'{\"title\":\"Tags\",\"html_en\":\"<p>{{blog_tags}}<\\/p>\\r\\n\",\"html_de\":null,\"roles\":null,\"content_id\":\"18\"}');


INSERT INTO `language_translations` (`id`, `language_id`, `scope`, `original`, `translated`, `checked`) VALUES
(91,	1,	'core',	'Blog',	'Blog',	0),
(224,	1,	'core',	'Blog Setting',	'Blog Einstellungen',	0),
(246,	1,	'blog',	'Categories',	'Kategorien',	0),
(247,	1,	'blog',	'Recent Comments',	'Letzten Kommentare',	0),
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


DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` varchar(255) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;