-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 02/05/2025 às 16:28
-- Versão do servidor: 8.3.0
-- Versão do PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `carteira_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `agency_number` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `account_number` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `success` tinyint NOT NULL DEFAULT '0',
  `attempted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(12, '2025-04-29-143347', 'App\\Database\\Migrations\\CreateUsers', 'default', 'App', 1746053197, 1),
(13, '2025-04-29-143447', 'App\\Database\\Migrations\\CreateTransactions', 'default', 'App', 1746053197, 1),
(14, '2025-04-30-001450', 'App\\Database\\Migrations\\CreateLoginAttemptsTable', 'default', 'App', 1746053197, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` enum('deposit','transfer','reversal') COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `related_user_id` int DEFAULT NULL,
  `is_reversed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_number` int NOT NULL,
  `agency_number` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1234',
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `cpf` varchar(14) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('ativo','inativo') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'ativo',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf` (`cpf`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `account_number` (`account_number`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `account_number`, `agency_number`, `name`, `email`, `cpf`, `password`, `balance`, `status`, `created_at`, `updated_at`) VALUES
(1, 1001, '1234', 'João Silva', 'joao@gmail.com', '688.450.740-30', '$2y$10$ZPM97gFOX0CkO4EFpEVLiuTzGFrGABuZxNI4Xk2f6QQyQMJylKMRy', 1000.00, 'ativo', '2025-05-02 16:27:11', '0000-00-00 00:00:00'),
(2, 1002, '1234', 'Maria Souza', 'maria@gmail.com', '534.636.710-18', '$2y$10$S.tbgbxkKt0ObBDMze8YXuTKHbGSGNp9DZbmhaJBBlFTeJ3nnIVpO', 1500.00, 'ativo', '2025-05-02 16:27:11', '0000-00-00 00:00:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
