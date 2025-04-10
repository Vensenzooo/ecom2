-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 09 avr. 2025 à 23:10
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `livresgourmands`
--

-- --------------------------------------------------------

--
-- Structure de la table `alerts`
--

CREATE TABLE `alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','danger') NOT NULL DEFAULT 'info',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `alerts`
--

INSERT INTO `alerts` (`id`, `user_id`, `created_by`, `message`, `type`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'vous travailler meme pas', 'warning', '2025-04-09 23:48:59', '2025-04-09 23:47:38', '2025-04-09 23:48:59'),
(2, 8, 1, 'Votre compte a été restreint: appelle moi urgent', 'danger', NULL, '2025-04-10 00:10:06', '2025-04-10 00:10:06'),
(3, 2, 1, 'Votre compte a été restreint: applelle moi urgent demain', 'danger', '2025-04-10 00:11:38', '2025-04-10 00:10:55', '2025-04-10 00:11:38'),
(4, 2, 1, 'La restriction sur votre compte a été levée.', 'info', '2025-04-10 00:33:26', '2025-04-10 00:30:09', '2025-04-10 00:33:26');

-- --------------------------------------------------------

--
-- Structure de la table `books`
--

CREATE TABLE `books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `categorie_id` bigint(20) UNSIGNED NOT NULL,
  `niveau_expertise` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `prix` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `books`
--

