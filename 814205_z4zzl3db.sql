-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: mysql51-012.wc1:3306
-- Generation Time: Mar 28, 2016 at 11:44 AM
-- Server version: 5.1.61
-- PHP Version: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `814205_z4zzl3db`
--

-- --------------------------------------------------------

--
-- Table structure for table `global_settings`
--

CREATE TABLE IF NOT EXISTS `global_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` text CHARACTER SET utf8,
  `contact_address` text CHARACTER SET utf8,
  `contact_address2` text CHARACTER SET utf8,
  `contact_city` text CHARACTER SET utf8,
  `contact_province` text CHARACTER SET utf8,
  `contact_postal_code` text CHARACTER SET utf8,
  `contact_country` text CHARACTER SET utf8,
  `contact_phone` text CHARACTER SET utf8,
  `contact_fax` text CHARACTER SET utf8,
  `contact_toll_free` text CHARACTER SET utf8,
  `contact_email` text CHARACTER SET utf8,
  `facebook` text CHARACTER SET utf8,
  `facebook_appid` text CHARACTER SET utf8,
  `facebook_secret` text CHARACTER SET utf8,
  `twitter` text CHARACTER SET utf8,
  `pinterest` text CHARACTER SET utf8,
  `googleplus` text CHARACTER SET utf8,
  `youtube` text CHARACTER SET utf8,
  `linkedin` text CHARACTER SET utf8 NOT NULL,
  `sioppa` text CHARACTER SET utf8 NOT NULL,
  `meta_title` text CHARACTER SET utf8,
  `meta_description` text CHARACTER SET utf8,
  `meta_keywords` text CHARACTER SET utf8,
  `googleanalytics` text CHARACTER SET utf8,
  `ga_email` text CHARACTER SET utf8,
  `ga_profile` text CHARACTER SET utf8,
  `ga_password` text CHARACTER SET utf8,
  `ga_tracking` text CHARACTER SET utf8,
  `gps_lat` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `gps_lng` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `gps_warning` enum('0','1') CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `global_settings`
--

