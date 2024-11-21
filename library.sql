-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 03:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `available` int(1) DEFAULT 3,
  `image_url` varchar(255) DEFAULT NULL,
  `publication_date` date DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `description`, `category`, `available`, `image_url`, `publication_date`, `date_added`) VALUES
(72, 'La amiga estupenda', 'Elena Ferrante', 'Relato de una amistad entre dos mujeres en la Italia de mediados del siglo XX.', 'Ficción Contemporánea', 3, '/uploads/1731540316_91hocoJsFiL._UF1000,1000_QL80_.jpg', '2011-02-13', '2024-11-13 23:25:16'),
(73, 'Mil soles espléndidos', 'Khaled Hosseini', 'La vida de dos mujeres en Afganistán, unidas por el destino y la adversidad.', 'Ficción Contemporánea', 3, '/uploads/1731540364_images.jpg', '2007-06-13', '2024-11-13 23:26:04'),
(74, 'Brooklyn', 'Colm Tóibín', 'La historia de una joven irlandesa que emigra a Nueva York y enfrenta dilemas de identidad y amor.', 'Ficción Contemporánea', 3, '/uploads/1731540415_brooklyn-9781501106477_hr.jpg', '2009-09-07', '2024-11-13 23:26:55'),
(75, 'La maravillosa vida breve de Óscar Wao', 'Junot Díaz', 'Un vistazo a la vida de un joven dominicano-americano y la maldición que afecta a su familia.', 'Ficción Contemporánea', 3, '/uploads/1731540471_81KK-jc4RcL._AC_UF1000,1000_QL80_.jpg', '2007-08-15', '2024-11-13 23:27:51'),
(76, 'Todo está iluminado', 'Jonathan Safran Foer', 'La historia de un joven en busca de su pasado familiar en Ucrania.', 'Ficción Contemporánea', 3, '/uploads/1731540534_portada_todo-esta-iluminado_jonathan-safran-foer_201607251750.jpg', '2002-03-18', '2024-11-13 23:28:54'),
(77, 'El curioso incidente del perro a medianoche', 'Mark Haddon', 'Narrado desde el punto de vista de un adolescente con autismo que investiga la muerte de un perro.', 'Ficción Contemporánea', 3, '/uploads/1731540581_81iPsADsnFS._AC_UF1000,1000_QL80_.jpg', '2003-11-06', '2024-11-13 23:29:41'),
(78, 'Las hijas del capitán', 'María Dueñas', 'Las peripecias de tres jóvenes españolas en la Nueva York de los años 30.', 'Ficción Contemporánea', 3, '/uploads/1731540652_y648.jpg', '2018-12-20', '2024-11-13 23:30:25'),
(79, 'Olvidado Rey Gudú', 'Ana María Matute', 'Un mundo medieval fantástico que explora el poder, la magia y la ambición humana.', 'Ficción Contemporánea', 3, '/uploads/1731540701_images (2).jpg', '1996-06-11', '2024-11-13 23:31:41'),
(80, 'El Palacio de la Luna', 'Paul Auster', 'La vida de un joven en Nueva York se convierte en una serie de eventos extraños y reveladores.', 'Ficción Contemporánea', 3, '/uploads/1731540765_descarga (1).jpg', '1991-02-13', '2024-11-13 23:32:45'),
(81, 'Juego de tronos', 'George R.R. Martin', 'Comienza la épica batalla por el trono de hierro en los Siete Reinos.', 'Fantasía', 3, '/uploads/1731540881_91w3b+PWB3L._AC_UF1000,1000_QL80_.jpg', '1996-11-11', '2024-11-13 23:34:41'),
(82, 'El nombre del viento', 'Patrick Rothfuss', 'La vida de Kvothe, un talentoso joven con un pasado misterioso, en un mundo de magia.', 'Fantasía', 3, '/uploads/1731540955_descarga (2).jpg', '2007-09-11', '2024-11-13 23:35:55'),
(83, 'La historia interminable', 'Michael Ende', 'Un niño encuentra un libro mágico que lo transporta a un mundo de aventuras.', 'Fantasía', 3, '/uploads/1731541075_descarga (3).jpg', '2003-05-05', '2024-11-13 23:37:55'),
(84, 'El camino de los reyes', 'Brandon Sanderson', 'Primera entrega de la serie El Archivo de las Tormentas, donde héroes y villanos se enfrentan en un mundo devastado.', 'Fantasía', 3, '/uploads/1731541158_images (3).jpg', '2010-10-11', '2024-11-13 23:39:18'),
(85, 'Los héroes', 'Joe Abercrombie', 'Una guerra épica narrada con realismo y complejidad moral.', 'Fantasía', 3, '/uploads/1731541212_81jELQ4ajwL._AC_UF894,1000_QL80_.jpg', '2011-03-04', '2024-11-13 23:40:12'),
(86, 'American Gods', 'Neil Gaiman', 'Un hombre descubre un mundo oculto de dioses antiguos en Estados Unidos.', 'Fantasía', 3, '/uploads/1731541283_91U402Q1BXL._AC_UF894,1000_QL80_.jpg', '2001-01-11', '2024-11-13 23:41:23'),
(87, 'La guerra de las brujas', 'Maite Carranza', 'La historia de una joven destinada a ser la líder de una guerra entre brujas.', 'Fantasía', 3, '/uploads/1731541337_8165dpxMaVL.jpg', '2005-02-22', '2024-11-13 23:42:17'),
(88, 'El último deseo', 'Andrzej Sapkowski', 'rimer libro de la serie Geralt de Rivia, sobre un cazador de monstruos en un mundo fantástico.', 'Fantasía', 3, '/uploads/1731541435_descarga (4).jpg', '1993-04-27', '2024-11-13 23:43:55'),
(89, 'Eragon', 'Christopher Paolini', 'La aventura de un joven dragón y su jinete en un reino lleno de magia y dragones.', 'Fantasía', 3, '/uploads/1731541488_images (4).jpg', '2002-12-30', '2024-11-13 23:44:48'),
(90, 'La canción de los reyes', 'Bernard Cornwell', 'Primer libro de la serie Sajones, Vikingos y Normandos, que mezcla historia y mitología.', 'Fantasía', 3, '/uploads/1731541583_81-2aJswogL._AC_UF894,1000_QL80_.jpg', '1994-08-12', '2024-11-13 23:46:23'),
(91, 'Ready Player One', 'Ernest Cline', 'Un futuro distópico donde un joven compite en un mundo virtual para ganar una fortuna.', 'Ciencia Ficción', 3, '/uploads/1731541633_91FGDm7MfIL.jpg', '2011-02-23', '2024-11-13 23:47:13'),
(92, 'El problema de los tres cuerpos', 'Cixin Liu', 'Primer libro de la trilogía El recuerdo del pasado de la Tierra, que aborda el contacto con vida extraterrestre.', 'Ciencia Ficción', 3, '/uploads/1731541680_81Ot1LN8WlL._AC_UF894,1000_QL80_.jpg', '2008-05-28', '2024-11-13 23:48:00'),
(93, 'La carretera', 'Cormac McCarthy', 'Un padre y su hijo intentan sobrevivir en un mundo postapocalíptico.', 'Ciencia Ficción', 3, '/uploads/1731541733_815SHtVWWmL._AC_UF894,1000_QL80_.jpg', '2006-09-22', '2024-11-13 23:48:53'),
(94, 'Neuromante', 'William Gibson', 'Un thriller de ciberliteratura que explora un mundo de realidades virtuales.', 'Ciencia Ficción', 3, '/uploads/1731541781_images (5).jpg', '1999-04-04', '2024-11-13 23:49:41'),
(95, 'El marciano', 'Andy Weir', 'La historia de un astronauta varado en Marte y sus intentos de supervivencia.', 'Ciencia Ficción', 3, '/uploads/1731541837_25792193.jpg', '2011-06-05', '2024-11-13 23:50:37'),
(96, 'Snow Crash', 'Neal Stephenson', 'Una historia futurista donde un virus virtual amenaza tanto en el mundo digital como en el físico.', 'Ciencia Ficción', 3, '/uploads/1731541880_9780593599730.jpg', '1992-07-01', '2024-11-13 23:51:20'),
(97, 'La guerra interminable', 'Joe Haldeman', 'Un soldado lucha en una guerra intergaláctica que dura siglos.', 'Ciencia Ficción', 3, '/uploads/1731541953_15436--9788435021234.jpg', '1997-09-05', '2024-11-13 23:52:33'),
(98, 'La ciudad y las estrellas', 'Arthur C. Clarke', 'Una visión futurista de la humanidad en la última ciudad de la Tierra.', 'Ciencia Ficción', 3, '/uploads/1731542021_LaCiudadyLasEstrellas.jpg', '1994-01-31', '2024-11-13 23:53:41'),
(99, 'Cuna de gato', 'Kurt Vonnegut', 'Una sátira de ciencia ficción que examina el uso irresponsable de la tecnología.', 'Ciencia Ficción', 3, '/uploads/1731542085_images (6).jpg', '1991-06-12', '2024-11-13 23:54:45'),
(100, 'Altered Carbon', 'Richard K. Morgan', 'n un mundo donde la mente puede ser transferida entre cuerpos, un detective investiga un asesinato.', 'Ciencia Ficción', 3, '/uploads/1731542141_B13gH8nPGsS._AC_UF894,1000_QL80_.jpg', '2002-12-01', '2024-11-13 23:55:41'),
(101, 'Yo antes de ti', 'Jojo Moyes', 'Una historia de amor inesperada entre una joven y su empleador tetrapléjico.', 'Romance', 3, '/uploads/1731542300_images.png', '2012-05-30', '2024-11-13 23:58:20'),
(102, 'El cuaderno de Noah', 'Nicholas Sparks', 'La historia de amor entre dos personas separadas por el tiempo, pero unidas por su recuerdo.', 'Romance', 3, '/uploads/1731542359_81SJprBYtdL._AC_UF894,1000_QL80_.jpg', '1996-01-11', '2024-11-13 23:59:19'),
(103, 'Pídeme lo que quieras', 'Megan Maxwell', 'Una novela erótica sobre la relación entre una mujer española y su jefe alemán.', 'Romance', 3, '/uploads/1731542423_images (8).jpg', '2012-06-21', '2024-11-14 00:00:23'),
(104, 'Orgullo y prejuicio y zombies', 'Seth Grahame-Smith', 'Una versión alternativa del clásico de Jane Austen, donde Elizabeth Bennet enfrenta una invasión zombi.', 'Romance', 3, '/uploads/1731542490_images (9).jpg', '2009-09-19', '2024-11-14 00:01:30'),
(105, 'El amor en los tiempos del cólera', 'Gabriel García Márquez', 'na apasionante historia de amor y perseverancia.', 'Romance', 3, '/uploads/1731542547_El-amor-en-los-tiempos-del-cólera.jpg', '2003-08-18', '2024-11-14 00:02:27'),
(106, 'Un paseo para recordar', 'Nicholas Sparks', 'La historia romántica de un chico rebelde y una joven piadosa, donde el amor cambia sus vidas para siempre.', 'Romance', 3, '/uploads/1731542620_Un-paseo-para-recordar.jpg', '1999-06-17', '2024-11-14 00:03:40'),
(107, 'Perdona si te llamo amor', 'Federico Moccia', 'Una novela que relata la historia de amor entre una joven de 17 años y un hombre de 37, enfrentando prejuicios sociales.', 'Romance', 3, '/uploads/1731542678_61SOXJebxbL.jpg', '2008-11-11', '2024-11-14 00:04:38'),
(108, 'A tres metros sobre el cielo', 'Federico Moccia', 'n romance juvenil entre una chica de buena familia y un chico rebelde, que explora el amor y la rebeldía.', 'Romance', 3, '/uploads/1731542722_images (10).jpg', '1992-12-06', '2024-11-14 00:05:22'),
(109, 'Tal vez en otra vida', 'Taylor Jenkins Reid', 'La historia de una mujer cuya vida se bifurca en dos caminos, explorando el amor y las segundas oportunidades.', 'Romance', 3, '/uploads/1731542783_81JKzRWZHHL._AC_UF1000,1000_QL80_.jpg', '2015-12-01', '2024-11-14 00:06:23'),
(110, 'Los puentes de Madison County', 'Robert James Waller', 'Un romance efímero pero intenso entre una mujer casada y un fotógrafo que captura la belleza y la fugacidad de la vida.', 'Romance', 3, '/uploads/1731542827_71lqDudbkyL._AC_UF894,1000_QL80_.jpg', '1992-11-29', '2024-11-14 00:07:07'),
(111, 'Perdida', 'Gillian Flynn', 'Thriller psicológico donde una esposa desaparece misteriosamente y su esposo es el principal sospechoso.', 'Misterio y Suspenso', 3, '/uploads/1731542869_718KspqriCL._AC_UF894,1000_QL80_.jpg', '2012-07-12', '2024-11-14 00:07:49'),
(112, 'El código Da Vinci', 'Dan Brown', 'Un misterio que lleva a un profesor y una criptógrafa a descubrir secretos ocultos de la historia de la iglesia.', 'Misterio y Suspenso', 3, '/uploads/1731542933_descarga (5).jpg', '2003-01-01', '2024-11-14 00:08:53'),
(113, 'La chica del tren', 'Paula Hawkins', 'Una mujer que observa una pareja perfecta desde el tren se ve envuelta en un misterio cuando la mujer desaparece.', 'Misterio y Suspenso', 3, '/uploads/1731542986_la-chica-del-tren_9788408141471_contra.jpg', '2015-08-29', '2024-11-14 00:09:46'),
(114, 'La verdad sobre el caso Harry Quebert', 'Joël Dicker', 'Un escritor se enfrenta al pasado de su mentor al investigar un asesinato ocurrido 33 años atrás.', 'Misterio y Suspenso', 3, '/uploads/1731543045_images (11).jpg', '2012-10-10', '2024-11-14 00:10:45'),
(115, 'La trilogía de Nueva York', 'Paul Auster', 'Tres novelas cortas que exploran el misterio de la identidad y el concepto de investigación en una ciudad intrigante.', 'Misterio y Suspenso', 3, '/uploads/1731543085_81TWJDD-srL._UF1000,1000_QL80_.jpg', '1994-12-17', '2024-11-14 00:11:25'),
(116, 'La mujer en la ventana', 'A.J. Finn', 'Una mujer agorafóbica cree haber sido testigo de un crimen en la casa vecina, pero nadie le cree.', 'Misterio y Suspenso', 3, '/uploads/1731543151_9142nCJ8GTL._AC_UF894,1000_QL80_.jpg', '2018-11-21', '2024-11-14 00:12:31'),
(117, 'El psicoanalista', 'John Katzenbach', 'Un psicólogo recibe una amenaza de muerte y debe encontrar al remitente para salvar su vida.', 'Misterio y Suspenso', 3, '/uploads/1731543201_descarga (6).jpg', '2002-04-29', '2024-11-14 00:13:21'),
(118, 'La sonrisa de las mujeres', 'Nicolas Barreau', 'Un toque de romance y misterio en París, con secretos ocultos en las páginas de un libro.', 'Misterio y Suspenso', 3, '/uploads/1731543257_81akex+q72L._AC_UF894,1000_QL80_.jpg', '2010-10-10', '2024-11-14 00:14:17'),
(119, 'La desaparición de Stephanie Mailer', 'Joël Dicker', 'Un policía retirado investiga un caso no resuelto que resurge 20 años después de que una periodista desaparece.', 'Misterio y Suspenso', 3, '/uploads/1731543297_91PNAnmHREL.jpg', '2018-12-17', '2024-11-14 00:14:57'),
(120, 'Los hombres que no amaban a las mujeres', 'Stieg Larsson', 'Un periodista y una hacker investigan la desaparición de una mujer hace 40 años.', 'Misterio y Suspenso', 3, '/uploads/1731543338_71bhvWGuQ8L._UF1000,1000_QL80_.jpg', '2005-03-12', '2024-11-14 00:15:38'),
(121, 'El silencio de los inocentes', 'Thomas Harris', 'Thriller psicológico sobre un asesino en serie y la relación con una joven agente del FBI.', 'Terror', 3, '/uploads/1731543394_descarga (7).jpg', '1991-05-28', '2024-11-14 00:16:34'),
(122, 'Coraline', 'Neil Gaiman', 'Una niña descubre un mundo alterno en su hogar donde nada es lo que parece y enfrenta oscuros peligros.', 'Terror', 3, '/uploads/1731543452_71FvtJvTkRL._AC_UF1000,1000_QL80_.jpg', '2002-02-01', '2024-11-14 00:17:32'),
(123, 'Déjame entrar', 'John Ajvide Lindqvist', 'La historia de amistad entre un niño acosado y una niña vampira en los suburbios de Suecia.', 'Terror', 3, '/uploads/1731543507_2902_1_dejameentrar.jpg', '2004-04-12', '2024-11-14 00:18:27'),
(124, 'El resplandor', 'Stephen King', 'Un hombre y su familia enfrentan fuerzas malignas en un hotel aislado en las montañas.', 'Terror', 3, '/uploads/1731543576_El Resplandor - Stephen King.jpg', '1997-10-26', '2024-11-14 00:19:36'),
(125, 'El instituto', 'Stephen King', 'Niños con habilidades especiales son secuestrados y mantenidos en una instalación misteriosa.', 'Terror', 3, '/uploads/1731543685_0110873_el-instituto_550.jpeg', '2019-11-20', '2024-11-14 00:21:25'),
(126, 'El pacto', 'Michelle Richmond', 'Un thriller psicológico donde un matrimonio se une a una organización secreta para mantener su amor eterno.', 'Terror', 3, '/uploads/1731543737_71jBlUTZB8L._UF1000,1000_QL80_.jpg', '2017-08-19', '2024-11-14 00:22:17'),
(127, 'Los ojos de la oscuridad', 'Dean Koontz', 'Un hombre que ha perdido la memoria intenta descubrir su identidad, pero es perseguido por algo malévolo.', 'Terror', 3, '/uploads/1731543921_41aohQeF3YS._AC_UF894,1000_QL80_.jpg', '2003-12-03', '2024-11-14 00:25:21'),
(128, 'La Casa Infernal', 'Richard Matheson', 'Un grupo de personas pasa la noche en una mansión embrujada llena de secretos oscuros.', 'Terror', 3, '/uploads/1731543963_81JS1fX6QyL._AC_UF894,1000_QL80_.jpg', '2001-10-21', '2024-11-14 00:26:03'),
(129, 'El ritual', 'Adam Nevill', 'Un grupo de amigos se adentra en un bosque nórdico donde algo siniestro los acecha.', 'Terror', 3, '/uploads/1731544011_images (12).jpg', '2011-04-18', '2024-11-14 00:26:51'),
(130, 'Los elementales', 'Michael McDowell', 'Una casa abandonada en Alabama guarda oscuros secretos que afectan a las familias que intentan descubrirlos.', 'Terror', 3, '/uploads/1731544060_61kABTpa-AL._AC_UF894,1000_QL80_.jpg', '2014-06-14', '2024-11-14 00:27:40'),
(131, 'Watchmen', 'Alan Moore y Dave Gibbons', 'Ambientada en un mundo alternativo donde los superhéroes afectan el curso de la historia, Watchmen explora temas de poder, moralidad y la naturaleza humana.', 'Cómics y Novelas Gráficas', 3, '/uploads/1731544134_images (13).jpg', '1995-04-19', '2024-11-14 00:28:54'),
(132, 'Maus', 'Art Spiegelman', 'Novela gráfica biográfica que representa la experiencia del Holocausto utilizando animales como personajes, con los judíos como ratones y los nazis como gatos.', 'Cómics y Novelas Gráficas', 3, '/uploads/1731544186_71nXxfnNEcL._AC_UF894,1000_QL80_.jpg', '1991-07-15', '2024-11-14 00:29:46'),
(133, 'Sandman', 'Neil Gaiman', 'Serie oscura y mística que sigue a Morfeo, el Señor de los Sueños, en su lucha por redescubrir su propósito y lidiar con las consecuencias de sus acciones.', 'Cómics y Novelas Gráficas', 3, '/uploads/1731544251_cubierta_sandman_obertura_0_WEB.jpg', '1996-08-10', '2024-11-14 00:30:51'),
(134, 'Persepolis', 'Marjane Satrapi', 'utobiografía en formato de novela gráfica que narra la infancia y juventud de la autora en Irán durante y después de la Revolución Islámica.', 'Cómics y Novelas Gráficas', 3, '/uploads/1731544306_0114978_persepolis_550.jpeg', '2000-01-10', '2024-11-14 00:31:46'),
(135, 'V de Vendetta', 'Alan Moore y David Lloyd', 'Situada en un futuro distópico, la historia sigue a \"V\", un misterioso anarquista que se rebela contra un régimen opresor en el Reino Unido.', 'Cómics y Novelas Gráficas', 3, '/uploads/1731544363_images (14).jpg', '1990-10-30', '2024-11-14 00:32:43'),
(136, 'Blankets', 'Craig Thompson', 'Una novela gráfica que relata la infancia y adolescencia del autor en un ambiente cristiano, y su experiencia con el primer amor y la pérdida de la fe.', 'Cómics y Novelas Gráficas', 3, '/uploads/1731544404_A1wT6PxlLTL._AC_UF1000,1000_QL80_.jpg', '2003-11-03', '2024-11-14 00:33:24'),
(137, 'Scott Pilgrim', 'Bryan Lee O\'Malley', 'La serie sigue las aventuras de Scott Pilgrim, un joven que debe luchar contra los \"ex novios malvados\" de su interés amoroso. Es una mezcla de humor, romance y acción inspirada en videojuegos.', 'Cómics y Novelas Gráficas', 3, '/uploads/1731544491_ScottPilgrimColored.jpg', '2010-12-09', '2024-11-14 00:34:51'),
(138, 'The Walking Dead', 'Robert Kirkman y Tony Moore', 'Un grupo de supervivientes intenta mantenerse con vida en un mundo invadido por zombis, mientras exploran los horrores de la supervivencia y la moralidad.', 'Cómics y Novelas Gráficas', 3, '/uploads/1731544562_WalkingDead1.jpg', '2019-01-30', '2024-11-14 00:36:02'),
(139, 'un Home: Una familia tragicómica', 'Alison Bechdel', 'Esta autobiografía gráfica narra la infancia de la autora en una familia compleja y su descubrimiento de la sexualidad y la identidad.', 'Cómics y Novelas Gráficas', 3, '/uploads/1731544627_bbdc89a0d9b74d488bcc485f4fd38a46.jpg', '2006-08-11', '2024-11-14 00:37:07'),
(140, 'Y: The Last Man', 'Brian K. Vaughan y Pia Guerra', 'La historia sigue a Yorick, el único hombre superviviente en un mundo en el que todos los demás hombres han muerto, y su búsqueda para entender el fenómeno.', 'Cómics y Novelas Gráficas', 3, '/uploads/1731544686_71lBTGxPOOL._AC_UF1000,1000_QL80_.jpg', '2002-01-01', '2024-11-14 00:38:06');