INSERT INTO `books` (`id`, `titre`, `description`, `auteur`, `categorie_id`, `niveau_expertise`, `stock`, `prix`, `created_at`, `updated_at`, `image_url`) VALUES
(3, 'Voyage culinaire en Asie', 'Découvrez les saveurs de la cuisine asiatique à travers 100 recettes authentiques.', 'Sophie Chen', 4, 'débutant', 35, 32.00, '2025-04-09 02:37:23', '2025-04-09 11:06:49', 'https://www.shutterflycanada.ca/en/hardcover-photo-book-12x12'),
(4, 'Le grand livre du véganisme', 'Une approche complète de la cuisine vegan avec plus de 200 recettes.', 'Léa Dubois', 1, 'amateur', 52, 28.75, '2025-04-09 02:37:23', '2025-04-09 11:07:23', 'https://www.shutterflycanada.ca/en/hardcover-photo-book-12x12'),
(5, 'Cuisiner pour les enfants', 'Des recettes adaptées aux goûts des enfants et faciles à préparer.', 'Thomas Bernard', 1, 'Débutant', 63, 19.99, '2025-04-09 02:37:23', '2025-04-09 02:37:23', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(6, 'Porro fugit autem.', 'Aspernatur aliquam autem error unde earum iusto at. Qui debitis deserunt ipsam alias repellendus consequatur eaque nemo. Ipsa hic et est possimus veritatis. Qui et repellendus quia ipsam est repellat.', 'Colin McLaughlin', 6, 'Avancé', 29, 17.66, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(7, 'Quam soluta maiores.', 'Omnis vitae esse perferendis explicabo aliquam. Recusandae dolor doloremque sit exercitationem. Animi beatae nisi quasi ab itaque. Dignissimos corporis omnis molestiae sit deleniti.', 'Bradley Labadie', 7, 'Intermédiaire', 41, 14.03, '2025-04-09 02:37:24', '2025-04-09 10:23:51', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(8, 'Laborum numquam voluptatem ex.', 'Necessitatibus corrupti consequatur officia est enim et maxime corrupti. Omnis laborum deleniti sunt vero. Dolore ut est impedit consequatur et. Aut sapiente aut quam voluptatem illum sunt ipsam.', 'Carleton Kuphal', 8, 'Débutant', 92, 32.33, '2025-04-09 02:37:24', '2025-04-09 10:26:07', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(9, 'Ad aut.', 'Voluptatem maxime minus aliquam. Sunt tenetur nostrum hic quo ipsa et. Natus quasi non sequi consequatur consequuntur. Possimus et nihil at.', 'Antonina Ondricka', 9, 'Intermédiaire', 35, 32.60, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(10, 'Ad est alias deleniti est.', 'Aliquam sint quisquam omnis a quod sint. Eius qui aut a tenetur. Qui mollitia dolor recusandae ut. Doloribus nisi ea non facilis.', 'Shyann Gutmann', 10, 'Avancé', 29, 38.56, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(11, 'Nam atque est possimus.', 'Voluptatibus illo dicta sit aut quos. Veniam quia debitis facilis delectus quo dicta. Odio corporis impedit expedita cumque.', 'Shany Jerde', 11, 'Intermédiaire', 23, 93.25, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(12, 'Quae ut corporis occaecati.', 'Veniam repudiandae aperiam alias ipsam sint. Voluptas mollitia cumque optio cupiditate praesentium ad. Non eius id libero aliquam aut.', 'Flavie Franecki', 12, 'Débutant', 26, 78.09, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(13, 'Pariatur nihil perferendis corporis velit.', 'Laudantium cum quos cumque. Corporis et accusantium suscipit tenetur doloremque aut. Qui blanditiis non quia. Voluptatem qui sed rem unde earum excepturi alias.', 'Graham Cronin', 13, 'Débutant', 33, 37.69, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(14, 'Exercitationem mollitia.', 'Nemo ea iste excepturi voluptatum vel. Velit consectetur qui aut et inventore. Maxime eos laboriosam iure quibusdam suscipit et itaque. Sunt aut molestias saepe nisi ullam.', 'Prof. Meaghan Oberbrunner PhD', 14, 'Débutant', 78, 78.40, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(15, 'Voluptas totam consequatur.', 'Quia cumque velit aut sit. Aliquid perspiciatis fugiat nostrum at qui. Dignissimos voluptatem odit id necessitatibus. Quia deleniti et eum fugit eligendi consequuntur facere.', 'Prof. Louvenia Lubowitz I', 15, 'Intermédiaire', 83, 33.85, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(16, 'Nihil in aut.', 'Tenetur et perspiciatis perspiciatis ut repellat officiis sed. Vel animi maiores nihil in qui mollitia. Harum labore nam minima magnam. Et unde sunt sunt rem id nihil.', 'Dr. Wellington McKenzie', 16, 'Intermédiaire', 68, 42.28, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(17, 'Voluptas error qui.', 'Magni itaque dolorem labore voluptates voluptatem beatae ut. Et voluptate qui voluptatem blanditiis asperiores deserunt. Deleniti possimus quisquam saepe et non unde dicta.', 'Alexandra Wilkinson', 17, 'Débutant', 36, 26.52, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(18, 'Ea consequuntur omnis.', 'Unde dolor eveniet quibusdam eos. Numquam culpa dolores velit dolorem. Accusamus possimus veniam nostrum eum doloribus.', 'Fern Sawayn', 18, 'Débutant', 45, 94.70, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(19, 'Tenetur repellendus.', 'Qui quasi quis voluptatem eaque quae. Accusamus expedita aliquid dolor corrupti quisquam deleniti. Nisi sit sit aperiam error magni. Quia dolores amet sint neque voluptatem esse consequatur.', 'Shemar Howe', 19, 'Avancé', 72, 78.80, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine'),
(20, 'Velit repellendus quam.', 'Error vel nisi provident est eos voluptatum quidem. Eum possimus qui ad velit.', 'Dr. Lora Monahan', 20, 'Intermédiaire', 98, 38.36, '2025-04-09 02:37:24', '2025-04-09 02:37:24', 'https://placehold.co/600x800?text=Livre+de+Cuisine');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `book_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `book_id`, `quantity`, `created_at`, `updated_at`) VALUES
(4, 9, 7, 10, '2025-04-09 10:27:53', '2025-04-09 10:27:53');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Cuisine française', 'Livres sur la gastronomie française traditionnelle et contemporaine', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(2, 'Pâtisserie', 'Recettes de gâteaux, tartes, et autres délices sucrés', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(3, 'Cuisine du monde', 'Découvrez les saveurs des différentes cuisines internationales', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(4, 'Végétarien et Vegan', 'Recettes sans viande et alternatives végétales', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(5, 'Cuisine familiale', 'Recettes faciles et économiques pour toute la famille', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(6, 'sed cupiditate', 'Unde impedit laudantium esse dicta quis aliquid quae. Ut repudiandae occaecati aperiam quae quod veniam consequatur. Dignissimos ad et deleniti est.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(7, 'in repellendus', 'Culpa qui dolorem nam nihil necessitatibus. Voluptatem est reiciendis voluptas rem animi quidem. Alias laudantium similique officia non. Molestiae dolorem vel aut earum possimus nihil ab.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(8, 'quia voluptas', 'Vero magnam dolores et magnam qui illum excepturi. Magnam est debitis et quas aut. Aut magni delectus minima. Est laudantium facere reiciendis nobis natus laudantium ducimus.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(9, 'nostrum porro', 'Ea illum sint voluptatem hic inventore consequatur aut quasi. Qui exercitationem sit qui ratione.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(10, 'porro aperiam', 'Quisquam ipsam molestias dolores. Officia officiis possimus voluptatum accusamus minima ut. Ipsa voluptates inventore dolor dolores.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(11, 'voluptas molestias', 'Qui possimus dicta illo assumenda nemo. Veniam non ea dignissimos hic eveniet temporibus nobis. Praesentium natus culpa iste saepe.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(12, 'reiciendis nesciunt', 'Ut eius non dolor possimus. Officia asperiores animi itaque numquam. Rerum praesentium voluptatem id praesentium. Cupiditate et quod autem voluptatum error voluptate sed.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(13, 'et asperiores', 'Corporis at harum voluptatem molestias. Corporis nobis ut recusandae molestias occaecati ut. Neque labore assumenda exercitationem nesciunt ut. Est harum veritatis officia aliquid culpa vel.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(14, 'et impedit', 'Accusamus dolorem vero magni debitis eligendi enim. At natus et cum harum consequatur enim. Et voluptas voluptatem totam. Fuga omnis fuga expedita placeat.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(15, 'aperiam et', 'Voluptas non atque dignissimos iste explicabo et. Natus assumenda quo id. Harum pariatur explicabo iure aut sed. Qui est doloribus voluptatem aut officia ipsum quas.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(16, 'quae et', 'Officiis doloremque autem ut incidunt occaecati eveniet. Non reiciendis mollitia rem sapiente libero. Id modi ducimus laudantium. Ut sequi minus corporis enim facere.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(17, 'ipsam praesentium', 'Laborum tenetur quidem consequuntur et. Accusantium dolorum ut vero minima vitae. Natus incidunt quis ratione doloribus. Corrupti amet voluptatem voluptas non deleniti reiciendis.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(18, 'adipisci qui', 'Quod sunt facilis facilis harum vel dolores. Facilis vel atque rerum error. Et dolor distinctio recusandae animi. Aliquam et exercitationem ex harum repellendus.', '2025-04-09 02:37:23', '2025-04-09 02:37:23'),
(19, 'praesentium nihil', 'Repellat similique ut perspiciatis consectetur eveniet. Dolorum cumque dolore consequatur incidunt similique maxime rerum. Eaque et optio quia.', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(20, 'aperiam explicabo', 'Et nesciunt consectetur pariatur rerum aut voluptas. Quisquam excepturi qui architecto officia. Nihil sapiente aspernatur ex. Et sit enim laborum sunt.', '2025-04-09 02:37:24', '2025-04-09 02:37:24');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contenu` text NOT NULL,
  `statut` varchar(255) NOT NULL DEFAULT 'en attente',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `book_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `contenu`, `statut`, `user_id`, `book_id`, `created_at`, `updated_at`) VALUES
(2, 'Odit nobis repellendus voluptatem qui. Dolorem blanditiis voluptates fuga. Consequuntur assumenda hic maiores.', 'approuvé', 2, 17, '2025-04-09 02:37:24', '2025-04-09 09:20:52'),
(3, 'Autem neque cumque adipisci placeat sed. Voluptate omnis non sed culpa dolorem aut exercitationem. Quibusdam autem hic sed nihil voluptatem fugiat dolorem. Velit optio ea non facere repellat. Corporis dicta et sit cupiditate neque.', 'approuvé', 3, 3, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(4, 'Repellat est enim rerum dolorem officiis. Autem expedita eveniet at numquam sed hic. Nesciunt omnis sit non sit quasi. Officia corporis nostrum animi quia sit.', 'en attente', 8, 19, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(6, 'Maiores cumque ut ullam distinctio tenetur voluptatem qui. Architecto ut voluptas eos assumenda rem. Id rerum reprehenderit omnis ad velit est.', 'approuvé', 7, 7, '2025-04-09 02:37:24', '2025-04-09 22:58:16'),
(7, 'Voluptas qui corrupti dicta. Doloribus odio minima in vero et et est. Molestiae dolorum similique eum ratione eius atque aspernatur.', 'en attente', 8, 5, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(8, 'Eveniet et sit necessitatibus perferendis sed repellat. Dolor doloribus qui nisi. Est sed facere tempore placeat non. Molestiae numquam quaerat autem.', 'rejeté', 3, 14, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(10, 'Maxime eos delectus ut sit rerum aliquam id. Sed voluptatem quia sequi sunt. Natus dolor commodi cum magnam.', 'en attente', 1, 17, '2025-04-09 02:37:24', '2025-04-09 22:49:29'),
(11, 'Assumenda accusantium nihil sit ullam possimus quasi. Quibusdam et quia omnis repellat nihil et voluptatum. Vel ut molestiae consequatur ipsa aliquid aut. Ullam adipisci temporibus aut enim totam.', 'approuvé', 2, 10, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(12, 'Consequatur et aut nihil voluptate ut nihil. Autem minus quo quas nam sint omnis. Debitis iure voluptatem deleniti vero et aut inventore.', 'approuvé', 7, 12, '2025-04-09 02:37:24', '2025-04-09 22:48:48'),
(14, 'Numquam repellendus natus ducimus ut autem. Aspernatur autem porro et numquam vel. Vitae odio eos quibusdam quis consequatur corporis.', 'approuvé', 2, 19, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(15, 'Debitis fugiat est quae et et labore omnis. Et facere vel eius officiis. Corporis rerum earum explicabo minus deleniti accusantium.', 'en attente', 1, 6, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(18, 'Quis eum facilis nulla ipsum reprehenderit expedita eum. Ut esse molestias accusamus dignissimos expedita. Velit reiciendis omnis aut autem amet.', 'en attente', 3, 11, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(19, 'Tempora possimus fugit fugiat quod similique ut sed rerum. Ratione quisquam nihil provident non. Illum mollitia minus qui occaecati. Omnis cum iste recusandae aut sunt sunt qui.', 'approuvé', 8, 17, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(21, 'Unde ex id et distinctio. Velit reprehenderit officiis et modi hic ut nostrum. In vel consequuntur dolores a placeat nihil non. Eligendi est quam nemo quo est aut quis.', 'approuvé', 8, 20, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(22, 'Quam nihil et provident exercitationem enim. Est magnam ut deserunt ratione sed. Est sapiente nesciunt perferendis iure quae.', 'en attente', 3, 13, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(23, 'Aut sequi architecto animi cumque nam natus sit sapiente. Occaecati ut adipisci ut impedit ut nam nulla. Vitae consequuntur perferendis quos atque praesentium est et corrupti. Dolorem similique ut et voluptas. Sunt voluptas modi nihil consequuntur est quasi ab et.', 'approuvé', 4, 17, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(24, 'Aut assumenda incidunt fuga nemo eum ullam velit. Iure a vel consequatur qui.', 'rejeté', 1, 19, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(25, 'Dolores ducimus incidunt qui officiis velit. Aperiam incidunt libero perferendis unde. Reiciendis doloribus assumenda doloribus molestiae et. Possimus sunt reiciendis est.', 'rejeté', 5, 15, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(26, 'At quo et nulla repellendus nobis. Ea aperiam nemo natus sit. Ullam sed aliquam tenetur aut. Rem accusamus aut illo atque quos et recusandae alias.', 'approuvé', 1, 19, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(27, 'Ipsa enim distinctio veniam fuga autem omnis voluptas est. Porro laboriosam aut qui aut eveniet optio. Ea labore libero iure voluptas.', 'rejeté', 2, 17, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(28, 'Nihil eaque iste explicabo ab eos saepe. Eveniet et cupiditate velit et facere. Non illum deleniti a et modi.', 'rejeté', 2, 6, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(29, 'Laboriosam aut assumenda qui aut. Facere quis laborum eligendi odit dicta aperiam nisi. Cum et eum consectetur totam porro eos et accusamus. Officia rerum doloribus aut architecto in dolores animi.', 'en attente', 3, 15, '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(30, 'Quisquam numquam aut eos qui. Ducimus tempore eum eveniet blanditiis ut nihil. Ut rem in commodi quidem eum est sint. Quam exercitationem laudantium quo ab illo. Ipsa suscipit et tempore voluptatem quibusdam vero.', 'rejeté', 5, 4, '2025-04-09 02:37:24', '2025-04-09 02:37:24');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `friend_invitations`
--

CREATE TABLE `friend_invitations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gift_list_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gift_lists`
--

CREATE TABLE `gift_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_evenement` date DEFAULT NULL,
  `code_partage` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gift_list_items`
--

CREATE TABLE `gift_list_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gift_list_id` bigint(20) UNSIGNED NOT NULL,
  `book_id` bigint(20) UNSIGNED NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `reserve` tinyint(1) NOT NULL DEFAULT 0,
  `reserved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2023_06_01_000001_create_categories_table', 1),
(5, '2023_06_01_000002_create_books_table', 1),
(6, '2023_06_01_000003_create_roles_table', 1),
(7, '2023_06_01_000004_create_comments_table', 1),
(8, '2023_06_01_000005_create_sales_table', 1),
(9, '2023_06_01_000006_create_user_role_table', 1),
(10, '2025_04_09_000001_create_carts_table', 2),
(11, '2025_04_10_000001_create_gift_lists_table', 2),
(12, '2025_04_10_000002_create_gift_list_items_table', 2),
(13, '2025_04_10_000003_create_friend_invitations_table', 2),
(14, '2025_04_11_000001_create_orders_table', 2),
(15, '2023_04_13_000001_add_image_url_to_books_table', 3),
(16, '2025_04_10_000004_increase_image_url_length_in_books_table', 4),
(17, '2023_04_14_000001_create_alerts_table', 5),
(18, '2023_04_14_000002_add_status_to_users_table', 5),
(19, '2023_04_15_000001_add_capabilities_to_roles_table', 6),
(20, '2023_04_15_000002_create_permissions_table', 6),
(21, '2023_04_09_add_description_to_roles_table', 7);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `statut` varchar(255) NOT NULL,
  `mode_paiement` varchar(255) NOT NULL,
  `details_paiement` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details_paiement`)),
  `details_webhook` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details_webhook`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `transaction_id`, `montant_total`, `statut`, `mode_paiement`, `details_paiement`, `details_webhook`, `created_at`, `updated_at`) VALUES
(1, 9, 'ORDER_67f6124339780', 14.03, 'completed', 'paypal', '\"{\\\"simulated\\\":true}\"', NULL, '2025-04-09 10:23:03', '2025-04-09 10:23:03'),
(2, 9, 'ORDER_67f612746c2e2', 126.27, 'completed', 'paypal', '\"{\\\"simulated\\\":true}\"', NULL, '2025-04-09 10:23:51', '2025-04-09 10:23:51'),
(3, 9, 'ORDER_67f612fcd3d94', 32.33, 'completed', 'paypal', '\"{\\\"simulated\\\":true}\"', NULL, '2025-04-09 10:26:07', '2025-04-09 10:26:07');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `can_manage_books` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_categories` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_comments` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_sales` tinyint(1) NOT NULL DEFAULT 0,
  `can_view_dashboard` tinyint(1) NOT NULL DEFAULT 0,
  `max_books_per_day` int(11) DEFAULT NULL,
  `max_comments_per_day` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `nom`, `description`, `created_at`, `updated_at`, `can_manage_books`, `can_manage_categories`, `can_manage_comments`, `can_manage_sales`, `can_view_dashboard`, `max_books_per_day`, `max_comments_per_day`) VALUES
(1, 'admin', NULL, '2025-04-09 02:37:22', '2025-04-09 02:37:22', 0, 0, 0, 0, 0, NULL, NULL),
(2, 'gestionnaire', NULL, '2025-04-09 02:37:22', '2025-04-09 02:37:22', 0, 0, 0, 0, 0, NULL, NULL),
(3, 'editeur', NULL, '2025-04-09 02:37:22', '2025-04-09 02:37:22', 0, 0, 0, 0, 0, NULL, NULL),
(4, 'client', NULL, '2025-04-09 09:57:51', '2025-04-09 09:57:51', 0, 0, 0, 0, 0, NULL, NULL),
(5, 'support', 'gestion', '2025-04-10 01:07:43', '2025-04-10 01:07:43', 0, 0, 1, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `role_permission`
--

CREATE TABLE `role_permission` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `book_id` bigint(20) UNSIGNED NOT NULL,
  `quantité` int(11) NOT NULL,
  `prix_unitaire` decimal(8,2) NOT NULL,
  `date_vente` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sales`
--

INSERT INTO `sales` (`id`, `book_id`, `quantité`, `prix_unitaire`, `date_vente`, `created_at`, `updated_at`) VALUES
(1, 19, 2, 78.80, '2025-02-07', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(2, 10, 5, 38.56, '2025-01-18', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(3, 13, 5, 37.69, '2025-03-23', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(5, 7, 2, 14.03, '2025-03-27', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(6, 12, 3, 78.09, '2025-03-16', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(7, 15, 5, 33.85, '2025-03-21', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(8, 6, 3, 17.66, '2025-01-19', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(9, 13, 5, 37.69, '2025-02-14', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(10, 10, 4, 38.56, '2025-01-19', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(11, 14, 2, 78.40, '2025-03-16', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(12, 5, 2, 19.99, '2025-02-13', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(13, 17, 1, 26.52, '2025-02-14', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(14, 16, 5, 42.28, '2025-01-14', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(16, 11, 5, 93.25, '2025-01-24', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(17, 18, 2, 94.70, '2025-03-02', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(18, 7, 4, 14.03, '2025-01-18', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(20, 16, 2, 42.28, '2025-01-22', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(21, 10, 3, 38.56, '2025-03-26', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(22, 12, 4, 78.09, '2025-03-02', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(23, 19, 2, 78.80, '2025-02-11', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(24, 13, 4, 37.69, '2025-02-16', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(25, 8, 5, 32.33, '2025-02-17', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(27, 19, 2, 78.80, '2025-02-25', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(28, 8, 3, 32.33, '2025-03-18', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(29, 5, 5, 19.99, '2025-04-06', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(30, 3, 1, 32.00, '2025-02-12', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(31, 8, 4, 32.33, '2025-02-14', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(32, 14, 5, 78.40, '2025-01-24', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(33, 10, 2, 38.56, '2025-02-11', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(34, 17, 1, 26.52, '2025-01-19', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(35, 5, 5, 19.99, '2025-02-10', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(36, 16, 3, 42.28, '2025-03-13', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(38, 12, 3, 78.09, '2025-04-01', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(39, 7, 4, 14.03, '2025-01-23', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(40, 9, 3, 32.60, '2025-03-15', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(41, 11, 5, 93.25, '2025-03-01', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(42, 8, 3, 32.33, '2025-01-16', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(43, 7, 5, 14.03, '2025-02-24', '2025-04-09 02:37:24', '2025-04-09 02:37:24'),
(44, 4, 5, 28.75, '2025-03-06', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(45, 16, 4, 42.28, '2025-03-29', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(46, 8, 2, 32.33, '2025-01-08', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(48, 5, 1, 19.99, '2025-01-29', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(49, 3, 4, 32.00, '2025-01-28', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(50, 7, 2, 14.03, '2025-01-12', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(51, 3, 1, 32.00, '2025-03-23', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(52, 14, 4, 78.40, '2025-01-26', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(53, 4, 3, 28.75, '2025-03-16', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(54, 14, 3, 78.40, '2025-03-22', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(55, 17, 5, 26.52, '2025-04-04', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(56, 9, 4, 32.60, '2025-03-17', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(57, 7, 2, 14.03, '2025-01-14', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(58, 18, 2, 94.70, '2025-01-09', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(59, 13, 4, 37.69, '2025-02-10', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(60, 17, 3, 26.52, '2025-01-09', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(61, 5, 5, 19.99, '2025-02-25', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(62, 4, 2, 28.75, '2025-04-03', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(63, 10, 1, 38.56, '2025-02-06', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(64, 7, 1, 14.03, '2025-01-29', '2025-04-09 02:37:25', '2025-04-09 02:37:25'),
(65, 20, 5, 38.36, '2025-01-16', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(67, 4, 4, 28.75, '2025-03-19', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(69, 6, 3, 17.66, '2025-03-29', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(70, 14, 3, 78.40, '2025-01-26', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(71, 15, 2, 33.85, '2025-03-18', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(74, 10, 3, 38.56, '2025-03-20', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(75, 12, 4, 78.09, '2025-01-20', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(76, 6, 2, 17.66, '2025-03-27', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(77, 4, 3, 28.75, '2025-02-06', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(78, 4, 1, 28.75, '2025-01-11', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(80, 11, 2, 93.25, '2025-03-14', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(81, 6, 3, 17.66, '2025-02-16', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(82, 9, 2, 32.60, '2025-03-09', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(83, 6, 5, 17.66, '2025-02-15', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(84, 11, 4, 93.25, '2025-02-12', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(85, 19, 5, 78.80, '2025-01-22', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(86, 17, 5, 26.52, '2025-01-24', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(87, 17, 2, 26.52, '2025-02-16', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(89, 12, 2, 78.09, '2025-02-13', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(90, 7, 2, 14.03, '2025-01-14', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(91, 7, 2, 14.03, '2025-03-02', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(92, 16, 4, 42.28, '2025-03-29', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(95, 17, 5, 26.52, '2025-01-11', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(96, 15, 1, 33.85, '2025-03-15', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(97, 14, 2, 78.40, '2025-03-05', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(98, 12, 3, 78.09, '2025-03-14', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(100, 4, 5, 28.75, '2025-03-06', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(101, 4, 2, 28.75, '2025-03-16', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(102, 7, 3, 14.03, '2025-03-23', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(103, 9, 1, 32.60, '2025-03-11', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(104, 20, 2, 38.36, '2025-03-29', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(105, 20, 2, 38.36, '2025-03-18', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(106, 5, 3, 19.99, '2025-04-06', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(107, 15, 1, 33.85, '2025-03-30', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(109, 5, 5, 19.99, '2025-04-04', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(110, 4, 5, 28.75, '2025-04-04', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(112, 9, 4, 32.60, '2025-03-19', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(113, 12, 3, 78.09, '2025-03-11', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(114, 12, 3, 78.09, '2025-03-24', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(115, 12, 3, 78.09, '2025-03-26', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(116, 8, 5, 32.33, '2025-03-18', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(117, 9, 2, 32.60, '2025-03-12', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(118, 17, 2, 26.52, '2025-03-16', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(119, 9, 2, 32.60, '2025-03-19', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(120, 3, 2, 32.00, '2025-04-06', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(121, 16, 4, 42.28, '2025-04-04', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(122, 7, 2, 14.03, '2025-04-08', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(123, 15, 4, 33.85, '2025-03-10', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(124, 5, 3, 19.99, '2025-03-18', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(125, 6, 1, 17.66, '2025-03-13', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(127, 11, 5, 93.25, '2025-03-19', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(128, 17, 1, 26.52, '2025-03-25', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(129, 20, 2, 38.36, '2025-04-07', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(130, 14, 1, 78.40, '2025-03-24', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(131, 12, 2, 78.09, '2025-04-01', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(132, 17, 2, 26.52, '2025-04-03', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(133, 17, 5, 26.52, '2025-04-04', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(134, 12, 1, 78.09, '2025-03-20', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(135, 9, 5, 32.60, '2025-03-19', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(136, 5, 4, 19.99, '2025-03-20', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(137, 15, 5, 33.85, '2025-03-23', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(138, 9, 5, 32.60, '2025-03-17', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(139, 8, 3, 32.33, '2025-04-01', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(140, 9, 2, 32.60, '2025-03-25', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(141, 11, 1, 93.25, '2025-03-22', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(142, 3, 4, 32.00, '2025-03-27', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(143, 18, 5, 94.70, '2025-03-10', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(145, 16, 3, 42.28, '2025-03-17', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(146, 12, 3, 78.09, '2025-03-26', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(147, 13, 3, 37.69, '2025-03-31', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(148, 10, 1, 38.56, '2025-03-16', '2025-04-09 02:37:26', '2025-04-09 02:37:26'),
(151, 7, 1, 14.03, '2025-04-09', '2025-04-09 10:23:03', '2025-04-09 10:23:03'),
(152, 7, 9, 14.03, '2025-04-09', '2025-04-09 10:23:51', '2025-04-09 10:23:51'),
(153, 8, 1, 32.33, '2025-04-09', '2025-04-09 10:26:07', '2025-04-09 10:26:07');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('j5BFu3kL8TaBaH4Kc4ruutbxIJ2R9wedYDgjDWy0', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTUFGVDBRZGFpZXRWUWphaVdPbGtNRWYwd2lVd3FQQ0dDcXNFRElteSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yb2xlcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1744232877);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_restricted` tinyint(1) NOT NULL DEFAULT 0,
  `restriction_reason` text DEFAULT NULL,
  `restricted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_restricted`, `restriction_reason`, `restricted_at`) VALUES
(1, 'Admina', 'admin@livresgourmands.net', NULL, '$2y$12$rsz4FpZAeCO0YypZ2KgupOLtFEqWaLAkPt8XpWLHEFaM/o/ItB7me', NULL, '2025-04-09 02:37:22', '2025-04-09 23:24:12', 0, NULL, NULL),
(2, 'Gestion sup', 'gestionnaire@livresgourmands.net', NULL, '$2y$12$GofRxcZUemc./65G.nQa1Oe452ubLqqzYpoj5Wrd.llCb7ctoJ9mi', NULL, '2025-04-09 02:37:22', '2025-04-10 00:30:09', 0, NULL, NULL),
(3, 'Éditeur', 'editeur@livresgourmands.net', NULL, '$2y$12$/.nD1v7WP7xpHIX2wJjglu5MjSFTsDWHCNduPxcZqCXcFMtNm7RUS', NULL, '2025-04-09 02:37:23', '2025-04-09 02:37:23', 0, NULL, NULL),
(4, 'Prof. Liliane Wunsch', 'jaiden55@example.net', '2025-04-09 02:37:23', '$2y$12$M0KZCbeU.jHt4TbCkiAix.dOyP/czlTqjkQaB9Wzvx7QN.xrdCnHq', 'K6XuBYy9JP', '2025-04-09 02:37:23', '2025-04-09 02:37:23', 0, NULL, NULL),
(5, 'Alfredo Gutkowski DDS', 'hammes.rubye@example.org', '2025-04-09 02:37:23', '$2y$12$M0KZCbeU.jHt4TbCkiAix.dOyP/czlTqjkQaB9Wzvx7QN.xrdCnHq', 'Tf0nUGSrRH', '2025-04-09 02:37:23', '2025-04-09 02:37:23', 0, NULL, NULL),
(6, 'Kirsten Pollich', 'georgiana96@example.net', '2025-04-09 02:37:23', '$2y$12$M0KZCbeU.jHt4TbCkiAix.dOyP/czlTqjkQaB9Wzvx7QN.xrdCnHq', 'FSgcfYvKXR', '2025-04-09 02:37:23', '2025-04-09 02:37:23', 0, NULL, NULL),
(7, 'Mrs. Michele Dicki PhD', 'reggie60@example.com', '2025-04-09 02:37:23', '$2y$12$M0KZCbeU.jHt4TbCkiAix.dOyP/czlTqjkQaB9Wzvx7QN.xrdCnHq', 'SPhWiG2vPl', '2025-04-09 02:37:23', '2025-04-09 02:37:23', 0, NULL, NULL),
(8, 'Dr. Natalia Hahn', 'colleen16@example.net', '2025-04-09 02:37:23', '$2y$12$M0KZCbeU.jHt4TbCkiAix.dOyP/czlTqjkQaB9Wzvx7QN.xrdCnHq', 'R4BASBiiVm', '2025-04-09 02:37:23', '2025-04-10 00:10:06', 1, 'appelle moi urgent', '2025-04-10 00:10:06'),
(9, 'faycale', 'quen@gmail.com', NULL, '$2y$12$yCsMNMhTAgMsBqU2aKPpFumTR448mUjf1mC67w/v56lFKejGof5ey', NULL, '2025-04-09 03:35:50', '2025-04-09 23:49:58', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_role`
--

CREATE TABLE `user_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 3, 3, NULL, NULL),
(4, 4, 2, NULL, NULL),
(5, 5, 2, NULL, NULL),
(6, 6, 1, NULL, NULL),
(7, 7, 3, NULL, NULL),
(8, 8, 2, NULL, NULL),
(9, 1, 4, NULL, NULL),
(10, 9, 4, NULL, NULL),
(11, 1, 2, '2025-04-09 22:45:16', '2025-04-09 22:45:16'),
(12, 1, 3, '2025-04-09 22:45:16', '2025-04-09 22:45:16');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alerts_user_id_foreign` (`user_id`),
  ADD KEY `alerts_created_by_foreign` (`created_by`);

--
-- Index pour la table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `books_categorie_id_foreign` (`categorie_id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carts_user_id_book_id_unique` (`user_id`,`book_id`),
  ADD KEY `carts_book_id_foreign` (`book_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_book_id_foreign` (`book_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `friend_invitations`
--
ALTER TABLE `friend_invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `friend_invitations_token_unique` (`token`),
  ADD KEY `friend_invitations_gift_list_id_foreign` (`gift_list_id`);

--
-- Index pour la table `gift_lists`
--
ALTER TABLE `gift_lists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gift_lists_code_partage_unique` (`code_partage`),
  ADD KEY `gift_lists_user_id_foreign` (`user_id`);

--
-- Index pour la table `gift_list_items`
--
ALTER TABLE `gift_list_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gift_list_items_gift_list_id_foreign` (`gift_list_id`),
  ADD KEY `gift_list_items_book_id_foreign` (`book_id`),
  ADD KEY `gift_list_items_reserved_by_foreign` (`reserved_by`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_transaction_id_unique` (`transaction_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Index pour la table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_book_id_foreign` (`book_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_role_user_id_foreign` (`user_id`),
  ADD KEY `user_role_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `friend_invitations`
--
ALTER TABLE `friend_invitations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gift_lists`
--
ALTER TABLE `gift_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gift_list_items`
--
ALTER TABLE `gift_list_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alerts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `friend_invitations`
--
ALTER TABLE `friend_invitations`
  ADD CONSTRAINT `friend_invitations_gift_list_id_foreign` FOREIGN KEY (`gift_list_id`) REFERENCES `gift_lists` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `gift_lists`
--
ALTER TABLE `gift_lists`
  ADD CONSTRAINT `gift_lists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `gift_list_items`
--
ALTER TABLE `gift_list_items`
  ADD CONSTRAINT `gift_list_items_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gift_list_items_gift_list_id_foreign` FOREIGN KEY (`gift_list_id`) REFERENCES `gift_lists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gift_list_items_reserved_by_foreign` FOREIGN KEY (`reserved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_role_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
