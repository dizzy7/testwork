<?php


namespace Service;


use Db\Db;
use Db\OrderDao;
use Service\Templating;

class OrderService
{
    public function processOrder($formData)
    {
        $db = new OrderDao();
        $cartService = new CartService();
        $cartData = $cartService->getCartData();

        Db::getInstance()->beginTransaction();

        $orderId = $db->insert(
            [
                $cartData['bonus']['id'] ?? null,
                $cartData['sum'],
                (new \DateTime())->format('Y-m-d H:i:s'),
                $formData['card'],
                $formData['cvv'],
                $formData['name'],
                $formData['email'],
                $formData['phone']
            ]
        );

        foreach ($cartData['items'] as $item) {
            $db->addOrderProduct($orderId, $item['id'], $item['qty']);
        }

        Db::getInstance()->commit();

        $this->sendEmail($formData, $cartData, $orderId);
    }

    private function sendEmail($formData, $cartData, $orderId)
    {
        $templating = new Templating();

        $to = 'operator@test.ru';
        $subject = 'создан новый заказ';
        $body = $templating->render('orderEmail.php', ['form' => $formData, 'cart' => $cartData, 'orderId' => $orderId], false);

        $mailer = MailerService::getInstance();
        $mailer->send($to, $subject, $body);
    }
}