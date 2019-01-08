<?php

//
ini_set('display_errors', "On");
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);
ini_startup();//定数読み込み
session_start();

//その他自動ロード系ライブラリファイル
require_once(APPROOT."/lib/priveledge.php");
require_once(APPROOT."/lib/pdoutil.php");

//connection to DB
$db = new pdoutil();

//View向け共通定義
$_view['faction'] = ["*UNDEF*","RES","ENL","XF"];

// --------------------------

//View helper: htmlエスケープ済みでecho
function e($str){
  echo htmlspecialchars($str);
}

//View helper: htmlエスケープ済みでprint_r
function er($arr){
  print "<pre>".htmlspecialchars(print_r($arr, true))."</pre>";
}


// --------------------------

//定数読み込み
function ini_startup(){
  $ini = parse_ini_file("../config/config.ini");
  foreach($ini as $k=>$v){
    define($k, $v);
  }
  return;
}


/*

DB Schema:

-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2018 年 10 月 02 日 11:24
-- サーバのバージョン： 5.7.23-0ubuntu0.16.04.1
-- PHP Version: 7.0.32-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ingressfs`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `agents`
--

CREATE TABLE `agents` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_',
  `faction` int(11) NOT NULL DEFAULT '1' COMMENT 'o:Undef, 1*Res, 2:Enl',
  `lvfrom` int(11) NOT NULL DEFAULT '0',
  `lvto` int(11) NOT NULL DEFAULT '0',
  `apfrom` int(11) NOT NULL DEFAULT '0',
  `apto` int(11) NOT NULL DEFAULT '0',
  `trfrom` int(11) NOT NULL DEFAULT '0',
  `trto` int(11) NOT NULL DEFAULT '0',
  `memo` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_',
  `eventdate` date NOT NULL DEFAULT '2018-01-01',
  `mfrom` timestamp NOT NULL DEFAULT '2018-01-01 04:00:00',
  `mto` timestamp NOT NULL DEFAULT '2018-01-01 06:00:00',
  `pass_hq` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_',
  `pass_leaders` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_',
  `pass_agents` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_',
  `available` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0:Enable, 1:not4Ag,Leader, 2:not4ALL',
  `memo` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_',
  `team_type` int(11) NOT NULL DEFAULT '1' COMMENT '0:Undef, 1:Res, 2:Enl, 3:XF',
  `event_id` int(11) DEFAULT NULL,
  `memo` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

*/