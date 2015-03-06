-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `phalconeye` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `phalconeye`;

DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `object` varchar(55) NOT NULL,
  `action` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `value` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`object`,`action`,`role_id`),
  KEY `fki-access-roles-role_id-id` (`role_id`),
  CONSTRAINT `fk-access-roles-role_id-id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `access` (`object`, `action`, `role_id`, `value`) VALUES
('AdminArea',	'access',	1,	'allow'),
('AdminArea',	'access',	2,	'deny'),
('AdminArea',	'access',	3,	'deny');

DROP TABLE IF EXISTS `content`;
CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `widget_id` int(11) NOT NULL,
  `widget_order` int(5) NOT NULL,
  `layout` varchar(50) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fki-content-widgets-widget_id-id` (`widget_id`),
  KEY `fki-content-pages-page_id-id` (`page_id`),
  CONSTRAINT `fk-content-pages-page_id-id` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`),
  CONSTRAINT `fk-content-widgets-widget_id-id` FOREIGN KEY (`widget_id`) REFERENCES `widgets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `content` (`id`, `page_id`, `widget_id`, `widget_order`, `layout`, `params`) VALUES
(1,	3,	1,	1,	'top',	'{\"title\":\"Header\",\"html_en\":\"<p>Header<\\/p>\\r\\n\",\"html_ru\":\"\",\"html\":null,\"roles\":null,\"content_id\":\"67\"}'),
(2,	3,	1,	1,	'right',	'{\"title\":\"Right\",\"html_en\":\"<p>Right<\\/p>\\r\\n\",\"html_ru\":\"\",\"html\":null,\"roles\":null,\"content_id\":\"70\"}'),
(3,	3,	1,	1,	'left',	'{\"title\":\"Left\",\"html_en\":\"<p>Left<\\/p>\\r\\n\",\"html_ru\":\"\",\"html\":null,\"roles\":null,\"content_id\":\"71\"}'),
(4,	3,	1,	1,	'middle',	'{\"title\":\"Content\",\"html_en\":\"<p>Content<\\/p>\\r\\n\",\"html_ru\":\"\",\"html\":null,\"roles\":null}'),
(5,	3,	1,	2,	'top',	'{\"title\":\"Header2\",\"html_en\":\"<p>Header2<\\/p>\\r\\n\",\"html_ru\":\"\",\"html\":null,\"roles\":null}'),
(6,	3,	1,	2,	'right',	'{\"title\":\"Right2\",\"html_en\":\"<p>Right2<\\/p>\\r\\n\",\"html_ru\":\"\",\"html\":null,\"roles\":null}'),
(7,	3,	1,	2,	'middle',	'{\"title\":\"Content2\",\"html_en\":\"<p>Content2<\\/p>\\r\\n\",\"html_ru\":\"\",\"html\":null,\"roles\":null}'),
(8,	3,	1,	2,	'left',	'{\"title\":\"Left2\",\"html_en\":\"<p>Left2<\\/p>\\r\\n\",\"html_ru\":\"\",\"html\":null,\"roles\":null}'),
(9,	1,	3,	1,	'middle',	'{\"logo\":\"assets\\/img\\/core\\/pe_logo.png\",\"show_title\":null,\"show_auth\":\"1\",\"roles\":null,\"content_id\":\"112\"}'),
(10,	1,	2,	2,	'middle',	'{\"title\":\"\",\"class\":\"\",\"menu\":\"Default menu\",\"menu_id\":\"1\",\"roles\":null}'),
(11,	2,	1,	1,	'middle',	'{\"title\":\"\",\"html_en\":\"<p style=\\\"text-align: center;\\\">PhalconEye v.0.4.0<\\/p>\\r\\n\",\"roles\":null,\"content_id\":\"11\"}');

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `language` varchar(2) NOT NULL,
  `locale` varchar(5) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `languages` (`id`, `name`, `language`, `locale`, `icon`) VALUES
