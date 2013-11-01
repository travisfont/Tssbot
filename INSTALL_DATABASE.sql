CREATE DATABASE IF NOT EXISTS tssbot;

USE tssbot;


DROP TABLE IF EXISTS server;

CREATE TABLE `server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` varchar(255) DEFAULT NULL,
  `host` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS channels;

CREATE TABLE `channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` int(11) DEFAULT NULL,
  `channel` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS logs;

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel` varchar(255) DEFAULT NULL,
  `nick` varchar(255) DEFAULT NULL,
  `msg` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;