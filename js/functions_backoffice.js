function verifyFormUserAdd(username, mail, password, password2,modify)
{
    var message = "";

    if (mail !== "")
    {
        if (mail.length > 255)
            message += "L'adresse courriel ne doit pas excéder 255 caractères.<br/>";
        else
        {
            if (!checkMailAdress(mail))
                message += "L'adresse courriel n'est pas valide.<br/>";
        }
    }
    else
    {
        message += "L'adresse courriel ne peut être vide.<br/>";
    }
    
    if(modify)
    {
        if(password.length > 0)
        {
            if (password.length < 8 )
                message += "Votre mot de passe doit comporter minimum 8 caractères.<br/>";
            else
            {
                if (password !== password2)
                {
                    message += "Votre mot de passe et la confirmation ne correspondent pas.<br/>";
                }
            }
        }
    }
    else
    {
        if (username !== "")
        {
            if (username.length > 45)
                message += "Le nom d'utilisateur ne doit pas excéder 45 caractères.<br/>";
        }
        else
        {
            message += "Le nom d'utilisateur ne peut être vide.<br/>";
        }
        if (password.length < 8 )
            message += "Votre mot de passe doit comporter minimum 8 caractères.<br/>";
        else
        {
            if (password !== password2)
            {
                message += "Votre mot de passe et la confirmation ne correspondent pas.<br/>";
            }
        }
    }
    return message;
}


function checkMailAdress(mail)
{
    // Vérifie que l'adresse mail ne comporte pas de point en début et avant l'arobase ainsi
    // que la présence d'un arobase, du nom de domaine.
    // Vérifie que l'adresse mail ne comporte pas de point en début et avant l'arobase ainsi
    // que la présence d'un arobase, du nom de domaine.
    
    if(/^[^\.][\w!#$%&'*+-\/=?^_`{|}~]*[^\.][@][-_.\w]+[.]+[\w]{2,}/.test(mail))
    {
        return true;
    }
    return false;
}