(1,	'English',	'en',	'en_US',	NULL),
(2,	'Deutsch',	'de',	'de_de',	NULL);

DROP TABLE IF EXISTS `language_translations`;
CREATE TABLE `language_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `scope` varchar(25) DEFAULT NULL,
  `original` text NOT NULL,
  `translated` text NOT NULL,
  `checked` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fki-language_translations-languages-language_id-id` (`language_id`),
  CONSTRAINT `fk-language_translations-languages-language_id-id` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `language_translations` (`id`, `language_id`, `scope`, `original`, `translated`, `checked`) VALUES
(1,	1,	'core',	'Header',	'Header',	0),
(2,	1,	'core',	'Right',	'Right',	0),
(3,	1,	'core',	'Left',	'Left',	0),
(4,	1,	'core',	'Content',	'Content',	0),
(5,	1,	'core',	'Header2',	'Header2',	0),
(6,	1,	'core',	'Right2',	'Right2',	0),
(7,	1,	'core',	'Content2',	'Content2',	0),
(8,	1,	'core',	'Left2',	'Left2',	0),
(9,	1,	'core',	'Home',	'Home',	0),
(10,	1,	'core',	'Do you really want to delete this item?',	'Do you really want to delete this item?',	0),
(11,	1,	'core',	'Close this window?',	'Close this window?',	0),
(12,	1,	'core',	'PhalconEye',	'PhalconEye',	0),
(13,	1,	'core',	'PhalconEye Home Page',	'PhalconEye Home Page',	0),
(14,	1,	'core',	'Welcome, ',	'Welcome, ',	0),
(15,	1,	'core',	'Admin panel',	'Admin panel',	0),
(16,	1,	'core',	'Logout',	'Logout',	0),
(17,	1,	'core',	'Github',	'Github',	0),
(18,	1,	'core',	'Dashboard',	'Dashboard',	0),
(19,	1,	'core',	'Manage',	'Manage',	0),
(20,	1,	'core',	'Users and Roles',	'Users and Roles',	0),
(21,	1,	'core',	'Pages',	'Pages',	0),
(22,	1,	'core',	'Menus',	'Menus',	0),
(23,	1,	'core',	'Languages',	'Languages',	0),
(24,	1,	'core',	'Files',	'Files',	0),
(25,	1,	'core',	'Packages',	'Packages',	0),
(26,	1,	'core',	'Settings',	'Settings',	0),
(27,	1,	'core',	'System',	'System',	0),
(28,	1,	'core',	'Performance',	'Performance',	0),
(29,	1,	'core',	'Access Rights',	'Access Rights',	0),
(30,	1,	'core',	'Back to site',	'Back to site',	0),
(31,	1,	'core',	'Debug mode',	'Debug mode',	0),
(32,	1,	'core',	'Clear cache',	'Clear cache',	0),
(33,	1,	'core',	'ID',	'ID',	0),
(34,	1,	'core',	'Title',	'Title',	0),
(35,	1,	'core',	'Url',	'Url',	0),
(36,	1,	'core',	'Layout',	'Layout',	0),
(37,	1,	'core',	'Controller',	'Controller',	0),
(38,	1,	'core',	'Are you really want to delete this page?',	'Are you really want to delete this page?',	0),
(39,	1,	'core',	'Browse',	'Browse',	0),
(40,	1,	'core',	'Create new page',	'Create new page',	0),
(41,	1,	'core',	'Actions',	'Actions',	0),
(42,	1,	'core',	'Filter',	'Filter',	0),
(43,	1,	'core',	'Reset',	'Reset',	0),
(44,	1,	'core',	'Edit',	'Edit',	0),
(45,	1,	'core',	'Menu title',	'Menu title',	0),
(46,	1,	'core',	'Create new menu',	'Create new menu',	0),
(47,	1,	'core',	'Delete',	'Delete',	0),
(48,	1,	'core',	'Menu Editing',	'Menu Editing',	0),
(49,	1,	'core',	'Edit Menu',	'Edit Menu',	0),
(50,	1,	'core',	'Edit this menu.',	'Edit this menu.',	0),
(51,	1,	'core',	'Name',	'Name',	0),
(52,	1,	'core',	'Save',	'Save',	0),
(53,	1,	'core',	'Cancel',	'Cancel',	0),
(54,	1,	'core',	'Manage menu',	'Manage menu',	0),
(55,	1,	'core',	'Add new item',	'Add new item',	0),
(56,	1,	'core',	'Saved...',	'Saved...',	0),
(57,	1,	'core',	'Items: ',	'Items: ',	0),
(58,	1,	'core',	'Remove',	'Remove',	0),
(59,	1,	'core',	'Edit menu item',	'Edit menu item',	0),
(60,	1,	'core',	'This menu item will be available under menu or parent menu item.',	'This menu item will be available under menu or parent menu item.',	0),
(61,	1,	'core',	'Target',	'Target',	0),
(62,	1,	'core',	'Link type',	'Link type',	0),
(63,	1,	'core',	'Default link',	'Default link',	0),
(64,	1,	'core',	'Opens the linked document in a new window or tab',	'Opens the linked document in a new window or tab',	0),
(65,	1,	'core',	'Opens the linked document in the parent frame',	'Opens the linked document in the parent frame',	0),
(66,	1,	'core',	'Opens the linked document in the full body of the window',	'Opens the linked document in the full body of the window',	0),
(67,	1,	'core',	'Select url type',	'Select url type',	0),
(68,	1,	'core',	'System page',	'System page',	0),
(69,	1,	'core',	'Do not type url with starting slash... Example: \"somepage/url/to?param=1\"',	'Do not type url with starting slash... Example: \"somepage/url/to?param=1\"',	0),
(70,	1,	'core',	'Page',	'Page',	0),
(71,	1,	'core',	'Start typing to see pages variants.',	'Start typing to see pages variants.',	0),
(72,	1,	'core',	'OnClick',	'OnClick',	0),
(73,	1,	'core',	'Type JS action that will be performed when this menu item is selected.',	'Type JS action that will be performed when this menu item is selected.',	0),
(74,	1,	'core',	'Tooltip',	'Tooltip',	0),
(75,	1,	'core',	'Tooltip position',	'Tooltip position',	0),
(76,	1,	'core',	'Top',	'Top',	0),
(77,	1,	'core',	'Bottom',	'Bottom',	0),
(78,	1,	'core',	'Select icon',	'Select icon',	0),
(79,	1,	'core',	'Select file',	'Select file',	0),
(80,	1,	'core',	'Icon position',	'Icon position',	0),
(81,	1,	'core',	'Choose the language in which the menu item will be displayed.                    If no one selected - will be displayed at all.',	'Choose the language in which the menu item will be displayed.                    If no one selected - will be displayed at all.',	0),
(82,	1,	'core',	'English',	'English',	0),
(83,	1,	'core',	'Roles',	'Roles',	0),
(84,	1,	'core',	'If no value is selected, will be allowed to all (also as all selected).',	'If no value is selected, will be allowed to all (also as all selected).',	0),
(85,	1,	'core',	'Admin',	'Admin',	0),
(86,	1,	'core',	'User',	'User',	0),
(87,	1,	'core',	'Guest',	'Guest',	0),
(88,	1,	'core',	'Is enabled',	'Is enabled',	0),
(89,	1,	'core',	'Close',	'Close',	0),
(90,	1,	'core',	'Create new menu item',	'Create new menu item',	0),
(92,	1,	'core',	'Not Found',	'Not Found',	0),
(93,	1,	'user',	'Username',	'Username',	0),
(94,	1,	'user',	'Email',	'Email',	0),
(95,	1,	'user',	'Role',	'Role',	0),
(96,	1,	'user',	'Creation Date',	'Creation Date',	0),
(97,	1,	'user',	'Users',	'Users',	0),
(98,	1,	'user',	'Are you really want to delete this user?',	'Are you really want to delete this user?',	0),
(99,	1,	'user',	'Create new user',	'Create new user',	0),
(100,	1,	'user',	'Create new role',	'Create new role',	0),
(101,	1,	'core',	'Language',	'Language',	0),
(102,	1,	'core',	'Locale',	'Locale',	0),
(103,	1,	'core',	'Icon',	'Icon',	0),
(104,	1,	'core',	'Create new language',	'Create new language',	0),
(105,	1,	'core',	'Compile languages',	'Compile languages',	0),
(106,	1,	'core',	'Import...',	'Import...',	0),
(107,	1,	'core',	'File',	'File',	0),
(108,	1,	'core',	'No icon',	'No icon',	0),
(109,	1,	'core',	'Export',	'Export',	0),
(110,	1,	'core',	'Files management',	'Files management',	0),
(111,	1,	'core',	'Packages management - Modules',	'Packages management - Modules',	0),
(112,	1,	'core',	'Are you really want to remove this package? Once removed, it can not be restored.',	'Are you really want to remove this package? Once removed, it can not be restored.',	0),
(113,	1,	'core',	'Modules',	'Modules',	0),
(114,	1,	'core',	'Themes',	'Themes',	0),
(115,	1,	'core',	'Widgets',	'Widgets',	0),
(116,	1,	'core',	'Plugins',	'Plugins',	0),
(117,	1,	'core',	'Libraries',	'Libraries',	0),
(118,	1,	'core',	'Upload new package',	'Upload new package',	0),
(119,	1,	'core',	'Create new package',	'Create new package',	0),
(120,	1,	'core',	'Performance settings',	'Performance settings',	0),
(121,	1,	'core',	'Cache prefix',	'Cache prefix',	0),
(122,	1,	'core',	'Example: \"pe_\"',	'Example: \"pe_\"',	0),
(123,	1,	'core',	'Cache lifetime',	'Cache lifetime',	0),
(124,	1,	'core',	'This determines how long the system will keep cached data before                    reloading it from the database server.                    A shorter cache lifetime causes greater database server CPU usage,                    however the data will be more current.',	'This determines how long the system will keep cached data before                    reloading it from the database server.                    A shorter cache lifetime causes greater database server CPU usage,                    however the data will be more current.',	0),
(125,	1,	'core',	'Cache adapter',	'Cache adapter',	0),
(126,	1,	'core',	'Cache type. Where cache will be stored.',	'Cache type. Where cache will be stored.',	0),
(127,	1,	'core',	'Memcached',	'Memcached',	0),
(128,	1,	'core',	'APC',	'APC',	0),
(129,	1,	'core',	'Mongo',	'Mongo',	0),
(130,	1,	'core',	'Files location',	'Files location',	0),
(131,	1,	'core',	'Memcached host',	'Memcached host',	0),
(132,	1,	'core',	'Memcached port',	'Memcached port',	0),
(133,	1,	'core',	'Create a persistent connection to memcached?',	'Create a persistent connection to memcached?',	0),
(134,	1,	'core',	'A MongoDB connection string',	'A MongoDB connection string',	0),
(135,	1,	'core',	'Mongo database name',	'Mongo database name',	0),
(136,	1,	'core',	'Mongo collection in the database',	'Mongo collection in the database',	0),
(137,	1,	'core',	'All system cache will be cleaned.',	'All system cache will be cleaned.',	0),
(138,	1,	'core',	'Wrong controller name. Example: NameController->someAction',	'Wrong controller name. Example: NameController->someAction',	0),
(139,	1,	'core',	'Page Creation',	'Page Creation',	0),
(140,	1,	'core',	'Create new page.',	'Create new page.',	0),
(141,	1,	'core',	'Page will be available under http://phalconeye/page/[URL NAME]',	'Page will be available under http://phalconeye/page/[URL NAME]',	0),
(142,	1,	'core',	'Description',	'Description',	0),
(143,	1,	'core',	'Keywords',	'Keywords',	0),
(144,	1,	'core',	'Controller and action name that will handle this page. Example: NameController->someAction',	'Controller and action name that will handle this page. Example: NameController->someAction',	0),
(145,	1,	'core',	'Create',	'Create',	0),
(146,	1,	'core',	'Page not saved! Dou you want to leave?',	'Page not saved! Dou you want to leave?',	0),
(147,	1,	'core',	'Error while saving...',	'Error while saving...',	0),
(148,	1,	'core',	'Save (NOT  SAVED)',	'Save (NOT  SAVED)',	0),
(149,	1,	'core',	'Footer',	'Footer',	0),
(150,	1,	'core',	'If you switch to new layout you will lose some widgets, are you shure?',	'If you switch to new layout you will lose some widgets, are you shure?',	0),
(151,	1,	'core',	'Manage page',	'Manage page',	0),
(152,	1,	'core',	'Change layout',	'Change layout',	0),
(153,	1,	'core',	'Select layout type for current page',	'Select layout type for current page',	0),
(154,	1,	'core',	'Saving...',	'Saving...',	0),
(155,	1,	'core',	'HTML block, for:',	'HTML block, for:',	0),
(156,	1,	'core',	'HtmlBlock',	'HtmlBlock',	0),
(157,	1,	'core',	'HTML (EN)',	'HTML (EN)',	0),
(158,	1,	'core',	'Recent Posts',	'Recent Posts',	0),
(159,	1,	'core',	'Language Creation',	'Language Creation',	0),
(160,	1,	'core',	'Create new language.',	'Create new language.',	0),
(161,	1,	'core',	'Wizard',	'Wizard',	0),
(162,	1,	'core',	'Languages compilation finished!',	'Languages compilation finished!',	0),
(163,	1,	'core',	'Translations wizard',	'Translations wizard',	0),
(164,	1,	'core',	'There is no translations that required to be translated.',	'There is no translations that required to be translated.',	0),
(165,	1,	'core',	'Scope',	'Scope',	0),
(166,	1,	'core',	'Original',	'Original',	0),
(167,	1,	'core',	'Translated',	'Translated',	0),
(168,	1,	'core',	'Manage language',	'Manage language',	0),
(169,	1,	'core',	'Show untranslated',	'Show untranslated',	0),
(170,	1,	'core',	'Synchronize',	'Synchronize',	0),
(171,	1,	'core',	'Search',	'Search',	0),
(172,	1,	'core',	'No items',	'No items',	0),
(173,	1,	'core',	'Last',	'Last',	0),
(174,	1,	'core',	'First',	'First',	0),
(175,	1,	'core',	'Name must be in lowercase and contains only letters.',	'Name must be in lowercase and contains only letters.',	0),
(176,	1,	'core',	'Version must be in correct format: 1.0.0 or 1.0.0.0',	'Version must be in correct format: 1.0.0 or 1.0.0.0',	0),
(177,	1,	'core',	'Package Creation',	'Package Creation',	0),
(178,	1,	'core',	'Create new package.',	'Create new package.',	0),
(179,	1,	'core',	'Package type',	'Package type',	0),
(180,	1,	'core',	'Module',	'Module',	0),
(181,	1,	'core',	'Plugin',	'Plugin',	0),
(182,	1,	'core',	'Theme',	'Theme',	0),
(183,	1,	'core',	'Widget',	'Widget',	0),
(184,	1,	'core',	'Library',	'Library',	0),
(185,	1,	'core',	'Version',	'Version',	0),
(186,	1,	'core',	'Type package version. Ex.: 0.5.7',	'Type package version. Ex.: 0.5.7',	0),
(187,	1,	'core',	'Author',	'Author',	0),
(188,	1,	'core',	'Who create this package? Identify yourself!',	'Who create this package? Identify yourself!',	0),
(189,	1,	'core',	'Website',	'Website',	0),
(190,	1,	'core',	'Where user will look for new version?',	'Where user will look for new version?',	0),
(191,	1,	'core',	'Header comments',	'Header comments',	0),
(192,	1,	'core',	'This text will be placed in each file of package. Use comment block /**  **/.',	'This text will be placed in each file of package. Use comment block /**  **/.',	0),
(193,	1,	'core',	'Widget information',	'Widget information',	0),
(194,	1,	'core',	'Is related to module?',	'Is related to module?',	0),
(195,	1,	'core',	'No',	'No',	0),
(196,	1,	'core',	'Core',	'Core',	0),
(197,	1,	'core',	'Is Paginated?',	'Is Paginated?',	0),
(198,	1,	'core',	'If this enabled - widget will has additional control                    enabled for allowed per page items count selection in admin form',	'If this enabled - widget will has additional control                    enabled for allowed per page items count selection in admin form',	0),
(199,	1,	'core',	'Is ACL controlled?',	'Is ACL controlled?',	0),
(200,	1,	'core',	'If this enabled - widget will has additional control                    enabled for allowed roles selection in admin form',	'If this enabled - widget will has additional control                    enabled for allowed roles selection in admin form',	0),
(201,	1,	'core',	'Admin form',	'Admin form',	0),
(202,	1,	'core',	'Does this widget have some controlling form?',	'Does this widget have some controlling form?',	0),
(203,	1,	'core',	'Action',	'Action',	0),
(204,	1,	'core',	'Form class',	'Form class',	0),
(205,	1,	'core',	'Enter existing form class',	'Enter existing form class',	0),
(206,	1,	'core',	'Enabled?',	'Enabled?',	0),
(207,	1,	'core',	'Events',	'Events',	0),
(208,	1,	'core',	'Disable',	'Disable',	0),
(209,	1,	'core',	'Uninstall',	'Uninstall',	0),
(210,	1,	'core',	'Export Package',	'Export Package',	0),
(211,	1,	'core',	'Select package dependency (not necessarily).',	'Select package dependency (not necessarily).',	0),
(212,	1,	'core',	'Export with translations',	'Export with translations',	0),
(213,	1,	'core',	'Page Editing',	'Page Editing',	0),
(214,	1,	'core',	'Edit Page',	'Edit Page',	0),
(215,	1,	'core',	'Edit this page.',	'Edit this page.',	0),
(216,	1,	'core',	'HTML (DE)',	'HTML (DE)',	0),
(217,	1,	'core',	'System settings',	'System settings',	0),
(218,	1,	'core',	'All system settings here.',	'All system settings here.',	0),
(219,	1,	'core',	'Site name',	'Site name',	0),
(220,	1,	'core',	'Default',	'Default',	0),
(221,	1,	'core',	'Default language',	'Default language',	0),
(222,	1,	'core',	'Auto detect',	'Auto detect',	0),
(223,	1,	'core',	'Deutsch',	'Deutsch',	0),
(225,	1,	'core',	'Login',	'Login',	0),
(226,	1,	'core',	'Register',	'Register',	0),
(227,	1,	'user',	'Use you email or username to login.',	'Use you email or username to login.',	0),
(228,	1,	'user',	'Password',	'Password',	0),
(229,	1,	'user',	'Enter',	'Enter',	0),
(230,	1,	'core',	'Available resources',	'Available resources',	0),
(231,	1,	'core',	'Resource name',	'Resource name',	0),
(232,	1,	'core',	'Options',	'Options',	0),
(233,	1,	'core',	'Module settings',	'Module settings',	0),
(234,	1,	'core',	'This module has no settings...',	'This module has no settings...',	0),
(236,	1,	'core',	'Menu Creation',	'Menu Creation',	0),
(237,	1,	'core',	'Create new menu.',	'Create new menu.',	0),
(238,	1,	'core',	'Menu',	'Menu',	0),
(239,	1,	'core',	'Select menu that will be rendered.',	'Select menu that will be rendered.',	0),
(240,	1,	'core',	'Menu css class',	'Menu css class',	0),
(241,	1,	'core',	'Start typing to see menus variants',	'Start typing to see menus variants',	0),
(249,	1,	'core',	'Install new package',	'Install new package',	0),
(250,	1,	'core',	'Select package you want to install (zip extension).',	'Select package you want to install (zip extension).',	0),
(251,	1,	'core',	'Package',	'Package',	0),
(252,	1,	'core',	'Upload',	'Upload',	0),
(253,	1,	'core',	'Newsletter',	'Newsletter',	0),
(254,	1,	'core',	'Edit package',	'Edit package',	0),
(255,	1,	'core',	'Edit this package.',	'Edit this package.',	0),
(259,	1,	'user',	'User Creation',	'User Creation',	0),
(260,	1,	'user',	'Create new user.',	'Create new user.',	0),
(261,	1,	'user',	'Select user role',	'Select user role',	0),
(267,	1,	'core',	'Packages management - Themes',	'Packages management - Themes',	0),
(268,	1,	'core',	'No packages',	'No packages',	0),
(269,	1,	'core',	'Packages management - Widgets',	'Packages management - Widgets',	0),
(272,	1,	'user',	'User Editing',	'User Editing',	0),
(273,	1,	'user',	'Edit User',	'Edit User',	0),
(274,	1,	'user',	'Edit this user.',	'Edit this user.',	0);

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `menus` (`id`, `name`) VALUES
(1,	'Default menu');

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `onclick` varchar(255) DEFAULT NULL,
  `target` varchar(10) DEFAULT NULL,
  `tooltip` varchar(255) DEFAULT NULL,
  `tooltip_position` varchar(10) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `icon_position` varchar(10) NOT NULL,
  `languages` varchar(150) DEFAULT NULL,
  `roles` varchar(150) DEFAULT NULL,
  `is_enabled` tinyint(1) DEFAULT NULL,
  `item_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fki-menu_items-menus-menu_id-id` (`menu_id`),
  KEY `fki-menu_items-menu_items-parent_id-id` (`parent_id`),
  CONSTRAINT `fk-menu_items-menus-menu_id-id` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`),
  CONSTRAINT `fk-menu_items-menu_items-parent_id-id` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `menu_items` (`id`, `title`, `menu_id`, `parent_id`, `page_id`, `url`, `onclick`, `target`, `tooltip`, `tooltip_position`, `icon`, `icon_position`, `languages`, `roles`, `is_enabled`, `item_order`) VALUES
(1,	'Home',	1,	NULL,	NULL,	'/',	NULL,	NULL,	NULL,	'top',	'files/PE_logo.png',	'left',	NULL,	NULL,	1,	0);

DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `type` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` text,
  `version` varchar(32) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `is_system` tinyint(1) NOT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `packages` (`id`, `name`, `type`, `title`, `description`, `version`, `author`, `website`, `enabled`, `is_system`, `data`) VALUES
