# laravel-protocolo-pdf
# Protocolo de Prevención y Atención de Violencia en el IPRODHA

Este repositorio contiene un proyecto Laravel diseñado para notificar a los empleados sobre un Protocolo de Prevención, Atención y Seguimiento de Casos de Violencia en el Instituto Provincial de Desarrollo Habitacional de Misiones (IPRODHA).

## Archivos Principales

- [ProtocoloController.php](app/Http/Controllers/Generales/ProtocoloController.php)
- [protocolo.blade.php](resources/views/Generales/protocolo.blade.php)
- [web.php](routes/web.php)

## Descripción

El proyecto tiene como objetivo principal proporcionar un marco para la prevención y atención de casos de violencia dentro del IPRODHA, estableciendo un protocolo y una comisión dedicada a abordar estas situaciones. A través del sitio web los empleados pueden tomar conocimiento de sus derechos en caso de sufrir alguna de las situaciones contempladas en el protocolo, para lo cual puede descargar los pdf del formulario para presentar la denuncia en caso de necesitar, y de la Resolución aprobatoria de dicho protocolo.

## Uso

### `ProtocoloController.php`

Este controlador maneja la lógica detrás de la visualización de la información del protocolo. Además, proporciona rutas para descargar documentos relacionados.

### `protocolo.blade.php`

La vista que presenta la información del protocolo y permite descargar documentos relacionados.

## Configuración

- Los documentos relacionados están ubicados en `/storage/pdf/`.
- Se debe tener en cuenta que los archivos en `/storage` tengan los permisos adecuados.

## Ejemplo de Configuración

No se requiere configuración adicional.

## Requisitos

- PHP >= 7.4
- Composer
- Laravel
- Permisos adecuados para la carpeta `/storage`

## Contribuciones

Si desea contribuir al proyecto, siga estos pasos:

1. Abra un problema para discutir los cambios que desea realizar.
2. Fork el repositorio.
3. Cree una nueva rama (`git checkout -b feature/nueva-funcionalidad`).
4. Realice los cambios y confirme (`git commit -am 'Agregar nueva funcionalidad'`).
5. Envíe a su rama (`git push origin feature/nueva-funcionalidad`).
6. Abra una solicitud de extracción.

## Licencia

Este proyecto está bajo la licencia CC BY-NC.

## Notas Adicionales

- Este proyecto se rige por los principios de atención personalizada, confidencialidad, imparcialidad y legalidad.
- Las denuncias deben realizarse dentro de los 6 meses del incidente.
- No se aceptarán denuncias anónimas.

**¡Gracias por contribuir al Protocolo de Prevención y Atención de Violencia en el IPRODHA!**