-- --------------------------------------------------------

--
-- Table structure for table `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `comentario` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `username` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `comentarios_likes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comentarios`
--

INSERT INTO `comentarios` (`id`, `user_id`, `profile_picture`, `comentario`, `fecha`, `username`, `likes`, `comentarios_likes`) VALUES
(125, 1, '1731629659_descarga (2).jpg', 'HOLAA!', '2024-11-15 15:29:20', 'miwelito', 0, NULL),
(126, 1, '1731629659_descarga (2).jpg', 'HOOLLAA', '2024-11-15 15:31:29', 'miwelito', 0, NULL),
(127, 1, '1731629659_descarga (2).jpg', 'XDDDD', '2024-11-15 15:31:31', 'miwelito', 0, NULL),
(128, 1, '1731629659_descarga (2).jpg', 'QUE DURO', '2024-11-15 15:31:35', 'miwelito', 0, NULL),
(129, 1, '1731629659_descarga (2).jpg', 'BANCACGAT\r\n', '2024-11-15 15:31:42', 'miwelito', 0, NULL),
(130, 1, '1731629659_descarga (2).jpg', 'XDDDDD', '2024-11-15 15:31:55', 'miwelito', 0, NULL),
(131, 1, '1731629659_descarga (2).jpg', 'MMMGGGGG', '2024-11-15 21:28:51', 'miwelito', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comentarios_likes`
--

