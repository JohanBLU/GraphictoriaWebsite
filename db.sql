/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for xdiscuss3
CREATE DATABASE IF NOT EXISTS `xdiscuss3` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `xdiscuss3`;

-- Dumping structure for table xdiscuss3.badges
CREATE TABLE IF NOT EXISTS `badges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT 0,
  `badgeId` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.badges: ~0 rows (approximately)
/*!40000 ALTER TABLE `badges` DISABLE KEYS */;
/*!40000 ALTER TABLE `badges` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.badHashes
CREATE TABLE IF NOT EXISTS `badHashes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.badHashes: ~0 rows (approximately)
/*!40000 ALTER TABLE `badHashes` DISABLE KEYS */;
/*!40000 ALTER TABLE `badHashes` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.banlogs
CREATE TABLE IF NOT EXISTS `banlogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banned_by_uid` int(11) NOT NULL DEFAULT 0,
  `banned_by_uname` varchar(50) DEFAULT NULL,
  `banned_uid` int(11) NOT NULL DEFAULT 0,
  `banned_uname` varchar(50) DEFAULT NULL,
  `reason` varchar(512) DEFAULT NULL,
  `bantype` int(11) NOT NULL DEFAULT 0,
  `date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.banlogs: ~0 rows (approximately)
/*!40000 ALTER TABLE `banlogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `banlogs` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.blogposts
CREATE TABLE IF NOT EXISTS `blogposts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poster_uid` int(11) DEFAULT 0,
  `date` timestamp NULL DEFAULT current_timestamp(),
  `title` varchar(64) DEFAULT NULL,
  `content` varchar(30000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.blogposts: ~0 rows (approximately)
/*!40000 ALTER TABLE `blogposts` DISABLE KEYS */;
/*!40000 ALTER TABLE `blogposts` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.catagories
CREATE TABLE IF NOT EXISTS `catagories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `visible` int(11) unsigned NOT NULL DEFAULT 0,
  `developer` int(11) unsigned NOT NULL DEFAULT 0,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.catagories: ~0 rows (approximately)
/*!40000 ALTER TABLE `catagories` DISABLE KEYS */;
/*!40000 ALTER TABLE `catagories` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.catalog
CREATE TABLE IF NOT EXISTS `catalog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` int(11) NOT NULL DEFAULT 0,
  `currencyType` int(11) NOT NULL DEFAULT 0,
  `creator_uid` int(11) NOT NULL DEFAULT 0,
  `buyable` int(11) NOT NULL DEFAULT 1,
  `assetid` int(11) DEFAULT NULL,
  `deleted` int(11) DEFAULT 0,
  `approved` int(11) DEFAULT 0,
  `declined` int(11) DEFAULT 0,
  `rbxasset` int(11) DEFAULT 0,
  `name` varchar(50) DEFAULT NULL,
  `fileHash` varchar(512) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `datafile` varchar(50) DEFAULT NULL,
  `createDate` timestamp NULL DEFAULT current_timestamp(),
  `imgTime` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.catalog: ~0 rows (approximately)
/*!40000 ALTER TABLE `catalog` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.characterColors
CREATE TABLE IF NOT EXISTS `characterColors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT 0,
  `color` int(11) DEFAULT 0,
  `type` varchar(50) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.characterColors: ~0 rows (approximately)
/*!40000 ALTER TABLE `characterColors` DISABLE KEYS */;
/*!40000 ALTER TABLE `characterColors` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.chat_members
CREATE TABLE IF NOT EXISTS `chat_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) NOT NULL DEFAULT 0,
  `userId` int(11) NOT NULL DEFAULT 0,
  `rank` int(11) NOT NULL DEFAULT 0,
  `lastActive` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastType` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.chat_members: ~0 rows (approximately)
/*!40000 ALTER TABLE `chat_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_members` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.chat_messages
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) DEFAULT 0,
  `userId` int(11) DEFAULT 0,
  `message` varchar(65000) DEFAULT NULL,
  `date` int(11) DEFAULT 0,
  `bot` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.chat_messages: ~0 rows (approximately)
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.chat_sessions
CREATE TABLE IF NOT EXISTS `chat_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chatName` varchar(50) NOT NULL DEFAULT '128',
  `chatImage` varchar(50) NOT NULL DEFAULT '128',
  `chatKey` varchar(50) NOT NULL DEFAULT '128',
  `chatJoinKey` varchar(50) NOT NULL DEFAULT '128',
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastActive` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.chat_sessions: ~0 rows (approximately)
/*!40000 ALTER TABLE `chat_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_sessions` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.errorReports
CREATE TABLE IF NOT EXISTS `errorReports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `string` varchar(1024) DEFAULT NULL,
  `ip` varchar(128) DEFAULT NULL,
  `time` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.errorReports: ~0 rows (approximately)
/*!40000 ALTER TABLE `errorReports` DISABLE KEYS */;
/*!40000 ALTER TABLE `errorReports` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.forums
CREATE TABLE IF NOT EXISTS `forums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) DEFAULT NULL,
  `developer` int(11) DEFAULT 0,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `locked` int(11) DEFAULT 0,
  `posts` int(11) DEFAULT 0,
  `replies` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.forums: ~0 rows (approximately)
/*!40000 ALTER TABLE `forums` DISABLE KEYS */;
/*!40000 ALTER TABLE `forums` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.friendRequests
CREATE TABLE IF NOT EXISTS `friendRequests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senduid` int(11) DEFAULT 0,
  `recvuid` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.friendRequests: ~0 rows (approximately)
/*!40000 ALTER TABLE `friendRequests` DISABLE KEYS */;
/*!40000 ALTER TABLE `friendRequests` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.friends
CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId1` int(11) DEFAULT 0,
  `userId2` int(11) DEFAULT 0,
  `friendSince` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.friends: ~0 rows (approximately)
/*!40000 ALTER TABLE `friends` DISABLE KEYS */;
/*!40000 ALTER TABLE `friends` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.gameJoins
CREATE TABLE IF NOT EXISTS `gameJoins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT 0,
  `gameId` int(11) DEFAULT NULL,
  `time` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.gameJoins: ~0 rows (approximately)
/*!40000 ALTER TABLE `gameJoins` DISABLE KEYS */;
/*!40000 ALTER TABLE `gameJoins` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.gameKeys
CREATE TABLE IF NOT EXISTS `gameKeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT 0,
  `key` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.gameKeys: ~0 rows (approximately)
/*!40000 ALTER TABLE `gameKeys` DISABLE KEYS */;
/*!40000 ALTER TABLE `gameKeys` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.games
CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` int(11) DEFAULT 0,
  `creator_uid` int(11) DEFAULT 0,
  `public` int(11) DEFAULT 0,
  `name` varchar(32) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `key` varchar(128) DEFAULT NULL,
  `privatekey` varchar(128) DEFAULT NULL,
  `placeURL` varchar(256) DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `port` int(5) DEFAULT 53640,
  `numPlayers` int(5) DEFAULT 0,
  `dedi` int(5) DEFAULT 0,
  `date` timestamp NULL DEFAULT current_timestamp(),
  `imgTime` timestamp NULL DEFAULT current_timestamp(),
  `lastPing` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.games: ~0 rows (approximately)
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
/*!40000 ALTER TABLE `games` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuid` int(11) NOT NULL DEFAULT 0,
  `closed` int(11) NOT NULL DEFAULT 0,
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(32) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `shout` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.groups: ~0 rows (approximately)
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.group_members
CREATE TABLE IF NOT EXISTS `group_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT 0,
  `gid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.group_members: ~0 rows (approximately)
/*!40000 ALTER TABLE `group_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_members` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.loginAttempts
CREATE TABLE IF NOT EXISTS `loginAttempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) DEFAULT NULL,
  `uid` int(11) NOT NULL DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `count` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.loginAttempts: ~0 rows (approximately)
/*!40000 ALTER TABLE `loginAttempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `loginAttempts` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recv_uid` int(11) NOT NULL DEFAULT 0,
  `sender_uid` int(11) NOT NULL DEFAULT 0,
  `read` int(11) NOT NULL DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `title` varchar(64) DEFAULT NULL,
  `content` varchar(30000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.messages: ~0 rows (approximately)
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.ownedItems
CREATE TABLE IF NOT EXISTS `ownedItems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT 0,
  `catalogid` int(11) DEFAULT 0,
  `type` varchar(50) DEFAULT NULL,
  `deleted` int(11) DEFAULT 0,
  `rbxasset` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.ownedItems: ~0 rows (approximately)
/*!40000 ALTER TABLE `ownedItems` DISABLE KEYS */;
/*!40000 ALTER TABLE `ownedItems` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.passwordresets
CREATE TABLE IF NOT EXISTS `passwordresets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT 0,
  `used` int(11) NOT NULL DEFAULT 0,
  `key` varchar(256) DEFAULT NULL,
  `date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.passwordresets: ~0 rows (approximately)
/*!40000 ALTER TABLE `passwordresets` DISABLE KEYS */;
/*!40000 ALTER TABLE `passwordresets` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.profile_views
CREATE TABLE IF NOT EXISTS `profile_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `viewer` int(11) NOT NULL DEFAULT 0,
  `profile` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.profile_views: ~0 rows (approximately)
/*!40000 ALTER TABLE `profile_views` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile_views` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.pwdreset
CREATE TABLE IF NOT EXISTS `pwdreset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) DEFAULT NULL,
  `date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.pwdreset: ~0 rows (approximately)
/*!40000 ALTER TABLE `pwdreset` DISABLE KEYS */;
/*!40000 ALTER TABLE `pwdreset` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.read
CREATE TABLE IF NOT EXISTS `read` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT 0,
  `postId` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.read: ~0 rows (approximately)
/*!40000 ALTER TABLE `read` DISABLE KEYS */;
/*!40000 ALTER TABLE `read` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.renders
CREATE TABLE IF NOT EXISTS `renders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `render_id` varchar(512) NOT NULL DEFAULT '0',
  `type` varchar(50) DEFAULT NULL,
  `rendered` int(11) DEFAULT 0,
  `version` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.renders: ~0 rows (approximately)
/*!40000 ALTER TABLE `renders` DISABLE KEYS */;
/*!40000 ALTER TABLE `renders` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.replies
CREATE TABLE IF NOT EXISTS `replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forumId` int(11) NOT NULL DEFAULT 0,
  `developer` int(11) NOT NULL DEFAULT 0,
  `author_uid` int(11) DEFAULT NULL,
  `postId` int(11) DEFAULT NULL,
  `content` varchar(30000) DEFAULT NULL,
  `post_time` timestamp NULL DEFAULT current_timestamp(),
  `updatedOn` timestamp NULL DEFAULT current_timestamp(),
  `updatedBy` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.replies: ~0 rows (approximately)
/*!40000 ALTER TABLE `replies` DISABLE KEYS */;
/*!40000 ALTER TABLE `replies` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.reports
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reportIP` varchar(128) DEFAULT NULL,
  `target` varchar(20) DEFAULT NULL,
  `reason` varchar(256) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.reports: ~0 rows (approximately)
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.serverRequests
CREATE TABLE IF NOT EXISTS `serverRequests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `placeLocation` varchar(512) DEFAULT NULL,
  `serverName` varchar(512) DEFAULT NULL,
  `serverDescription` varchar(512) DEFAULT NULL,
  `serverVersion` int(11) DEFAULT 0,
  `serverPrivacy` int(11) DEFAULT 0,
  `userID` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.serverRequests: ~0 rows (approximately)
/*!40000 ALTER TABLE `serverRequests` DISABLE KEYS */;
/*!40000 ALTER TABLE `serverRequests` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `factorFinish` int(11) DEFAULT 0,
  `sessionId` varchar(255) DEFAULT NULL,
  `csrfToken` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `useragent` varchar(255) DEFAULT NULL,
  `lastUsed` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.sessions: 0 rows
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.topics
CREATE TABLE IF NOT EXISTS `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forumId` int(11) NOT NULL DEFAULT 0,
  `developer` int(11) NOT NULL DEFAULT 0,
  `author_uid` int(11) DEFAULT 0,
  `locked` int(1) DEFAULT 0,
  `lockedByStaff` int(1) DEFAULT 0,
  `pinned` int(1) DEFAULT 0,
  `views` int(1) DEFAULT 0,
  `title` varchar(1024) DEFAULT NULL,
  `content` varchar(30000) DEFAULT NULL,
  `postTime` timestamp NULL DEFAULT current_timestamp(),
  `lastActivity` timestamp NULL DEFAULT NULL,
  `updatedOn` timestamp NULL DEFAULT NULL,
  `updatedBy` int(11) DEFAULT 0,
  `replies` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.topics: ~0 rows (approximately)
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
/*!40000 ALTER TABLE `topics` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banned` int(1) NOT NULL DEFAULT 0,
  `publicBan` int(1) NOT NULL DEFAULT 0,
  `bantype` int(1) NOT NULL DEFAULT 0,
  `emailverified` int(1) NOT NULL DEFAULT 0,
  `developer` int(1) NOT NULL DEFAULT 0,
  `hatuploader` int(1) NOT NULL DEFAULT 0,
  `charap` int(1) NOT NULL DEFAULT 0,
  `inGameId` int(128) NOT NULL DEFAULT 0,
  `emailverifyCode` varchar(256) DEFAULT NULL,
  `emailcodeTime` timestamp NULL DEFAULT NULL,
  `banreason` varchar(512) DEFAULT NULL,
  `bantime` timestamp NULL DEFAULT NULL,
  `username` varchar(21) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `gameKey` varchar(128) DEFAULT NULL,
  `lastIP` varchar(128) DEFAULT NULL,
  `registerIP` varchar(128) DEFAULT NULL,
  `passwordChangeIP` varchar(128) DEFAULT NULL,
  `passwordChangeDate` timestamp NULL DEFAULT NULL,
  `about` varchar(256) DEFAULT NULL,
  `authId` varchar(256) DEFAULT NULL,
  `useragent` varchar(1024) DEFAULT NULL,
  `authKey` varchar(1024) DEFAULT NULL,
  `rank` int(1) NOT NULL DEFAULT 0,
  `hideStatus` int(1) NOT NULL DEFAULT 0,
  `coins` int(128) NOT NULL DEFAULT 0,
  `posties` int(128) NOT NULL DEFAULT 0,
  `imgTime` int(128) NOT NULL DEFAULT 0,
  `passwordVersion` int(128) NOT NULL DEFAULT 1,
  `imgp` int(1) NOT NULL DEFAULT 1,
  `inGame` int(1) NOT NULL DEFAULT 0,
  `themeChoice` int(1) NOT NULL DEFAULT 0,
  `profileviews` int(1) NOT NULL DEFAULT 0,
  `lastAward` timestamp NULL DEFAULT NULL,
  `lastAward2` timestamp NULL DEFAULT NULL,
  `joinDate` timestamp NULL DEFAULT current_timestamp(),
  `lastSeen` timestamp NULL DEFAULT NULL,
  `lastUpload` timestamp NULL DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `password_salt` varchar(128) DEFAULT NULL,
  `password_hash` varchar(128) DEFAULT NULL,
  `2facode` varchar(128) DEFAULT NULL,
  `2faInit` int(11) DEFAULT 0,
  `2faEnabled` int(1) DEFAULT 0,
  `2fagentime` timestamp NULL DEFAULT NULL,
  `formcode` varchar(128) DEFAULT NULL,
  `lastPost` timestamp NULL DEFAULT NULL,
  `lastIDGen` timestamp NULL DEFAULT NULL,
  `lastFR` timestamp NULL DEFAULT NULL,
  `showAds` int(11) DEFAULT 0,
  `posts` int(11) DEFAULT 0,
  `lastForumContent` varchar(30000) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.users: ~0 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table xdiscuss3.wearing
CREATE TABLE IF NOT EXISTS `wearing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalogId` int(11) NOT NULL DEFAULT 0,
  `uid` int(11) NOT NULL DEFAULT 0,
  `aprString` varchar(128) DEFAULT NULL,
  `type` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table xdiscuss3.wearing: ~0 rows (approximately)
/*!40000 ALTER TABLE `wearing` DISABLE KEYS */;
/*!40000 ALTER TABLE `wearing` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