(1,	'core',	'module',	'Core',	'PhalconEye Core',	'0.4.0',	'PhalconEye Team',	'http://phalconeye.com/',	1,	1,	NULL),
(2,	'user',	'module',	'Users',	'PhalconEye Users',	'0.4.0',	'PhalconEye Team',	'http://phalconeye.com/',	1,	1,	NULL);

DROP TABLE IF EXISTS `package_dependencies`;
CREATE TABLE `package_dependencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `dependency_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fki-package_dependencies-packages-package_id-id` (`package_id`),
  KEY `fki-package_dependencies-packages-dependency_id-id` (`dependency_id`),
  CONSTRAINT `fk-package_dependencies-packages-dependency_id-id` FOREIGN KEY (`dependency_id`) REFERENCES `packages` (`id`),
  CONSTRAINT `fk-package_dependencies-packages-package_id-id` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `description` text,
  `keywords` text,
  `layout` varchar(50) NOT NULL,
  `controller` varchar(50) DEFAULT NULL,
  `roles` varchar(150) DEFAULT NULL,
  `view_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `pages` (`id`, `title`, `type`, `url`, `description`, `keywords`, `layout`, `controller`, `roles`, `view_count`) VALUES
(1,	'Header',	'header',	NULL,	'Header content',	'',	'middle',	NULL,	NULL,	0),
(2,	'Footer',	'footer',	NULL,	'Footer content',	'',	'middle',	NULL,	NULL,	0),
(3,	'Home',	'home',	'/',	'PhalconEye Home Page',	'PhalconEye',	'top_right_middle_left',	NULL,	NULL,	0);

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `type` varchar(10) NOT NULL,
  `undeletable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `roles` (`id`, `name`, `description`, `is_default`, `type`, `undeletable`) VALUES
(1,	'Admin',	'Administrator.',	0,	'admin',	1),
(2,	'User',	'Default user role.',	1,	'user',	1),
(3,	'Guest',	'Guest role.',	0,	'guest',	1);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `name` varchar(60) NOT NULL,
  `value` varchar(250) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`name`, `value`) VALUES
('system_default_language',	'en'),
('system_theme',	'default'),
('system_title',	'2Phalcon Eye');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_username` (`username`),
  KEY `ix_email` (`email`),
  KEY `fki-users-roles-role_id-id` (`role_id`),
  CONSTRAINT `fk-users-roles-role_id-id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `role_id`, `username`, `password`, `email`, `creation_date`, `modified_date`) VALUES
(1,	1,	'dataclub',	'$2a$08$Vb6IZPh2FehnvDfwUJJ7NuWvcq8FhCrbhjk.Ka4oynXwVKDxtY8SS',	'dataclub@mailfish.de',	'2015-02-18 01:56:42',	NULL),
(2,	1,	'test',	'$2a$08$vklr7ElrpLWN.aErQUzXYOF8UXMl.1eugmg4izzmJNm2.T9Vf9QV6',	'test@test.de',	'2015-03-03 14:20:14',	NULL);

DROP TABLE IF EXISTS `widgets`;
CREATE TABLE `widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `module` varchar(64) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_paginated` tinyint(1) NOT NULL,
  `is_acl_controlled` tinyint(1) NOT NULL,
  `admin_form` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `widgets` (`id`, `name`, `module`, `description`, `is_paginated`, `is_acl_controlled`, `admin_form`, `enabled`) VALUES
(1,	'HtmlBlock',	'core',	'Insert any HTML of you choice',	0,	1,	'action',	1),
(2,	'Menu',	'core',	'Render menu',	0,	1,	'\\Core\\Form\\Admin\\Widget\\Menu',	1),
(3,	'Header',	'core',	'Settings for header of you site.',	0,	1,	'\\Core\\Form\\Admin\\Widget\\Header',	1);

-- 2015-02-19 00:08:48


-- Changes for Blog-Module

INSERT INTO `pages` (`id`, `title`, `type`, `url`, `description`, `keywords`, `layout`, `controller`, `roles`, `view_count`) VALUES
(4,	'Blog',	NULL,	'blogView',	NULL,	NULL,	'right_middle',	NULL,	NULL,	0);

INSERT INTO `menu_items` (`title`, `menu_id`, `parent_id`, `page_id`, `url`, `onclick`, `target`, `tooltip`, `tooltip_position`, `icon`, `icon_position`, `languages`, `roles`, `is_enabled`, `item_order`) VALUES
('Blog',	1,	NULL,	NULL,	'blog',	NULL,	NULL,	NULL,	'top',	NULL,	'left',	NULL,	NULL,	1,	1);

INSERT INTO `packages` (`id`, `name`, `type`, `title`, `description`, `version`, `author`, `website`, `enabled`, `is_system`, `data`) VALUES
(3,	'blog',	'module',	'Blog',	NULL,	'1.0.1',	'dataclub',	NULL,	1,	0,	'{\"events\":[],\"widgets\":[]}');

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
  `body` text,
  `creation_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;