# Goxens v2 php SDK

La librairie GoxensV2Php vous permet d'utiliser les services de Goxens via son API V2.

## Installation

```bash
composer require goxenspro/goxens-v2-php
```

## Utilisation
Pour utiliser le SDK PHP Goxens V2, vous aurez besoin d'une clé API ou d'un jeton JWT. Vous pouvez obtenir une clé API à partir du tableau de bord Goxens, ou vous pouvez utiliser la classe Auth pour générer un jeton JWT à l'aide de votre e-mail et de votre mot de passe.
```php
require_once __DIR__ . '/vendor/autoload.php';
```


##  Générer un jeton d'authentification Bearer

```php
$auth = new Goxens\GoxensV2Php\Auth();
$jwt = $auth->generateToken('your@email.com', 'yourpassword');
```

## Balance

Pour vérifier votre solde, vous devez envoyer une requête GET à l'URL suivante :

> Vous avez le choix entre utiliser votre jeton d'authentification ou votre clé API.

```php
$balance = new Goxens\GoxensV2Php\Balance();
$balance->getBalance($jwt); // ou $balance->getBalance($apiKey);
```


## Expediteurs

### Créer un expéditeur

> Vous avez le choix entre utiliser votre jeton d'authentification ou votre clé API.

```php
    $sender = new Goxens\GoxensV2Php\Sender();
    $senderName = $sender->createSender('My Sender', $token);
```

### Obtenir la liste des expéditeurs

```php
    $sender = new Goxens\GoxensV2Php\Sender();
    $senderList = $sender->findSenders($token);
```

### Delete un expéditeur

```php
    $sender = new Goxens\GoxensV2Php\Sender();
    $sender->deleteSender($senderId, $token);
```



## Envoi de SMS

Pour envoyer un SMS, vous devez envoyer une requête POST à l'URL suivante :

> Vous avez le choix entre utiliser votre jeton d'authentification ou votre clé API.

```php
    // Pour générer un jeton d'authentification
    $auth = new Goxens\GoxensV2Php\Auth();
    $token = $auth->generateToken('your@email.com', 'yourpassword');
 
    
    // Pour envoyer un SMS
    $simplesend = new Goxens\GoxensV2Php\Simplesend();
    $data = [
        "sender" => "My Sender",
        "typeContact" => "compose",
        "listeContacts" => "22991107506",
        "message" => "Bonjour",
        "hasSchedule" => false,
        "programDate" => null,
        "programTime" => null,
        "typeSmsSend" => "standard"
    ];
   
    $sendResult = $simplesend->sendSimpleSend($data, $token);
```









