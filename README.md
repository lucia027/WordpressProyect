# CodeGaming Arcade

- **Web:** http://localhost:8080/
- **Panel de WordPress:** http://localhost:8080/wp-admin/

## 1. Dar acceso al compañero

**No hace falta hacer un fork.** Los dos trabajaréis en el mismo repositorio.

1. Lucía entra en el repositorio de GitHub y abre **Settings → Collaborators → Add people**.
2. Invita a su compañero con su usuario de GitHub.
3. El compañero acepta la invitación.
4. Los archivos del proyecto ya están en este repositorio.

## 2. Prepararlo en el ordenador del compañero

1. Instala **Git** y **Docker Desktop** con contenedores Linux. Si pide instalar WSL o reiniciar, hazlo. Abre Docker Desktop y espera a que esté listo.
2. Abre **PowerShell** en la carpeta donde quieras guardar el proyecto y ejecuta:

```powershell
git clone https://github.com/lucia027/WordpressProyect.git
cd WordpressProyect
Copy-Item .env.example .env
```

Si Git pide iniciar sesión, usa tu cuenta de GitHub y acepta el acceso desde el navegador.

3. Abre el archivo `.env` y cambia las contraseñas de `MYSQL_PASSWORD` y `MYSQL_ROOT_PASSWORD`. Usa letras, números y guiones bajos. Guarda el archivo. No cambies el puerto ni estas contraseñas después de arrancarlo por primera vez.
4. Arranca el proyecto:

```powershell
docker compose up -d
```

5. Entra en la web usando el enlace del principio. La primera vez puede tardar unos minutos. Completa la instalación de WordPress y elige tu usuario y contraseña.
6. Vuelve a PowerShell y ejecuta estos dos comandos para tener el diseño y los tres artículos:

```powershell
docker compose cp scripts/setup-codegaming.php wordpress:/tmp/setup-codegaming.php
docker compose exec -T wordpress php /tmp/setup-codegaming.php
```

7. Recarga la web. Para editarla, entra en el enlace del panel con el usuario que acabas de crear.
8. Si ya hay un archivo `database/wordpress.sql`, importa la copia siguiendo el apartado 3 para recibir los últimos cambios.

**Estos pasos se hacen solo la primera vez.** Cada uno abre su propia copia en su ordenador; no necesitáis estar en la misma casa.

## 3. Trabajar y compartir cambios

**Turnaos para cambiar páginas, artículos y ajustes.** No se sincronizan solos.

### Al terminar tu turno

Si has cambiado algo desde el panel de WordPress, exporta primero su contenido:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/database.ps1 export
```

Después sube los cambios:

```powershell
git add .
git commit -m "Actualiza la web"
git push
```

Si es tu primer commit y Git pide tu identidad, ejecuta estos dos comandos con tus datos y repite el commit:

```powershell
git config --global user.name "Tu nombre"
git config --global user.email "Tu correo de GitHub"
```

**Usad un repositorio privado para compartir esa copia:** incluye datos de las cuentas de WordPress, como los usuarios y correos. No subáis el archivo `.env`.

### Cuando recibes el turno

Con tus cambios anteriores ya guardados y Docker Desktop abierto:

```powershell
git pull --ff-only
docker compose up -d
```

Si tu compañero ha exportado cambios de WordPress, cárgalos:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/database.ps1 import
```

Escribe `IMPORTAR` cuando lo pida. **Sustituye el contenido de tu WordPress por el de tu compañero**, aunque guarda antes una copia de seguridad. También pasarás a usar los usuarios y contraseñas de la copia importada.

Para apagar el proyecto sin perder el trabajo:

```powershell
docker compose stop
```

Si el puerto 8080 está ocupado, cierra el otro programa o contenedor que lo esté usando. No ejecutes `docker compose down -v`: borra la base de datos local.