CREATE TABLE `comentarios_likes` (
  `id` int(11) NOT NULL,
  `comentario_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `expiration_date` date NOT NULL,
  `publication_date` date DEFAULT NULL,
  `loan_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `respuestas`
--

CREATE TABLE `respuestas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `comentario_id` int(11) DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `respuesta` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `respuestas_likes`
--

CREATE TABLE `respuestas_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `respuesta_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `profile_picture` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `verification_code` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `profile_picture`, `activation_code`, `is_active`, `verification_code`) VALUES
(1, 'miwelito', 'juniorvazquezx@gmail.com', '$2y$10$GWrdYUXJAgNrO9ptgphbb.bZMbkU8HZHayx5sXG3TT.M3Bn3rohDm', 'user', '1731629659_descarga (2).jpg', '', 1, NULL),
(50, 'admin', 'admin@gmail.com', '$2y$10$WK6zJyJlHSfDHiZ46LY17eW2Z2PocEB/KKkr/sQE40qJsm.Du9zvC', 'admin', NULL, '', 1, NULL),
(51, 'mwcrt', 'whoismiwelbruh@gmail.com', '$2y$10$D8ofmtepwO6o25XXNEGnHeFG2j0spupz9yzu5HnoHBE0GD8m2QtLK', 'user', NULL, '', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comentarios_likes`
--
ALTER TABLE `comentarios_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comentario_id` (`comentario_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_book_id` (`book_id`);

--
-- Indexes for table `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `comentario_id` (`comentario_id`);

--
-- Indexes for table `respuestas_likes`
--
ALTER TABLE `respuestas_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `respuesta_id` (`respuesta_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `comentarios_likes`
--
ALTER TABLE `comentarios_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `respuestas_likes`
--
ALTER TABLE `respuestas_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comentarios_likes`
--
ALTER TABLE `comentarios_likes`
  ADD CONSTRAINT `comentarios_likes_ibfk_1` FOREIGN KEY (`comentario_id`) REFERENCES `comentarios` (`id`),
  ADD CONSTRAINT `comentarios_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `respuestas`
--
ALTER TABLE `respuestas`
  ADD CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `respuestas_ibfk_2` FOREIGN KEY (`comentario_id`) REFERENCES `comentarios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `respuestas_likes`
--
ALTER TABLE `respuestas_likes`
  ADD CONSTRAINT `respuestas_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `respuestas_likes_ibfk_2` FOREIGN KEY (`respuesta_id`) REFERENCES `respuestas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
