# Copia compartida de la base de datos

El script `scripts/database.ps1 export` crea aqui `wordpress.sql` para
compartir paginas, menus, usuarios y ajustes con vuestro compañero.

No se copia automaticamente: exportad antes de subir cambios e importad
solo cuando querais sustituir vuestra base de datos local.

El SQL contiene correos, contenido y hashes de contraseñas. Usad un
repositorio privado y datos de prueba, nunca datos personales reales.
Las copias previas a una importacion se guardan en `backups/`, fuera de Git.
