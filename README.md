# chapp_solutions
Enlace: https://chapp.ilu7ion.com/
Repositorio: https://github.com/i7strelok/chapp_solutions
Autor: Carlos Andrés Fernández Sépich

A considerar: Heroku ha experimendado varios problemas en los últimos días. Además, no es compatible con SQLite. La presente aplicación web está desarrollada totalmente en DQL, por lo que, funcionaría en cualquier SGDB.

Descripción: Test realizado como parte de la prueba técnica para Chapp Solutions.

Teconologías utilizadas: 

    - PHP 8.0.3

    - Framework Symfony 5.4.9

    - SDGB: SQLite

    - Boostrap 4.6.1
    
    - Jquery 3.5.1

Descripción técnica: Se ha diseñado y desarrollado la presente aplicación web en base a los requisitos establecidos en la hoja de
candidatos. Se ha considerado necesario un diseño mínimamente elegable, por lo que se ha llevado a cabo. También se ha considerado necesario realizar funcionales extras como las que se detallará a continuación:

- Cliente: agregar, eliminar, modificar y visualizar.

- Etiquetas: agregar, eliminar, modificar y visualizar.

- Habitaciones: agregar, eliminar, modificar y visualizar.

- Reservas: además de la posibilidad de añadir nuevas reservas, también se da la posibilidad de eliminar, visualizar y modificar.

- Las etiquetas están relacionadas a las habitaciones y ayudan a describirlas, por ende, también son últiles a la hora de implementar buscadores.

Existen un sinfín de funcionalidades que, sí bien se han pensando, no se han implementado debido al poco tiempo diario disponible con el que cuenta este candidado, no obstante, se procederá a mencionar algunas:

1) Teniendo en cuenta el hecho de que, esta aplicación web se encuentra en línea, lo ideal hubiese sido que contara con un sistema
de autenticación básico (registro, inicio de sesión, cerrar sesión, recuperación de contraseñas, asignacion de privilegios, etc).

2) Los filtros se hubiesen implementado con Ajax.

3) Cada empleado del Hostel contaría con su propio usuario y cada usuario estaría vinculado a un privilegio en concreto, con el fin de los supervisores fueran capaces de acceder a funcionalidades que ciertos empleados no. Actualmente todo el mundo tiene acceso a todas las funcionalidades.

4) Buscadores y/o filtros en todos los listados. Se ha pensado en la idea de desarrollador un buscador que funcione a través de Ajax que sea capaz de filtrar por N campos.

5) Falta por mejorar detalles estéticos.

6) Falta por mejorar las comprobaciones, y, por ende, la seguridad.

7) Falta la realización de un refactoring para optimizar algunas DQL.

8) El proceso de reserva de una habitación es extremadamente básico, sin dudas, podría mejorarse.

9) Personalización de los mensajes de error.


