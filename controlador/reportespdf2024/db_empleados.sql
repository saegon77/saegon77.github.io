
create table usuario(
usua_codigo int primary key auto_increment,
usua_nombre varchar(32) not null ,
usua_clave  varchar(32) not null,
id_rol int,
 foreign key (id_rol) references roles (id_rol)


) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `usuario` (`usua_codigo`, `usua_nombre`, `usua_clave`, `id_rol`) VALUES (NULL, 'Joan Mateo Gaitan Baez', '12', '1'), (NULL, ' Juan Sebastian Yepes Gomez', '123', '1'), (NULL, 'Mateo', '1', '2'), (NULL, ' Mateo Deavila', '1234', '2'), (NULL, 'Maeto Lozano', '12345', '2'), (NULL, 'Manuel De Avila', '123456', '2'), (NULL, 'Edwin Aragon', '1234567', '2'), (NULL, 'Sandra Canas', '12345678', '2'), (NULL, 'Amaury Martelo', '123456789', '2'), (NULL, 'Felipe Feria', ' 1234567890', '2'), (NULL, ' Daniela Umana', '0', '2'), (NULL, 'Fernando Payares', '01', '2'), (NULL, 'Venancio Araujo', '012', '2'), (NULL, 'Jamal Musiala', '0123', '2'), (NULL, 'Ousmane Coutinho', ' 01234', '2'), (NULL, 'Alisson Becker', '012345', '2'), (NULL, ' Radamel Falcao', '0123456', '2'), (NULL, 'Daniel Quintero', '01234567', '2'), (NULL, ' Andres Daza', ' 012345678', '2'), (NULL, 'David Parra', '0123456789', '2'), (NULL, 'Juan Silva', '00', '2'), (NULL, ' Javier Montana', '11', '2'), (NULL, 'Ana Munoz', '22', '2'), (NULL, 'Samuel Acosta', '33', '2'), (NULL, 'Nicolas Urrego', '44', '2'), (NULL, ' Mateo Quevedo', '45', '2'), (NULL, 'Danna Grisales', '46', '2'), (NULL, 'Jeison Colmenares', '47', '2'), (NULL, 'Cristian Camargo', '48', '2'), (NULL, 'Jeison Reyes', '49', '2'), (NULL, 'Nicolas Mejia', '50', '2'), (NULL, 'Adrian Bonilla', '51', '2'), (NULL, 'Juan Quintero', '52', '2'), (NULL, 'Thomas Grisales', '67', '2'), (NULL, 'Diana Gomez', '66', '2'), (NULL, 'Nicol Rodriguez', '65', '2'), (NULL, 'Tatiana Cuchimaque', '64', '2'), (NULL, 'Alejandra Forero', '63', '2'), (NULL, 'Veronica Garcia', '62', '2'), (NULL, 'Juan Camargo', '61', '2'), (NULL, 'Cristian Avila', '6', '2'), (NULL, ' Melany Colmenares', '39', '2'), (NULL, 'Sharit Herrera', '38', '2'), (NULL, 'Daniel Daza', '37', '2'), (NULL, 'Andres Sanchez', '36', '2'), (NULL, 'Valeria Quevedo', '35', '2'), (NULL, 'Sebastian Macias', '34', '2'), (NULL, 'Natalia Zamora', '33', '2'), (NULL, 'Tomas Garcia', '32', '2'), (NULL, 'Kevin Aragon', '31', '2'), (NULL, 'Miguel Pineda', '3', '2');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `personal`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idp`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `usuario`
  MODIFY `idp` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key', AUTO_INCREMENT=64;

  -- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);
COMMIT;