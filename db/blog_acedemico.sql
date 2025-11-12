-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-11-2025 a las 06:37:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `blog acedemico`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articles`
--

CREATE TABLE `articles` (
  `id` int(20) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(225) NOT NULL,
  `category` varchar(50) NOT NULL,
  `article` text NOT NULL,
  `user` varchar(50) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articles`
--

INSERT INTO `articles` (`id`, `title`, `image`, `category`, `article`, `user`, `date`) VALUES
(22, 'Entendiendo la Tabla Periódica', 'aad.png', 'Química', '<p><span style=\"font-size: 18pt;\">La Tabla Peri&oacute;dica de los Elementos es una herramienta fundamental en qu&iacute;mica. Organiza todos los elementos qu&iacute;micos conocidos en una disposici&oacute;n tabular basada en sus propiedades at&oacute;micas. Comprender la tabla peri&oacute;dica es esencial para cualquier persona que estudie qu&iacute;mica, ya que proporciona informaci&oacute;n valiosa sobre el comportamiento y las caracter&iacute;sticas de los elementos.</span></p>\r\n<h2 dir=\"ltr\" style=\"text-align: justify;\"><strong><span style=\"font-size: 18pt;\">Organizaci&oacute;n de la Tabla Peri&oacute;dica</span></strong></h2>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La tabla peri&oacute;dica est&aacute; organizada en filas horizontales, llamadas per&iacute;odos, y columnas verticales, llamadas grupos. Los elementos est&aacute;n ordenados en orden creciente de su n&uacute;mero at&oacute;mico, que es el n&uacute;mero de protones en el n&uacute;cleo de un &aacute;tomo de ese elemento.</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Per&iacute;odos: Los per&iacute;odos indican el n&uacute;mero de niveles de energ&iacute;a que tiene un &aacute;tomo. Por ejemplo, los elementos del per&iacute;odo 1 tienen un nivel de energ&iacute;a, mientras que los elementos del per&iacute;odo 2 tienen dos niveles de energ&iacute;a.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Grupos: Los grupos contienen elementos que tienen el mismo n&uacute;mero de electrones de valencia, que son los electrones en el nivel de energ&iacute;a m&aacute;s externo de un &aacute;tomo. Los elementos del mismo grupo tienden a tener propiedades qu&iacute;micas similares.</span></p>\r\n</li>\r\n</ul>\r\n<h3 dir=\"ltr\" style=\"text-align: justify;\"><strong><span style=\"font-size: 18pt;\">Bloques de la Tabla Peri&oacute;dica</span></strong></h3>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La tabla peri&oacute;dica tambi&eacute;n se puede dividir en bloques seg&uacute;n la configuraci&oacute;n electr&oacute;nica de los elementos:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Bloque s: Incluye los grupos 1 y 2 (metales alcalinos y metales alcalinot&eacute;rreos).</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Bloque p: Incluye los grupos 13 al 18 (boro, carbono, nitr&oacute;geno, ox&iacute;geno, hal&oacute;genos y gases nobles).</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Bloque d: Incluye los grupos 3 al 12 (metales de transici&oacute;n).</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Bloque f: Incluye los lant&aacute;nidos y act&iacute;nidos, que se encuentran generalmente debajo del cuerpo principal de la tabla peri&oacute;dica.</span></p>\r\n</li>\r\n</ul>\r\n<h2 dir=\"ltr\" style=\"text-align: justify;\"><strong><span style=\"font-size: 18pt;\">Propiedades de los Elementos</span></strong></h2>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La tabla peri&oacute;dica no solo organiza los elementos, sino que tambi&eacute;n permite predecir sus propiedades. Algunas de las propiedades clave que se pueden determinar a partir de la tabla peri&oacute;dica incluyen:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Electronegatividad: La capacidad de un &aacute;tomo para atraer electrones hacia s&iacute; mismo en un enlace qu&iacute;mico. La electronegatividad aumenta de izquierda a derecha en un per&iacute;odo y disminuye de arriba a abajo en un grupo.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Afinidad electr&oacute;nica: El cambio de energ&iacute;a que ocurre cuando se agrega un electr&oacute;n a un &aacute;tomo neutro en estado gaseoso.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Radio at&oacute;mico: El tama&ntilde;o de un &aacute;tomo. El radio at&oacute;mico disminuye de izquierda a derecha en un per&iacute;odo y aumenta de arriba a abajo en un grupo.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Energ&iacute;a de ionizaci&oacute;n: La energ&iacute;a requerida para remover un electr&oacute;n de un &aacute;tomo en estado gaseoso.</span></p>\r\n</li>\r\n</ul>\r\n<h3 dir=\"ltr\" style=\"text-align: justify;\"><strong><span style=\"font-size: 18pt;\">Metales, No Metales y Metaloides</span></strong></h3>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Los elementos de la tabla peri&oacute;dica se pueden clasificar en tres categor&iacute;as principales:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Metales: Generalmente son brillantes, buenos conductores de calor y electricidad, y son maleables y d&uacute;ctiles. Se encuentran principalmente en los bloques s y d de la tabla peri&oacute;dica.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">No metales: Generalmente son opacos, malos conductores de calor y electricidad, y son fr&aacute;giles. Se encuentran principalmente en el bloque p de la tabla peri&oacute;dica.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Metaloides: Tienen propiedades intermedias entre metales y no metales. A menudo se les llama semiconductores.</span></p>\r\n</li>\r\n</ul>\r\n<h2 dir=\"ltr\" style=\"text-align: justify;\"><strong><span style=\"font-size: 18pt;\">V&iacute;deo Explicativo</span></strong></h2>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Para complementar esta informaci&oacute;n, te recomiendo ver este video que explica de manera concisa la organizaci&oacute;n de la tabla peri&oacute;dica:</span></p>\r\n<p dir=\"ltr\"><iframe src=\"https://www.youtube.com/embed/Ii-f6IwqKR8?ab_channel=TheWildProject\" width=\"500\" height=\"280\" allowfullscreen=\"allowfullscreen\"></iframe></p>\r\n<h2 dir=\"ltr\" style=\"text-align: justify;\"><strong><span style=\"font-size: 18pt;\">Conclusi&oacute;n</span></strong></h2>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La tabla peri&oacute;dica es una herramienta esencial para comprender la qu&iacute;mica de los elementos. Su organizaci&oacute;n revela patrones en las propiedades de los elementos, lo que nos permite predecir su comportamiento en diversas reacciones qu&iacute;micas. El estudio de la tabla peri&oacute;dica es fundamental para cualquier persona que desee comprender el mundo que nos rodea a nivel at&oacute;mico y molecular.</span></p>', '4', '2025-04-13'),
(41, 'Aprendiendo a Dividir: Guía Paso a Paso', 'Números-primos.png', 'Matemáticas', '<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La divisi&oacute;n es una de las operaciones matem&aacute;ticas fundamentales, &iexcl;y dominarla es esencial para muchas situaciones de la vida diaria! En este art&iacute;culo, te guiaremos a trav&eacute;s de los conceptos b&aacute;sicos y te proporcionaremos ejemplos pr&aacute;cticos para que te conviertas en un experto en la divisi&oacute;n.</span></p>\r\n<h3 dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">&iquest;Qu&eacute; es la Divisi&oacute;n?</span></h3>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">En esencia, la divisi&oacute;n consiste en repartir una cantidad en partes iguales. Por ejemplo, si tienes 10 galletas y quieres repartirlas entre 2 personas, divides 10 entre 2 para saber cu&aacute;ntas galletas le tocan a cada uno.</span></p>\r\n<h3 dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">T&eacute;rminos Clave</span></h3>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Antes de comenzar, es importante que conozcas algunos t&eacute;rminos clave:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Dividendo: El n&uacute;mero que se va a dividir (por ejemplo, el 10 en el ejemplo de las galletas).</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Divisor: El n&uacute;mero entre el que se divide el dividendo (por ejemplo, el 2 en el ejemplo de las galletas).</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Cociente: El resultado de la divisi&oacute;n (por ejemplo, el 5 en el ejemplo de las galletas).</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Resto: La cantidad que sobra cuando la divisi&oacute;n no es exacta.</span></p>\r\n</li>\r\n</ul>\r\n<h3 dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">El Proceso de la Divisi&oacute;n</span></h3>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La forma m&aacute;s com&uacute;n de realizar una divisi&oacute;n es a trav&eacute;s de un proceso llamado \"divisi&oacute;n larga\". Aqu&iacute; te presentamos los pasos b&aacute;sicos:</span></p>\r\n<ol style=\"text-align: justify;\">\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Escribe el problema: Coloca el dividendo dentro de la \"casita\" de divisi&oacute;n y el divisor afuera.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Divide el primer d&iacute;gito: Divide el primer d&iacute;gito del dividendo entre el divisor. Si el divisor es mayor, toma el siguiente d&iacute;gito del dividendo.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Escribe el cociente: Escribe el resultado de la divisi&oacute;n encima del d&iacute;gito del dividendo que utilizaste.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Multiplica: Multiplica el cociente por el divisor y escribe el resultado debajo del dividendo.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Resta: Resta el resultado de la multiplicaci&oacute;n al d&iacute;gito del dividendo.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Baja el siguiente d&iacute;gito: Baja el siguiente d&iacute;gito del dividendo y col&oacute;calo junto al resultado de la resta.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Repite: Repite los pasos 2 al 6 hasta que hayas utilizado todos los d&iacute;gitos del dividendo.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Encuentra el resto: Si al final de la divisi&oacute;n te queda un n&uacute;mero menor que el divisor, ese es el resto.</span></p>\r\n</li>\r\n</ol>\r\n<h3 dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Ejemplos Pr&aacute;cticos</span></h3>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Veamos algunos ejemplos para que te familiarices con el proceso:</span></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Ejemplo 1: Divisi&oacute;n sin resto</span></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Divide 12 entre 3:</span></p>\r\n<ol style=\"text-align: justify;\">\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Escribe el problema: 3⟌12</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Divide: 3 entre 12 es 4.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Escribe el cociente: Escribe 4 encima del 2 en el 12.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Multiplica: 4 x 3 = 12.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Resta: 12 - 12 = 0.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Resultado: El cociente es 4 y el resto es 0.</span></p>\r\n</li>\r\n</ol>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Ejemplo 2: Divisi&oacute;n con resto</span></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Divide 14 entre 4:</span></p>\r\n<ol style=\"text-align: justify;\">\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Escribe el problema: 4⟌14</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Divide: 4 entre 14 es 3.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Escribe el cociente: Escribe 3 encima del 4 en el 14.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Multiplica: 3 x 4 = 12.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Resta: 14 - 12 = 2.</span></p>\r\n</li>\r\n<li dir=\"ltr\" style=\"font-size: 18pt;\" aria-level=\"1\">\r\n<p dir=\"ltr\" role=\"presentation\"><span style=\"font-size: 18pt;\">Resultado: El cociente es 3 y el resto es 2.</span></p>\r\n</li>\r\n</ol>\r\n<h3 dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">&iexcl;Practica, Practica, Practica!</span></h3>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La clave para dominar la divisi&oacute;n es la pr&aacute;ctica. Resuelve muchos ejercicios diferentes, comenzando con divisiones sencillas y avanzando hacia divisiones m&aacute;s complejas. &iexcl;No te desanimes si al principio te equivocas! Con el tiempo y la pr&aacute;ctica, te convertir&aacute;s en un experto en la divisi&oacute;n.</span></p>', '4', '2025-04-14'),
(42, 'La Importancia de la Literatura', 'literatura.png', 'Literatura', '<div class=\"ProseMirror\" contenteditable=\"true\" translate=\"no\">\r\n<p style=\"text-align: left;\"><span style=\"font-size: 18pt;\">La literatura, en su esencia, es mucho m&aacute;s que un conjunto de libros; es un viaje a trav&eacute;s del tiempo, las culturas y la condici&oacute;n humana. Nos conecta con el pasado, da forma a nuestro presente y nos prepara para los desaf&iacute;os del futuro. En este art&iacute;culo, exploraremos por qu&eacute; la literatura es tan importante y c&oacute;mo enriquece nuestras vidas.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>Un Espejo de la Sociedad y la Cultura</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Las obras literarias son un reflejo de la sociedad en la que nacen. A trav&eacute;s de sus p&aacute;ginas, podemos asomarnos a las ideas, los valores, las creencias y las luchas de diferentes &eacute;pocas y culturas. Desde los poemas &eacute;picos de la antigua Grecia hasta las novelas contempor&aacute;neas, la literatura nos permite comprender c&oacute;mo las sociedades han evolucionado, c&oacute;mo han enfrentado sus problemas y c&oacute;mo han celebrado sus triunfos. Este conocimiento nos ayuda a valorar nuestro propio patrimonio cultural y a entender mejor nuestro lugar en el mundo.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>El Desarrollo del Pensamiento Cr&iacute;tico</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La literatura nos desaf&iacute;a a ir m&aacute;s all&aacute; de la superficie de las palabras. El an&aacute;lisis literario nos exige interpretar, evaluar y cuestionar diferentes perspectivas y argumentos. Aprendemos a identificar temas, s&iacute;mbolos y met&aacute;foras, desentra&ntilde;ando las capas de significado que se esconden en el texto. Este proceso fortalece nuestra capacidad de an&aacute;lisis, nos convierte en pensadores m&aacute;s cr&iacute;ticos y nos proporciona herramientas esenciales para enfrentar los desaf&iacute;os intelectuales y pr&aacute;cticos de la vida.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>La Ampliaci&oacute;n de Nuestros Horizontes</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Leer una variedad de textos literarios, desde poes&iacute;a hasta novelas, tiene un impacto profundo en nuestro lenguaje y nuestra comprensi&oacute;n. Nos expone a un vocabulario rico y diverso, mejora nuestra gram&aacute;tica y nos familiariza con diferentes estilos de escritura. Esta inmersi&oacute;n en el lenguaje no solo mejora nuestras habilidades de comunicaci&oacute;n, sino que tambi&eacute;n nos permite comprender mejor cualquier tipo de texto que encontremos, desde un art&iacute;culo cient&iacute;fico hasta un contrato legal.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>El Cultivo de la Empat&iacute;a y la Comprensi&oacute;n</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La literatura tiene el poder de transportarnos a diferentes &eacute;pocas y lugares, permiti&eacute;ndonos experimentar la vida desde la perspectiva de personajes diversos. Nos pone en la piel de personas de diferentes culturas, con diferentes experiencias y diferentes puntos de vista. Esta capacidad de ponernos en el lugar del otro desarrolla nuestra empat&iacute;a, nuestra tolerancia y nuestra comprensi&oacute;n de la complejidad de la condici&oacute;n humana.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>Un Est&iacute;mulo para la Imaginaci&oacute;n y la Creatividad</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La literatura nos invita a explorar mundos imaginarios, a conocer personajes fascinantes y a reflexionar sobre temas universales como el amor, la p&eacute;rdida, la alegr&iacute;a y el dolor. Nos libera de las limitaciones de la realidad y nos anima a pensar de forma original, a cuestionar lo establecido y a buscar nuevas posibilidades. La literatura es un poderoso est&iacute;mulo para nuestra imaginaci&oacute;n y nuestra creatividad, cualidades esenciales para la innovaci&oacute;n y el progreso.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>El Desarrollo de Habilidades de Comunicaci&oacute;n</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">El estudio de la literatura no es una actividad pasiva. Implica el debate, la discusi&oacute;n y la presentaci&oacute;n de ideas. Ya sea en un aula o en un club de lectura, la literatura nos brinda oportunidades para expresar nuestras opiniones, escuchar diferentes puntos de vista y construir argumentos s&oacute;lidos. Estas interacciones mejoran nuestras habilidades de comunicaci&oacute;n oral y escrita, nos ense&ntilde;an a articular nuestros pensamientos con claridad y persuasi&oacute;n, y nos permiten participar de manera m&aacute;s efectiva en el di&aacute;logo social y profesional.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>Una Exploraci&oacute;n de la Condici&oacute;n Humana</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">En el centro de la literatura se encuentra la exploraci&oacute;n de lo que significa ser humano. Las grandes obras literarias nos confrontan con las complejidades de la existencia: la b&uacute;squeda de la identidad, el significado de la vida, las relaciones humanas, el bien y el mal. Al leer sobre estos temas, reflexionamos sobre nuestras propias experiencias, emociones y valores, lo que nos permite alcanzar una comprensi&oacute;n m&aacute;s profunda de nosotros mismos y de nuestro lugar en el mundo.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">En conclusi&oacute;n, la literatura es mucho m&aacute;s que una simple forma de entretenimiento. Es una herramienta esencial para el desarrollo personal, la comprensi&oacute;n social y el enriquecimiento cultural. Nos conecta con el pasado, nos ilumina el presente y nos prepara para construir un futuro m&aacute;s humano y significativo.</span></p>\r\n</div>', '4', '2025-04-14'),
(46, 'La Importancia de las Matemáticas en la Educación', 'importancia.png', 'Matemáticas', '<div class=\"ProseMirror\" contenteditable=\"true\" translate=\"no\">\r\n<p style=\"text-align: left;\"><span style=\"font-size: 18pt;\">Las matem&aacute;ticas son una disciplina fundamental que va m&aacute;s all&aacute; de los n&uacute;meros y las ecuaciones; es una herramienta esencial para la vida y el desarrollo del pensamiento l&oacute;gico. Su importancia en la educaci&oacute;n es innegable, ya que proporciona a los estudiantes habilidades cruciales para desenvolverse en el mundo actual y futuro.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>&iquest;Por qu&eacute; son importantes las matem&aacute;ticas en la educaci&oacute;n?</strong></span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Desarrollan el pensamiento l&oacute;gico y anal&iacute;tico:</strong> Las matem&aacute;ticas nos entrenan para abordar problemas de manera sistem&aacute;tica, identificar patrones, analizar datos y llegar a conclusiones l&oacute;gicas. Estas habilidades son transferibles a todas las &aacute;reas de la vida, desde la toma de decisiones personales hasta el an&aacute;lisis de situaciones complejas en el &aacute;mbito profesional.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Fomentan la resoluci&oacute;n de problemas:</strong> Las matem&aacute;ticas nos desaf&iacute;an a encontrar soluciones a diversos problemas, desde los m&aacute;s sencillos hasta los m&aacute;s complejos. Este proceso desarrolla nuestra creatividad, perseverancia y capacidad para encontrar diferentes enfoques para resolver una situaci&oacute;n.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Son la base de otras disciplinas:</strong> Las matem&aacute;ticas son el lenguaje de la ciencia, la tecnolog&iacute;a, la ingenier&iacute;a y muchas otras disciplinas. Un s&oacute;lido conocimiento matem&aacute;tico es esencial para comprender y avanzar en estos campos, que son fundamentales para el desarrollo de la sociedad.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Mejoran la capacidad de abstracci&oacute;n y razonamiento:</strong> Las matem&aacute;ticas nos permiten trabajar con conceptos abstractos y desarrollar nuestra capacidad de razonamiento. Esto nos ayuda a comprender el mundo que nos rodea de una manera m&aacute;s profunda y a desarrollar nuestra capacidad de pensamiento cr&iacute;tico.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Son esenciales para la vida cotidiana:</strong> Las matem&aacute;ticas est&aacute;n presentes en muchas de las actividades que realizamos a diario, desde calcular el presupuesto familiar y medir ingredientes para una receta hasta comprender las estad&iacute;sticas de un partido de f&uacute;tbol o analizar la informaci&oacute;n de un gr&aacute;fico.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Preparan para el futuro:</strong> En un mundo cada vez m&aacute;s tecnol&oacute;gico y basado en datos, las habilidades matem&aacute;ticas son cada vez m&aacute;s valoradas en el mercado laboral. Las empresas buscan profesionales con capacidad para analizar informaci&oacute;n, resolver problemas complejos y tomar decisiones basadas en datos, habilidades que se desarrollan a trav&eacute;s del estudio de las matem&aacute;ticas.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Promueven el desarrollo del pensamiento creativo:</strong> Aunque pueda parecer contradictorio, las matem&aacute;ticas tambi&eacute;n pueden ser una fuente de creatividad. La b&uacute;squeda de soluciones a problemas matem&aacute;ticos complejos a menudo requiere pensar fuera de lo convencional y encontrar enfoques innovadores.</span></p>\r\n</li>\r\n</ul>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">En resumen, las matem&aacute;ticas son una herramienta fundamental para el desarrollo intelectual, personal y profesional de los estudiantes. Su estudio proporciona habilidades esenciales para la vida, abre las puertas a numerosas oportunidades y nos permite comprender y apreciar el mundo que nos rodea en toda su complejidad.</span></p>\r\n</div>', '4', '2025-04-14'),
(47, 'Biología: La Ciencia de la Vida', 'biologiaa.png', 'Biología', '<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La biolog&iacute;a es la ciencia que explora el fascinante mundo de los seres vivos. Desde las bacterias microsc&oacute;picas hasta las ballenas gigantes, la biolog&iacute;a abarca la diversidad de la vida en la Tierra y los procesos que la sustentan.</span></p>\r\n<div class=\"ProseMirror\" contenteditable=\"true\" translate=\"no\">\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>&iquest;Qu&eacute; estudia la biolog&iacute;a?</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La biolog&iacute;a es un campo amplio y diverso que se divide en varias ramas, cada una de las cuales se enfoca en un aspecto particular de la vida. Algunas de las principales ramas de la biolog&iacute;a incluyen:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Biolog&iacute;a celular:</strong> El estudio de la estructura y funci&oacute;n de las c&eacute;lulas, las unidades fundamentales de la vida.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Gen&eacute;tica:</strong> El estudio de la herencia y la variaci&oacute;n de los rasgos en los organismos.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Evoluci&oacute;n:</strong> El estudio de c&oacute;mo los organismos cambian a lo largo del tiempo y c&oacute;mo surgen nuevas especies.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Ecolog&iacute;a:</strong> El estudio de las interacciones entre los organismos y su entorno.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Microbiolog&iacute;a:</strong> El estudio de los microorganismos, como bacterias, virus y hongos.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Zoolog&iacute;a:</strong> El estudio de los animales, su comportamiento, fisiolog&iacute;a y clasificaci&oacute;n.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Bot&aacute;nica:</strong> El estudio de las plantas, su estructura, propiedades y procesos bioqu&iacute;micos.</span></p>\r\n</li>\r\n</ul>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La importancia de la biolog&iacute;a</span></p>\r\n<p style=\"text-align: justify;\"><iframe src=\"https://www.youtube.com/embed/PLOt9WBjjQw\" width=\"560\" height=\"314\" allowfullscreen=\"allowfullscreen\"></iframe></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La biolog&iacute;a es esencial para nuestra comprensi&oacute;n del mundo natural y tiene numerosas aplicaciones pr&aacute;cticas. La investigaci&oacute;n biol&oacute;gica ha llevado a descubrimientos revolucionarios en medicina, agricultura, biotecnolog&iacute;a y otras &aacute;reas. Algunos ejemplos de la importancia de la biolog&iacute;a incluyen:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Medicina:</strong> La biolog&iacute;a es la base de nuestra comprensi&oacute;n de las enfermedades, el desarrollo de vacunas y tratamientos, y la investigaci&oacute;n de nuevas terapias.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Agricultura:</strong> La biolog&iacute;a ha permitido el desarrollo de cultivos m&aacute;s resistentes y productivos, as&iacute; como la comprensi&oacute;n de c&oacute;mo protegerlos de plagas y enfermedades.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Biotecnolog&iacute;a:</strong> La biolog&iacute;a se utiliza para desarrollar nuevos productos y procesos, como medicamentos, biocombustibles y alimentos transg&eacute;nicos.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Conservaci&oacute;n:</strong> La biolog&iacute;a es fundamental para comprender la biodiversidad y c&oacute;mo proteger las especies en peligro de extinci&oacute;n y los ecosistemas amenazados.</span></p>\r\n</li>\r\n</ul>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>El futuro de la biolog&iacute;a</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La biolog&iacute;a es un campo en constante evoluci&oacute;n, con nuevos descubrimientos que se realizan todo el tiempo. Algunos de los temas m&aacute;s emocionantes que se est&aacute;n explorando en la biolog&iacute;a actual incluyen:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Gen&oacute;mica:</strong> El estudio del genoma completo de un organismo, que est&aacute; revolucionando nuestra comprensi&oacute;n de la herencia, la evoluci&oacute;n y la enfermedad.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Biolog&iacute;a sint&eacute;tica:</strong> El dise&ntilde;o y la construcci&oacute;n de nuevos sistemas biol&oacute;gicos, lo que tiene el potencial de crear nuevas formas de vida y resolver problemas importantes.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Neurociencia:</strong> El estudio del sistema nervioso, que est&aacute; revelando los misterios del cerebro, la conciencia y el comportamiento.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Biolog&iacute;a computacional:</strong> El uso de la inform&aacute;tica para analizar grandes conjuntos de datos biol&oacute;gicos, lo que est&aacute; acelerando el ritmo de los descubrimientos biol&oacute;gicos.</span></p>\r\n</li>\r\n</ul>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La biolog&iacute;a seguir&aacute; siendo una de las ciencias m&aacute;s importantes en el futuro, ya que nos ayuda a comprender los misterios de la vida y a desarrollar nuevas soluciones para los desaf&iacute;os que enfrenta la humanidad.</span></p>\r\n</div>', '4', '2025-04-14'),
(48, 'Biología Avanzada: Más Allá de lo Básico', 'Biologia-avanzada.png', 'Biología', '<div class=\"ProseMirror\" contenteditable=\"true\" translate=\"no\">\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La biolog&iacute;a, la ciencia de la vida, es un campo vasto y fascinante que se extiende mucho m&aacute;s all&aacute; de los conceptos b&aacute;sicos. Para apreciar verdaderamente su complejidad y relevancia, debemos adentrarnos en temas m&aacute;s avanzados que revelan la intrincada maquinaria de la vida y su impacto en el mundo que nos rodea.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>Explorando las Fronteras de la Biolog&iacute;a</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Aqu&iacute;, profundizamos en algunas &aacute;reas de estudio avanzadas que est&aacute;n dando forma a nuestra comprensi&oacute;n de la vida:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Biolog&iacute;a Molecular:</strong> En el coraz&oacute;n de la vida se encuentran las mol&eacute;culas. La biolog&iacute;a molecular explora la estructura, funci&oacute;n e interacciones de las macromol&eacute;culas esenciales como el ADN, el ARN y las prote&iacute;nas. Esta &aacute;rea ha revolucionado nuestra comprensi&oacute;n de la gen&eacute;tica, la herencia y las enfermedades, y ha dado lugar a tecnolog&iacute;as innovadoras como la ingenier&iacute;a gen&eacute;tica y la terapia g&eacute;nica.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Biolog&iacute;a del Desarrollo:</strong> Desde un &oacute;vulo fertilizado hasta un organismo complejo, la biolog&iacute;a del desarrollo desentra&ntilde;a los intrincados procesos que orquestan el crecimiento y la diferenciaci&oacute;n. El estudio de los genes maestros, las v&iacute;as de se&ntilde;alizaci&oacute;n y la morfog&eacute;nesis revela c&oacute;mo se forma la vida y ofrece informaci&oacute;n sobre los defectos de nacimiento y la regeneraci&oacute;n de tejidos.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Neurobiolog&iacute;a:</strong> El cerebro, el &oacute;rgano m&aacute;s complejo del cuerpo, es el foco de la neurobiolog&iacute;a. Esta disciplina explora la estructura, funci&oacute;n y desarrollo del sistema nervioso, desde las neuronas individuales hasta las redes neuronales que dan lugar al comportamiento, la cognici&oacute;n y la conciencia. Los avances en la neurobiolog&iacute;a est&aacute;n desvelando los misterios de las enfermedades neurol&oacute;gicas y abriendo nuevas v&iacute;as para el desarrollo de f&aacute;rmacos y terapias.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Inmunolog&iacute;a:</strong> El sistema inmunol&oacute;gico, la defensa del cuerpo contra los invasores extra&ntilde;os, es un &aacute;rea de estudio crucial en la biolog&iacute;a. La inmunolog&iacute;a explora los componentes y procesos del sistema inmunol&oacute;gico, incluida la respuesta inmune a los pat&oacute;genos, el desarrollo de vacunas y las enfermedades autoinmunes. La investigaci&oacute;n inmunol&oacute;gica est&aacute; impulsando los esfuerzos para combatir enfermedades infecciosas, el c&aacute;ncer y los trastornos inmunol&oacute;gicos.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Ecolog&iacute;a Avanzada:</strong> La ecolog&iacute;a, el estudio de las interacciones entre los organismos y su entorno, se vuelve a&uacute;n m&aacute;s complejo cuando consideramos las intrincadas redes de relaciones y los procesos din&aacute;micos que dan forma a los ecosistemas. La ecolog&iacute;a avanzada explora temas como la din&aacute;mica de las poblaciones, las interacciones comunitarias, el flujo de energ&iacute;a y nutrientes, y el impacto de las actividades humanas en la biodiversidad y el funcionamiento de los ecosistemas. Esta &aacute;rea es fundamental para abordar los desaf&iacute;os ambientales globales como el cambio clim&aacute;tico, la p&eacute;rdida de biodiversidad y la degradaci&oacute;n de los ecosistemas.</span></p>\r\n</li>\r\n</ul>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>El Impacto de la Biolog&iacute;a Avanzada</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">Los conocimientos adquiridos a trav&eacute;s de la biolog&iacute;a avanzada tienen profundas implicaciones para nuestra sociedad:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Medicina personalizada:</strong> La comprensi&oacute;n de la base molecular de las enfermedades est&aacute; allanando el camino para tratamientos personalizados adaptados a la composici&oacute;n gen&eacute;tica de cada individuo.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Nuevas fronteras agr&iacute;colas:</strong> La biolog&iacute;a avanzada est&aacute; impulsando el desarrollo de cultivos m&aacute;s resistentes, nutritivos y sostenibles para alimentar a una poblaci&oacute;n mundial en crecimiento.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Biotecnolog&iacute;a y bioingenier&iacute;a:</strong> La capacidad de manipular sistemas biol&oacute;gicos est&aacute; dando lugar a nuevas biotecnolog&iacute;as innovadoras, como la edici&oacute;n de genes, la biolog&iacute;a sint&eacute;tica y la ingenier&iacute;a de tejidos, con aplicaciones revolucionarias en medicina, industria y agricultura.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Conservaci&oacute;n de la biodiversidad:</strong> La investigaci&oacute;n ecol&oacute;gica avanzada informa los esfuerzos de conservaci&oacute;n para proteger especies en peligro de extinci&oacute;n, preservar ecosistemas cr&iacute;ticos y mantener el delicado equilibrio de la vida en la Tierra.</span></p>\r\n</li>\r\n</ul>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La biolog&iacute;a avanzada es un campo din&aacute;mico y de r&aacute;pido crecimiento que contin&uacute;a expandiendo nuestra comprensi&oacute;n de la vida y su intrincada complejidad. Desde desentra&ntilde;ar los misterios del genoma humano hasta explorar las profundidades de los ecosistemas, la biolog&iacute;a avanzada est&aacute; preparada para abordar algunos de los desaf&iacute;os m&aacute;s apremiantes de la humanidad y dar forma al futuro de nuestro planeta.</span></p>\r\n</div>', '4', '2025-04-14'),
(51, 'Física: La Ciencia Fundamental de la Naturaleza', 'fisica.png', 'Física', '<div class=\"ProseMirror\" contenteditable=\"true\" translate=\"no\">\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La f&iacute;sica es la ciencia que busca comprender los principios fundamentales que gobiernan el universo. Desde las part&iacute;culas subat&oacute;micas hasta la inmensidad del cosmos, la f&iacute;sica explora las leyes que rigen la materia, la energ&iacute;a, el espacio y el tiempo.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>&iquest;Qu&eacute; estudia la f&iacute;sica?</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La f&iacute;sica es un campo amplio y abarcador que se divide en varias ramas, cada una de las cuales se enfoca en un aspecto particular del universo. Algunas de las principales ramas de la f&iacute;sica incluyen:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Mec&aacute;nica Cl&aacute;sica:</strong> Estudia el movimiento de los objetos macrosc&oacute;picos bajo la influencia de fuerzas, como la gravedad y el electromagnetismo.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Termodin&aacute;mica:</strong> Examina las relaciones entre el calor y otras formas de energ&iacute;a, y c&oacute;mo la energ&iacute;a se transfiere y se transforma.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Electromagnetismo:</strong> Investiga las interacciones entre cargas el&eacute;ctricas y campos magn&eacute;ticos, y su unificaci&oacute;n en una sola fuerza electromagn&eacute;tica.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>&Oacute;ptica:</strong> Estudia el comportamiento y las propiedades de la luz, incluyendo su interacci&oacute;n con la materia y el dise&ntilde;o de instrumentos &oacute;pticos.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Mec&aacute;nica Cu&aacute;ntica:</strong> Explora el comportamiento de la materia y la energ&iacute;a a escala at&oacute;mica y subat&oacute;mica, donde las leyes de la f&iacute;sica cl&aacute;sica ya no son v&aacute;lidas.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Relatividad:</strong> Desarrollada por Albert Einstein, esta teor&iacute;a describe la relaci&oacute;n entre el espacio, el tiempo, la gravedad y el movimiento a altas velocidades.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>F&iacute;sica de Part&iacute;culas:</strong> Investiga los componentes elementales de la materia y las fuerzas fundamentales que interact&uacute;an con ellos.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Astrof&iacute;sica:</strong> Aplica los principios de la f&iacute;sica al estudio de los objetos celestes, como estrellas, galaxias y el universo en su conjunto.</span></p>\r\n</li>\r\n</ul>\r\n<p style=\"font-size: 18pt;\"><span style=\"font-size: 18pt;\"><iframe src=\"https://www.youtube.com/embed/7r2xz7tKY24?si=rJt_OHms3xTG6y3s\" width=\"560\" height=\"314\" allowfullscreen=\"allowfullscreen\"></iframe></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>La importancia de la f&iacute;sica</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La f&iacute;sica es esencial para nuestra comprensi&oacute;n del universo y tiene un impacto profundo en nuestra vida cotidiana. Los descubrimientos de la f&iacute;sica han impulsado avances revolucionarios en:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Tecnolog&iacute;a:</strong> La f&iacute;sica es la base de numerosas tecnolog&iacute;as que utilizamos a diario, incluyendo la electr&oacute;nica, las comunicaciones, la computaci&oacute;n, la medicina y la energ&iacute;a.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Medicina:</strong> La f&iacute;sica m&eacute;dica ha permitido el desarrollo de t&eacute;cnicas de diagn&oacute;stico como los rayos X, la resonancia magn&eacute;tica y la tomograf&iacute;a, as&iacute; como tratamientos como la radioterapia.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Energ&iacute;a:</strong> La f&iacute;sica juega un papel crucial en el desarrollo de nuevas fuentes de energ&iacute;a, como la energ&iacute;a nuclear, la energ&iacute;a solar y la energ&iacute;a e&oacute;lica.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Exploraci&oacute;n Espacial:</strong> La f&iacute;sica es fundamental para el dise&ntilde;o de naves espaciales, sat&eacute;lites y telescopios que nos permiten explorar el universo.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Comprender el Universo:</strong> La f&iacute;sica nos ayuda a responder preguntas fundamentales sobre el origen, la evoluci&oacute;n y el destino del universo.</span></p>\r\n</li>\r\n</ul>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\"><strong>El futuro de la f&iacute;sica</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La f&iacute;sica es un campo din&aacute;mico y en constante evoluci&oacute;n. Algunos de los campos de investigaci&oacute;n m&aacute;s prometedores en la f&iacute;sica actual incluyen:</span></p>\r\n<ul style=\"text-align: justify;\">\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>F&iacute;sica de la Materia Condensada:</strong> Explora las propiedades macrosc&oacute;picas de la materia que surgen de las interacciones a nivel at&oacute;mico, lo que lleva al desarrollo de nuevos materiales con propiedades sorprendentes.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>&Oacute;ptica Cu&aacute;ntica:</strong> Estudia la interacci&oacute;n de la luz con la materia a nivel cu&aacute;ntico, con aplicaciones potenciales en computaci&oacute;n cu&aacute;ntica, criptograf&iacute;a y comunicaci&oacute;n.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>Cosmolog&iacute;a:</strong> Investiga el origen, la evoluci&oacute;n y la estructura a gran escala del universo, incluyendo temas como la materia oscura, la energ&iacute;a oscura y los agujeros negros.</span></p>\r\n</li>\r\n<li style=\"font-size: 18pt;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>F&iacute;sica de Altas Energ&iacute;as:</strong> Busca descubrir las leyes fundamentales de la naturaleza mediante experimentos con aceleradores de part&iacute;culas de alta energ&iacute;a, como el Gran Colisionador de Hadrones.</span></p>\r\n</li>\r\n</ul>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 18pt;\">La f&iacute;sica seguir&aacute; desempe&ntilde;ando un papel vital en la configuraci&oacute;n de nuestro futuro, impulsando la innovaci&oacute;n, abordando los desaf&iacute;os globales y profundizando nuestra comprensi&oacute;n del universo.</span></p>\r\n</div>', '4', '2025-04-14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `image` varchar(250) NOT NULL,
  `category` varchar(250) NOT NULL,
  `summary` text NOT NULL,
  `date` date NOT NULL,
  `author` text NOT NULL,
  `book` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `books`
--

INSERT INTO `books` (`id`, `title`, `image`, `category`, `summary`, `date`, `author`, `book`) VALUES
(3, 'Educación Artística', 'artistica1-1.png', 'Educación Artística', '¿Sientes la llamada del arte? Colección Bicentenario Artística 1 te revela los elementos clave de la percepción y expresión en las artes visuales, escénicas y musicales: forma, espacio, tiempo, línea, color, voz, movimiento y melodía. ¡Una puerta de entrada fundamental para comprender el lenguaje artístico!', '2025-04-08', 'Colección Bicentenario', 'artistica1.pdf'),
(4, 'Historia de Venezuela y Nuestra América', 'historia1-1.png', 'Historia de Venezuela', 'Historia de Venezuela y Nuestra América: Despierta tu curiosidad sobre el pasado que construye nuestro presente. Más que fechas, explora las huellas históricas en nuestra cultura y sociedad. Aprende a pensar la historia activamente, analizando y comprendiendo su impacto para construir un futuro mejor. ¡Una invitación a conectar el ayer con el mañana!', '2025-04-01', 'Gabriela Mistral ', 'historia1.pdf'),
(5, 'Matemática para la Vida', 'matematica1-1.png', 'Matemáticas', 'Descubre cómo las matemáticas son mucho más que números en un papel. Este libro te guía a través de conceptos y herramientas matemáticas esenciales, mostrándote cómo aplicarlos en situaciones reales de tu día a día. Aprende a resolver problemas prácticos, a entender el mundo que te rodea y a desarrollar habilidades útiles para tu futuro.', '2025-04-06', 'Colección Bicentenario', 'matematica1.pdf'),
(6, 'Palabra de Identidad', 'lengua-1.png', 'Lengua y Literatura', 'Descubre tu voz y tu identidad con Palabra de Identidad. Este libro te invita a explorar el fascinante mundo del lenguaje, desde la oralidad venezolana hasta la literatura universal. Conoce la riqueza de nuestras lenguas indígenas y la Lengua de Señas Venezolana. Desarrolla tu pensamiento crítico y tu capacidad de expresión, fortaleciendo tu conexión con tu cultura y tu identidad como venezolano.', '2025-04-04', 'Colección Bicentenario', 'lengua.pdf'),
(7, 'El Espacio Geográfico de la Humanidad', 'geografia1-1.png', 'Ciencias Sociales', '¿Por qué nuestro mundo es como es? El Espacio Geográfico de la Humanidad te muestra la geografía como una ciencia de conexiones, invitándote a explorar cómo las acciones humanas a lo largo del tiempo han transformado nuestro planeta. A través de actividades y reflexiones, comprenderás tu papel en este espacio y cómo el pasado influye en tu presente. ¡Sé protagonista de tu aprendizaje geográfico!', '2025-04-04', 'Colección Bicentenario', 'geografia1.pdf'),
(12, 'Alimentando con Ciencias', 'CienciasNaturales1ero-1.png', 'Ciencias Naturales', 'Despierta tu interés por las maravillas de las Ciencias Naturales. Más que conceptos, explora cómo la ciencia nutre tu comprensión del mundo. Aprende a observar activamente, analizando y comprendiendo los principios que rigen la naturaleza. ¡Una invitación a descubrir la ciencia que te rodea!', '2025-04-21', 'Colección Bicentenario', 'CienciasNaturales1ero.pdf'),
(14, 'El Porvenir de la Vida', 'El-por-venir-de-la-Vida.png', 'Ciencias Naturales', 'Despierta tu asombro por el fascinante mundo de las Ciencias Naturales. Más que datos, explora los secretos de la vida, la naturaleza y el universo que nos rodea. Aprende a observar, experimentar y comprender activamente los principios científicos que dan forma a nuestro presente y construirán un futuro lleno de descubrimientos. ¡Una invitación a conectar con la maravilla de la ciencia!', '2025-04-21', 'Colección Bicentenario', 'Ciencias Naturales, 2do año. El porvenir de la vida.pdf'),
(30, 'Conciencia Matemática', 'conciencia-matematica-2do.png', 'Matemática', 'Despierta tu mente para explorar el fascinante mundo de las Matemáticas. Más que solo cuentas, descubre cómo los números y las formas están presentes en todo lo que nos rodea. Aprende a pensar matemáticamente, analizando, resolviendo y comprendiendo su lógica para construir un futuro lleno de ideas brillantes. ¡Una invitación a conectar con la inteligencia de los números!', '2025-04-21', 'Colección Bicentenario', 'Matemática, 2do año. Conciencia Matemática_.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `imagen` varchar(200) NOT NULL,
  `text` varchar(350) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id`, `nombre`, `imagen`, `text`) VALUES
(1, 'Matemáticas', 'Matematicas.png', '¡Atrévete a pensar diferente! Aprende a pensar con lógica y domina los números.'),
(3, 'Educación Física', 'Edu Fisica.png', '¡Muévete, suda y diviértete! Aprende a mantener tu cuerpo en forma y a trabajar en equipo.'),
(4, 'Biología', 'Biologia.png', '¡Descubre los secretos de la vida! Descubre la asombrosa complejidad de la vida'),
(5, 'Física', 'Fisica.png', '¡Domina las leyes del movimiento! Comprende la interacción entre fuerza, masa, velocidad y aceleración.'),
(6, 'Ciencias Ambientales', 'CienciasAmbientales.png', '¡Salva el planeta! Aprende cómo proteger nuestro hogar, construyendo un futuro sostenible.'),
(7, 'Geografía', 'Geografia.png', '¡Explora el mundo sin salir del aula! Mapas, climas y nuevas culturas te esperan en esta aventura.'),
(8, 'Historia del arte', 'HistoriadelArte.png', '¡Viaja a través del tiempo con el arte! Descubre las historias detrás de las obras más famosas.'),
(9, 'Química', 'Quimica.png', '¡Conviértete en un alquimista moderno! Descubre cómo se transforma la materia y crea nuevas sustancias.'),
(10, 'Historia Universal', 'HistoriaUniversal.png', '¡Viaja a través de las épocas! Descubre cómo las civilizaciones antiguas, las revoluciones y los grandes personajes han creado el mundo en el que vivimos.'),
(13, 'Literatura', 'descargar (93).png', 'El arte de contar historias y expresar ideas a través de la palabra escrita.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `articulo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `comentario_padre_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `articulo_id`, `usuario_id`, `contenido`, `fecha_creacion`, `comentario_padre_id`) VALUES
(63, 51, 4, 'asdasd8', '2025-05-14 18:19:38', NULL),
(64, 51, 4, 'fdfd', '2025-05-14 18:19:59', NULL),
(65, 51, 4, 'dsadsaasdas', '2025-05-14 19:19:46', NULL),
(66, 51, 11, 'hOLA SOY josEeeeee', '2025-07-14 08:47:14', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `article_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'administrador'),
(2, 'profesor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `imagen` varchar(200) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `biografia` text DEFAULT NULL,
  `state` varchar(20) DEFAULT 'activo',
  `suspension_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `rol_id`, `imagen`, `token`, `biografia`, `state`, `suspension_reason`) VALUES
(4, 'Dixon Tejedor', 'djtejedoromero2015@gmail.com', '$2y$10$PJmb1Tn9KWakAo7BA6mA3emab9eDEgzFV4MzLI0QfTAZheAjHF.92', 1, '193597822_1203185883466310_7420591489642384619_n.jpg', '', 'Me apasiona aprender!! :)', 'activo', NULL),
(6, 'admin', 'admin@gmail.com', '$2y$10$RkE6Kpefrl29GvQZ9RBTAesKOHWpUiLV36zB9DthUFsoUDbAaDUQq', 1, 'NoFoto.png', NULL, 'Soy el ADMIN', 'activo', NULL),
(10, 'Hola', 'hola@gmail.com', '$2y$10$8foz3e..uHpqhtpy4Zl7I.4t7o7XLfLMETNTJuH.AkBOQIN6DpJEC', NULL, 'NoFoto.png', NULL, NULL, 'activo', NULL),
(11, 'Jose', 'hola1@gmail.com', '$2y$10$Cli.V9UM.MAwVbtnsfsrS.k1Ke4SgHULnGB3iKcWScyeAXTWMSeRi', NULL, 'NoFotoPerfil.png', NULL, 'Hola!', 'activo', NULL),
(12, 'Hola12', 'hola12@gmail.com', '$2y$10$lQnCNN81uWzGVZsMJFX3sODgLV0yThqZPJfvtTJf10IL8QuLm9XGC', NULL, 'NoFoto.png', NULL, NULL, 'activo', 'Insultos en los comentarios');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `comentario_padre_id` (`comentario_padre_id`),
  ADD KEY `comentarios_fk_articulo` (`articulo_id`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de la tabla `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_fk_articulo` FOREIGN KEY (`articulo_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`articulo_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_3` FOREIGN KEY (`comentario_padre_id`) REFERENCES `comentarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
