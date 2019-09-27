<?php

namespace Drupal\eventbrite_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EventbriteApiController
 *
 * @package Drupal\eventbrite_api\Controller
 *
 * For testing use ./ngrok http -host-header=rewrite dcamp.localhost:80
 */
class EventbriteApiController extends ControllerBase {

  public function processPayload(Request $request) {
    $payload = \GuzzleHttp\json_decode($request->getContent());

    switch ($payload->config->action) {
      case 'order.placed':
//        $eb_user = \EventBriteApi->getOrder($payload->config->user_id);
//
//        $eb_user->name
//        $eb_user->mail
//        $eb_user->company
        // Crear user?
        break;
      case 'order.updated':
        // no hacer nada.
        break;
      case 'order.refund':
        // bloquear user?
        break;

    }

//    return new JsonResponse([
//      'user' => $payload->config->user_id,
//      'webhook_id' => $payload->config->webhook_id,
//      'action' => $payload->config->action,
//    ]);
    return new JsonResponse([]);

  }
}


/**
- Crear o bloquear usuarios de drupal con la info del order de eb. (mail de biembvenida con login)
- Añadir los campos imagen, redes sociales, foto etc (lo necesario para el listado de asistentes.)
- Si el usuario elimina su cuenta aclararle en el confirmation que esto implica un refund automatico en eventbrite.
 */
