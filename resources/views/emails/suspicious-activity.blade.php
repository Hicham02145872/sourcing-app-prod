<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerte de Sécurité</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #dc3545;">⚠️ Alerte de Sécurité</h2>
        
        <p>Bonjour {{ $user->name }},</p>
        
        <p>Nous avons détecté une activité suspecte sur votre compte :</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0;">
            <p><strong>Type d'activité :</strong> 
                @if($activityType === 'login')
                    Connexion depuis une nouvelle adresse IP
                @elseif($activityType === 'password_change')
                    Changement de mot de passe depuis une nouvelle adresse IP
                @else
                    Activité suspecte
                @endif
            </p>
            <p><strong>Adresse IP :</strong> {{ $data['ip'] ?? 'Non disponible' }}</p>
            <p><strong>Date et heure :</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
        
        <p>Si vous êtes à l'origine de cette activité, vous pouvez ignorer cet email.</p>
        
        <p><strong>Si vous n'êtes pas à l'origine de cette activité :</strong></p>
        <ul>
            <li>Changez immédiatement votre mot de passe</li>
            <li>Vérifiez vos sessions actives dans les paramètres de votre compte</li>
            <li>Déconnectez toutes les sessions suspectes</li>
            <li>Contactez notre support si nécessaire</li>
        </ul>
        
        <p style="margin-top: 30px;">
            <a href="{{ route('password.request') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Réinitialiser mon mot de passe
            </a>
        </p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">
        
        <p style="font-size: 12px; color: #666;">
            Cet email a été envoyé automatiquement pour votre sécurité. 
            Si vous avez des questions, contactez notre support.
        </p>
    </div>
</body>
</html>