INSERT INTO `global_settings` (`id`, `company_name`, `contact_address`, `contact_address2`, `contact_city`, `contact_province`, `contact_postal_code`, `contact_country`, `contact_phone`, `contact_fax`, `contact_toll_free`, `contact_email`, `facebook`, `facebook_appid`, `facebook_secret`, `twitter`, `pinterest`, `googleplus`, `youtube`, `linkedin`, `sioppa`, `meta_title`, `meta_description`, `meta_keywords`, `googleanalytics`, `ga_email`, `ga_profile`, `ga_password`, `ga_tracking`, `gps_lat`, `gps_lng`, `gps_warning`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `salt` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `permissions` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`username`, `password`, `salt`, `email`, `permissions`, `id`) VALUES
('admin', '472d27e2a8f0610bb36be88c4d79fa803aca4449', '7512a1b7088ab362352090bc5cb55f5d22b44543', 'scott@pixelarmy.ca', 'All', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nav1`
--

CREATE TABLE IF NOT EXISTS `nav1` (
  `name` varchar(200) NOT NULL,
  `page` varchar(200) NOT NULL,
  `controller` varchar(200) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `page_title` varchar(200) NOT NULL,
  `meta_title` varchar(200) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `content` mediumtext NOT NULL,
  `sidebar` mediumtext NOT NULL,
  `image` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `urltarget` int(11) NOT NULL,
  `showhide` int(11) NOT NULL DEFAULT '0',
  `deleteable` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '101',
  `reference` int(11) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_page_title` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `nav1`
--

INSERT INTO `nav1` (`name`, `page`, `controller`, `type`, `page_title`, `meta_title`, `meta_description`, `meta_keywords`, `content`, `sidebar`, `image`, `url`, `urltarget`, `showhide`, `deleteable`, `ordering`, `reference`, `last_modified`, `id`, `sub_page_title`) VALUES
('Great Lengths', 'great-lengths', 'great-lengths', 0, 'Great Lengths', 'What Is Great Lengths', '', '', '<p>Great Lengths are 100% human hair extensions that are attached using a keratin bond throughout the bottom sections of hair.  They last 4-6 months with minimal maintenance and are by far the leading brand of extensions on the market today.</p>\r\n<h3>What can Great Lengths do for me?</h3>\r\n<p>There are 3 ways that Great Lengths are most commonly used. For length,  volume, and color. If you are most interested in having longer, thicker, and healthy looking hair then an application for length would be for you. If you are happy with the length of your hair but would like it to look a lot fuller, then volume would be the application style for you. If you would like to have a color change but don&rsquo;t want to worry about fading or damage,  try our color installation.</p>\r\n<h3>Why choose Great Lengths hair extensions?</h3>\r\n<ul>\r\n    <li>They last 4-6 months with little to no maintenance</li>\r\n    <li>No need for tightening or re-bonding</li>\r\n    <li>Great Lengths hair is colored using a soaking method that does not chemically destroy the hair</li>\r\n    <li>The hair color does not fade or deteriorate in quality</li>\r\n    <li>Great Lengths come naturally wavy to enhance your style options</li>\r\n    <li>Amazing at maintaining the quality of your hair while continuing to grow</li>\r\n    <li>The most natural hair extensions on the market and extremely comfortable to wear</li>\r\n    <li>Can be used in multiple ways; for length, thickness, color, or funky sections</li>\r\n</ul>', '', '', '', 0, 0, 0, 101, 1001, '2013-05-28 18:56:04', 1, 'What Is'),
('Services / Pricing', 'services-pricing', 'services-pricing', 0, 'Services / Pricing', 'View Our Services / Pricing', '', '', '<p>Consultation - <b>Free!</b></p>\r\n<table width=''100%''>\r\n<tr>\r\n<td style=''vertical-align:top''>\r\n<h3>Length</h3>\r\n<ul>\r\n<li>&nbsp;&nbsp;8 inches - $1200</li>\r\n<li>12 inches - $1400</li>\r\n<li>16 inches - $1600</li>\r\n<li>18 inches - $1800</li>\r\n<li>20 inches - $2000</li>\r\n<li>24 inches - $2200</li>\r\n</ul>\r\n<h3>100% Human Hair Wigs</h3>\r\n<ul>\r\n<li>Color Service - starting at $150</li>\r\n<li>Wig Cut - starting at $70</li>\r\n<li>Blowout & Style - starting at $50</li>\r\n<li>Dry Style - starting at $30</li>\r\n</ul>\r\n</td>\r\n<td style=''vertical-align: top''>\r\n<h3>Volume</h3>\r\n<p>Prices starting at $200</p>\r\n\r\n<h3>Color</h3>\r\n<p>Prices starting at $50</p>\r\n</td>\r\n</tr>\r\n</table>\r\n<p><small>Above prices are subject to change upon consultation</small></p>', '', '', '', 0, 0, 0, 101, 1002, '2013-04-03 22:19:22', 2, 'View Our'),
('Hair Come From', 'where-does-hair-come-from', 'hair-come-from', 0, 'Where Does Hair Come From', 'Where Does Hair Come From', '', '', '<p><img src="../images/tirumala.jpg" width="200" height="111" alt="Hindu Temple, Tirumala" align="left" />    Great Lengths sources their hair from the Hindu Temple, Tirumala, where the traditional religious ceremony &quot;tonsuring&quot; is practiced. Entire families (men, women, children, grandparents) make the pilgrimage to the temple to voluntarily have their heads shaved. This act of thanksgiving typically takes place before or after a momentous, joyful event.</p>', '', '', '', 0, 0, 0, 101, 1003, '2013-05-09 19:48:06', 3, 'Where Does'),
('About Zazzle', 'read-about-zazzle', 'read-about-zazzle', 0, 'About Zazzle', 'About Zazzle', '', '', '<p>As a hairstylist for over 12 years I found myself correcting many hair extension disasters. At the beginning of my carreer was when women were first really starting to experiment with hair extensions and, let me tell you, it was not pretty. I had many clients sit in my chair in tears because they went and got extensions somewhere that had broken all their hair off. It was after many attempts at talking people out of getting extensions that I realized it couldn''t be done. Women wanted longer hair and that was that. So I took it upon myself to research and find the best hair extensions available knowing that my clients were going to get them anyways, at least they had a chance at keeping their hair in the same condition that they started in. This is when I found Great Lengths.</p>\r\n <p>At the time I took the Great Lengths course it was practically unheard of in the Edmonton area. It took a lot of time and energy on my part to inform people of why Great Lengths was by far the leading brand of extensions. After a few years of keeping at it I found myself with an overwhelming Great Lengths clientele coming from all parts of the country... and beyond! It was clear that Great Lengths were what women wanted.</p>\r\n <p>As a certified Great Lengths artist I take pride in having the ability to enhance my clients'' image while maintaing the health and integrity of their hair. I feel that there is no point in having long, flowing locks when underneath them there is a broken up mess. My goal is to provide my clients with all the necessary tools to be able to attain any look they could possibly imagine no matter what their current hair state is.</p>', '', '', '', 0, 0, 0, 101, 1004, '2013-05-28 18:54:11', 4, 'Read'),
('Contact Us', 'contact-us', 'contact-us', 0, 'Contact Us', 'Contact Us', '', '', '<p><strong>Lacie Sousa</strong><br/>\r\n<strong>Russo Innovative Hair Design</strong><br />\r\n7921 104 Street<br />\r\nEdmonton, Alberta<br />\r\nT6E 4E1</p>\r\n<p><strong>Phone:</strong> 780-450-8379</p>\r\n<p><strong>Email:</strong> <a href="mailto:laciekus@shaw.ca">laciekus@shaw.ca</a></p>', '', '', '', 0, 0, 0, 101, 1005, '2013-05-28 18:57:36', 5, 'Say Hello'),
('Home', 'home', 'home', 0, 'Hair Extensions By Lacie Sousa', 'Hair Extensions By Lacie', '', '', '<p></p><p></p><p></p><p></p>', '', '', '', 0, 1, 0, 101, 1006, '2013-04-05 19:46:34', 6, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nav2_1002`
--

CREATE TABLE IF NOT EXISTS `nav2_1002` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `page` varchar(200) NOT NULL,
  `type` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `sidebar` mediumtext NOT NULL,
  `image` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `urltarget` int(11) NOT NULL,
  `showhide` int(11) NOT NULL,
  `deleteable` int(11) NOT NULL,
  `reference` int(11) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '101',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `nav2_1002`
--

INSERT INTO `nav2_1002` (`id`, `name`, `page`, `type`, `content`, `sidebar`, `image`, `url`, `urltarget`, `showhide`, `deleteable`, `reference`, `ordering`) VALUES
(1, 'Standard Extensions', 'standard-extensions', 0, '<h2>The Original Extensions</h2><p><img src=''../images/standardextensions.png'' width=''200'' height=''183'' alt=''The Original Extensions'' align=''right''/>With these flowing transitions, Flowstrands add a totally new dimension to the subject of hair colour. Due to the colour graduation, from a natural, dark shade to a lighter colour in the lower half of the strand on our base shade range, they can be beautifully integrated into the natural hair.</p>', '', '', '', 0, 0, 0, 2001, 1),
(2, 'Crazy Color', 'crazy-color', 0, '<h2>Crazy Color</h2><p><img src=''../images/crazycolors.jpg'' width=''200'' height=''198'' alt=''Crazy Color'' align=''right'' />With our fashion strands you may add a touch of personality to your look in a matter of minutes.</p>', '', '', '', 0, 0, 0, 2002, 2),
(3, 'Diamond Strands', 'diamond-strands', 0, '<h2>Diamond Strands</h2><p><img src=''../images/diamondstrands.jpg'' width=''200'' height=''229'' alt=''Diamond Strands - Swarovski Crystals'' align=''right''/>For our diamond strands, we exclusively use Swarovski crystals! We offer a huge selection of jewels including emeralds, rubies, sapphires, or black diamonds!</p>', '', '', '', 0, 0, 0, 2003, 3),
(4, 'Flow Strands', 'flow-strands', 0, '<h2>Flow Strands</h2><p><img src=''../images/flowstrands.jpg'' width=''200'' alt=''Flow Strands'' align=''right''/>With these flowing transitions, Flowstrands add a totally new dimension to the subject of hair colour. Due to the colour graduation, from a natural, dark shade to a lighter colour in the lower half of the strand on our base shade range, they can be beautifully integrated into the natural hair.</p>', '', '', '', 0, 0, 0, 2004, 4);

-- --------------------------------------------------------

--
-- Table structure for table `nav2_1003`
--

CREATE TABLE IF NOT EXISTS `nav2_1003` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `page` varchar(200) NOT NULL,
  `type` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `sidebar` mediumtext NOT NULL,
  `image` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `urltarget` int(11) NOT NULL,
  `showhide` int(11) NOT NULL,
  `deleteable` int(11) NOT NULL,
  `reference` int(11) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '101',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `nav2_1004`
--

CREATE TABLE IF NOT EXISTS `nav2_1004` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `page` varchar(200) NOT NULL,
  `type` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `sidebar` mediumtext NOT NULL,
  `image` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `urltarget` int(11) NOT NULL,
  `showhide` int(11) NOT NULL,
  `deleteable` int(11) NOT NULL,
  `reference` int(11) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '101',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `nav2_1005`
--

CREATE TABLE IF NOT EXISTS `nav2_1005` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `page` varchar(200) NOT NULL,
  `type` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `sidebar` mediumtext NOT NULL,
  `image` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `urltarget` int(11) NOT NULL,
  `showhide` int(11) NOT NULL,
  `deleteable` int(11) NOT NULL,
  `reference` int(11) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '101',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `nav2_1006`
--

CREATE TABLE IF NOT EXISTS `nav2_1006` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `page` varchar(200) NOT NULL,
  `type` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `sidebar` mediumtext NOT NULL,
  `image` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `urltarget` int(11) NOT NULL,
  `showhide` int(11) NOT NULL,
  `deleteable` int(11) NOT NULL,
  `reference` int(11) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '101',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
