<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invitation à consulter une liste de cadeaux</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4e73df;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            background-color: #f8f9fc;
            padding: 20px;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            background-color: #4e73df;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8rem;
            color: #858796;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Invitation à consulter une liste de cadeaux</h1>
        </div>
        
        <div class="content">
            <p>Bonjour,</p>
            
            <p>{{ $user->name }} vous invite à consulter sa liste de cadeaux "{{ $giftList->titre }}".</p>
            
            @if($message)
                <p><strong>Message de {{ $user->name }} :</strong></p>
                @if(isset($messageContent))
                    <p>{{ $messageContent }}</p>
                @endif
            @endif
            
            <p>Cette liste contient des idées de cadeaux que {{ $user->name }} aimerait recevoir.</p>
            
            <p>Vous pouvez consulter cette liste et réserver des articles en cliquant sur le bouton ci-dessous :</p>
            
            <a href="{{ route('gift-lists.shared', $giftList->code_partage) }}" class="button">Voir la liste de cadeaux</a>
            
            <p><small>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur : {{ route('gift-lists.shared', $giftList->code_partage) }}</small></p>
        </div>
        
        <div class="footer">
            <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
            <p>© {{ date('Y') }} LivresGourmands. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
